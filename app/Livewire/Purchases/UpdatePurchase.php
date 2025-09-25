<?php

namespace App\Livewire\Purchases;

use App\Models\Purchase;
use App\Models\Employee;
use App\Models\Product;
use App\Models\ItemPurchase;
use Livewire\Component;
class UpdatePurchase extends Component
{

    public Purchase $purchase;
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
        'invoice_number' => 'required|string',
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

    public function mount($id)
    {
        $this->purchase = Purchase::with(['employee', 'itemPurchases.product'])->findOrFail($id);
        $this->authorize('update', $this->purchase);

        // Load purchase data
        $this->invoice_number = $this->purchase->invoice_number;
        $this->date = $this->purchase->date;
        $this->employee_id = $this->purchase->employee_id;
        $this->supplier_name = $this->purchase->supplier_name;
        $this->payment_method = $this->purchase->payment_method;
        $this->notes = $this->purchase->notes;

        // Load items
        $this->items = $this->purchase->itemPurchases->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->product_name,
                'product_code' => $item->product->product_code,
                'quantity' => $item->quantity,
                'price' => (float) $item->price,
                'amount' => (float) $item->amount,
                'original_quantity' => $item->quantity, // For stock adjustment
            ];
        })->toArray();
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
                'id' => null, // New item
                'product_id' => $this->selectedProduct,
                'product_name' => $product->product_name,
                'product_code' => $product->product_code,
                'quantity' => $this->quantity,
                'price' => $this->price,
                'amount' => $this->quantity * $this->price,
                'original_quantity' => 0, // New item has no original quantity
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

        // Update purchase
        $this->purchase->update([
            'invoice_number' => $this->invoice_number,
            'date' => $this->date,
            'employee_id' => $this->employee_id,
            'supplier_name' => $this->supplier_name,
            'total_amount' => (string) $this->getTotalAmount(),
            'payment_method' => $this->payment_method,
            'notes' => $this->notes,
        ]);

        // Handle item updates
        $existingItemIds = collect($this->items)->pluck('id')->filter()->toArray();

        // Delete removed items and adjust stock
        $this->purchase->itemPurchases()->whereNotIn('id', $existingItemIds)->each(function ($item) {
            // Reduce stock (remove the quantity that was added during purchase)
            $item->product->decrement('stock', $item->quantity);
            $item->delete();
        });

        // Update or create items
        foreach ($this->items as $itemData) {
            if ($itemData['id']) {
                // Update existing item
                $item = ItemPurchase::find($itemData['id']);
                $oldQuantity = $item->quantity;

                $item->update([
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'amount' => $itemData['amount'],
                ]);

                // Adjust stock based on quantity difference
                $quantityDiff = $itemData['quantity'] - $oldQuantity;
                if ($quantityDiff != 0) {
                    if ($quantityDiff > 0) {
                        $item->product->increment('stock', $quantityDiff);
                    } else {
                        $item->product->decrement('stock', abs($quantityDiff));
                    }
                }
            } else {
                // Create new item
                ItemPurchase::create([
                    'purchase_id' => $this->purchase->id,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'amount' => $itemData['amount'],
                ]);

                // Add stock for new item
                Product::find($itemData['product_id'])->increment('stock', $itemData['quantity']);
            }
        }

        session()->flash('message', 'Purchase updated successfully!');
        return redirect()->route('purchases.index');
    }

    public function render()
    {
        $employees = Employee::orderBy('name')->get();
        $products = Product::orderBy('product_name')->get();

        return view('livewire.purchases.update-purchase', [
            'employees' => $employees,
            'products' => $products,
        ]);
    }
}
