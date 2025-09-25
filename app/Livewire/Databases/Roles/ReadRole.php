<?php

namespace App\Livewire\Databases\Roles;

use App\Models\Role;
use Livewire\Component;

class ReadRole extends Component
{
    public $role;

    public function mount($id)
    {
        $this->role = Role::with(['permissions', 'users'])->findOrFail($id);
    }

    public function render()
    {
        $groupedPermissions = $this->role->permissions->groupBy(function($permission) {
            return explode(':', $permission->name)[0];
        });

        return view('livewire.databases.roles.read-role', [
            'groupedPermissions' => $groupedPermissions
        ]);
    }
}
