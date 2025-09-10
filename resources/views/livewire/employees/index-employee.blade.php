<app>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="flex justify-between items-center">
                <div>
                    <flux:heading size="xl">Manage Employees</flux:heading>
                    <flux:text class="mt-2">Store employee data management</flux:text>
                </div>
                <div>
                    <flux:button variant="primary" icon="plus" :href="route('employees.create')">Add Employee</flux:button>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-lg shadow-xl p-4 space-y-3">
                <div class="flex justify-between items-center">
                    <div><flux:heading size="lg" icon="funnel">Filter & Search</flux:heading></div>
                </div>

                <div class="flex justify-between items-center gap-4 mt-3">
                    <div class="flex-1 max-w">
                        <flux:input wire:model.live.debounce.500ms="search" placeholder="Search name, position, phone, email..." icon="magnifying-glass" />
                    </div>
                    <div class="flex gap-4">
                        <div>
                            <flux:select wire:model.live="positionFilter" placeholder="Filter Position">
                                <flux:select.option value="">All Position</flux:select.option>
                                <flux:select.option value="kasir">Kasir</flux:select.option>
                                <flux:select.option value="sales">Sales</flux:select.option>
                                <flux:select.option value="admin">Admin</flux:select.option>
                                <flux:select.option value="manager">Manager</flux:select.option>
                            </flux:select>
                        </div>
                        <div>
                            <flux:select wire:model.live="perPage" placeholder="Per Page">
                                <flux:select.option value="5">5 per page</flux:select.option>
                                <flux:select.option value="10">10 per page</flux:select.option>
                                <flux:select.option value="25">25 per page</flux:select.option>
                                <flux:select.option value="50">50 per page</flux:select.option>
                                <flux:select.option value="100">100 per page</flux:select.option>
                            </flux:select>
                        </div>
                        <div>
                            <flux:button wire:click="resetFilters" icon="x-mark">Reset</flux:button>
                        </div>
                    </div>
                </div>

                <!-- Info Bar -->
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <div>
                        Showing {{ $fetch->firstItem() ?? 0 }} to {{ $fetch->lastItem() ?? 0 }}
                        of {{ $fetch->total() }} results
                    </div>
                    @if($search || $positionFilter)
                        <div class="flex items-center gap-2">
                            <span>Active filters:</span>
                            @if($search)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                    Search: "{{ $search }}"
                                </span>
                            @endif
                            @if($positionFilter)
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                    Position: {{ ucfirst($positionFilter) }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-lg shadow-xl p-4 grid grid-cols-1 gap-4">
                <div class="flex justify-start gap-1">
                    <div><flux:icon.users class="text-blue-500" /></div>
                    <div><flux:heading size="lg">Employee List</flux:heading></div>
                </div>

                <div>
                    <!-- Loading Indicator -->
                    <div wire:loading class="flex justify-center p-4">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                    </div>

                    <div wire:loading.remove class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">Position</th>
                                    <th scope="col" class="px-6 py-3">Phone</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fetch as $employee)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $employee->name }}
                                    </th>
                                    <td class="px-6 capitalize">
                                        <span class="px-2 py-1 rounded-full text-xs
                                            {{ $employee->position == 'manager' ? 'bg-purple-100 text-purple-800' :
                                            ($employee->position == 'admin' ? 'bg-blue-100 text-blue-800' :
                                            ($employee->position == 'sales' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ $employee->position }}
                                        </span>
                                    </td>
                                    <td class="px-6">{{ $employee->phone }}</td>
                                    <td class="px-6">{{ $employee->email }}</td>
                                    <td class="px-6">
                                        <div class="flex justify-end gap-1">
                                            <flux:button :href="route('employees.read', $employee->id)" icon="eye" size="xs" title="View"></flux:button>
                                            <flux:button :href="route('employees.update', $employee->id)" icon="pencil-square" size="xs" variant="primary" color="blue" title="Edit"></flux:button>
                                            <flux:button wire:click="delete({{ $employee->id }})" wire:confirm="Are you sure you want to delete {{ $employee->name }}?" icon="trash" size="xs" variant="danger" title="Delete"></flux:button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="p-10 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <flux:icon.users class="w-12 h-12 text-gray-300" />
                                            <p class="text-lg text-gray-500">No employees found</p>
                                            @if($search || $positionFilter)
                                                <p class="text-sm text-gray-400">Try adjusting your search or filter criteria</p>
                                                <flux:button wire:click="resetFilters" variant="ghost" size="sm">Clear filters</flux:button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
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
