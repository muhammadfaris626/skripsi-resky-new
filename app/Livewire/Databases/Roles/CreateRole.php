<?php

namespace App\Livewire\Databases\Roles;

use App\Models\Permission;
use App\Models\Role;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class CreateRole extends Component
{
    public $name = '';
    public $selectedPermissions = [];
    public $action;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name',
            'selectedPermissions' => 'required|array|min:1',
            'selectedPermissions.*' => 'exists:permissions,name',
        ];
    }

    public function setAction($action)
    {
        $this->action = $action;
        $this->store();
    }

    public function render()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode(':', $permission->name)[0];
        });

        return view('livewire.databases.roles.create-role', [
            'permissions' => $permissions
        ]);
    }

    public function store()
    {
        $this->validate();

        $role = Role::create([
            'name' => $this->name,
            'guard_name' => 'web'
        ]);

        $role->givePermissionTo($this->selectedPermissions);

        $this->reset(['name', 'selectedPermissions']);

        if ($this->action === 'save_and_add') {
            LivewireAlert::text('Role created successfully.')->success()->toast()->position('top-end')->show();
            return back();
        } else {
            session()->flash('success', 'Role created successfully.');
            return to_route('roles.index');
        }
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
