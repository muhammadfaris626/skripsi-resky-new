<?php

namespace App\Livewire\Categories;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class CreateCategory extends Component
{
    public $name, $action;

    public function setAction($action) {
        $this->action = $action;
        $this->store();
    }

    public function render()
    {
        return view('livewire.categories.create-category');
    }

    public function store() {
        request()->merge([
            'name' => $this->name
        ]);
        $validated = app(CategoryRequest::class)->validated();
        Category::create($validated);
        $this->reset(['name']);
        if ($this->action === 'save_and_add') {
            LivewireAlert::text('Data added successfully.')->success()->toast()->position('top-end')->show();
            return back();
        } else {
            session()->flash('success', 'Data added successfully.');
            return to_route('categories.index');
        }
    }
}
