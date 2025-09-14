<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class IndexProduct extends Component
{
    use WithPagination;

    // Search and filter properties
    public $search = '';
    public $filterCategory = '';
    public $filterStock = '';
    public $sortField = 'product_name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Delete properties
    public $isDeleteModalOpen = false;
    public $deleteId;

    // Categories for filter dropdown
    public $categories;

    protected $listeners = [
        'productCreated' => 'refreshProducts',
        'productUpdated' => 'refreshProducts',
    ];

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
    }

    public function refreshProducts()
    {
        $this->resetPage();
    }

    // Lifecycle hooks for pagination reset
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function updatingFilterStock()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    // Sorting method
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete($id)
    {
        Product::find($id)->delete();
        LivewireAlert::text('Data deleted successfully.')->success()->toast()->position('top-end')->show();
    }

    // Get products with filters using model scopes
    public function getProductsProperty()
    {
        $query = Product::with('category');

        // Search using scope
        if ($this->search) {
            $query->search($this->search);
        }

        // Filter by category using scope
        if ($this->filterCategory) {
            $query->byCategory($this->filterCategory);
        }

        // Filter by stock using scopes
        if ($this->filterStock !== '') {
            switch ($this->filterStock) {
                case 'out_of_stock':
                    $query->outOfStock();
                    break;
                case 'low_stock':
                    $query->lowStock();
                    break;
                case 'in_stock':
                    $query->inStock();
                    break;
            }
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.products.index-product', [
            'fetch' => $this->products
        ]);
    }
}
