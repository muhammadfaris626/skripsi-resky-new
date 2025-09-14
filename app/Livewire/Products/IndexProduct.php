<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class IndexProduct extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $stockFilter = '';
    public $priceRangeFilter = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'stockFilter' => ['except' => ''],
        'priceRangeFilter' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingStockFilter()
    {
        $this->resetPage();
    }

    public function updatingPriceRangeFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'categoryFilter',
            'stockFilter',
            'priceRangeFilter',
            'sortBy',
            'sortDirection',
            'perPage'
        ]);
        $this->resetPage();
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            session()->flash('success', 'Produk berhasil dihapus.');
        }
    }

    public function render()
    {
        $query = Product::with('category');

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('product_name', 'like', '%' . $this->search . '%')
                  ->orWhere('product_code', 'like', '%' . $this->search . '%')
                  ->orWhereHas('category', function($categoryQuery) {
                      $categoryQuery->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter by category
        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        // Filter by stock status
        if ($this->stockFilter) {
            switch ($this->stockFilter) {
                case 'in_stock':
                    $query->inStock();
                    break;
                case 'out_of_stock':
                    $query->outOfStock();
                    break;
                case 'low_stock':
                    $query->lowStock(10);
                    break;
            }
        }

        // Filter by price range
        if ($this->priceRangeFilter) {
            switch ($this->priceRangeFilter) {
                case 'under_100k':
                    $query->where('selling_price', '<', 100000);
                    break;
                case '100k_500k':
                    $query->whereBetween('selling_price', [100000, 500000]);
                    break;
                case '500k_1m':
                    $query->whereBetween('selling_price', [500000, 1000000]);
                    break;
                case 'above_1m':
                    $query->where('selling_price', '>', 1000000);
                    break;
            }
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $products = $query->paginate($this->perPage);
        $categories = Category::orderBy('name')->get();

        return view('livewire.products.index-product', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}
