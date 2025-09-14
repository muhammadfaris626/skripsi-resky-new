<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">View Product</flux:heading>
                    <flux:text class="mt-2">View product database records</flux:text>
                </div>
                <div>
                    <flux:button variant="danger" icon="arrow-long-left" :href="route('products.index')">Back</flux:button>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-xl grid grid-cols-1 gap-4 p-4">
                <div>
                    <div class="flex justify-start gap-1">
                        <div><flux:icon.shopping-bag class="text-blue-500" /></div>
                        <div><flux:heading size="lg" icon="plus">Detail Product</flux:heading></div>
                    </div>
                </div>
                <div>
                    <div class="border rounded-lg p-4">
                        <div>
                            <flux:heading size="lg" icon="plus">Product Information</flux:heading>
                        </div>
                        <div class="grid grid-cols-2 mt-4 gap-4">
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.square-3-stack-3d class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Category</flux:text>
                                        <flux:heading size="md">{{ $data->category->name }}</flux:heading>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.qr-code class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Product Code</flux:text>
                                        <flux:heading size="md" class="capitalize">{{ $data->product_code }}</flux:heading>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.tag class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Product Name</flux:text>
                                        <flux:heading size="md">{{ $data->product_name }}</flux:heading>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.currency-dollar class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Purchase Price</flux:text>
                                        <flux:heading size="md">Rp {{ number_format($data->purchase_price, 0, ',', '.') }}</flux:heading>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.currency-dollar class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Selling Price</flux:text>
                                        <flux:heading size="md">Rp {{ number_format($data->selling_price, 0, ',', '.') }}</flux:heading>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-start items-center gap-2">
                                    <div>
                                        <flux:icon.archive-box class="size-5" />
                                    </div>
                                    <div>
                                        <flux:text>Stock</flux:text>
                                        <flux:heading size="md">{{ $data->stock }}</flux:heading>
                                    </div>
                                </div>
                            </div>
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
