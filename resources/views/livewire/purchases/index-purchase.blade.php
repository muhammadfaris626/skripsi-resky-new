<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Manage Purchases</flux:heading>
                    <flux:text class="mt-2">Store purchase data management</flux:text>
                </div>
                <div>
                    @can('create', App\Models\Purchase::class)
                        <flux:button variant="primary" icon="plus" :href="route('purchases.create')">Add Purchase</flux:button>
                    @endcan
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-xl p-4 space-y-3">
                <div class="flex justify-between items-center">
                    <div><flux:heading size="lg" icon="funnel">Filter & Search</flux:heading></div>
                </div>
                <div class="flex justify-between items-center gap-4 mt-3">
                    <div class="flex-1 max-w">
                        <flux:input wire:model.live.debounce.500ms="search" placeholder="Search for invoice, supplier, or employee..." icon="magnifying-glass" />
                    </div>
                    <div class="flex gap-4">
                        <div>
                            <flux:select wire:model.live="filterEmployee" placeholder="Filter Employee">
                                <flux:select.option value="">All Employees</flux:select.option>
                                @foreach($employees as $employee)
                                    <flux:select.option value="{{ $employee->id }}">{{ $employee->name }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>
                        <div>
                            <flux:input wire:model.live.debounce.500ms="filterSupplier" placeholder="Filter by supplier..." />
                        </div>
                        <div>
                            <flux:select wire:model.live="perPage" placeholder="Per Page">
                                <flux:select.option value="5">5 per page</flux:select.option>
                                <flux:select.option value="10">10 per page</flux:select.option>
                                <flux:select.option value="25">25 per page</flux:select.option>
                                <flux:select.option value="50">50 per page</flux:select.option>
                                <flux:select.option value="100">100 per page</flux:select.option>
                            </flux:select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <div>
                        Showing {{ $fetch->firstItem() ?? 0 }} to {{ $fetch->lastItem() ?? 0 }}
                        of {{ $fetch->total() }} results
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-xl p-4 grid grid-cols-1 gap-4">
                <div class="flex justify-start gap-1">
                    <div><flux:icon.shopping-cart class="text-blue-500" /></div>
                    <div><flux:heading size="lg">Purchase List</flux:heading></div>
                </div>
                <div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Invoice Number</th>
                                    <th scope="col" class="px-6 py-3">Date</th>
                                    <th scope="col" class="px-6 py-3">Employee</th>
                                    <th scope="col" class="px-6 py-3">Supplier</th>
                                    <th scope="col" class="px-6 py-3">Total Amount</th>
                                    <th scope="col" class="px-6 py-3">Payment Method</th>
                                    <th scope="col" class="px-6 py-3">Items</th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fetch as $purchase)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $purchase->invoice_number }}
                                    </th>
                                    <td class="px-6">{{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}</td>
                                    <td class="px-6">{{ $purchase->employee->name }}</td>
                                    <td class="px-6">{{ $purchase->supplier_name }}</td>
                                    <td class="px-6">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-6">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($purchase->payment_method == 'cash') bg-green-100 text-green-800
                                            @elseif($purchase->payment_method == 'transfer') bg-blue-100 text-blue-800
                                            @elseif($purchase->payment_method == 'credit') bg-orange-100 text-orange-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($purchase->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="px-6">{{ $purchase->itemPurchases->count() }} items</td>
                                    <td class="px-6">
                                        <div class="flex justify-end gap-1">
                                            @can('view', $purchase)
                                                <flux:button :href="route('purchases.read', $purchase->id)" icon="eye" size="xs" title="View"></flux:button>
                                            @endcan
                                            @can('update', $purchase)
                                                <flux:button :href="route('purchases.update', $purchase->id)" icon="pencil-square" size="xs" variant="primary" color="blue" title="Edit"></flux:button>
                                            @endcan
                                            @can('delete', $purchase)
                                                <flux:button wire:click="delete({{ $purchase->id }})" wire:confirm="Are you sure you want to delete {{ $purchase->invoice_number }}?" icon="trash" size="xs" variant="danger" title="Delete"></flux:button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="p-10 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <flux:icon.shopping-cart class="w-12 h-12 text-gray-300" />
                                            <p class="text-lg text-gray-500">No purchases found</p>
                                            <p class="text-sm text-gray-400">Try adjusting your search or filter criteria</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($fetch->hasPages())
                <div>
                    {{ $fetch->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</app>
