<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Update Role</flux:heading>
                    <flux:text class="mt-2">Edit role information and permissions</flux:text>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-xl p-4 grid grid-cols-1 gap-4">
                <form wire:submit.prevent="update" class="space-y-6">
                    <div>
                        <flux:input wire:model="name" label="Role Name" placeholder="Enter role name" badge="required" />
                    </div>

                    <div>
                        <flux:field>
                            <flux:label badge="required">Permissions</flux:label>
                            <flux:text class="text-sm text-gray-600 mb-4">Select the permissions for this role. You can click on entity names to toggle all permissions for that entity.</flux:text>

                            <div class="space-y-4">
                                @foreach($permissions as $entity => $entityPermissions)
                                    <div class="border rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="text-lg font-semibold text-gray-900 capitalize">{{ $entity }}</h3>
                                            <flux:button
                                                type="button"
                                                size="sm"
                                                variant="outline"
                                                wire:click="toggleAllPermissions('{{ $entity }}')"
                                            >
                                                Toggle All
                                            </flux:button>
                                        </div>
                                        <div class="grid grid-cols-5 gap-2">
                                            @foreach($entityPermissions as $permission)
                                                @php
                                                    $action = explode(':', $permission->name)[1] ?? '';
                                                @endphp
                                                <flux:checkbox
                                                    wire:model="selectedPermissions"
                                                    value="{{ $permission->name }}"
                                                    label="{{ ucfirst($action) }}"
                                                />
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </flux:field>
                    </div>

                    <div>
                        <div class="flex justify-start gap-2">
                            <div><flux:button variant="primary" color="red" :href="route('roles.index')">Cancel</flux:button></div>
                            <div><flux:button variant="primary" type="submit">Update Role</flux:button></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</app>
