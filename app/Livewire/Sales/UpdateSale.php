<?php

namespace App\Livewire\Sales;

use App\Models\Employee;
use App\Models\Product;
use App\Models\Sale;
use App\Models\ItemSale;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UpdateSale extends Component
{
    public $saleId;
    public $invoice_number, $date, $employee_id = '', $total_amount, $payment_method = '', $action = 'update';
    public $sale_items = [];

    protected $rules = [
        'date' => 'required|date',
        'payment_method' => 'required|in:Tunai,Transfer,Kartu',
        'employee_id' => 'required|exists:employees,id',
        'sale_items' => 'required|array|min:1',
        'sale_items.*.product_id' => 'required|exists:products,id',
        'sale_items.*.quantity' => 'required|integer|min:1',
        'sale_items.*.price' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'date.required' => 'Tanggal harus diisi',
        'payment_method.required' => 'Metode pembayaran harus dipilih',
        'employee_id.required' => 'Karyawan harus dipilih',
        'sale_items.required' => 'Minimal harus ada satu item',
        'sale_items.min' => 'Minimal harus ada satu item',
        'sale_items.*.product_id.required' => 'Produk harus dipilih',
        'sale_items.*.quantity.required' => 'Kuantitas harus diisi',
        'sale_items.*.quantity.min' => 'Kuantitas minimal 1',
        'sale_items.*.price.required' => 'Harga harus diisi',
    ];

    public function mount($id)
    {
        $this->saleId = $id;
        $sale = Sale::with('itemSales.product')->findOrFail($id);

        $this->invoice_number = $sale->invoice_number;
        $this->date = $sale->date ? $sale->date->format('Y-m-d') : null;
        $this->employee_id = $sale->employee_id;
        $this->payment_method = $sale->payment_method;
        $this->total_amount = $sale->total_amount;

        $this->sale_items = [];
        foreach ($sale->itemSales as $item) {
            $product = $item->product;
            // For existing items, available stock should include the quantity reserved by this sale
            $availableStock = $product ? ($product->stock + $item->quantity) : 0;
            $this->sale_items[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'amount' => $item->amount,
                'stock' => $availableStock,
                'original_quantity' => $item->quantity,
                'product_name' => $product ? $product->product_name : '',
            ];
        }

        if (empty($this->sale_items)) {
            $this->sale_items[] = [
                'product_id' => '',
                'quantity' => 1,
                'price' => 0,
                'amount' => 0,
                'original_quantity' => 0,
            ];
        }

        // Recalculate accurate available stock per item (accounts for multiple items with same product)
        $this->recalculateStocks();
    }

    public function addItem()
    {
        $this->sale_items[] = [
            'product_id' => '',
            'quantity' => 1,
            'price' => 0,
            'amount' => 0,
            'stock' => 0,
            'original_quantity' => 0,
            'product_name' => '',
        ];
    }

    public function removeItem($index)
    {
        if (count($this->sale_items) > 1) {
            unset($this->sale_items[$index]);
            $this->sale_items = array_values($this->sale_items);
        }
    }

    public function updatedSaleItems($value, $key)
    {
        $parts = explode('.', $key);
        $index = $parts[0];
        $field = $parts[1];

        if ($field === 'product_id') {
            $product = Product::find($value);
            if ($product) {
                // If this item was previously part of the sale, keep its original reserved quantity
                $original = $this->sale_items[$index]['original_quantity'] ?? 0;
                // available stock includes original reserved amount
                $this->sale_items[$index]['price'] = $product->selling_price;
                // compute available stock considering other items
                $this->sale_items[$index]['stock'] = $this->computeAvailableForIndex($index);
                $this->sale_items[$index]['product_name'] = $product->product_name;
                $this->calculateAmount($index);
            } else {
                $this->sale_items[$index]['price'] = 0;
                $this->sale_items[$index]['stock'] = 0;
                $this->sale_items[$index]['product_name'] = '';
                $this->sale_items[$index]['amount'] = 0;
                $this->sale_items[$index]['original_quantity'] = 0;
            }
        } elseif ($field === 'quantity' || $field === 'price') {
            $this->calculateAmount($index);
        }

        // Update available stocks for all items after any change
        $this->recalculateStocks();
    }

    public function increaseQuantity($index)
    {
        $available = $this->computeAvailableForIndex($index);
        $current = $this->sale_items[$index]['quantity'] ?? 0;
        if ($current < $available) {
            $this->sale_items[$index]['quantity'] = $current + 1;
            $this->calculateAmount($index);
            $this->recalculateStocks();
        } else {
            session()->flash('warning', 'Quantity cannot exceed available stock!');
        }
    }

    private function computeAvailableForIndex($index)
    {
        $item = $this->sale_items[$index] ?? null;
        if (! $item) return 0;
        $productId = $item['product_id'] ?? null;
        if (! $productId) return 0;

        $product = Product::find($productId);
        if (! $product) return 0;

        // DB stock is current stock (after original sale decreased it)
        $dbStock = $product->stock;

        // total original reserved for this product across all current sale items
        $totalOriginal = 0;
        foreach ($this->sale_items as $it) {
            if (($it['product_id'] ?? null) == $productId) {
                $totalOriginal += ($it['original_quantity'] ?? 0);
            }
        }

        $availableTotal = $dbStock + $totalOriginal;

        // current quantity reserved by other items (exclude this index)
        $otherCurrent = 0;
        foreach ($this->sale_items as $k => $it) {
            if ($k == $index) continue;
            if (($it['product_id'] ?? null) == $productId) {
                $otherCurrent += ($it['quantity'] ?? 0);
            }
        }

        $allowed = $availableTotal - $otherCurrent;
        return $allowed > 0 ? $allowed : 0;
    }

    private function recalculateStocks()
    {
        foreach ($this->sale_items as $i => $it) {
            $this->sale_items[$i]['stock'] = $this->computeAvailableForIndex($i);
        }
    }

    public function decreaseQuantity($index)
    {
        if ($this->sale_items[$index]['quantity'] > 1) {
            $this->sale_items[$index]['quantity']--;
            $this->calculateAmount($index);
        }
    }

    private function calculateAmount($index)
    {
        $quantity = $this->sale_items[$index]['quantity'] ?? 0;
        $price = $this->sale_items[$index]['price'] ?? 0;
        $this->sale_items[$index]['amount'] = $quantity * $price;
    }

    public function getTotalAmount()
    {
        return collect($this->sale_items)->sum('amount');
    }

    public function setAction($action)
    {
        $this->action = $action;
        $this->store();
    }

    public function update()
    {
        $this->validate();

        // Additional checks
        foreach ($this->sale_items as $i => $item) {
            $product = Product::find($item['product_id']);
            if (! $product) {
                $this->addError("sale_items.$i.product_id", 'Produk tidak ditemukan');
                return;
            }

            $quantity = (int) ($item['quantity'] ?? 0);
            if ($quantity <= 0) {
                $this->addError("sale_items.$i.quantity", 'Kuantitas harus lebih dari 0');
                return;
            }
        }

        $total = $this->getTotalAmount();

        try {
            DB::transaction(function () use ($total) {
                // Reload sale with lock
                $sale = Sale::with('itemSales')->lockForUpdate()->findOrFail($this->saleId);

                // Restore stock from existing items
                foreach ($sale->itemSales as $old) {
                    $product = Product::where('id', $old->product_id)->lockForUpdate()->first();
                    if ($product) {
                        $product->stock = $product->stock + $old->quantity;
                        $product->save();
                    }
                }

                // Remove old item sales
                ItemSale::where('sale_id', $sale->id)->delete();

                // Update sale header
                $sale->update([
                    'invoice_number' => $this->invoice_number ?: $sale->invoice_number,
                    'date' => $this->date,
                    'employee_id' => $this->employee_id,
                    'total_amount' => $total,
                    'payment_method' => $this->payment_method,
                ]);

                // Create new item sales and decrement stock
                foreach ($this->sale_items as $item) {
                    $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();
                    if (! $product) {
                        throw new \Exception('Produk tidak ditemukan saat menyimpan');
                    }

                    $quantity = (int) $item['quantity'];
                    if ($quantity > $product->stock) {
                        throw new \Exception("Stok tidak mencukupi untuk produk {$product->product_name}");
                    }

                    $price = $item['price'] ?? $product->selling_price;
                    $amount = $quantity * $price;

                    ItemSale::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'amount' => $amount,
                    ]);

                    // decrement stock
                    $product->stock = $product->stock - $quantity;
                    $product->save();
                }
            });

            session()->flash('success', 'Data updated successfully.');
            return to_route('sales.index');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal memperbarui penjualan: ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        return view('livewire.sales.update-sale', [
            'employees' => Employee::all(),
            'products' => Product::all(),
        ]);
    }
}
