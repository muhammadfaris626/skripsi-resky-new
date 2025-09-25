<?php

namespace App\Livewire\Databases\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class UpdateUser extends Component
{
    public $user;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRoles = [];

    public function mount($id)
    {
        $this->user = User::with('roles')->findOrFail($id);
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->selectedRoles = $this->user->roles->pluck('name')->toArray();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,name',
        ];
    }

    public function render()
    {
        $roles = Role::all();
        return view('livewire.databases.users.update-user', [
            'roles' => $roles
        ]);
    }

    public function update()
    {
        $this->validate();

        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $updateData['password'] = Hash::make($this->password);
        }

        $this->user->update($updateData);
        $this->user->syncRoles($this->selectedRoles);

        session()->flash('success', 'User updated successfully.');
        return to_route('users.index');
    }
}
