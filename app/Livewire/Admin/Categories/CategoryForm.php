<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Categoría')]
class CategoryForm extends Component
{
    public ?Category $category = null;

    public string $name = '';

    public string $description = '';

    public function mount(?Category $category = null): void
    {
        $this->category = $category;
        if ($this->category?->exists) {
            $this->name = $category->name;
            $this->description = $category->description ?? '';
        }
    }

    public function save(): void
    {
        $this->authorize($this->category?->exists ? 'update' : 'create', $this->category ?? Category::class);

        $data = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($this->name);

        if ($this->category?->exists) {
            $this->category->update($data);
        } else {
            $this->category = Category::query()->create($data);
        }

        $this->dispatch('notify', message: 'Categoría guardada correctamente.');
        $this->redirect(route('admin.categories.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.categories.category-form');
    }
}
