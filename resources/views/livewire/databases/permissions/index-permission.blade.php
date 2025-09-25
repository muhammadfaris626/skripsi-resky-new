<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Manage Permissions</flux:heading>
                    <flux:text class="mt-2">System permission management</flux:text>
                </div>
                <div>
                    <flux:button variant="primary" icon="plus" :href="route('permissions.create')">Add Permission</flux:button>
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
                        <flux:input wire:model.live.debounce.500ms="search" placeholder="Search for permission name..." icon="magnifying-glass" />
                    </div>
                    <div class="w-48">
                        <flux:select wire:model.live="entityFilter" placeholder="Filter by entity">
                            <flux:select.option value="">All Entities</flux:select.option>
                            @foreach($entities as $entity)
                                <flux:select.option value="{{ $entity }}">{{ ucfirst($entity) }}</flux:select.option>
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
                    <div><flux:icon.key class="text-blue-500" /></div>
                    <div><flux:heading size="lg">Permission List</flux:heading></div>
                </div>
                <div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Permission Name</th>
                                    <th scope="col" class="px-6 py-3">Entity</th>
                                    <th scope="col" class="px-6 py-3">Action</th>
                                    <th scope="col" class="px-6 py-3">Roles Count</th>
                                    <th scope="col" class="px-6 py-3">Created</th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            @forelse($fetch as $value)
                            @php
                                $parts = explode(':', $value->name);
                                $entity = $parts[0] ?? '';
                                $action = $parts[1] ?? '';
                            @endphp
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex items-center gap-2">
                                        <flux:icon.key class="w-4 h-4 text-blue-500" />
                                        {{ $value->name }}
                                    </div>
                                </th>
                                <td class="px-6 py-3">
                                    <flux:badge size="sm" color="purple">{{ ucfirst($entity) }}</flux:badge>
                                </td>
                                <td class="px-6 py-3">
                                    <flux:badge size="sm" color="green">{{ ucfirst($action) }}</flux:badge>
                                </td>
                                <td class="px-6 py-3">
                                    <flux:badge size="sm" color="blue">{{ $value->roles_count }} roles</flux:badge>
                                </td>
                                <td class="px-6 py-3">{{ $value->created_at->format('M d, Y') }}</td>
                                <td class="px-6">
                                    <div class="flex justify-end gap-1">
                                        <flux:button :href="route('permissions.read', $value->id)" icon="eye" size="xs" title="View"></flux:button>
                                        <flux:button :href="route('permissions.update', $value->id)" icon="pencil-square" size="xs" variant="primary" color="blue" title="Edit"></flux:button>
                                        <flux:button wire:click="delete({{ $value->id }})" wire:confirm="Are you sure you want to delete {{ $value->name }}?" icon="trash" size="xs" variant="danger" title="Delete"></flux:button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <flux:icon.key class="w-12 h-12 text-gray-300" />
                                        <p class="text-lg text-gray-500">No permissions found</p>
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
