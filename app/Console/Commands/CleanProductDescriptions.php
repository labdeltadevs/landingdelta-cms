<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Brochure;
use App\Models\Product;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('products:clean-descriptions')]
#[Description('Limpia tags HTML, hipervínculos y entidades de las descripciones en products, brands y brochures')]
class CleanProductDescriptions extends Command
{
    public function handle(): void
    {
        $this->info('=== Limpieza de descripciones ===');
        $this->newLine();

        $models = [
            'products' => Product::class,
            'brands' => Brand::class,
            'brochures' => Brochure::class,
        ];

        $totalCleaned = 0;

        foreach ($models as $label => $modelClass) {
            $records = $modelClass::query()
                ->whereNotNull('description')
                ->where('description', '!=', '')
                ->get();

            $count = 0;
            foreach ($records as $record) {
                $clean = $this->cleanHtml($record->description);
                if ($clean !== $record->description) {
                    $record->description = $clean;
                    $record->save();
                    $count++;
                }
            }

            $this->line("  {$label}: {$count} limpiados de {$records->count()} con descripción");
            $totalCleaned += $count;
        }

        $this->newLine();
        $this->info("Total: {$totalCleaned} descripciones limpiadas.");
    }

    private function cleanHtml(string $html): string
    {
        // 1) Extraer texto de hipervínculos y eliminar el tag <a>
        $html = preg_replace('/<a[^>]*>([^<]*)<\/a>/i', '$1', $html);

        // 2) Nuevo línea tras cada tag cerrado — cubre inline y block tags
        $html = preg_replace('/>/', ">\n", $html);

        // 3) <br> → salto simple
        $html = preg_replace('/<br\s*\/?>/i', "\n", $html);

        // 4) strip_tags remueve todo lo que quede
        $text = strip_tags($html);

        // 5) Decodificar entidades
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

        // 6) Convertir secuencias de escape literales a caracteres reales
        $text = str_replace(['\r\n', '\n', '\r', '\t'], ["\r\n", "\n", "\r", "\t"], $text);

        // 7) Normalizar whitespace
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text);
        $text = preg_replace('/^[\s]+|[\s]+$/m', '', $text);

        return trim($text);
    }
}
