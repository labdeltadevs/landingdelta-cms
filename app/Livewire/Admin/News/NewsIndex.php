<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Noticias')]
class NewsIndex extends Component
{
    use WithPagination;

    public string $search = '';
    protected $queryString = ['search'];
    public function updatingSearch(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        $news = News::query()->findOrFail($id);
        $this->authorize('delete', $news);
        $news->delete();
    }

    public function render(): View
    {
        $news = News::query()
            ->where('title', 'like', '%'.$this->search.'%')
            ->orderByDesc('published_at')
            ->paginate(15);

        return view('livewire.admin.news.news-index', compact('news'));
    }
}
