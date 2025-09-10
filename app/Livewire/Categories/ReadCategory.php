<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class ReadCategory extends Component
{
    public $data;

    public function mount($id) {
        $this->data = Category::findOrFail($id);
    }
    public function render()
    {
        return view('livewire.categories.read-category', [
            'data' => $this->data
        ]);
    }
}
