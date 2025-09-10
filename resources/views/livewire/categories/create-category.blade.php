<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Create Category</flux:heading>
                    <flux:text class="mt-2">Add a new category to the system</flux:text>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-xl p-4 grid grid-cols-1 gap-4">
                <form wire:submit.prevent="store" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:input wire:model="name" label="Name" placeholder="Category name" badge="required" />
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-start gap-2">
                            <div><flux:button variant="primary" color="red" :href="route('categories.index')">Cancel</flux:button></div>
                            <div><flux:button variant="primary" wire:click.prevent="setAction('save')">Save</flux:button></div>
                            <div><flux:button wire:click.prevent="setAction('save_and_add')">Save and Add another</flux:button></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</app>
