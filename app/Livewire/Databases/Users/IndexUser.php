<?php

namespace App\Livewire\Databases\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class IndexUser extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $roleFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'roleFilter' => ['except' => ''],
    ];

    public function render()
    {
        $data = User::query()
            ->select('id', 'name', 'email', 'created_at')
            ->with('roles')
            ->when($this->search, function($query) {
                $query->search($this->search);
            })
            ->when($this->roleFilter, function($query) {
                $query->byRole($this->roleFilter);
            })
            ->latest('created_at')
            ->paginate($this->perPage);

        $roles = \App\Models\Role::all();

        return view('livewire.databases.users.index-user', [
            'fetch' => $data,
            'roles' => $roles
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedRoleFilter()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting current user
        if ($user->id === Auth::user()->id) {
            LivewireAlert::text('You cannot delete your own account.')->error()->toast()->position('top-end')->show();
            return;
        }

        $user->delete();
        LivewireAlert::text('User deleted successfully.')->success()->toast()->position('top-end')->show();
        return back();
    }
}
