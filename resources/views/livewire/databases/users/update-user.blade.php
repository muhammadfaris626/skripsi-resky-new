<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Update User</flux:heading>
                    <flux:text class="mt-2">Edit user information and roles</flux:text>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-xl p-4 grid grid-cols-1 gap-4">
                <form wire:submit.prevent="update" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:input wire:model="name" label="Name" placeholder="Full name" badge="required" />
                        </div>
                        <div>
                            <flux:input wire:model="email" label="Email" type="email" placeholder="user@example.com" badge="required" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:input wire:model="password" label="Password" type="password" placeholder="Leave blank to keep current password" />
                        </div>
                        <div>
                            <flux:input wire:model="password_confirmation" label="Confirm Password" type="password" placeholder="Confirm new password" />
                        </div>
                    </div>
                    <div>
                        <flux:field>
                            <flux:label badge="required">Roles</flux:label>
                            <div class="grid grid-cols-3 gap-2 mt-2">
                                @foreach($roles as $role)
                                    <flux:checkbox wire:model="selectedRoles" value="{{ $role->name }}" label="{{ ucfirst($role->name) }}" />
                                @endforeach
                            </div>
                        </flux:field>
                    </div>
                    <div>
                        <div class="flex justify-start gap-2">
                            <div><flux:button variant="primary" color="red" :href="route('users.index')">Cancel</flux:button></div>
                            <div><flux:button variant="primary" type="submit">Update User</flux:button></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</app>
