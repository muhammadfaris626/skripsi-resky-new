<?php

namespace App\Livewire\Databases\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class CreateUser extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRoles = [];
    public $action;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,name',
        ];
    }

    public function setAction($action)
    {
        $this->action = $action;
        $this->store();
    }

    public function render()
    {
        $roles = Role::all();
        return view('livewire.databases.users.create-user', [
            'roles' => $roles
        ]);
    }

    public function store()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole($this->selectedRoles);

        $this->reset(['name', 'email', 'password', 'password_confirmation', 'selectedRoles']);

        if ($this->action === 'save_and_add') {
            LivewireAlert::text('User created successfully.')->success()->toast()->position('top-end')->show();
            return back();
        } else {
            session()->flash('success', 'User created successfully.');
            return to_route('users.index');
        }
    }
}
