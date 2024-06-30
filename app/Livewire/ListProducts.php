<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class ListProducts extends Component
{
    public Category | null $category = null;
    public $products;
    public string $title = 'Discover';

    public function mount()
    {
        $keyword = Request::get('keyword');

        if ($this->category) {
            $this->title = 'Category ' . $this->category->nama;
            return $this->products = Product::whereBelongsTo($this->category)->get();
        }

        if ($keyword) {
            $this->title = 'Searching "' . $keyword . '"';
            return $this->products = Product::where('nama', 'LIKE', '%' . $keyword . '%')->get();
        }

        return $this->products = Product::all();
    }

    public function render()
    {
        return view('livewire.list-products');
    }
}
