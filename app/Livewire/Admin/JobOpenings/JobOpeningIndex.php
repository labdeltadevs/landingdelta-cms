<?php

namespace App\Livewire\Admin\JobOpenings;

use App\Models\JobOpening;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Ofertas Laborales')]
class JobOpeningIndex extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $jobOpening = JobOpening::query()->findOrFail($id);
        $this->authorize('delete', $jobOpening);
        $jobOpening->delete();
    }

    public function render(): View
    {
        $jobOpenings = JobOpening::query()
            ->where('title', 'like', '%'.$this->search.'%')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.admin.job-openings.job-opening-index', compact('jobOpenings'));
    }
}
