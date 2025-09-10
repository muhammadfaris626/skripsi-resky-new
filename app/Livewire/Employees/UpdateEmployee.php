<?php

namespace App\Livewire\Employees;

use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Livewire\Component;

class UpdateEmployee extends Component
{
    public $id, $name, $position, $phone, $email;
    public function render()
    {
        return view('livewire.employees.update-employee');
    }

    public function mount($id) {
        $data = Employee::findOrFail($id);
        $this->fill($data->only(['id', 'name', 'position', 'phone', 'email']));
    }

    public function update() {
        request()->merge([
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position,
            'phone' => $this->phone,
            'email' => $this->email
        ]);
        $validated = app(EmployeeRequest::class)->validated();
        Employee::findOrFail($this->id)->update($validated);
        session()->flash('success', 'Data updated successfully.');
        return to_route('employees.index');
    }
}
