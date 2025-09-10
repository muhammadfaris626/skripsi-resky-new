<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Update Target</flux:heading>
                    <flux:text class="mt-2">Update target information in database</flux:text>
                </div>
            </div>
        </div>
        <div>
            <div>
                <div class="bg-white rounded-lg shadow-xl p-4 grid grid-cols-1 gap-4">
                    <form wire:submit.prevent="update" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <flux:select wire:model="employee_id" label="Employee Name" badge="required" placeholder="Select employee">
                                    @foreach($employees as $key => $value)
                                        <flux:select.option value="{{ $value->id }}">{{ $value->name }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                            </div>
                            <div>
                                <flux:input type="month" wire:model="month" label="Month" placeholder="MM-YYYY" badge="required" />
                            </div>

                            <div>
                                <flux:input wire:model="sale_target" label="Sale Target" badge="required" placeholder="Sale target" />
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-start gap-2">
                                <div><flux:button variant="primary" color="red" :href="route('targets.index')">Cancel</flux:button></div>
                                <div><flux:button variant="primary" type="submit">Update</flux:button></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</app>
