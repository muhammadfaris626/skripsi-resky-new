<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Create Employee</flux:heading>
                    <flux:text class="mt-2">Add new employee to the system</flux:text>
                </div>
            </div>
        </div>
        <div>
            <div>
                <div class="bg-white rounded-lg shadow-xl p-4 grid grid-cols-1 gap-4">
                    <form wire:submit.prevent="store" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <flux:input wire:model="name" label="Name" placeholder="Full name" badge="required" />
                            </div>
                            <div>
                                <flux:select wire:model="position" label="Position" badge="required" placeholder="Select position">
                                    <flux:select.option value="kasir">Kasir</flux:select.option>
                                    <flux:select.option value="sales">Sales</flux:select.option>
                                    <flux:select.option value="admin">Admin</flux:select.option>
                                    <flux:select.option value="manager">Manager</flux:select.option>
                                </flux:select>
                            </div>
                            <div>
                                <flux:input wire:model="phone" mask="+62999999999999" label="Phone" badge="required" placeholder="Example : 8xxxxxxxxxx" />
                            </div>
                            <div>
                                <flux:input wire:model="email" label="Email" placeholder="Example : example@gmail.com" badge="required" />
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-start gap-2">
                                <div><flux:button variant="primary" color="red" :href="route('employees.index')">Cancel</flux:button></div>
                                <div><flux:button variant="primary" wire:click.prevent="setAction('save')">Save</flux:button></div>
                                <div><flux:button wire:click.prevent="setAction('save_and_add')">Save and Add another</flux:button></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</app>
