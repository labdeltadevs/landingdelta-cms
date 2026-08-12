<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use App\Services\FileUploader;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Noticia')]
class NewsForm extends Component
{
    use WithFileUploads;

    public ?News $news = null;

    public string $title = '';

    public string $excerpt = '';

    public string $body = '';

    public bool $is_active = true;

    public $published_at = '';

    public $cover;

    public function mount(?News $news = null): void
    {
        $this->news = $news;
        if ($this->news?->exists) {
            $this->title = $news->title;
            $this->excerpt = $news->excerpt ?? '';
            $this->body = $news->body ?? '';
            $this->is_active = $news->is_active;
            $this->published_at = $news->published_at?->format('Y-m-d\TH:i') ?? '';
        }
    }

    public function save(FileUploader $uploader): void
    {
        $this->authorize($this->news?->exists ? 'update' : 'create', $this->news ?? News::class);

        $data = $this->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
            'cover' => 'nullable|image|max:4096',
        ]);

        $data['slug'] = $this->news?->exists
            ? Str::slug($this->title).'-'.$this->news->id
            : Str::slug($this->title).'-'.Str::lower(Str::random(5));

        if ($this->cover) {
            $data['cover_image_path'] = $uploader->upload($this->cover, 'news/covers');
            if ($this->news?->exists) {
                $uploader->delete($this->news->cover_image_path);
            }
        }

        if ($this->news?->exists) {
            $this->news->update($data);
        } else {
            $this->news = News::query()->create($data);
        }

        $this->dispatch('notify', message: 'Noticia guardada correctamente.');
        $this->redirect(route('admin.news.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.news.news-form');
    }
}
