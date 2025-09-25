<?php

namespace App\Livewire\Dashboards;

use App\Models\Product;
use App\Models\Sale;
use App\Models\ItemSale;
use App\Models\Purchase;
use App\Models\ItemPurchase;
use Carbon\Carbon;
use Livewire\Component;

class IndexDashboard extends Component
{
    public $thisMonthSales = 0;
    public $thisYearSales = 0;
    public $thisMonthPurchases = 0;
    public $thisYearPurchases = 0;
    public $profit = 0;
    public $lowStockCount = 0;
    public $latestTransactions = [];
    public $latestPurchases = [];
    public $stockAlerts = [];

    public function mount()
    {
        $now = Carbon::now();

        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $startOfYear = $now->copy()->startOfYear();
        $endOfYear = $now->copy()->endOfYear();

        // Sales totals - convert string dates to proper format for comparison
        $this->thisMonthSales = Sale::whereBetween('date', [
            $startOfMonth->format('Y-m-d'),
            $endOfMonth->format('Y-m-d')
        ])->sum('total_amount');

        $this->thisYearSales = Sale::whereBetween('date', [
            $startOfYear->format('Y-m-d'),
            $endOfYear->format('Y-m-d')
        ])->sum('total_amount');

        // Purchases totals
        $this->thisMonthPurchases = Purchase::whereBetween('date', [
            $startOfMonth->format('Y-m-d'),
            $endOfMonth->format('Y-m-d')
        ])->sum('total_amount');

        $this->thisYearPurchases = Purchase::whereBetween('date', [
            $startOfYear->format('Y-m-d'),
            $endOfYear->format('Y-m-d')
        ])->sum('total_amount');

        // Profit: sum over item_sales (selling_price - purchase_price) * quantity
        $this->profit = ItemSale::join('products', 'item_sales.product_id', '=', 'products.id')
            ->join('sales', 'item_sales.sale_id', '=', 'sales.id')
            ->whereBetween('sales.date', [
                $startOfMonth->format('Y-m-d'),
                $endOfMonth->format('Y-m-d')
            ])
            ->selectRaw('SUM((CAST(item_sales.price AS DECIMAL(15,2)) - CAST(products.purchase_price AS DECIMAL(15,2))) * item_sales.quantity) as profit')
            ->value('profit') ?? 0;

        // Low stock
        $this->lowStockCount = Product::where('stock', '<=', 10)->count();

        // Latest transactions (last 5 sales)
        $this->latestTransactions = Sale::with('employee')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Latest purchases (last 5 purchases)
        $this->latestPurchases = Purchase::with('employee')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
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
            'thisMonthPurchases' => $this->thisMonthPurchases,
            'thisYearPurchases' => $this->thisYearPurchases,
            'profit' => $this->profit,
            'lowStockCount' => $this->lowStockCount,
            'latestTransactions' => $this->latestTransactions,
            'latestPurchases' => $this->latestPurchases,
            'stockAlerts' => $this->stockAlerts,
        ]);
    }
}
