<?php

namespace App\Livewire\Admin\JobOpenings;

use App\Livewire\Admin\Concerns\GeneratesQrCodes;
use App\Models\Brand;
use App\Models\JobOpening;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Ofertas Laborales')]
class JobOpeningIndex extends Component
{
    use GeneratesQrCodes;
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

    protected function qrTarget(int $id): JobOpening
    {
        return JobOpening::query()->findOrFail($id);
    }

    protected function qrRoute(Product|Brand|JobOpening $target): string
    {
        return route('public.work-with-us.show', $target);
    }

    protected function qrFilename(Model $target): string
    {
        return 'codigo-convocatoria-'.($target->slug ?? $target->getRouteKey()).'.jpg';
    }

    protected function barcodeValue(Product|Brand|JobOpening $target): string
    {
        return $target->slug;
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
