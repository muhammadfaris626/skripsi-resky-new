<?php

namespace App\Livewire\Databases\Permissions;

use App\Models\Permission;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class CreatePermission extends Component
{
    public $name = '';
    public $action;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:permissions,name',
        ];
    }

    public function setAction($action)
    {
        $this->action = $action;
        $this->store();
    }

    public function render()
    {
        return view('livewire.databases.permissions.create-permission');
    }

    public function store()
    {
        $this->validate();

        Permission::create([
            'name' => $this->name,
            'guard_name' => 'web'
        ]);

        $this->reset(['name']);

        if ($this->action === 'save_and_add') {
            LivewireAlert::text('Permission created successfully.')->success()->toast()->position('top-end')->show();
            return back();
        } else {
            session()->flash('success', 'Permission created successfully.');
            return to_route('permissions.index');
        }
    }
}
