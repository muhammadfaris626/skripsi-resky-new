<?php

namespace App\Livewire\Databases\Roles;

use App\Models\Role;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class IndexRole extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function render()
    {
        $data = Role::query()
            ->select('id', 'name', 'guard_name', 'created_at')
            ->withCount(['users', 'permissions'])
            ->when($this->search, function($query) {
                $query->where('name', 'LIKE', '%' . $this->search . '%');
            })
            ->latest('created_at')
            ->paginate($this->perPage);

        return view('livewire.databases.roles.index-role', [
            'fetch' => $data
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);

        // Check if role has users assigned
        if ($role->users()->count() > 0) {
            LivewireAlert::text('Cannot delete role that has users assigned to it.')->error()->toast()->position('top-end')->show();
            return;
        }

        $role->delete();
        LivewireAlert::text('Role deleted successfully.')->success()->toast()->position('top-end')->show();
        return back();
    }
}
