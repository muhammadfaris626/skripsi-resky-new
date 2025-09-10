<?php

namespace App\Livewire\Targets;

use App\Http\Requests\TargetRequest;
use App\Models\Employee;
use App\Models\Target;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class CreateTarget extends Component
{
    public $employee_id = '';
    public $month = '';
    public $sale_target = '';
    public $action;
    public function render()
    {
        return view('livewire.targets.create-target', [
            'employees' => Employee::all()
        ]);
    }

    public function setAction($action) {
        $this->action = $action;
        $this->store();
    }

    public function store() {
        request()->merge([
            'employee_id' => $this->employee_id,
            'month' => $this->month,
            'sale_target' => $this->sale_target
        ]);
        $validated = app(TargetRequest::class)->validated();
        Target::create($validated);
        $this->reset(['employee_id', 'month', 'sale_target']);
        if ($this->action === 'save_and_add') {
            LivewireAlert::text('Data added successfully.')->success()->toast()->position('top-end')->show();
            return back();
        } else {
            session()->flash('success', 'Data added successfully.');
            return to_route('targets.index');
        }
    }
}
