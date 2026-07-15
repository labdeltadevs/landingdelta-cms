<?php

namespace App\Livewire\Public\Search;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProductSearch extends Component
{
    public string $query = '';

    public function render(): View
    {
        $results = [];

        if (strlen($this->query) >= 2) {
            $results = Product::query()
                ->active()
                ->search($this->query)
                ->with('brand', 'category')
                ->limit(8)
                ->get();
        }

        return view('livewire.public.search.product-search', compact('results'));
    }
}
