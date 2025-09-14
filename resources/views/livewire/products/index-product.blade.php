<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Manage Products</flux:heading>
                    <flux:text class="mt-2">Store product data management</flux:text>
                </div>
                <div>
                    <flux:button variant="primary" icon="plus" :href="route('products.create')">Add Product</flux:button>
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
                        <flux:input wire:model.live.debounce.500ms="search" placeholder="Search for product name..." icon="magnifying-glass" />
                    </div>
                    <div class="flex gap-4">
                        <div>
                            <flux:select wire:model.live="filterCategory" placeholder="Filter Category">
                                <flux:select.option value="">All Categories</flux:select.option>
                                @foreach($categories as $key => $value)
                                    <flux:select.option value="{{ $value->id }}">{{ $value->name }}</flux:select.option>
                                @endforeach
                            </flux:select>
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
                    <div><flux:icon.shopping-bag class="text-blue-500" /></div>
                    <div><flux:heading size="lg">Product List</flux:heading></div>
                </div>
                <div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Category</th>
                                    <th scope="col" class="px-6 py-3">Code</th>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">Purchase Price</th>
                                    <th scope="col" class="px-6 py-3">Selling Price</th>
                                    <th scope="col" class="px-6 py-3">Stock</th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fetch as $value)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $value->category->name }}
                                    </th>
                                    <td class="px-6">{{ $value->product_code }}</td>
                                    <td class="px-6">{{ $value->product_name }}</td>
                                    <td class="px-6">Rp {{ number_format($value->purchase_price, 0, ',', '.') }}</td>
                                    <td class="px-6">Rp {{ number_format($value->selling_price, 0, ',', '.') }}</td>
                                    <td class="px-6">{{ $value->stock }}</td>
                                    <td class="px-6">
                                        <div class="flex justify-end gap-1">
                                            <flux:button :href="route('products.read', $value->id)" icon="eye" size="xs" title="View"></flux:button>
                                            <flux:button :href="route('products.update', $value->id)" icon="pencil-square" size="xs" variant="primary" color="blue" title="Edit"></flux:button>
                                            <flux:button wire:click="delete({{ $value->id }})" wire:confirm="Are you sure you want to delete {{ $value->category->name }}?" icon="trash" size="xs" variant="danger" title="Delete"></flux:button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="p-10 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <flux:icon.rocket-launch class="w-12 h-12 text-gray-300" />
                                            <p class="text-lg text-gray-500">No products found</p>
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
