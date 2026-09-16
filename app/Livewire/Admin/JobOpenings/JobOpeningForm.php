<?php

namespace App\Livewire\Admin\JobOpenings;

use App\Models\JobOpening;
use App\Services\ImageOptimizer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Oferta Laboral')]
class JobOpeningForm extends Component
{
    use WithFileUploads;

    public ?JobOpening $jobOpening = null;

    public string $title = '';

    public string $description = '';

    public string $application_email = '';

    public string $valid_from = '';

    public string $valid_until = '';

    public bool $is_active = true;

    public $image;

    public function mount(?JobOpening $jobOpening = null): void
    {
        $this->jobOpening = $jobOpening;

        if ($this->jobOpening?->exists) {
            $this->title = $this->jobOpening->title;
            $this->description = $this->jobOpening->description ?? '';
            $this->application_email = $this->jobOpening->application_email ?? '';
            $this->valid_from = $this->jobOpening->valid_from?->format('Y-m-d') ?? '';
            $this->valid_until = $this->jobOpening->valid_until?->format('Y-m-d') ?? '';
            $this->is_active = $this->jobOpening->is_active;
        }
    }

    public function save(): void
    {
        $this->authorize($this->jobOpening?->exists ? 'update' : 'create', $this->jobOpening ?? JobOpening::class);

        $data = $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'application_email' => 'nullable|email|max:255',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        if ($this->image && is_string($this->image)) {
            $data['image_path'] = $this->image;

            if ($this->jobOpening?->exists && $this->jobOpening->image_path) {
                Storage::disk('public')->delete($this->jobOpening->image_path);
            }
        }

        if ($this->jobOpening?->exists) {
            $this->jobOpening->update($data);
        } else {
            $this->jobOpening = JobOpening::query()->create($data);
        }

        $this->dispatch('notify', message: 'Oferta laboral guardada correctamente.');
        $this->redirect(route('admin.job-openings.index'), navigate: true);
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
            $newPath = app(ImageOptimizer::class)->optimizeLivewireTempFile($disk, $storagePath, 'job');
        } catch (\Throwable) {
            $this->dispatch('upload:errored', name: $name)->self();

            return;
        }

        $this->image = $newPath;

        $this->dispatch('upload:finished', name: $name, tmpFilenames: [$filename])->self();
        app('livewire')->updateProperty($this, $name, $newPath);
    }

    public function render(): View
    {
        return view('livewire.admin.job-openings.job-opening-form');
    }
}
