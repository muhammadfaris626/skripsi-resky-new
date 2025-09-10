<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use Livewire\Component;

class ReadEmployee extends Component
{
    public $data;
    public function mount($id) {
        $this->data = Employee::findOrFail($id);
    }
    public function render()
    {
        return view('livewire.employees.read-employee', [
            'data' => $this->data
        ]);
    }
}
