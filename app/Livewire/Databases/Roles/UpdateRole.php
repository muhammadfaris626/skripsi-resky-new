<?php

namespace App\Livewire\Databases\Roles;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class UpdateRole extends Component
{
    public $role;
    public $name = '';
    public $selectedPermissions = [];

    public function mount($id)
    {
        $this->role = Role::with('permissions')->findOrFail($id);
        $this->name = $this->role->name;
        $this->selectedPermissions = $this->role->permissions->pluck('name')->toArray();
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($this->role->id)],
            'selectedPermissions' => 'required|array|min:1',
            'selectedPermissions.*' => 'exists:permissions,name',
        ];
    }

    public function render()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode(':', $permission->name)[0];
        });

        return view('livewire.databases.roles.update-role', [
            'permissions' => $permissions
        ]);
    }

    public function update()
    {
        $this->validate();

        $this->role->update([
            'name' => $this->name,
        ]);

        $this->role->syncPermissions($this->selectedPermissions);

        session()->flash('success', 'Role updated successfully.');
        return to_route('roles.index');
    }

    public function toggleAllPermissions($entity)
    {
        $entityPermissions = Permission::where('name', 'LIKE', $entity . ':%')->pluck('name')->toArray();

        $allSelected = !array_diff($entityPermissions, $this->selectedPermissions);

        if ($allSelected) {
            $this->selectedPermissions = array_diff($this->selectedPermissions, $entityPermissions);
        } else {
            $this->selectedPermissions = array_unique(array_merge($this->selectedPermissions, $entityPermissions));
        }
    }
}
