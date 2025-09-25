<app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <!-- Enhanced Header Section -->
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg">
                                <flux:icon.chart-bar class="w-8 h-8 text-white" />
                            </div>
                            <div>
                                <flux:heading size="2xl" class="bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                    Business Analytics Dashboard
                                </flux:heading>
                                <flux:text class="text-gray-600 text-lg">
                                    Comprehensive insights into your business performance
                                </flux:text>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <flux:button icon="arrow-path" wire:click="resetFilters" variant="ghost" size="sm">
                            Reset Filters
                        </flux:button>
                        {{-- <flux:button icon="arrow-down-tray" variant="primary" size="sm">
                            Export Report
                        </flux:button> --}}
                    </div>
                </div>
            </div>

            <!-- Enhanced Tabs Navigation -->
            <div x-data="{ tab: 'sales-report' }" class="space-y-6">
                <div class="p-2">
                    <flux:tabs variant="segmented" class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                        <flux:tab
                            icon="chart-bar"
                            x-on:click.prevent="tab = 'sales-report'"
                            x-bind:class="tab === 'sales-report' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg transform scale-105' : 'hover:bg-gray-50 text-gray-700'"
                            class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl transition-all duration-300 font-medium">
                            <span class="hidden sm:inline">Sales Analytics</span>
                            <span class="sm:hidden">Sales</span>
                        </flux:tab>
                        <flux:tab
                            icon="shopping-bag"
                            x-on:click.prevent="tab = 'purchase-report'"
                            x-bind:class="tab === 'purchase-report' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg transform scale-105' : 'hover:bg-gray-50 text-gray-700'"
                            class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl transition-all duration-300 font-medium">
                            <span class="hidden sm:inline">Purchase Analytics</span>
                            <span class="sm:hidden">Purchases</span>
                        </flux:tab>
                        <flux:tab
                            icon="users"
                            x-on:click.prevent="tab = 'employee-targets'"
                            x-bind:class="tab === 'employee-targets' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg transform scale-105' : 'hover:bg-gray-50 text-gray-700'"
                            class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl transition-all duration-300 font-medium">
                            <span class="hidden sm:inline">Employee Performance</span>
                            <span class="sm:hidden">Employees</span>
                        </flux:tab>
                        <flux:tab
                            icon="cube"
                            x-on:click.prevent="tab = 'product-analysis'"
                            x-bind:class="tab === 'product-analysis' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg transform scale-105' : 'hover:bg-gray-50 text-gray-700'"
                            class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl transition-all duration-300 font-medium">
                            <span class="hidden sm:inline">Product Analysis</span>
                            <span class="sm:hidden">Products</span>
                        </flux:tab>
                    </flux:tabs>
                </div>

                <!-- Sales Analytics Tab -->
                <div x-show="tab === 'sales-report'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
                    <!-- Enhanced Filters Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg">
                                <flux:icon.funnel class="w-5 h-5 text-white" />
                            </div>
                            <flux:heading size="lg" class="text-gray-800">Filters & Controls</flux:heading>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="space-y-2">
                                <flux:field>
                                    <flux:label class="text-sm font-medium text-gray-700">From Date</flux:label>
                                    <flux:input type="date" wire:model.live="dateFrom" class="rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500/20" />
                                </flux:field>
                            </div>
                            <div class="space-y-2">
                                <flux:field>
                                    <flux:label class="text-sm font-medium text-gray-700">To Date</flux:label>
                                    <flux:input type="date" wire:model.live="dateTo" class="rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500/20" />
                                </flux:field>
                            </div>
                            <div class="space-y-2">
                                <flux:field>
                                    <flux:label class="text-sm font-medium text-gray-700">Employee</flux:label>
                                    <flux:select wire:model.live="selectedEmployee" class="rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500/20">
                                        <option value="">All Employees</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                        @endforeach
                                    </flux:select>
                                </flux:field>
                            </div>
                            <div class="flex items-end">
                                <flux:button icon="x-mark" wire:click="resetFilters" variant="outline" class="w-full rounded-xl border-gray-200 hover:bg-gray-50 transition-all duration-200">
                                    Clear All
                                </flux:button>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Key Metrics Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                        <div class="group relative overflow-hidden bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                            <div class="relative flex items-center justify-between">
                                <div class="space-y-2">
                                    <p class="text-emerald-100 text-sm font-medium uppercase tracking-wide">Total Sales</p>
                                    <p class="text-2xl lg:text-3xl font-bold">Rp {{ number_format($totalSales) }}</p>
                                    <div class="flex items-center gap-1 text-emerald-100">
                                        <flux:icon.arrow-trending-up class="w-4 h-4" />
                                        <span class="text-xs">+12.5% from last period</span>
                                    </div>
                                </div>
                                <div class="p-4 bg-emerald-400/30 rounded-2xl backdrop-blur-sm">
                                    <flux:icon.currency-dollar class="w-8 h-8" />
                                </div>
                            </div>
                        </div>

                        <div class="group relative overflow-hidden bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                            <div class="relative flex items-center justify-between">
                                <div class="space-y-2">
                                    <p class="text-blue-100 text-sm font-medium uppercase tracking-wide">Total Profit</p>
                                    <p class="text-2xl lg:text-3xl font-bold">Rp {{ number_format($totalProfit) }}</p>
                                    <div class="flex items-center gap-1 text-blue-100">
                                        <flux:icon.arrow-trending-up class="w-4 h-4" />
                                        <span class="text-xs">+8.3% margin</span>
                                    </div>
                                </div>
                                <div class="p-4 bg-blue-400/30 rounded-2xl backdrop-blur-sm">
                                    <flux:icon.arrow-trending-up class="w-8 h-8" />
                                </div>
                            </div>
                        </div>

                        <div class="group relative overflow-hidden bg-gradient-to-br from-violet-500 via-purple-500 to-fuchsia-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                            <div class="relative flex items-center justify-between">
                                <div class="space-y-2">
                                    <p class="text-purple-100 text-sm font-medium uppercase tracking-wide">Transactions</p>
                                    <p class="text-2xl lg:text-3xl font-bold">{{ number_format($totalTransactions) }}</p>
                                    <div class="flex items-center gap-1 text-purple-100">
                                        <flux:icon.shopping-cart class="w-4 h-4" />
                                        <span class="text-xs">{{ $dailySales->count() }} days tracked</span>
                                    </div>
                                </div>
                                <div class="p-4 bg-purple-400/30 rounded-2xl backdrop-blur-sm">
                                    <flux:icon.shopping-cart class="w-8 h-8" />
                                </div>
                            </div>
                        </div>

                        <div class="group relative overflow-hidden bg-gradient-to-br from-orange-500 via-amber-500 to-yellow-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                            <div class="relative flex items-center justify-between">
                                <div class="space-y-2">
                                    <p class="text-orange-100 text-sm font-medium uppercase tracking-wide">Avg. Transaction</p>
                                    <p class="text-2xl lg:text-3xl font-bold">Rp {{ $totalTransactions > 0 ? number_format($totalSales / $totalTransactions) : '0' }}</p>
                                    <div class="flex items-center gap-1 text-orange-100">
                                        <flux:icon.calculator class="w-4 h-4" />
                                        <span class="text-xs">Per transaction</span>
                                    </div>
                                </div>
                                <div class="p-4 bg-orange-400/30 rounded-2xl backdrop-blur-sm">
                                    <flux:icon.calculator class="w-8 h-8" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Charts and Analytics -->
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <!-- Daily Sales Chart - Takes 2 columns on xl screens -->
                        <div class="xl:col-span-2 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg">
                                        <flux:icon.chart-bar class="w-5 h-5 text-white" />
                                    </div>
                                    <flux:heading size="lg" class="text-gray-800">Daily Sales Trend</flux:heading>
                                </div>
                                <flux:button variant="ghost" size="sm" class="text-gray-500 hover:text-gray-700">
                                    <flux:icon.ellipsis-horizontal class="w-5 h-5" />
                                </flux:button>
                            </div>
                            <div class="h-80 flex items-center justify-center border-2 border-dashed border-gray-200 rounded-xl bg-gradient-to-br from-gray-50 to-gray-100">
                                <div class="text-center space-y-4">
                                    <div class="p-4 bg-blue-100 rounded-full w-fit mx-auto">
                                        <flux:icon.chart-bar class="w-12 h-12 text-blue-600" />
                                    </div>
                                    <div>
                                        <p class="text-gray-700 font-medium text-lg">Daily Sales Chart</p>
                                        <p class="text-gray-500">{{ $dailySales->count() }} days of sales data</p>
                                        <p class="text-sm text-gray-400 mt-2">Chart visualization will be implemented here</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg">
                                    <flux:icon.credit-card class="w-5 h-5 text-white" />
                                </div>
                                <flux:heading size="lg" class="text-gray-800">Payment Methods</flux:heading>
                            </div>
                            <div class="space-y-4">
                                @foreach($paymentMethodStats as $payment)
                                    <div class="group p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 border border-gray-100 hover:border-blue-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-3 h-3 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                                                <span class="font-medium text-gray-700 group-hover:text-blue-700 transition-colors">{{ ucfirst($payment->payment_method) }}</span>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-gray-800">Rp {{ number_format($payment->total) }}</p>
                                                <p class="text-sm text-gray-500">{{ $payment->count }} transactions</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Sales by Employee -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg">
                                <flux:icon.users class="w-5 h-5 text-white" />
                            </div>
                            <flux:heading size="lg" class="text-gray-800">Sales Performance by Employee</flux:heading>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-4 px-4 font-semibold text-gray-700 bg-gray-50 rounded-tl-xl">Employee</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700 bg-gray-50">Total Sales</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700 bg-gray-50">Transactions</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700 bg-gray-50 rounded-tr-xl">Avg. per Transaction</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salesByEmployee as $index => $sale)
                                        <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 group">
                                            <td class="py-4 px-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative">
                                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center shadow-lg">
                                                            <span class="text-white font-bold text-sm">{{ substr($sale->employee->name, 0, 1) }}</span>
                                                        </div>
                                                        @if($index < 3)
                                                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                                                                <span class="text-white text-xs font-bold">{{ $index + 1 }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold text-gray-800 group-hover:text-blue-700 transition-colors">{{ $sale->employee->name }}</span>
                                                        <p class="text-sm text-gray-500">{{ $sale->employee->position ?? 'Sales Staff' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="font-bold text-lg text-gray-800">Rp {{ number_format($sale->total_sales) }}</span>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="font-medium text-gray-700">{{ $sale->total_transactions }}</span>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="font-medium text-gray-700">Rp {{ number_format($sale->total_sales / $sale->total_transactions) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Purchase Analytics Tab -->
                <div x-show="tab === 'purchase-report'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
                    <!-- Enhanced Key Metrics Cards for Purchases -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                        <div class="group relative overflow-hidden bg-gradient-to-br from-indigo-500 via-blue-500 to-cyan-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                            <div class="relative flex items-center justify-between">
                                <div class="space-y-2">
                                    <p class="text-indigo-100 text-sm font-medium uppercase tracking-wide">Total Purchases</p>
                                    <p class="text-2xl lg:text-3xl font-bold">Rp {{ number_format($totalPurchases) }}</p>
                                    <div class="flex items-center gap-1 text-indigo-100">
                                        <flux:icon.shopping-bag class="w-4 h-4" />
                                        <span class="text-xs">Supplier orders</span>
                                    </div>
                                </div>
                                <div class="p-4 bg-indigo-400/30 rounded-2xl backdrop-blur-sm">
                                    <flux:icon.shopping-bag class="w-8 h-8" />
                                </div>
                            </div>
                        </div>

                        <div class="group relative overflow-hidden bg-gradient-to-br from-purple-500 via-violet-500 to-fuchsia-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                            <div class="relative flex items-center justify-between">
                                <div class="space-y-2">
                                    <p class="text-purple-100 text-sm font-medium uppercase tracking-wide">Purchase Transactions</p>
                                    <p class="text-2xl lg:text-3xl font-bold">{{ number_format($totalPurchaseTransactions) }}</p>
                                    <div class="flex items-center gap-1 text-purple-100">
                                        <flux:icon.document-text class="w-4 h-4" />
                                        <span class="text-xs">{{ $dailyPurchases->count() }} days tracked</span>
                                    </div>
                                </div>
                                <div class="p-4 bg-purple-400/30 rounded-2xl backdrop-blur-sm">
                                    <flux:icon.document-text class="w-8 h-8" />
                                </div>
                            </div>
                        </div>

                        <div class="group relative overflow-hidden bg-gradient-to-br from-teal-500 via-cyan-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                            <div class="relative flex items-center justify-between">
                                <div class="space-y-2">
                                    <p class="text-teal-100 text-sm font-medium uppercase tracking-wide">Suppliers</p>
                                    <p class="text-2xl lg:text-3xl font-bold">{{ $purchasesBySupplier->count() }}</p>
                                    <div class="flex items-center gap-1 text-teal-100">
                                        <flux:icon.building-storefront class="w-4 h-4" />
                                        <span class="text-xs">Active suppliers</span>
                                    </div>
                                </div>
                                <div class="p-4 bg-teal-400/30 rounded-2xl backdrop-blur-sm">
                                    <flux:icon.building-storefront class="w-8 h-8" />
                                </div>
                            </div>
                        </div>

                        <div class="group relative overflow-hidden bg-gradient-to-br from-rose-500 via-pink-500 to-red-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                            <div class="relative flex items-center justify-between">
                                <div class="space-y-2">
                                    <p class="text-rose-100 text-sm font-medium uppercase tracking-wide">Avg. Purchase</p>
                                    <p class="text-2xl lg:text-3xl font-bold">Rp {{ $totalPurchaseTransactions > 0 ? number_format($totalPurchases / $totalPurchaseTransactions) : '0' }}</p>
                                    <div class="flex items-center gap-1 text-rose-100">
                                        <flux:icon.calculator class="w-4 h-4" />
                                        <span class="text-xs">Per transaction</span>
                                    </div>
                                </div>
                                <div class="p-4 bg-rose-400/30 rounded-2xl backdrop-blur-sm">
                                    <flux:icon.calculator class="w-8 h-8" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Charts and Analytics for Purchases -->
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <!-- Daily Purchases Chart -->
                        <div class="xl:col-span-2 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg">
                                        <flux:icon.chart-bar class="w-5 h-5 text-white" />
                                    </div>
                                    <flux:heading size="lg" class="text-gray-800">Daily Purchase Trend</flux:heading>
                                </div>
                            </div>
                            <div class="h-80 flex items-center justify-center border-2 border-dashed border-gray-200 rounded-xl bg-gradient-to-br from-gray-50 to-gray-100">
                                <div class="text-center space-y-4">
                                    <div class="p-4 bg-indigo-100 rounded-full w-fit mx-auto">
                                        <flux:icon.chart-bar class="w-12 h-12 text-indigo-600" />
                                    </div>
                                    <div>
                                        <p class="text-gray-700 font-medium text-lg">Daily Purchase Chart</p>
                                        <p class="text-gray-500">{{ $dailyPurchases->count() }} days of purchase data</p>
                                        <p class="text-sm text-gray-400 mt-2">Chart visualization will be implemented here</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Payment Methods -->
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg">
                                    <flux:icon.credit-card class="w-5 h-5 text-white" />
                                </div>
                                <flux:heading size="lg" class="text-gray-800">Purchase Payment Methods</flux:heading>
                            </div>
                            <div class="space-y-4">
                                @foreach($purchasePaymentMethodStats as $payment)
                                    <div class="group p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl hover:from-purple-50 hover:to-pink-50 transition-all duration-200 border border-gray-100 hover:border-purple-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-3 h-3 rounded-full bg-gradient-to-r from-purple-500 to-pink-500"></div>
                                                <span class="font-medium text-gray-700 group-hover:text-purple-700 transition-colors">{{ ucfirst($payment->payment_method) }}</span>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-gray-800">Rp {{ number_format($payment->total) }}</p>
                                                <p class="text-sm text-gray-500">{{ $payment->count }} transactions</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Purchases by Supplier -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-lg">
                                <flux:icon.building-storefront class="w-5 h-5 text-white" />
                            </div>
                            <flux:heading size="lg" class="text-gray-800">Purchases by Supplier</flux:heading>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-4 px-4 font-semibold text-gray-700 bg-gray-50 rounded-tl-xl">Supplier</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700 bg-gray-50">Total Purchases</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700 bg-gray-50">Transactions</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700 bg-gray-50 rounded-tr-xl">Avg. per Transaction</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchasesBySupplier as $index => $purchase)
                                        <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-cyan-50 hover:to-blue-50 transition-all duration-200 group">
                                            <td class="py-4 px-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative">
                                                        <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full flex items-center justify-center shadow-lg">
                                                            <span class="text-white font-bold text-sm">{{ substr($purchase->supplier_name, 0, 1) }}</span>
                                                        </div>
                                                        @if($index < 3)
                                                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                                                                <span class="text-white text-xs font-bold">{{ $index + 1 }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold text-gray-800 group-hover:text-cyan-700 transition-colors">{{ $purchase->supplier_name }}</span>
                                                        <p class="text-sm text-gray-500">Supplier</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="font-bold text-lg text-gray-800">Rp {{ number_format($purchase->total_purchases) }}</span>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="font-medium text-gray-700">{{ $purchase->total_transactions }}</span>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="font-medium text-gray-700">Rp {{ number_format($purchase->total_purchases / $purchase->total_transactions) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Top Purchased Products -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-gradient-to-r from-pink-500 to-rose-500 rounded-lg">
                                <flux:icon.cube class="w-5 h-5 text-white" />
                            </div>
                            <flux:heading size="lg" class="text-gray-800">Top Purchased Products</flux:heading>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-4 px-4 font-semibold text-gray-700 bg-gray-50 rounded-tl-xl">Product</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700 bg-gray-50">Quantity Purchased</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700 bg-gray-50">Total Cost</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700 bg-gray-50 rounded-tr-xl">Avg. Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topPurchasedProducts as $index => $product)
                                        <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-pink-50 hover:to-rose-50 transition-all duration-200 group">
                                            <td class="py-4 px-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative">
                                                        <div class="w-10 h-10 bg-gradient-to-r from-pink-500 to-rose-500 rounded-full flex items-center justify-center shadow-lg">
                                                            <flux:icon.cube class="w-5 h-5 text-white" />
                                                        </div>
                                                        @if($index < 3)
                                                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                                                                <span class="text-white text-xs font-bold">{{ $index + 1 }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-800 group-hover:text-pink-700 transition-colors">{{ $product->product->product_name }}</p>
                                                        <p class="text-sm text-gray-500">{{ $product->product->product_code }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="font-bold text-lg text-gray-800">{{ number_format($product->total_quantity) }}</span>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="font-bold text-lg text-gray-800">Rp {{ number_format($product->total_amount) }}</span>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="font-medium text-gray-700">Rp {{ number_format($product->total_amount / $product->total_quantity) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Employee Performance Tab -->
                <div x-show="tab === 'employee-targets'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
                    <!-- Month Filter -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg">
                                    <flux:icon.chart-pie class="w-5 h-5 text-white" />
                                </div>
                                <flux:heading size="lg" class="text-gray-800">Employee Target Performance</flux:heading>
                            </div>
                            <div class="flex items-center gap-4">
                                <flux:field>
                                    <flux:label class="text-sm font-medium text-gray-700">Select Month</flux:label>
                                    <flux:input type="month" wire:model.live="selectedMonth" class="rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500/20" />
                                </flux:field>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Monthly Comparison -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg">
                                <flux:icon.chart-bar class="w-5 h-5 text-white" />
                            </div>
                            <flux:heading size="lg" class="text-gray-800">Monthly Sales Comparison</flux:heading>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl border border-blue-200">
                                <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full w-fit mx-auto mb-4">
                                    <flux:icon.calendar class="w-6 h-6 text-white" />
                                </div>
                                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide mb-2">Current Month</p>
                                <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($monthlySalesComparison['current_month']) }}</p>
                            </div>
                            <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border border-gray-200">
                                <div class="p-3 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full w-fit mx-auto mb-4">
                                    <flux:icon.clock class="w-6 h-6 text-white" />
                                </div>
                                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide mb-2">Previous Month</p>
                                <p class="text-3xl font-bold text-gray-600">Rp {{ number_format($monthlySalesComparison['previous_month']) }}</p>
                            </div>
                            <div class="text-center p-6 bg-gradient-to-br {{ $monthlySalesComparison['growth_percentage'] >= 0 ? 'from-green-50 to-emerald-100 border-green-200' : 'from-red-50 to-rose-100 border-red-200' }} rounded-2xl border">
                                <div class="p-3 bg-gradient-to-r {{ $monthlySalesComparison['growth_percentage'] >= 0 ? 'from-green-500 to-emerald-500' : 'from-red-500 to-rose-500' }} rounded-full w-fit mx-auto mb-4">
                                    <flux:icon.arrow-trending-up class="w-6 h-6 text-white {{ $monthlySalesComparison['growth_percentage'] < 0 ? 'rotate-180' : '' }}" />
                                </div>
                                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide mb-2">Growth Rate</p>
                                <p class="text-3xl font-bold {{ $monthlySalesComparison['growth_percentage'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $monthlySalesComparison['growth_percentage'] }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Employee Targets Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($employeeTargets as $target)
                            <div class="group bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 hover:scale-105">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center shadow-lg">
                                                <span class="text-white font-bold text-lg">{{ substr($target['employee']->name, 0, 1) }}</span>
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 w-6 h-6 {{ $target['status'] === 'achieved' ? 'bg-green-500' : 'bg-red-500' }} rounded-full flex items-center justify-center">
                                                <flux:icon.check class="w-3 h-3 text-white" />
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-800 group-hover:text-blue-700 transition-colors">{{ $target['employee']->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $target['employee']->position ?? 'Sales Staff' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($target['status'] === 'achieved')
                                            <flux:badge color="green" size="sm" class="bg-gradient-to-r from-green-500 to-emerald-500 text-white border-0">
                                                <flux:icon.check class="w-3 h-3" />
                                                Achieved
                                            </flux:badge>
                                        @else
                                            <flux:badge color="red" size="sm" class="bg-gradient-to-r from-red-500 to-rose-500 text-white border-0">
                                                <flux:icon.x-mark class="w-3 h-3" />
                                                Pending
                                            </flux:badge>
                                        @endif
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                        <span class="text-sm text-gray-600 font-medium">Target:</span>
                                        <span class="font-bold text-gray-800">Rp {{ number_format($target['target_amount']) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-xl">
                                        <span class="text-sm text-gray-600 font-medium">Actual:</span>
                                        <span class="font-bold text-blue-700">Rp {{ number_format($target['actual_sales']) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 {{ $target['achievement_percentage'] >= 100 ? 'bg-green-50' : 'bg-red-50' }} rounded-xl">
                                        <span class="text-sm text-gray-600 font-medium">Achievement:</span>
                                        <span class="font-bold text-xl {{ $target['achievement_percentage'] >= 100 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $target['achievement_percentage'] }}%
                                        </span>
                                    </div>
                                </div>

                                <!-- Enhanced Progress Bar -->
                                <div class="mt-6 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Progress</span>
                                        <span class="font-medium">{{ min($target['achievement_percentage'], 100) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                        <div class="bg-gradient-to-r {{ $target['achievement_percentage'] >= 100 ? 'from-green-500 to-emerald-500' : 'from-blue-500 to-indigo-500' }} h-3 rounded-full transition-all duration-500 ease-out" style="width: {{ min($target['achievement_percentage'], 100) }}%"></div>
                                    </div>
                                </div>

                                <div class="mt-4 text-center">
                                    <span class="text-sm font-medium {{ $target['difference'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $target['difference'] >= 0 ? '+' : '' }}Rp {{ number_format($target['difference']) }}
                                        <span class="text-gray-500">from target</span>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Enhanced Product Analysis Tab -->
                <div x-show="tab === 'product-analysis'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg">
                                <flux:icon.cube class="w-5 h-5 text-white" />
                            </div>
                            <flux:heading size="lg" class="text-gray-800">Top Selling Products</flux:heading>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-4 px-4 font-semibold text-gray-700 bg-gray-50 rounded-tl-xl">Product</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700 bg-gray-50">Quantity Sold</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700 bg-gray-50">Total Revenue</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700 bg-gray-50 rounded-tr-xl">Avg. Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProducts as $index => $product)
                                        <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 transition-all duration-200 group">
                                            <td class="py-4 px-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative">
                                                        <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center shadow-lg">
                                                            <flux:icon.cube class="w-5 h-5 text-white" />
                                                        </div>
                                                        @if($index < 3)
                                                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                                                                <span class="text-white text-xs font-bold">{{ $index + 1 }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-800 group-hover:text-orange-700 transition-colors">{{ $product->product->product_name }}</p>
                                                        <p class="text-sm text-gray-500">{{ $product->product->product_code }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="font-bold text-lg text-gray-800">{{ number_format($product->total_quantity) }}</span>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="font-bold text-lg text-gray-800">Rp {{ number_format($product->total_amount) }}</span>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="font-medium text-gray-700">Rp {{ number_format($product->total_amount / $product->total_quantity) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</app>
