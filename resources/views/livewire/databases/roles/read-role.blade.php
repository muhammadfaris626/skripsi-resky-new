<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Role Details</flux:heading>
                    <flux:text class="mt-2">View role information and permissions</flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:button variant="primary" color="blue" icon="pencil-square" :href="route('roles.update', $role->id)">Edit Role</flux:button>
                    <flux:button variant="primary" color="red" :href="route('roles.index')">Back to List</flux:button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Role Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-xl p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center">
                            <flux:icon.shield-check class="w-8 h-8 text-white" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ ucfirst($role->name) }}</h2>
                            <p class="text-gray-600">{{ $role->users->count() }} users assigned</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:field>
                                <flux:label>Role Name</flux:label>
                                <flux:input value="{{ $role->name }}" readonly />
                            </flux:field>
                        </div>
                        <div>
                            <flux:field>
                                <flux:label>Guard Name</flux:label>
                                <flux:input value="{{ $role->guard_name }}" readonly />
                            </flux:field>
                        </div>
                        <div>
                            <flux:field>
                                <flux:label>Created At</flux:label>
                                <flux:input value="{{ $role->created_at->format('M d, Y H:i') }}" readonly />
                            </flux:field>
                        </div>
                        <div>
                            <flux:field>
                                <flux:label>Updated At</flux:label>
                                <flux:input value="{{ $role->updated_at->format('M d, Y H:i') }}" readonly />
                            </flux:field>
                        </div>
                    </div>

                    <!-- Assigned Users -->
                    @if($role->users->count() > 0)
                        <div class="mt-6">
                            <flux:heading size="lg" class="mb-4">Assigned Users</flux:heading>
                            <div class="grid grid-cols-1 gap-2">
                                @foreach($role->users as $user)
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            {{ $user->initials() }}
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Permissions -->
            <div>
                <div class="bg-white rounded-lg shadow-xl p-6">
                    <flux:heading size="lg" class="mb-4">Permissions</flux:heading>
                    <div class="space-y-3">
                        @forelse($groupedPermissions as $entity => $permissions)
                            <div class="border rounded-lg p-3">
                                <h4 class="font-semibold text-gray-900 mb-2">{{ ucfirst($entity) }}</h4>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($permissions as $permission)
                                        @php
                                            $action = explode(':', $permission->name)[1] ?? '';
                                        @endphp
                                        <flux:badge size="sm" color="green">{{ ucfirst($action) }}</flux:badge>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No permissions assigned</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</app>
