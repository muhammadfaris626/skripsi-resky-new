<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Permission Details</flux:heading>
                    <flux:text class="mt-2">View permission information and assigned roles</flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:button variant="primary" color="blue" icon="pencil-square" :href="route('permissions.update', $permission->id)">Edit Permission</flux:button>
                    <flux:button variant="primary" color="red" :href="route('permissions.index')">Back to List</flux:button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Permission Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-xl p-6">
                    @php
                        $parts = explode(':', $permission->name);
                        $entity = $parts[0] ?? '';
                        $action = $parts[1] ?? '';
                    @endphp

                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center">
                            <flux:icon.key class="w-8 h-8 text-white" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $permission->name }}</h2>
                            <p class="text-gray-600">{{ $permission->roles->count() }} roles assigned</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:field>
                                <flux:label>Permission Name</flux:label>
                                <flux:input value="{{ $permission->name }}" readonly />
                            </flux:field>
                        </div>
                        <div>
                            <flux:field>
                                <flux:label>Guard Name</flux:label>
                                <flux:input value="{{ $permission->guard_name }}" readonly />
                            </flux:field>
                        </div>
                        <div>
                            <flux:field>
                                <flux:label>Entity</flux:label>
                                <flux:input value="{{ ucfirst($entity) }}" readonly />
                            </flux:field>
                        </div>
                        <div>
                            <flux:field>
                                <flux:label>Action</flux:label>
                                <flux:input value="{{ ucfirst($action) }}" readonly />
                            </flux:field>
                        </div>
                        <div>
                            <flux:field>
                                <flux:label>Created At</flux:label>
                                <flux:input value="{{ $permission->created_at->format('M d, Y H:i') }}" readonly />
                            </flux:field>
                        </div>
                        <div>
                            <flux:field>
                                <flux:label>Updated At</flux:label>
                                <flux:input value="{{ $permission->updated_at->format('M d, Y H:i') }}" readonly />
                            </flux:field>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Roles -->
            <div>
                <div class="bg-white rounded-lg shadow-xl p-6">
                    <flux:heading size="lg" class="mb-4">Assigned Roles</flux:heading>
                    <div class="space-y-3">
                        @forelse($permission->roles as $role)
                            <div class="border rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ ucfirst($role->name) }}</h4>
                                        <p class="text-sm text-gray-600">{{ $role->users->count() }} users</p>
                                    </div>
                                    <flux:badge size="sm" color="blue">{{ ucfirst($role->name) }}</flux:badge>
                                </div>

                                @if($role->users->count() > 0)
                                    <div class="mt-2 pt-2 border-t">
                                        <p class="text-xs text-gray-500 mb-1">Users with this role:</p>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($role->users->take(3) as $user)
                                                <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $user->name }}</span>
                                            @endforeach
                                            @if($role->users->count() > 3)
                                                <span class="text-xs text-gray-500">+{{ $role->users->count() - 3 }} more</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-500">No roles assigned to this permission</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</app>
