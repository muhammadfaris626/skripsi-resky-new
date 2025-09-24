<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;

class ReadSale extends Component
{
    public $data;

    public function mount($id) {
        $this->data = Sale::findOrFail($id);
    }
    public function render()
    {
        return view('livewire.sales.read-sale', [
            'data' => $this->data
        ]);
    }
}
