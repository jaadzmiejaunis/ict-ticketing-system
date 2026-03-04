<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Ticket Management</h1>

                <div class="flex gap-3">
                    @if(request('filter') || request('search') || request('status') || request('priority'))
                        <a href="{{ route('tickets.index') }}"
                           class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-md font-bold transition shadow-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path></svg>
                            Show All Tickets
                        </a>
                    @endif

                    <a href="{{ route('tickets.index', ['filter' => 'assigned_by_me']) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-md font-bold transition shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Assigned by Me
                    </a>

                    <a href="{{ route('tickets.create') }}"
                       class="bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-md font-bold transition shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        New Ticket
                    </a>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm mb-8">
                <form action="{{ route('tickets.index') }}" method="GET" class="flex flex-wrap md:flex-nowrap gap-4">
                    @if(request('filter'))
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif

                    <div class="flex-grow">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Search Keywords</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, issue, or description..." class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="w-full md:w-48">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                        <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">All Statuses</option>
                            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>

                    <div class="w-full md:w-48">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Priority</label>
                        <select name="priority" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">All Priorities</option>
                            <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>

                    <div class="w-full md:w-48">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sort By</label>
                        <select name="sort" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="id_desc" {{ request('sort') == 'id_desc' ? 'selected' : '' }}>Newest (ID)</option>
                            <option value="id_asc" {{ request('sort') == 'id_asc' ? 'selected' : '' }}>Oldest (ID)</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-bold transition">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Complainant</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Issue</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($tickets as $ticket)
                                <tr class="group hover:bg-gray-50 transition border-b border-gray-100">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $ticket->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $ticket->reporter_name }}</td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex flex-col">
                                            <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 font-semibold hover:underline">
                                                {{ $ticket->title }}
                                            </a>

                                            <div class="mt-1">
                                                @if($ticket->status === 'Resolved' && $ticket->resolved_by)
                                                    <span class="text-[10px] text-gray-500 font-bold">
                                                        Resolved by {{ $ticket->resolver->name ?? 'Staff' }}
                                                    </span>
                                                @elseif($ticket->assigned_to)
                                                    <span class="text-[10px] text-indigo-600 font-bold">
                                                        Assigned to {{ $ticket->assignee->name }}

                                                        @if($ticket->assigned_by)
                                                            <span class="text-gray-400 font-normal italic opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                                (by {{ $ticket->assigner->name ?? 'System' }})
                                                            </span>
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="text-[10px] text-gray-400 italic">Currently Unassigned</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-700">{{ $ticket->category }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-bold rounded-full
                                            {{ $ticket->priority == 'High' ? 'bg-red-100 text-red-700' : ($ticket->priority == 'Medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                            {{ $ticket->priority }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-bold rounded-full border shadow-sm
                                            {{ $ticket->status == 'Open' ? 'bg-green-50 text-green-700 border-green-200' :
                                            ($ticket->status == 'Assigned' ? 'bg-blue-50 text-blue-700 border-blue-200' :
                                            ($ticket->status == 'On Hold' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-gray-100 text-gray-700 border-gray-200')) }}">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('tickets.edit', $ticket) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                                                Edit
                                            </a>

                                            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this ticket?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">No tickets found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
