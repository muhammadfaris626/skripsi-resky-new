<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Manage Users</flux:heading>
                    <flux:text class="mt-2">User account management</flux:text>
                </div>
                <div>
                    <flux:button variant="primary" icon="plus" :href="route('users.create')">Add User</flux:button>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-xl p-4 space-y-3">
                <div class="flex justify-between items-center">
                    <div><flux:heading size="lg" icon="funnel">Filter & Search</flux:heading></div>
                </div>
                <div class="flex justify-between items-center gap-4 mt-3">
                    <div class="flex-1 max-w-md">
                        <flux:input wire:model.live.debounce.500ms="search" placeholder="Search for name or email..." icon="magnifying-glass" />
                    </div>
                    <div class="w-48">
                        <flux:select wire:model.live="roleFilter" placeholder="Filter by role">
                            <flux:select.option value="">All Roles</flux:select.option>
                            @foreach($roles as $role)
                                <flux:select.option value="{{ $role->name }}">{{ ucfirst($role->name) }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <div>
                        Showing {{ $fetch->firstItem() ?? 0 }} to {{ $fetch->lastItem() ?? 0 }}
                        of {{ $fetch->total() }} results
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-xl p-4 grid grid-cols-1 gap-4">
                <div class="flex justify-start gap-1">
                    <div><flux:icon.users class="text-blue-500" /></div>
                    <div><flux:heading size="lg">User List</flux:heading></div>
                </div>
                <div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Roles</th>
                                    <th scope="col" class="px-6 py-3">Created</th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            @forelse($fetch as $value)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            {{ $value->initials() }}
                                        </div>
                                        {{ $value->name }}
                                    </div>
                                </th>
                                <td class="px-6 py-3">{{ $value->email }}</td>
                                <td class="px-6 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($value->roles as $role)
                                            <flux:badge size="sm" color="blue">{{ ucfirst($role->name) }}</flux:badge>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-3">{{ $value->created_at->format('M d, Y') }}</td>
                                <td class="px-6">
                                    <div class="flex justify-end gap-1">
                                        <flux:button :href="route('users.read', $value->id)" icon="eye" size="xs" title="View"></flux:button>
                                        <flux:button :href="route('users.update', $value->id)" icon="pencil-square" size="xs" variant="primary" color="blue" title="Edit"></flux:button>
                                        @if($value->id !== auth()->id())
                                            <flux:button wire:click="delete({{ $value->id }})" wire:confirm="Are you sure you want to delete {{ $value->name }}?" icon="trash" size="xs" variant="danger" title="Delete"></flux:button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <flux:icon.users class="w-12 h-12 text-gray-300" />
                                        <p class="text-lg text-gray-500">No users found</p>
                                        <p class="text-sm text-gray-400">Try adjusting your search or filter criteria</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
                @if($fetch->hasPages())
                <div>
                    {{ $fetch->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</app>
