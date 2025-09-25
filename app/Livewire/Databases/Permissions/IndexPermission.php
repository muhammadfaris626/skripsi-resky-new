<?php

namespace App\Livewire\Databases\Permissions;

use App\Models\Permission;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPermission extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $entityFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'entityFilter' => ['except' => ''],
    ];

    public function render()
    {
        $data = Permission::query()
            ->select('id', 'name', 'guard_name', 'created_at')
            ->withCount('roles')
            ->when($this->search, function($query) {
                $query->where('name', 'LIKE', '%' . $this->search . '%');
            })
            ->when($this->entityFilter, function($query) {
                $query->where('name', 'LIKE', $this->entityFilter . ':%');
            })
            ->latest('created_at')
            ->paginate($this->perPage);

        $entities = Permission::all()
            ->map(function($permission) {
                return explode(':', $permission->name)[0];
            })
            ->unique()
            ->sort()
            ->values();

        return view('livewire.databases.permissions.index-permission', [
            'fetch' => $data,
            'entities' => $entities
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedEntityFilter()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $permission = Permission::findOrFail($id);

        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            LivewireAlert::text('Cannot delete permission that is assigned to roles.')->error()->toast()->position('top-end')->show();
            return;
        }

        $permission->delete();
        LivewireAlert::text('Permission deleted successfully.')->success()->toast()->position('top-end')->show();
        return back();
    }
}
