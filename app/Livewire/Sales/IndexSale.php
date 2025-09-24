<?php

namespace App\Livewire\Sales;

use App\Models\Category;
use App\Models\Sale;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class IndexSale extends Component
{
    use WithPagination;
    public $search = '';
    public $filterCategory = '';
    public $perPage = 10;
    public $categories;
    public function mount() {
        $this->categories = Category::orderBy('name')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function getSalesProperty()
    {
        $query = Sale::query()
            ->when($this->search, function ($q) {
                $search = $this->search;
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($qe) use ($search) {
                      $qe->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('itemSales.product', function ($qe) use ($search) {
                      $qe->where('product_name', 'like', "%{$search}%");
                  });
            })
            ->when($this->filterCategory, function ($q) {
                $q->whereHas('itemSales.product', function ($qe) {
                    $qe->where('category_id', $this->filterCategory);
                });
            })
            ->orderBy('created_at', 'desc');

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.sales.index-sale', [
            'fetch' => $this->sales
        ]);
    }
}
