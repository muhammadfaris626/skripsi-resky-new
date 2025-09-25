<?php

namespace App\Livewire\Purchases;

use App\Models\Purchase;
use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPurchase extends Component
{
    use WithPagination;

    public $search = '';
    public $filterEmployee = '';
    public $filterSupplier = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterEmployee' => ['except' => ''],
        'filterSupplier' => ['except' => ''],
    ];

    public function mount()
    {
        $this->authorize('viewAny', Purchase::class);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterEmployee()
    {
        $this->resetPage();
    }

    public function updatingFilterSupplier()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $purchase = Purchase::findOrFail($id);
        $this->authorize('delete', $purchase);

        // Reduce stock for each item in the purchase
        foreach ($purchase->itemPurchases as $item) {
            $item->product->decrement('stock', $item->quantity);
        }

        $purchase->delete();

        session()->flash('message', 'Purchase deleted successfully!');
    }

    public function render()
    {
        $query = Purchase::with(['employee', 'itemPurchases'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('invoice_number', 'like', '%' . $this->search . '%')
                      ->orWhere('supplier_name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('employee', function ($query) {
                          $query->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->filterEmployee, function ($query) {
                $query->where('employee_id', $this->filterEmployee);
            })
            ->when($this->filterSupplier, function ($query) {
                $query->where('supplier_name', 'like', '%' . $this->filterSupplier . '%');
            })
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        $fetch = $query->paginate($this->perPage);
        $employees = Employee::orderBy('name')->get();
        $suppliers = Purchase::select('supplier_name')
            ->distinct()
            ->orderBy('supplier_name')
            ->pluck('supplier_name');

        return view('livewire.purchases.index-purchase', [
            'fetch' => $fetch,
            'employees' => $employees,
            'suppliers' => $suppliers,
        ]);
    }
}
