<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">User Details</flux:heading>
                    <flux:text class="mt-2">View user information and permissions</flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:button variant="primary" color="blue" icon="pencil-square" :href="route('users.update', $user->id)">Edit User</flux:button>
                    <flux:button variant="primary" color="red" :href="route('users.index')">Back to List</flux:button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- User Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-xl p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white text-xl font-bold">
                            {{ $user->initials() }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:field>
                                <flux:label>Name</flux:label>
                                <flux:input value="{{ $user->name }}" readonly />
                            </flux:field>
                        </div>
                        <div>
                            <flux:field>
                                <flux:label>Email</flux:label>
                                <flux:input value="{{ $user->email }}" readonly />
                            </flux:field>
                        </div>
                        <div>
                            <flux:field>
                                <flux:label>Created At</flux:label>
                                <flux:input value="{{ $user->created_at->format('M d, Y H:i') }}" readonly />
                            </flux:field>
                        </div>
                        <div>
                            <flux:field>
                                <flux:label>Updated At</flux:label>
                                <flux:input value="{{ $user->updated_at->format('M d, Y H:i') }}" readonly />
                            </flux:field>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles and Permissions -->
            <div>
                <div class="bg-white rounded-lg shadow-xl p-6">
                    <flux:heading size="lg" class="mb-4">Assigned Roles</flux:heading>
                    <div class="space-y-2 mb-6">
                        @forelse($user->roles as $role)
                            <flux:badge size="lg" color="blue">{{ ucfirst($role->name) }}</flux:badge>
                        @empty
                            <p class="text-gray-500">No roles assigned</p>
                        @endforelse
                    </div>

                    <flux:heading size="lg" class="mb-4">Permissions</flux:heading>
                    <div class="space-y-3">
                        @php
                            $allPermissions = $user->roles->flatMap->permissions->unique('id');
                            $groupedPermissions = $allPermissions->groupBy(function($permission) {
                                return explode(':', $permission->name)[0];
                            });
                        @endphp

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
