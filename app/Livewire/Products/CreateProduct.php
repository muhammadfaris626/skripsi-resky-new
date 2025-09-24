<?php

namespace App\Livewire\Products;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class CreateProduct extends Component
{
    public $category_id="", $product_code, $product_name, $purchase_price, $selling_price, $stock, $action;

    public function setAction($action) {
        $this->action = $action;
        $this->store();
    }

    public function store()
    {
        $prefix = 'PRD';
        $number = str_pad(Product::count() + 1, 5, '0', STR_PAD_LEFT);
        $this->product_code = $prefix . '-' . $number;
        request()->merge([
            'category_id' => $this->category_id,
            'product_code' => $this->product_code,
            'product_name' => $this->product_name,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'stock' => $this->stock
        ]);
        $validated = app(ProductRequest::class)->validated();
        Product::create($validated);
        $this->reset(['category_id', 'product_code', 'product_name', 'purchase_price', 'selling_price', 'stock']);
        if ($this->action === 'save_and_add') {
            LivewireAlert::text('Data added successfully.')->success()->toast()->position('top-end')->show();
            return back();
        } else {
            session()->flash('success', 'Data added successfully.');
            return to_route('products.index');
        }
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();
        return view('livewire.products.create-product', compact('categories'));
    }
}
