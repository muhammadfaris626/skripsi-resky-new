<?php

namespace App\Livewire\Databases\Permissions;

use App\Models\Permission;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class UpdatePermission extends Component
{
    public $permission;
    public $name = '';

    public function mount($id)
    {
        $this->permission = Permission::findOrFail($id);
        $this->name = $this->permission->name;
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($this->permission->id)],
        ];
    }

    public function render()
    {
        return view('livewire.databases.permissions.update-permission');
    }

    public function update()
    {
        $this->validate();

        $this->permission->update([
            'name' => $this->name,
        ]);

        session()->flash('success', 'Permission updated successfully.');
        return to_route('permissions.index');
    }
}
