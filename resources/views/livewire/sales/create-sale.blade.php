<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Create Sale</flux:heading>
                    <flux:text class="mt-2">Add new sale to the system</flux:text>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-xl p-4 grid grid-cols-1 gap-4">
                <form wire:submit.prevent="store" class="space-y-4">
                    <div class="grid grid-cols-1 xl:grid-cols-3 lg:grid-cols-3 md:grid-cols-3 sm:grid-cols-3 gap-4">
                        <div>
                            <flux:input wire:model="date" type="date" label="Date" badge="required" />
                        </div>
                        <div>
                            <flux:select wire:model="payment_method" label="Payment Method" badge="required" placeholder="Select payment method">
                                <flux:select.option value="Tunai">Tunai</flux:select.option>
                                <flux:select.option value="Transfer">Transfer</flux:select.option>
                                <flux:select.option value="Kartu">Kartu</flux:select.option>
                            </flux:select>
                        </div>
                        <div>
                            <flux:select wire:model="employee_id" label="Employee" badge="required" placeholder="Select employee">
                                @foreach($employees as $key => $value)
                                    <flux:select.option value="{{ $value->id }}">{{ $value->name }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-md font-semibold">Sales Items</h3>
                                </div>
                                <div>
                                    <flux:button type="button" size="sm" color="blue" variant="primary" wire:click="addItem">+ Add Item</flux:button>
                                </div>
                            </div>
                        </div>
                        <div class="border rounded-lg">
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Product
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Qty
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Price
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Amount
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                <span class="sr-only">Action</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sale_items as $index => $item)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                                <flux:select wire:model="sale_items.{{ $index }}.product_id" placeholder="Select product">
                                                    @foreach($products as $key => $value)
                                                        <flux:select.option value="{{ $value->id }}">{{ $value->product_name }}</flux:select.option>
                                                    @endforeach
                                                </flux:select>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <button wire:click="decreaseQuantity({{ $index }})" class="inline-flex items-center justify-center p-1 me-3 text-sm font-medium h-6 w-6 text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">
                                                        <span class="sr-only">Decrease quantity</span>
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                                                        </svg>
                                                    </button>

                                                    <div>
                                                        <input wire:model="sale_items.{{ $index }}.quantity" type="number" class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="1" />
                                                    </div>

                                                    <button wire:click="increaseQuantity({{ $index }})" class="inline-flex items-center justify-center h-6 w-6 p-1 ms-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">
                                                        <span class="sr-only">Increase quantity</span>
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                                                        </svg>
                                                    </button>

                                                    @if(!empty($item['product_id']))
                                                        @php
                                                            $product = $products->find($item['product_id']);
                                                        @endphp
                                                        @if($product)
                                                            <h3 class="text-xs font-semibold ms-2 text-red-500">
                                                                Stock: {{ $product->stock }}
                                                            </h3>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                                Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                                Rp {{ number_format($item['amount'] ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <flux:button type="button" wire:click="removeItem({{ $index }})" icon="trash" variant="danger" size="xs" />
                                            </td>
                                        </tr>
                                        @empty

                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="text-right">
                            <h3 class="text-lg font-semibold">TOTAL : Rp {{ number_format($this->getTotalAmount(), 0, ',', '.') }}</h3>
                        </div>
                    </div>
                    <div>
                            <div class="flex justify-start gap-2">
                                <div><flux:button variant="primary" color="red" :href="route('sales.index')">Cancel</flux:button></div>
                                <div><flux:button variant="primary" wire:click.prevent="setAction('save')">Save</flux:button></div>
                                <div><flux:button wire:click.prevent="setAction('save_and_add')">Save and Add another</flux:button></div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</app>
