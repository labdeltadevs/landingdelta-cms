<?php

namespace App\Livewire\Admin\Brochures;

use App\Models\Brochure;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Rotafolios')]
class BrochureIndex extends Component
{
    use WithPagination;

    public function delete(int $id): void
    {
        $brochure = Brochure::query()->findOrFail($id);
        $this->authorize('delete', $brochure);
        $brochure->delete();
    }

    public function render(): View
    {
        $brochures = Brochure::query()->orderBy('sort')->paginate(15);

        return view('livewire.admin.brochures.brochure-index', compact('brochures'));
    }
}
