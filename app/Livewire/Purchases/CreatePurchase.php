<?php

namespace App\Livewire\Purchases;

use App\Models\Purchase;
use App\Models\Employee;
use App\Models\Product;
use App\Models\ItemPurchase;
use Livewire\Component;
use Carbon\Carbon;

class CreatePurchase extends Component
{

    public $invoice_number = '';
    public $date = '';
    public $employee_id = '';
    public $supplier_name = '';
    public $payment_method = 'cash';
    public $notes = '';

    public $items = [];
    public $selectedProduct = '';
    public $quantity = 1;
    public $price = 0;

    protected $rules = [
        'invoice_number' => 'required|string|unique:purchases,invoice_number',
        'date' => 'required|date',
        'employee_id' => 'required|exists:employees,id',
        'supplier_name' => 'required|string|max:255',
        'payment_method' => 'required|in:cash,transfer,credit',
        'notes' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->authorize('create', Purchase::class);
        $this->date = Carbon::now()->format('Y-m-d');
        $this->generateInvoiceNumber();
        $this->employee_id = Employee::first()->id;
    }

    public function generateInvoiceNumber()
    {
        $date = Carbon::now()->format('Ymd');
        $lastPurchase = Purchase::whereDate('created_at', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->first();

        $number = $lastPurchase ? (int) substr($lastPurchase->invoice_number, -4) + 1 : 1;
        $this->invoice_number = 'PUR-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function updatedSelectedProduct()
    {
        if ($this->selectedProduct) {
            $product = Product::find($this->selectedProduct);
            if ($product) {
                $this->price = (float) $product->purchase_price;
            }
        }
    }

    public function addItem()
    {
        $this->validate([
            'selectedProduct' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::find($this->selectedProduct);

        // Check if product already exists in items
        $existingIndex = collect($this->items)->search(function ($item) {
            return $item['product_id'] == $this->selectedProduct;
        });

        if ($existingIndex !== false) {
            // Update existing item
            $this->items[$existingIndex]['quantity'] += $this->quantity;
            $this->items[$existingIndex]['amount'] = $this->items[$existingIndex]['quantity'] * $this->items[$existingIndex]['price'];
        } else {
            // Add new item
            $this->items[] = [
                'product_id' => $this->selectedProduct,
                'product_name' => $product->product_name,
                'product_code' => $product->product_code,
                'quantity' => $this->quantity,
                'price' => $this->price,
                'amount' => $this->quantity * $this->price,
            ];
        }

        // Reset form
        $this->reset(['selectedProduct', 'quantity', 'price']);
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updateItemQuantity($index, $quantity)
    {
        if ($quantity > 0) {
            $this->items[$index]['quantity'] = $quantity;
            $this->items[$index]['amount'] = $quantity * $this->items[$index]['price'];
        }
    }

    public function updateItemPrice($index, $price)
    {
        if ($price >= 0) {
            $this->items[$index]['price'] = $price;
            $this->items[$index]['amount'] = $this->items[$index]['quantity'] * $price;
        }
    }

    public function getTotalAmount()
    {
        return collect($this->items)->sum('amount');
    }

    public function save()
    {
        $this->validate();

        $purchase = Purchase::create([
            'invoice_number' => $this->invoice_number,
            'date' => $this->date,
            'employee_id' => $this->employee_id,
            'supplier_name' => $this->supplier_name,
            'total_amount' => (string) $this->getTotalAmount(),
            'payment_method' => $this->payment_method,
            'notes' => $this->notes,
        ]);

        foreach ($this->items as $item) {
            ItemPurchase::create([
                'purchase_id' => $purchase->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'amount' => $item['amount'],
            ]);

            // Update product stock
            Product::find($item['product_id'])->increment('stock', $item['quantity']);
        }
        session()->flash('success', 'Purchase created successfully!');
        return redirect()->route('purchases.index');
    }

    public function render()
    {
        $employees = Employee::orderBy('name')->get();
        $products = Product::orderBy('product_name')->get();

        return view('livewire.purchases.create-purchase', [
            'employees' => $employees,
            'products' => $products,
        ]);
    }
}
