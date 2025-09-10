<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class IndexCategory extends Component
{
    use WithPagination;
    public $search = '';
    public $perPage = 10;
    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];
    public function render()
    {
        $data = Category::query()
            ->select('id', 'name', 'created_at')
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'LIKE', '%' . $this->search . '%');
                });
            })->latest('created_at')->paginate($this->perPage);
        return view('livewire.categories.index-category', [
            'fetch' => $data
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete($id) {
        $data = Category::findOrFail($id);
        $data->delete();
        LivewireAlert::text('Data deleted successfully.')->success()->toast()->position('top-end')->show();
        return back();
    }
}
