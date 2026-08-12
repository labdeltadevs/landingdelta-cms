<?php

namespace App\Livewire\Admin\Brochures;

use App\Models\Brochure;
use App\Services\FileUploader;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Rotafolio')]
class BrochureForm extends Component
{
    use WithFileUploads;

    public ?Brochure $brochure = null;

    public string $title = '';

    public string $description = '';

    public string $line = '';

    public bool $is_active = true;

    public $file;

    public function mount(?Brochure $brochure = null): void
    {
        $this->brochure = $brochure;
        if ($this->brochure?->exists) {
            $this->title = $brochure->title;
            $this->description = $brochure->description ?? '';
            $this->line = $brochure->line ?? '';
            $this->is_active = $brochure->is_active;
        }
    }

    public function save(FileUploader $uploader): void
    {
        $this->authorize($this->brochure?->exists ? 'update' : 'create', $this->brochure ?? Brochure::class);

        $data = $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'line' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'file' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        if ($this->file) {
            $data['file_path'] = $uploader->upload($this->file, 'brochures');
            if ($this->brochure?->exists) {
                $uploader->delete($this->brochure->file_path);
            }
        }

        if ($this->brochure?->exists) {
            $this->brochure->update($data);
        } else {
            $this->brochure = Brochure::query()->create($data);
        }

        $this->dispatch('notify', message: 'Rotafolio guardado correctamente.');
        $this->redirect(route('admin.brochures.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.brochures.brochure-form');
    }
}
