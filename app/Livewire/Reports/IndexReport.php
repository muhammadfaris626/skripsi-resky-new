<?php

namespace App\Livewire\Reports;

use App\Models\Sale;
use App\Models\Target;
use App\Models\Employee;
use App\Models\Product;
use App\Models\ItemSale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class IndexReport extends Component
{
    public $dateFrom;
    public $dateTo;
    public $selectedEmployee = '';
    public $selectedMonth;
    public $selectedYear;
    public $reportType = 'monthly';

    public function mount()
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->selectedMonth = Carbon::now()->format('Y-m');
        $this->selectedYear = Carbon::now()->year;
    }

    public function updatedDateFrom()
    {
        $this->validateDates();
    }

    public function updatedDateTo()
    {
        $this->validateDates();
    }

    private function validateDates()
    {
        if ($this->dateFrom && $this->dateTo) {
            if (Carbon::parse($this->dateFrom)->gt(Carbon::parse($this->dateTo))) {
                $this->dateTo = $this->dateFrom;
            }
        }
    }

    public function getSalesReportData()
    {
        $query = Sale::with(['employee', 'itemSales.product'])
            ->whereBetween('date', [$this->dateFrom, $this->dateTo]);

        if ($this->selectedEmployee) {
            $query->where('employee_id', $this->selectedEmployee);
        }

        return $query->get();
    }

    public function getTotalSales()
    {
        return Sale::whereBetween('date', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedEmployee, fn($q) => $q->where('employee_id', $this->selectedEmployee))
            ->sum('total_amount');
    }

    public function getTotalProfit()
    {
        $sales = $this->getSalesReportData();
        $totalProfit = 0;

        foreach ($sales as $sale) {
            foreach ($sale->itemSales as $item) {
                $profit = ($item->price - $item->product->purchase_price) * $item->quantity;
                $totalProfit += $profit;
            }
        }

        return $totalProfit;
    }

    public function getTotalTransactions()
    {
        return Sale::whereBetween('date', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedEmployee, fn($q) => $q->where('employee_id', $this->selectedEmployee))
            ->count();
    }

    public function getTopProducts()
    {
        return ItemSale::select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(amount) as total_amount'))
            ->whereHas('sale', function($query) {
                $query->whereBetween('date', [$this->dateFrom, $this->dateTo]);
                if ($this->selectedEmployee) {
                    $query->where('employee_id', $this->selectedEmployee);
                }
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
    }

    public function getSalesByEmployee()
    {
        return Sale::select('employee_id', DB::raw('SUM(total_amount) as total_sales'), DB::raw('COUNT(*) as total_transactions'))
            ->whereBetween('date', [$this->dateFrom, $this->dateTo])
            ->with('employee')
            ->groupBy('employee_id')
            ->orderBy('total_sales', 'desc')
            ->get();
    }

    public function getDailySales()
    {
        return Sale::select(DB::raw('DATE(date) as sale_date'), DB::raw('SUM(total_amount) as daily_total'), DB::raw('COUNT(*) as daily_count'))
            ->whereBetween('date', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedEmployee, fn($q) => $q->where('employee_id', $this->selectedEmployee))
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('sale_date')
            ->get();
    }

    public function getEmployeeTargets()
    {
        $month = $this->selectedMonth;

        return Employee::with(['targets' => function($query) use ($month) {
            $query->where('month', $month);
        }])
        ->withSum(['sales as actual_sales' => function($query) use ($month) {
            $query->whereYear('date', Carbon::parse($month)->year)
                  ->whereMonth('date', Carbon::parse($month)->month);
        }], 'total_amount')
        ->get()
        ->map(function($employee) {
            $target = $employee->targets->first();
            $actualSales = $employee->actual_sales ?? 0;
            $targetAmount = $target ? $target->sale_target : 0;

            return [
                'employee' => $employee,
                'target_amount' => $targetAmount,
                'actual_sales' => $actualSales,
                'achievement_percentage' => $targetAmount > 0 ? round(($actualSales / $targetAmount) * 100, 2) : 0,
                'difference' => $actualSales - $targetAmount,
                'status' => $actualSales >= $targetAmount ? 'achieved' : 'not_achieved'
            ];
        });
    }

    public function getPaymentMethodStats()
    {
        return Sale::select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->whereBetween('date', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedEmployee, fn($q) => $q->where('employee_id', $this->selectedEmployee))
            ->groupBy('payment_method')
            ->get();
    }

    public function getMonthlySalesComparison()
    {
        $currentMonth = Carbon::parse($this->selectedMonth);
        $previousMonth = $currentMonth->copy()->subMonth();

        $currentSales = Sale::whereYear('date', $currentMonth->year)
            ->whereMonth('date', $currentMonth->month)
            ->sum('total_amount');

        $previousSales = Sale::whereYear('date', $previousMonth->year)
            ->whereMonth('date', $previousMonth->month)
            ->sum('total_amount');

        $growth = $previousSales > 0 ? (($currentSales - $previousSales) / $previousSales) * 100 : 0;

        return [
            'current_month' => $currentSales,
            'previous_month' => $previousSales,
            'growth_percentage' => round($growth, 2),
            'growth_amount' => $currentSales - $previousSales
        ];
    }

    public function getEmployees()
    {
        return Employee::orderBy('name')->get();
    }

    public function resetFilters()
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->selectedEmployee = '';
        $this->selectedMonth = Carbon::now()->format('Y-m');
        $this->selectedYear = Carbon::now()->year;
    }

    public function render()
    {
        return view('livewire.reports.index-report', [
            'totalSales' => $this->getTotalSales(),
            'totalProfit' => $this->getTotalProfit(),
            'totalTransactions' => $this->getTotalTransactions(),
            'topProducts' => $this->getTopProducts(),
            'salesByEmployee' => $this->getSalesByEmployee(),
            'dailySales' => $this->getDailySales(),
            'employeeTargets' => $this->getEmployeeTargets(),
            'paymentMethodStats' => $this->getPaymentMethodStats(),
            'monthlySalesComparison' => $this->getMonthlySalesComparison(),
            'employees' => $this->getEmployees(),
        ]);
    }
}
