<?php

namespace App\Livewire\Employees;

use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class CreateEmployee extends Component
{
    public $name, $position="", $phone, $email, $action;

    public function setAction($action) {
        $this->action = $action;
        $this->store();
    }

    public function store() {
        request()->merge([
            'name' => $this->name,
            'position' => $this->position,
            'phone' => $this->phone,
            'email' => $this->email
        ]);
        $validated = app(EmployeeRequest::class)->validated();
        Employee::create($validated);
        $this->reset(['name', 'position', 'phone', 'email']);
        if ($this->action === 'save_and_add') {
            LivewireAlert::text('Data added successfully.')->success()->toast()->position('top-end')->show();
            return back();
        } else {
            session()->flash('success', 'Data added successfully.');
            return to_route('employees.index');
        }
    }

    public function render()
    {
        return view('livewire.employees.create-employee');
    }
}
