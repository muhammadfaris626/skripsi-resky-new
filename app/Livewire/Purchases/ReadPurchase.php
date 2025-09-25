<?php

namespace App\Livewire\Purchases;

use App\Models\Purchase;
use Livewire\Component;

class ReadPurchase extends Component
{
    public Purchase $purchase;

    public function mount($id)
    {
        $this->purchase = Purchase::with(['employee', 'itemPurchases.product'])->findOrFail($id);
        $this->authorize('view', $this->purchase);
    }

    public function render()
    {
        return view('livewire.purchases.read-purchase');
    }
}
