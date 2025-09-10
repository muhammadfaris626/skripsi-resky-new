<?php

namespace App\Livewire\Categories;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Livewire\Component;

class UpdateCategory extends Component
{
    public $id, $name;
    public function render()
    {
        return view('livewire.categories.update-category');
    }

    public function mount($id) {
        $data = Category::findOrFail($id);
        $this->fill($data->only(['id', 'name']));
    }

    public function update() {
        request()->merge([
            'id' => $this->id,
            'name' => $this->name
        ]);
        $validated = app(CategoryRequest::class)->validated();
        Category::findOrFail($this->id)->update($validated);
        session()->flash('success', 'Data updated successfully.');
        return to_route('categories.index');
    }
}
