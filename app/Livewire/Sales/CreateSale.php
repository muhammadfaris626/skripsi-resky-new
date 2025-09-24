<?php

namespace App\Livewire\Sales;

use App\Models\Employee;
use App\Models\Product;
use App\Models\Sale;
use App\Models\ItemSale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateSale extends Component
{
    public $invoice_number, $date, $employee_id = '', $total_amount, $payment_method = '', $action = 'save';
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

    public function mount() {
        $this->sale_items[] = [
            'product_id' => '',
            'quantity' => 1,
            'price' => 0,
            'amount' => 0,
        ];
    }

    public function addItem()
    {
        $this->sale_items[] = [
            'product_id' => '',
            'quantity' => 1,
            'price' => 0,
            'amount' => 0,
            'stock' => 0,
            'product_name' => '',
        ];
    }

    public function removeItem($index)
    {
        if (count($this->sale_items) > 1) {
            unset($this->sale_items[$index]);
            $this->sale_items = array_values($this->sale_items); // Re-index array
        }
    }

    public function updatedSaleItems($value, $key)
    {
        // Parse the key to get index and field
        $parts = explode('.', $key);
        $index = $parts[0];
        $field = $parts[1];

        if ($field === 'product_id') {
            $product = Product::find($value);
            if ($product) {
                // use selling_price from product model
                $this->sale_items[$index]['price'] = $product->selling_price;
                $this->sale_items[$index]['stock'] = $product->stock;
                $this->sale_items[$index]['product_name'] = $product->product_name;
                $this->calculateAmount($index);
            } else {
                // Reset if no product selected
                $this->sale_items[$index]['price'] = 0;
                $this->sale_items[$index]['stock'] = 0;
                $this->sale_items[$index]['product_name'] = '';
                $this->sale_items[$index]['amount'] = 0;
            }
        } elseif ($field === 'quantity' || $field === 'price') {
            $this->calculateAmount($index);
        }
    }

    public function store()
    {
        // Validate basic shape
        $this->validate();

        // Additional checks: ensure quantities do not exceed current stock
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

            if ($quantity > $product->stock) {
                $this->addError("sale_items.$i.quantity", "Kuantitas melebihi stok tersedia ({$product->stock})");
                return;
            }
        }

        // Calculate total
        $total = $this->getTotalAmount();

        // Generate invoice number if not provided
        $invoice = $this->invoice_number ?: 'INV-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

        try {
            DB::transaction(function () use ($invoice, $total) {
                $sale = Sale::create([
                    'invoice_number' => $invoice,
                    'date' => $this->date,
                    'employee_id' => $this->employee_id,
                    'total_amount' => $total,
                    'payment_method' => $this->payment_method,
                ]);

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

            session()->flash('success', 'Data added successfully.');
            // reset form for new entry
            $this->invoice_number = null;
            $this->date = null;
            $this->employee_id = '';
            $this->payment_method = '';
            $this->sale_items = [];
            $this->mount();
            return to_route('sales.index');
        } catch (\Throwable $e) {
            // Bubble up a friendly message
            session()->flash('error', 'Gagal menyimpan penjualan: ' . $e->getMessage());
            return;
        }
    }

    public function increaseQuantity($index)
    {
        // Check if quantity doesn't exceed stock
        $maxStock = $this->sale_items[$index]['stock'] ?? 0;
        if ($this->sale_items[$index]['quantity'] < $maxStock) {
            $this->sale_items[$index]['quantity']++;
            $this->calculateAmount($index);
        } else {
            session()->flash('warning', 'Quantity cannot exceed available stock!');
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

    public function setAction($action) {
        $this->action = $action;
        $this->store();
    }

    public function render()
    {
        return view('livewire.sales.create-sale', [
            'employees' => Employee::all(),
            'products' => Product::all()
        ]);
    }
}
