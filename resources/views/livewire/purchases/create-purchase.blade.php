<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Create Purchase</flux:heading>
                    <flux:text class="mt-2">Add new purchase to the system</flux:text>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-xl p-4 grid grid-cols-1 gap-4">
                <form wire:submit.prevent="save" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:input wire:model="invoice_number" label="Invoice Number" placeholder="PUR-xxxxx" readonly badge="read only" />
                        </div>
                        <div>
                            <flux:input type="date" wire:model="date" label="Date" badge="required" />
                        </div>
                        <div>
                            <flux:select wire:model="employee_id" label="Employee" badge="required" placeholder="Select employee">
                                @foreach($employees as $employee)
                                    <flux:select.option value="{{ $employee->id }}">{{ $employee->name }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>
                        <div>
                            <flux:input wire:model="supplier_name" label="Supplier Name" placeholder="Enter supplier name" badge="required" />
                        </div>
                        <div>
                            <flux:select wire:model="payment_method" label="Payment Method" badge="required">
                                <flux:select.option value="cash">Cash</flux:select.option>
                                <flux:select.option value="transfer">Bank Transfer</flux:select.option>
                                <flux:select.option value="credit">Credit</flux:select.option>
                            </flux:select>
                        </div>
                        <div>
                            <flux:textarea wire:model="notes" label="Notes" placeholder="Additional notes (optional)" rows="3" />
                        </div>
                    </div>

                    <!-- Add Items Section -->
                    <div class="border rounded-lg p-4">
                        <flux:heading size="lg">Add Items</flux:heading>
                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div>
                                <flux:select wire:model.live="selectedProduct" label="Product" placeholder="Select product">
                                    @foreach($products as $product)
                                        <flux:select.option value="{{ $product->id }}">
                                            {{ $product->product_code }} - {{ $product->product_name }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                            </div>
                            <div>
                                <flux:input type="number" wire:model="quantity" label="Quantity" min="1" />
                            </div>
                            <div>
                                <flux:input type="number" wire:model="price" label="Unit Price" step="0.01" min="0" />
                            </div>
                        </div>
                        <div class="mt-4">
                            <flux:button wire:click="addItem" type="button" variant="primary">Add Item</flux:button>
                        </div>
                    </div>

                    <!-- Items Table -->
                    @if(count($items) > 0)
                        <div class="border rounded-lg p-4">
                            <flux:heading size="lg">Purchase Items</flux:heading>
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Product</th>
                                            <th scope="col" class="px-6 py-3">Quantity</th>
                                            <th scope="col" class="px-6 py-3">Unit Price</th>
                                            <th scope="col" class="px-6 py-3">Amount</th>
                                            <th scope="col" class="px-6 py-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $index => $item)
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td class="px-6 py-4">
                                                    <div>
                                                        <div class="font-medium">{{ $item['product_name'] }}</div>
                                                        <div class="text-sm text-gray-500">{{ $item['product_code'] }}</div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="number"
                                                           wire:change="updateItemQuantity({{ $index }}, $event.target.value)"
                                                           value="{{ $item['quantity'] }}"
                                                           min="1"
                                                           class="w-20 px-2 py-1 border rounded">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="number"
                                                           wire:change="updateItemPrice({{ $index }}, $event.target.value)"
                                                           value="{{ $item['price'] }}"
                                                           step="0.01"
                                                           min="0"
                                                           class="w-24 px-2 py-1 border rounded">
                                                </td>
                                                <td class="px-6 py-4 font-medium">
                                                    Rp {{ number_format($item['amount'], 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <flux:button wire:click="removeItem({{ $index }})" size="xs" variant="danger">
                                                        Remove
                                                    </flux:button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-right">
                                <flux:heading size="lg">Total: Rp {{ number_format($this->getTotalAmount(), 0, ',', '.') }}</flux:heading>
                            </div>
                        </div>
                    @endif

                    <div>
                        <div class="flex justify-start gap-2">
                            <div><flux:button variant="primary" color="red" :href="route('purchases.index')">Cancel</flux:button></div>
                            <div><flux:button variant="primary" type="submit" :disabled="count($items) === 0">Save Purchase</flux:button></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</app>
