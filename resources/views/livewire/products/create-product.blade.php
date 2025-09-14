<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Create Product</flux:heading>
                    <flux:text class="mt-2">Add new product to the system</flux:text>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-xl p-4 grid grid-cols-1 gap-4">
                <form wire:submit.prevent="store" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:input wire:model="product_code" label="Product Code" placeholder="PRD-xxxxx" readonly badge="read only" />
                        </div>
                        <div>
                            <flux:select wire:model="category_id" label="Category" badge="required" placeholder="Select category">
                                @foreach($categories as $key => $value)
                                    <flux:select.option value="{{ $value->id }}">{{ $value->name }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>
                        <div>
                            <flux:input wire:model="product_name" label="Product Name" placeholder="Enter product name" badge="required" />
                        </div>
                        <div>
                            <flux:input wire:model="purchase_price" label="Purchase Price" placeholder="Enter purchase price" badge="required" />
                        </div>
                        <div>
                            <flux:input wire:model="selling_price" label="Selling Price" placeholder="Enter selling price" badge="required" />
                        </div>
                        <div>
                            <flux:input wire:model="stock" label="Stock" placeholder="Enter stock quantity" badge="required" />
                        </div>
                    </div>
                    <div>
                            <div class="flex justify-start gap-2">
                                <div><flux:button variant="primary" color="red" :href="route('products.index')">Cancel</flux:button></div>
                                <div><flux:button variant="primary" wire:click.prevent="setAction('save')">Save</flux:button></div>
                                <div><flux:button wire:click.prevent="setAction('save_and_add')">Save and Add another</flux:button></div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</app>
