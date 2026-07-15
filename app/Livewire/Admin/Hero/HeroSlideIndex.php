<?php

namespace App\Livewire\Admin\Hero;

use App\Models\HeroSlide;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Slides del Hero')]
class HeroSlideIndex extends Component
{
    public function delete(int $id): void
    {
        $slide = HeroSlide::query()->findOrFail($id);
        $this->authorize('delete', $slide);
        $slide->delete();
    }

    public function render(): View
    {
        $slides = HeroSlide::query()->orderBy('sort')->get();

        return view('livewire.admin.hero.hero-slide-index', compact('slides'));
    }
}
