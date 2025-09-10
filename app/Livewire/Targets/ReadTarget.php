<?php

namespace App\Livewire\Targets;

use App\Models\Target;
use Livewire\Component;

class ReadTarget extends Component
{
    public $data;

    public function mount($id) {
        $this->data = Target::findOrFail($id);
    }
    public function render()
    {
        return view('livewire.targets.read-target', [
            'data' => $this->data
        ]);
    }
}
