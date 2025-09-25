<?php

namespace App\Livewire\Databases\Users;

use App\Models\User;
use Livewire\Component;

class ReadUser extends Component
{
    public $user;

    public function mount($id)
    {
        $this->user = User::with('roles.permissions')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.databases.users.read-user');
    }
}
