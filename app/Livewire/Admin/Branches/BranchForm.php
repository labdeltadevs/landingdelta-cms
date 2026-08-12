<?php

namespace App\Livewire\Admin\Branches;

use App\Models\Branch;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Sucursal')]
class BranchForm extends Component
{
    public ?Branch $branch = null;

    public string $name = '';

    public string $city = '';

    public string $address = '';

    public string $phone = '';

    public string $phone_2 = '';

    public string $email = '';

    public function mount(?Branch $branch = null): void
    {
        $this->branch = $branch;
        if ($branch->exists) {
            $this->name = $branch->name;
            $this->city = $branch->city;
            $this->address = $branch->address;
            $this->phone = $branch->phone ?? '';
            $this->phone_2 = $branch->phone_2 ?? '';
            $this->email = $branch->email ?? '';
        }
    }

    public function save(): void
    {
        $this->authorize($this->branch?->exists ? 'update' : 'create', $this->branch ?? Branch::class);

        $data = $this->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:50',
            'phone_2' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        if ($this->branch?->exists) {
            $this->branch->update($data);
        } else {
            $this->branch = Branch::query()->create($data);
        }

        $this->dispatch('notify', message: 'Sucursal guardada correctamente.');
        $this->redirect(route('admin.branches.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.branches.branch-form');
    }
}
