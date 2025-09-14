<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;

class ReadProduct extends Component
{
    public $data;

    public function mount($id) {
        $this->data = Product::findOrFail($id);
    }
    public function render()
    {
        return view('livewire.products.read-product', [
            'data' => $this->data
        ]);
    }
}
