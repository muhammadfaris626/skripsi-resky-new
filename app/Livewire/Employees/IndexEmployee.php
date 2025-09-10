<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class IndexEmployee extends Component
{
    use WithPagination;

    public $search = '';
    public $positionFilter = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'positionFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function render()
    {
        $employees = Employee::query()
            // Optimasi: hanya select kolom yang diperlukan
            ->select('id', 'name', 'position', 'phone', 'email', 'created_at')
            // Filter search dengan grouping yang benar
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('position', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            // Filter position
            ->when($this->positionFilter, function($query) {
                $query->where('position', $this->positionFilter);
            })
            // Urutkan dan paginate
            ->latest('created_at')
            ->paginate($this->perPage);

        return view('livewire.employees.index-employee', [
            'fetch' => $employees
        ]);
    }

    // Reset halaman ketika filter berubah
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPositionFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // Hapus data employee dengan konfirmasi
    public function delete($id) {
        $data = Employee::findOrFail($id);
        $data->delete();
        LivewireAlert::text('Data deleted successfully.')->success()->toast()->position('top-end')->show();
        return back();
    }

    // Method untuk reset semua filter
    public function resetFilters()
    {
        $this->search = '';
        $this->positionFilter = '';
        $this->perPage = 10;
        $this->resetPage();

        LivewireAlert::info('Filter direset!');
    }
}
