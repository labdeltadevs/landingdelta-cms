<?php

namespace App\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\ImageOptimizer;
use App\Support\VademecumHtml;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Producto')]
class ProductForm extends Component
{
    use WithFileUploads;

    /** Secciones estandar con las que se precarga el vademecum de un producto nuevo. */
    private const STANDARD_LABELS = [
        'Presentación',
        'Composición',
        'Acción terapéutica',
        'Posología',
        'Indicaciones',
        'Contraindicaciones',
    ];

    public ?Product $product = null;

    public string $name = '';

    public string $active_ingredient = '';

    /** @var array<int, array{id: string, label: string, text: string}> */
    public array $vademecumRows = [];

    public ?float $approx_price = null;

    public ?int $brand_id = null;

    public ?int $category_id = null;

    public bool $is_active = true;

    public bool $is_featured = false;

    public $upload;

    public function mount(?Product $product = null): void
    {
        $this->product = $product;

        if ($this->product?->exists) {
            $this->name = $this->product->name;
            $this->active_ingredient = $this->product->active_ingredient ?? '';
            $this->approx_price = $this->product->approx_price;
            $this->brand_id = $this->product->brand_id;
            $this->category_id = $this->product->category_id;
            $this->is_active = $this->product->is_active;
            $this->is_featured = $this->product->is_featured;

            $this->vademecumRows = array_map(
                fn (array $row): array => $this->withRowId($row),
                VademecumHtml::parse($this->product->description)
            );

            // Descripciones legadas sin tabla: se cargan como una fila editable
            // para no perder el contenido al guardar.
            if ($this->vademecumRows === [] && filled($this->product->description)) {
                $this->vademecumRows = [
                    $this->withRowId(['label' => '', 'text' => $this->product->description]),
                ];
            }
        } else {
            $this->vademecumRows = array_map(
                fn (string $label): array => $this->withRowId(['label' => $label, 'text' => '']),
                self::STANDARD_LABELS
            );
        }
    }

    /**
     * Agrega una fila vacia al vademecum.
     */
    public function addVademecumRow(): void
    {
        $this->vademecumRows[] = $this->withRowId(['label' => '', 'text' => '']);
    }

    /**
     * Elimina la fila del indice indicado y reindexa el arreglo.
     */
    public function removeVademecumRow(int $index): void
    {
        unset($this->vademecumRows[$index]);
        $this->vademecumRows = array_values($this->vademecumRows);
    }

    /**
     * @param  array{label: string, text: string}  $row
     * @return array{id: string, label: string, text: string}
     */
    private function withRowId(array $row): array
    {
        return [
            'id' => Str::uuid()->toString(),
            'label' => $row['label'],
            'text' => $row['text'],
        ];
    }

    public function save(): void
    {
        $this->authorize($this->product?->exists ? 'update' : 'create', $this->product ?? Product::class);

        $data = $this->validate([
            'name' => 'required|string|max:255',
            'active_ingredient' => 'nullable|string|max:255',
            'approx_price' => 'nullable|numeric|min:0|max:999999.99',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data['description'] = VademecumHtml::build(array_map(
            fn (array $row): array => [
                'label' => $row['label'],
                'text' => $row['text'],
            ],
            $this->vademecumRows
        ));

        $data['slug'] = $this->product?->exists
            ? Str::slug($this->name).'-'.$this->product->id
            : Str::slug($this->name).'-'.Str::lower(Str::random(5));

        if (blank($this->product?->internal_code)) {
            do {
                $internalCode = 'PROD-'.Str::upper(Str::random(6));
            } while (Product::query()->where('internal_code', $internalCode)->exists());

            $data['internal_code'] = $internalCode;
        }

        if ($this->upload && is_string($this->upload)) {
            $data['main_image_path'] = $this->upload;

            if ($this->product?->exists && $this->product->main_image_path) {
                Storage::disk('public')->delete($this->product->main_image_path);
            }
        }

        if ($this->product?->exists) {
            $this->product->update($data);
        } else {
            $this->product = Product::query()->create($data);
        }

        $this->dispatch('notify', message: 'Producto guardado correctamente.');
        $this->redirect(route('admin.products.index'), navigate: true);
    }

    public function _finishUpload($name, $tmpPath, $isMultiple, $append = true): void
    {
        if (FileUploadConfiguration::shouldCleanupOldUploads()) {
            $this->cleanupOldUploads();
        }

        $tmpPath = collect($tmpPath)->map(function ($signedPath) {
            $path = TemporaryUploadedFile::extractPathFromSignedPath($signedPath);

            if ($path === false) {
                abort(403, 'Invalid upload reference.');
            }

            return $path;
        })->toArray();

        $filename = $tmpPath[0];
        $disk = FileUploadConfiguration::disk();
        $storagePath = FileUploadConfiguration::path($filename, false);

        if (! Storage::disk($disk)->exists($storagePath)) {
            $this->dispatch('upload:errored', name: $name)->self();

            return;
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        if (! app(ImageOptimizer::class)->allowsExtension($extension)
            || Storage::disk($disk)->size($storagePath) > 5 * 1024 * 1024) {
            Storage::disk($disk)->delete($storagePath);
            Storage::disk($disk)->delete($storagePath.'.json');
            $this->dispatch('upload:errored', name: $name)->self();

            return;
        }

        try {
            $newPath = app(ImageOptimizer::class)->optimizeLivewireTempFile($disk, $storagePath, 'product');
        } catch (\Throwable) {
            $this->dispatch('upload:errored', name: $name)->self();

            return;
        }

        $this->upload = $newPath;

        $this->dispatch('upload:finished', name: $name, tmpFilenames: [$filename])->self();
        app('livewire')->updateProperty($this, $name, $newPath);
    }

    public function render(): View
    {
        return view('livewire.admin.products.product-form', [
            'brands' => Brand::query()->ordered()->get(),
            'categories' => Category::query()->ordered()->get(),
        ]);
    }
}
