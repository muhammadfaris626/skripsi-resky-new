<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Business Reports</flux:heading>
                    <flux:text class="mt-2">Analysis of business performance and sales targets</flux:text>
                </div>
            </div>
        </div>
        <div x-data="{ tab: 'sales-report' }">
            <flux:tabs variant="segmented">
                <flux:tab
                    x-on:click.prevent="tab = 'sales-report'"
                    x-bind:class="tab === 'sales-report' ? 'bg-white shadow' : ''">
                    Sales Report
                </flux:tab>
                <flux:tab
                    x-on:click.prevent="tab = 'employee-targets'"
                    x-bind:class="tab === 'employee-targets' ? 'bg-white shadow' : ''">
                    Employee Targets
                </flux:tab>
            </flux:tabs>
            <div class="mt-4">
                <div x-show="tab === 'sales-report'">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-lg border text-card-foreground bg-white/90 backdrop-blur-sm shadow-lg">
                            <div class="flex flex-col space-y-1.5 p-6 pb-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-slate-500">Total Sales</p>
                                        <p class="text-2xl font-bold text-green-600">Rp 0</p>
                                    </div>
                                    <div class="p-3 rounded-xl bg-green-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up w-5 h-5 text-green-600">
                                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                            <polyline points="16 7 22 7 22 13"></polyline>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-lg border text-card-foreground bg-white/90 backdrop-blur-sm shadow-lg">
                            <div class="flex flex-col space-y-1.5 p-6 pb-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-slate-500">Profit</p>
                                        <p class="text-2xl font-bold text-purple-600">Rp 0</p>
                                    </div>
                                    <div class="p-3 rounded-xl bg-purple-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign w-5 h-5 text-purple-600">
                                            <line x1="12" x2="12" y1="2" y2="22"></line>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="tab === 'employee-targets'" x-transition>

                    <div class="bg-white p-4 rounded shadow-sm">
                        <p class="font-semibold">Board View</p>
                        <p class="text-sm text-slate-500">Kanban-style boards or cards for reports.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</app>
