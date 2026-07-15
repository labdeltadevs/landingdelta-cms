<?php

namespace App\Livewire\Admin\Hero;

use App\Models\HeroSlide;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Ordenar slides')]
class HeroSlideReorder extends Component
{
    public array $order = [];

    public function mount(): void
    {
        $this->order = HeroSlide::query()->orderBy('sort')->pluck('id')->toArray();
    }

    public function updateOrder(array $newOrder): void
    {
        $this->authorize('manage hero', HeroSlide::class);

        foreach ($newOrder as $index => $id) {
            HeroSlide::query()->where('id', $id)->update(['sort' => $index]);
        }

        $this->dispatch('notify', message: 'Orden actualizado.');
    }

    public function render(): View
    {
        $slides = HeroSlide::query()->orderBy('sort')->get();

        return view('livewire.admin.hero.hero-slide-reorder', compact('slides'));
    }
}
