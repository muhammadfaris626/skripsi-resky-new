<?php

namespace App\Livewire\Dashboards;

use App\Models\Product;
use App\Models\Sale;
use App\Models\ItemSale;
use Carbon\Carbon;
use Livewire\Component;

class IndexDashboard extends Component
{
    public $thisMonthSales = 0;
    public $thisYearSales = 0;
    public $profit = 0;
    public $lowStockCount = 0;
    public $latestTransactions = [];
    public $stockAlerts = [];

    public function mount()
    {
        $now = Carbon::now();

        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $startOfYear = $now->copy()->startOfYear();
        $endOfYear = $now->copy()->endOfYear();

        // Sales totals
        $this->thisMonthSales = Sale::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('total_amount');
        $this->thisYearSales = Sale::whereBetween('date', [$startOfYear, $endOfYear])->sum('total_amount');

        // Profit: sum over item_sales (selling_price - purchase_price) * quantity
        $this->profit = ItemSale::join('products', 'item_sales.product_id', '=', 'products.id')
            ->whereBetween('item_sales.created_at', [$startOfMonth, $endOfMonth])
            ->selectRaw('SUM((item_sales.price - products.purchase_price) * item_sales.quantity) as profit')
            ->value('profit') ?? 0;

        // Low stock
        $this->lowStockCount = Product::whereBetween('stock', [1, 10])->count();

        // Latest transactions (last 5 sales)
        $this->latestTransactions = Sale::with('employee')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Stock alerts: products with stock <= 10
        $this->stockAlerts = Product::where('stock', '<=', 10)->orderBy('stock', 'asc')->limit(5)->get();
    }

    public function render()
    {
        return view('livewire.dashboards.index-dashboard', [
            'thisMonthSales' => $this->thisMonthSales,
            'thisYearSales' => $this->thisYearSales,
            'profit' => $this->profit,
            'lowStockCount' => $this->lowStockCount,
            'latestTransactions' => $this->latestTransactions,
            'stockAlerts' => $this->stockAlerts,
        ]);
    }
}
