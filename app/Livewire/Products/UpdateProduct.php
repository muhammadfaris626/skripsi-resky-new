<?php

namespace App\Livewire\Products;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class UpdateProduct extends Component
{
    public $id, $category_id="", $product_code, $product_name, $purchase_price, $selling_price, $stock;

    public function render()
    {
        return view('livewire.products.update-product', [
            'categories' => Category::orderBy('name')->get()
        ]);
    }

    public function mount($id) {
        $data = Product::findOrFail($id);
        $this->fill($data->only(['id', 'category_id', 'product_code', 'product_name', 'purchase_price', 'selling_price', 'stock']));
    }

    public function update() {
        request()->merge([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'product_code' => $this->product_code,
            'product_name' => $this->product_name,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'stock' => $this->stock
        ]);
        $validated = app(ProductRequest::class)->validated();
        Product::findOrFail($this->id)->update($validated);
        session()->flash('success', 'Data updated successfully.');
        return to_route('products.index');
    }
}
