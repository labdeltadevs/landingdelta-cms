<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Support\VademecumHtml;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('products:vademecum {--dry-run : Muestra el reporte sin escribir en la base de datos} {--vademecum= : Ruta del CSV del vademecum} {--mapping= : Ruta del CSV de mapeo from/to}')]
#[Description('Importa la descripcion de los productos desde el vademecum segun el mapeo')]
class ImportVademecum extends Command
{
    private const DEFAULT_VADEMECUM = 'app/vademecum/VADEMECUMM 2022 (35 AÑOS) APROBADO.csv';

    private const DEFAULT_MAPPING = 'app/vademecum/vademecum-mapping.csv';

    private const ACCENTS = [
        'Á' => 'A', 'À' => 'A', 'Ä' => 'A',
        'É' => 'E', 'È' => 'E', 'Ë' => 'E',
        'Í' => 'I', 'Ì' => 'I', 'Ï' => 'I',
        'Ó' => 'O', 'Ò' => 'O', 'Ö' => 'O',
        'Ú' => 'U', 'Ù' => 'U', 'Ü' => 'U',
        'Ñ' => 'N', 'Ç' => 'C',
    ];

    private const FORM_WORDS = [
        'COMPRIMIDO', 'CAPSULA', 'TABLETA', 'GOTAS', 'SUSPENSION', 'JARABE',
        'CREMA', 'POLVO', 'SOLUCION', 'SUPOSITORIO', 'POMADA', 'GEL',
        'INYECTABLE', 'GRANULADO', 'MASTICABLE', 'OVULOS',
    ];

    /**
     * @var list<array{id: int, squash: string}>
     */
    private array $products = [];

    public function handle(): int
    {
        $vademecumPath = $this->option('vademecum') ?: storage_path(self::DEFAULT_VADEMECUM);
        $mappingPath = $this->option('mapping') ?: storage_path(self::DEFAULT_MAPPING);

        if (! is_file($vademecumPath) || ! is_file($mappingPath)) {
            $this->error('No se encontraron los archivos: '.$vademecumPath.' / '.$mappingPath);

            return self::FAILURE;
        }

        $rows = $this->readCsv($vademecumPath);
        $mapping = $this->readMapping($mappingPath);

        if ($rows === [] || $mapping === []) {
            $this->error('No se pudieron leer los CSV.');

            return self::FAILURE;
        }

        /** @var list<array{id: int, squash: string}> $products */
        $products = Product::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (Product $product): array => [
                'id' => $product->id,
                'squash' => $this->squash($product->name),
            ])
            ->values()
            ->all();

        $this->products = $products;

        $processed = 0;
        $written = 0;
        $noData = [];
        $toUnresolved = [];

        foreach ($mapping as $entry) {
            [$from, $to] = [$entry['from'], $entry['to']];

            $matchedRows = $this->matchRows($from, $rows);
            $products = $this->resolveProducts($to);

            if ($matchedRows === []) {
                $noData[$from] = $to;

                continue;
            }

            if ($products === []) {
                $toUnresolved[$from] = $to;

                continue;
            }

            $html = VademecumHtml::build($this->sections($matchedRows));

            if ($html === null) {
                $noData[$from] = $to;

                continue;
            }

            $processed++;

            if ($this->option('dry-run')) {
                foreach ($products as $id) {
                    $this->line("  [dry-run] {id=$id} <- {$from}");
                }
            } else {
                Product::query()->whereIn('id', $products)->update(['description' => $html]);
                $written += count($products);
            }
        }

        $this->newLine();

        if ($this->option('dry-run')) {
            $this->info("=== Reporte ({$processed} mapeos con datos) ===");
            $this->warn('No se escribió nada en la base de datos (--dry-run).');
        } else {
            $this->info("=== Importación completada: {$written} productos actualizados ===");
        }

        $this->reportUnresolved($noData, 'Vademécum sin datos (se omite)');
        $this->reportUnresolved($toUnresolved, 'Producto(s) destino sin resolver (se omite)');

        return self::SUCCESS;
    }

    /**
     * @return list<array<string, string>>
     */
    private function readCsv(string $path): array
    {
        $contents = file_get_contents($path);

        if ($contents === false) {
            return [];
        }

        if (! mb_check_encoding($contents, 'UTF-8')) {
            $contents = mb_convert_encoding($contents, 'UTF-8', 'Windows-1252');
        }

        $handle = fopen('php://temp', 'w+b');
        if ($handle === false) {
            return [];
        }

        fwrite($handle, $contents);
        rewind($handle);

        $header = fgetcsv($handle, escape: '\\');
        if ($header === false) {
            fclose($handle);

            return [];
        }

        $header = array_map(
            fn ($h): string => $this->squash(trim((string) $h)),
            $header
        );

        $rows = [];
        while (($data = fgetcsv($handle, escape: '\\')) !== false) {
            $row = [];
            foreach ($header as $index => $column) {
                $row[$column] = (string) ($data[$index] ?? '');
            }
            if (trim($row['PRODUCTO']) !== '') {
                $rows[] = $row;
            }
        }

        fclose($handle);

        return $rows;
    }

    /**
     * @return list<array{from: string, to: string}>
     */
    private function readMapping(string $path): array
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return [];
        }

        $mapping = [];
        while (($data = fgetcsv($handle, escape: '\\')) !== false) {
            $from = trim($data[0] ?? '');
            $to = trim($data[2] ?? '');

            if ($from === '' || $to === '') {
                continue;
            }

            if (preg_match('/^from$/i', $from)) {
                continue;
            }

            $mapping[] = ['from' => $from, 'to' => $to];
        }

        fclose($handle);

        return $mapping;
    }

    /**
     * Busca las filas del vademecum que corresponden a un `from` del mapeo.
     *
     * @param  list<array<string, string>>  $rows
     * @return list<array<string, string>>
     */
    private function matchRows(string $from, array $rows): array
    {
        $parts = $this->splitOutside($from);
        $combined = $this->tokens(str_replace(' / ', ' ', $from));

        $result = [];
        $fromSquash = $this->squash($from);

        foreach ($rows as $index => $row) {
            if ($this->squash($row['PRODUCTO']) === $fromSquash) {
                $result[$index] = $row;

                continue;
            }

            $rowTokens = $this->tokens($row['PRODUCTO']);
            if ($this->isTokenSubset($rowTokens, $combined) || $this->isTokenSubset($combined, $rowTokens)) {
                $result[$index] = $row;
            }
        }

        foreach ($parts as $part) {
            if (in_array($this->squash($part), self::FORM_WORDS, true) || empty($this->tokens($part))) {
                continue;
            }

            $partTokens = $this->tokens($part);

            foreach ($rows as $index => $row) {
                if (isset($result[$index])) {
                    continue;
                }

                $rowTokens = $this->tokens($row['PRODUCTO']);
                if ($this->isTokenSubset($rowTokens, $partTokens) || $this->isTokenSubset($partTokens, $rowTokens)) {
                    $result[$index] = $row;
                }
            }
        }

        ksort($result);

        return array_values($result);
    }

    /**
     * @param  list<array<string, string>>  $rows
     * @return list<array{label: string, text: string}>
     */
    private function sections(array $rows): array
    {
        $labels = [
            'PRESENTACION' => 'Presentación',
            'COMPOSICION' => 'Composición',
            'ACCIONTERAPEUTICA' => 'Acción terapéutica',
            'DOSIS' => 'Posología',
            'INDICACIONES' => 'Indicaciones',
            'CONTRAINDICACIONES' => 'Contraindicaciones',
        ];

        $sections = [];

        foreach ($labels as $column => $label) {
            $texts = [];

            foreach ($rows as $row) {
                $text = trim($row[$column] ?? '');
                if ($text !== '') {
                    $texts[] = $text;
                }
            }

            $sections[] = ['label' => $label, 'text' => implode("\n", $texts)];
        }

        return $sections;
    }

    /**
     * @return list<int>
     */
    private function resolveProducts(string $to): array
    {
        $ids = [];

        foreach ($this->splitOutside($to) as $candidate) {
            $squash = $this->squash($candidate);

            if ($squash === '') {
                continue;
            }

            foreach ($this->products as $product) {
                if ($product['squash'] === $squash) {
                    $ids[] = $product['id'];
                }
            }
        }

        return array_values(array_unique($ids));
    }

    private function squash(string $s): string
    {
        $s = mb_strtoupper($s, 'UTF-8');
        $s = strtr($s, self::ACCENTS);
        $s = preg_replace('/\([^)]*\)/', '', $s);
        $s = preg_replace('/[^A-Z0-9]/', '', $s);

        return $s;
    }

    /**
     * @return list<string>
     */
    private function tokens(string $s): array
    {
        $s = mb_strtoupper($s, 'UTF-8');
        $s = strtr($s, self::ACCENTS);
        $s = preg_replace('/\([^)]*\)/', ' ', $s);
        $s = preg_replace('/([A-Z])([0-9])/', '$1 $2', $s);
        $s = preg_replace('/([0-9])([A-Z])/', '$1 $2', $s);
        $tokens = preg_split('/[^A-Z0-9]+/', $s) ?: [];

        return array_values(array_filter($tokens, fn (string $t): bool => $t !== ''));
    }

    /**
     * @param  list<string>  $a
     * @param  list<string>  $b
     */
    private function isTokenSubset(array $a, array $b): bool
    {
        return $a !== [] && array_diff($a, $b) === [];
    }

    /**
     * Divide en candidatos solo por " / " (con espacios) fuera de parentesis.
     *
     * @return list<string>
     */
    private function splitOutside(string $s): array
    {
        $parts = [];
        $buffer = '';
        $depth = 0;
        $length = strlen($s);

        for ($i = 0; $i < $length; $i++) {
            $char = $s[$i];

            if ($char === '(') {
                $depth++;
                $buffer .= $char;

                continue;
            }

            if ($char === ')') {
                $depth = max(0, $depth - 1);
                $buffer .= $char;

                continue;
            }

            $isSeparator = $depth === 0
                && $char === '/'
                && ($i > 0 && $s[$i - 1] === ' ')
                && ($i + 1 < $length && $s[$i + 1] === ' ');

            if ($isSeparator) {
                $parts[] = $buffer;
                $buffer = '';
                $i++;

                continue;
            }

            $buffer .= $char;
        }

        $parts[] = $buffer;

        return array_map('trim', $parts);
    }

    /**
     * @param  array<string, string>  $entries
     */
    private function reportUnresolved(array $entries, string $title): void
    {
        if ($entries === []) {
            return;
        }

        $this->newLine();
        $this->warn("{$title}: ".count($entries));

        foreach ($entries as $from => $to) {
            $this->line("  {$from} => {$to}");
        }
    }
}
