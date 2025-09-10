<?php

namespace App\Livewire\Targets;

use App\Models\Employee;
use App\Models\Target;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class IndexTarget extends Component
{
    use WithPagination;

    public $search = '';
    public $employeeFilter = '';
    public $monthFilter = '';
    public $yearFilter = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'employeeFilter' => ['except' => ''],
        'monthFilter' => ['except' => ''],
        'yearFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEmployeeFilter()
    {
        $this->resetPage();
    }

    public function updatingMonthFilter()
    {
        $this->resetPage();
    }

    public function updatingYearFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'employeeFilter', 'monthFilter', 'yearFilter', 'perPage']);
        $this->resetPage();
    }

    public function delete($id)
    {
        $target = Target::find($id);
        if ($target) {
            $target->delete();
            LivewireAlert::text('Data deleted successfully.')->success()->toast()->position('top-end')->show();
        }
    }

    public function render()
    {
        $query = Target::with('employee');

        // Search by employee name
        if ($this->search) {
            $query->whereHas('employee', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by employee
        if ($this->employeeFilter) {
            $query->where('employee_id', $this->employeeFilter);
        }

        // Filter by month
        if ($this->monthFilter) {
            $query->where('month', $this->monthFilter);
        }

        // Filter by year - DIPERBAIKI
        if ($this->yearFilter) {
            $query->where('month', 'like', $this->yearFilter . '-%');
        }

        $salesTargets = $query->orderBy('month', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $employees = Employee::orderBy('name')->get();

        // Get years - DIPERBAIKI
        $years = Target::selectRaw('DISTINCT LEFT(month, 4) as year')
            ->whereNotNull('month')
            ->where('month', '!=', '') // Tambahan untuk memastikan month tidak kosong
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter(); // Remove any null/empty values

        return view('livewire.targets.index-target', [
            'fetch' => $salesTargets,
            'employees' => $employees,
            'years' => $years
        ]);
    }
}
