<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">View Sale</flux:heading>
                    <flux:text class="mt-2">View sale database records</flux:text>
                </div>
                <div>
                    <flux:button variant="danger" icon="arrow-long-left" :href="route('sales.index')">Back</flux:button>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-xl grid grid-cols-1 gap-4 p-4">
                <div>
                    <div class="flex justify-start gap-1">
                        <div><flux:icon.shopping-cart class="text-blue-500" /></div>
                        <div><flux:heading size="lg" icon="plus">Detail Sale</flux:heading></div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="border rounded-lg p-4">
                        <div>
                            <flux:heading size="lg" icon="plus">Transaction Information</flux:heading>
                        </div>
                        <div class="grid grid-cols-1 mt-4 gap-4">
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.calendar-days class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Date</flux:text>
                                        <flux:heading size="md">{{ \Carbon\Carbon::parse($data->date)->format('d F Y') }}</flux:heading>
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
                                        <flux:heading size="md">{{ $data->employee->name }}</flux:heading>
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
                                        <flux:badge color="blue" size="sm">{{ $data->payment_method }}</flux:badge>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border rounded-lg p-4">
                        <div>
                            <flux:heading size="lg">Total Sales</flux:heading>
                        </div>
                        <div class="flex justify-center items-center h-full">
                            <div class="grid grid-cols-1 gap-2">
                                <div class="text-center text-3xl font-bold text-green-600">
                                    Rp {{ number_format($data->total_amount, 0, ',', '.') }}
                                </div>
                                <div class="text-center text-gray-500">
                                    {{ $data->itemSales->count() }} items sold
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-full border rounded-lg p-4">
                        <div>
                            <flux:heading size="lg" icon="plus">Sales Item Details</flux:heading>
                        </div>
                        <div class="grid grid-cols-1 mt-4 gap-4">
                            <div class="relative overflow-x-auto">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Product name
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Quantity
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Price
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Amount
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data->itemSales as $key => $value)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $value->product->product_name }}
                                            </th>
                                            <td class="px-6 py-4">
                                            {{ $value->quantity }}
                                            </td>
                                            <td class="px-6 py-4">
                                                Rp {{ number_format($value->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                Rp {{ number_format($value->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="col-span-full border rounded-lg p-4">
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
                                        <flux:heading size="md">{{ $data->created_at }}</flux:heading>
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
                                        <flux:heading size="md">{{ $data->updated_at }}</flux:heading>
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
