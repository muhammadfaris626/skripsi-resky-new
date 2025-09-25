<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">View Purchase</flux:heading>
                    <flux:text class="mt-2">View purchase database records</flux:text>
                </div>
                <div>
                    <flux:button variant="danger" icon="arrow-long-left" :href="route('purchases.index')">Back</flux:button>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-xl grid grid-cols-1 gap-4 p-4">
                <div>
                    <div class="flex justify-start gap-1">
                        <div><flux:icon.shopping-cart class="text-blue-500" /></div>
                        <div><flux:heading size="lg" icon="plus">Detail Purchase</flux:heading></div>
                    </div>
                </div>
                <div>
                    <div class="border rounded-lg p-4">
                        <div>
                            <flux:heading size="lg" icon="plus">Purchase Information</flux:heading>
                        </div>
                        <div class="grid grid-cols-2 mt-4 gap-4">
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.document-text class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Invoice Number</flux:text>
                                        <flux:heading size="md">{{ $purchase->invoice_number }}</flux:heading>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.calendar-days class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Date</flux:text>
                                        <flux:heading size="md">{{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}</flux:heading>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.user class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Employee</flux:text>
                                        <flux:heading size="md">{{ $purchase->employee->name }}</flux:heading>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.building-storefront class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Supplier</flux:text>
                                        <flux:heading size="md">{{ $purchase->supplier_name }}</flux:heading>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.credit-card class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Payment Method</flux:text>
                                        <flux:heading size="md">{{ ucfirst($purchase->payment_method) }}</flux:heading>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.currency-dollar class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Total Amount</flux:text>
                                        <flux:heading size="md">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</flux:heading>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($purchase->notes)
                            <div class="mt-4">
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.document-text class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Notes</flux:text>
                                        <flux:heading size="md">{{ $purchase->notes }}</flux:heading>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Purchase Items -->
                <div>
                    <div class="border rounded-lg p-4">
                        <div>
                            <flux:heading size="lg" icon="plus">Purchase Items</flux:heading>
                        </div>
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Product</th>
                                        <th scope="col" class="px-6 py-3">Quantity</th>
                                        <th scope="col" class="px-6 py-3">Unit Price</th>
                                        <th scope="col" class="px-6 py-3">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchase->itemPurchases as $item)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td class="px-6 py-4">
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">{{ $item->product->product_name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->product->product_code }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 font-medium">{{ number_format($item->quantity) }}</td>
                                            <td class="px-6 py-4 font-medium">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 font-medium text-green-600">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-right border-t pt-4">
                            <flux:heading size="lg">Total: Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</flux:heading>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="border rounded-lg p-4">
                        <div>
                            <flux:heading size="lg" icon="plus">System Information</flux:heading>
                        </div>
                        <div class="grid grid-cols-2 mt-4 gap-4">
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.calendar-days class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Created At</flux:text>
                                        <flux:heading size="md">{{ $purchase->created_at->format('d M Y, H:i:s') }}</flux:heading>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.calendar-days class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Updated At</flux:text>
                                        <flux:heading size="md">{{ $purchase->updated_at->format('d M Y, H:i:s') }}</flux:heading>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</app>
