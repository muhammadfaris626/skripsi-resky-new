<?php

namespace App\Livewire\Databases\Permissions;

use App\Models\Permission;
use Livewire\Component;

class ReadPermission extends Component
{
    public $permission;

    public function mount($id)
    {
        $this->permission = Permission::with('roles.users')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.databases.permissions.read-permission');
    }
}
