<?php

namespace App\Livewire\Admin\Products;

use App\Livewire\Admin\Concerns\GeneratesQrCodes;
use App\Models\Brand;
use App\Models\JobOpening;
use App\Models\Product;
use App\Support\ProductCsv;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Productos')]
class ProductIndex extends Component
{
    use GeneratesQrCodes;
    use WithFileUploads;
    use WithPagination;

    public string $search = '';

    public $importFile = null;

    public ?array $importResult = null;

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleFeatured(int $id): void
    {
        $product = Product::query()->findOrFail($id);
        $this->authorize('update', $product);
        $product->update(['is_featured' => ! $product->is_featured]);
    }

    public function toggleActive(int $id): void
    {
        $product = Product::query()->findOrFail($id);
        $this->authorize('update', $product);
        $product->update(['is_active' => ! $product->is_active]);
    }

    public function delete(int $id): void
    {
        $product = Product::query()->findOrFail($id);
        $this->authorize('delete', $product);
        $product->delete();
    }

    public function export(): StreamedResponse
    {
        $this->authorize('viewAny', Product::class);

        $filename = 'productos-'.now()->format('Ymd-Hi').'.csv';

        return response()->streamDownload(function (): void {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ProductCsv::HEADERS, ProductCsv::DELIMITER);

            Product::query()->orderBy('id')->chunk(500, function ($products) use ($output): void {
                foreach ($products as $product) {
                    fputcsv($output, ProductCsv::row($product), ProductCsv::DELIMITER);
                }
            });

            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function updatedImportFile(): void
    {
        $this->importResult = null;
    }

    public function import(): void
    {
        $this->importResult = null;

        $this->validate([
            'importFile' => 'required|file|max:5120',
        ]);

        /** @var TemporaryUploadedFile $file */
        $file = $this->importFile;

        if (strtolower((string) $file->getClientOriginalExtension()) !== 'csv') {
            $this->addError('importFile', 'El archivo debe tener extensión .csv.');

            return;
        }

        $parsed = ProductCsv::parse((string) file_get_contents($file->getRealPath()));

        if ($parsed['missing'] !== []) {
            $this->addError('importFile', 'Faltan columnas: '.implode(', ', $parsed['missing']).'.');

            return;
        }

        $created = 0;
        $updated = 0;
        $errors = [];

        foreach ($parsed['rows'] as $index => $row) {
            $line = $index + 2;
            $existing = ($id = ProductCsv::normalizeId($row['id'] ?? null)) !== null
                ? Product::query()->find($id)
                : null;

            try {
                $this->authorize($existing !== null ? 'update' : 'create', $existing ?? Product::class);
            } catch (AuthorizationException) {
                $errors[] = "Fila {$line}: sin permiso.";

                continue;
            }

            $result = ProductCsv::syncRow($row);

            match ($result['status']) {
                'created' => $created++,
                'updated' => $updated++,
                'skipped' => $errors[] = "Fila {$line}: {$result['reason']}.",
            };
        }

        for ($i = 0; $i < $parsed['malformed']; $i++) {
            $errors[] = 'Fila con formato inválido (columnas incompletas).';
        }

        $this->importResult = [
            'created' => $created,
            'updated' => $updated,
            'skipped' => count($errors),
            'errors' => array_slice($errors, 0, 20),
            'truncated' => count($errors) > 20,
        ];

        $this->reset('importFile');
    }

    public function closeImport(): void
    {
        $this->reset(['importFile', 'importResult']);
    }

    protected function qrTarget(int $id): Product
    {
        return Product::query()->findOrFail($id);
    }

    protected function qrRoute(Product|Brand|JobOpening $target): string
    {
        return route('public.products.show', $target);
    }

    protected function qrFilename(Model $target): string
    {
        return 'codigo-producto-'.($target->slug ?? $target->getRouteKey()).'.jpg';
    }

    protected function barcodeValue(Product|Brand|JobOpening $target): string
    {
        return filled($target->internal_code ?? null) ? $target->internal_code : $target->slug;
    }

    public function render(): View
    {
        $products = Product::query()
            ->with('brand', 'category')
            ->search($this->search)
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.admin.products.product-index', compact('products'));
    }
}
