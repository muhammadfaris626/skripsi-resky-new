<?php

namespace App\Livewire\Targets;

use App\Http\Requests\TargetRequest;
use App\Models\Employee;
use App\Models\Target;
use Livewire\Component;

class UpdateTarget extends Component
{
    public $id, $employee_id, $month, $sale_target;
    public function render()
    {
        return view('livewire.targets.update-target', [
            'employees' => Employee::orderBy('name')->get()
        ]);
    }

    public function mount($id) {
        $data = Target::findOrFail($id);
        $this->fill($data->only(['id', 'employee_id', 'month', 'sale_target']));
    }

    public function update() {
        request()->merge([
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'month' => $this->month,
            'sale_target' => $this->sale_target
        ]);
        $validated = app(TargetRequest::class)->validated();
        Target::findOrFail($this->id)->update($validated);
        session()->flash('success', 'Data updated successfully.');
        return to_route('targets.index');
    }
}
