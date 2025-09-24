<app>
    <div class="grid grid-cols-1 gap-4">
        <div class="text-center">
            <p class="text-4xl font-bold">Dashboard Monitoring</p>
            <p>CV Angkasa Jaya - Sistem Penjualan</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-8 gap-4">
            <div class="md:col-span-2 rounded-lg border text-card-foreground relative overflow-hidden bg-white/90 backdrop-blur-sm shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="absolute top-0 right-0 w-32 h-32 bg-green-500 opacity-10 rounded-full transform translate-x-8 -translate-y-8"></div>
                <div class="flex flex-col space-y-1.5 p-6 pb-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500 mb-1">This Month's Sales</p>
                            <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($thisMonthSales ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-green-200 bg-opacity-20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="lucide lucide-shopping-cart w-5 h-5 text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:col-span-2 rounded-lg border text-card-foreground relative overflow-hidden bg-white/90 backdrop-blur-sm shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500 opacity-10 rounded-full transform translate-x-8 -translate-y-8"></div>
                <div class="flex flex-col space-y-1.5 p-6 pb-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500 mb-1">This Year's Sales</p>
                            <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($thisYearSales ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-blue-200 bg-opacity-20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="lucide lucide-shopping-cart w-5 h-5 text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:col-span-2 rounded-lg border text-card-foreground relative overflow-hidden bg-white/90 backdrop-blur-sm shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500 opacity-10 rounded-full transform translate-x-8 -translate-y-8"></div>
                <div class="flex flex-col space-y-1.5 p-6 pb-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500 mb-1">Profit</p>
                            <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($profit ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-purple-200 bg-opacity-20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="lucide lucide-shopping-cart w-6 h-6 text-purple-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:col-span-2 rounded-lg border text-card-foreground relative overflow-hidden bg-white/90 backdrop-blur-sm shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="absolute top-0 right-0 w-32 h-32 bg-orange-500 opacity-10 rounded-full transform translate-x-8 -translate-y-8"></div>
                <div class="flex flex-col space-y-1.5 p-6 pb-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500 mb-1">Low Stock Products</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $lowStockCount ?? 0 }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-orange-200 bg-opacity-20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="lucide lucide-shopping-cart w-6 h-6 text-orange-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:col-span-5 rounded-lg border text-card-foreground relative overflow-hidden bg-white/90 backdrop-blur-sm shadow-lg hover:shadow-xl transition-all duration-300 p-4 space-y-4">
                <div class="flex justify-start gap-1">
                    <div><flux:icon.shopping-cart class="text-blue-500" /></div>
                    <div><flux:heading size="lg">Latest Transactions</flux:heading></div>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    @forelse($latestTransactions as $tx)
                        <div class="bg-green-100 p-2 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-bold">{{ $tx->invoice_number ?? 'Sale' }}</p>
                                    <p class="text-xs">{{ $tx->date ? $tx->date->format('Y-m-d') : '' }}</p>
                                </div>
                                <div>
                                    <p class="font-bold text-green-700">Rp {{ number_format($tx->total_amount ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">No recent transactions</div>
                    @endforelse
                </div>
            </div>
            <div class="md:col-span-3 rounded-lg border text-card-foreground relative overflow-hidden bg-white/90 backdrop-blur-sm shadow-lg hover:shadow-xl transition-all duration-300 p-4 space-y-4">
                <div class="flex justify-start gap-1">
                    <div><flux:icon.exclamation-triangle class="text-orange-500" /></div>
                    <div><flux:heading size="lg">Stock Alert</flux:heading></div>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    @forelse($stockAlerts as $p)
                        <div class="bg-orange-100 p-2 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-bold">{{ $p->product_name }}</p>
                                    <p class="text-xs">Category: {{ optional($p->category)->name }}</p>
                                </div>
                                <div>
                                    <flux:badge variant="solid" color="orange">{{ $p->stock }} remaining</flux:badge>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">No stock alerts</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</app>
