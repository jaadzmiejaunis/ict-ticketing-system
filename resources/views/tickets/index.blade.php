<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class ="mb-4 sm:mb-6">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white transition-colors">Ticket Management</h1>
                <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm mt-1 transition-colors">
                    Browse and filter the full list of support requests. Click on any ticket subject to view its detail and actions.
                </p>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6 gap-4">
                <div class="flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-2 sm:gap-3 w-full">
                    @if(request('filter') || request('search') || request('status') || request('priority'))
                        <a href="{{ route('tickets.index') }}"
                           class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-white px-4 sm:px-5 py-3 sm:py-2.5 rounded-md font-bold transition shadow-sm text-xs sm:text-sm uppercase flex items-center justify-center gap-2 border border-gray-300 dark:border-gray-600 w-full sm:w-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path></svg>
                            Show All
                        </a>
                    @endif

                    <a href="{{ route('tickets.index', ['filter' => 'owned']) }}"
                       class="{{ request('filter') === 'owned' ? 'bg-indigo-600 text-white border-transparent' : 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600' }} px-4 sm:px-5 py-3 sm:py-2.5 rounded-md font-bold transition shadow-sm text-xs sm:text-sm uppercase flex items-center justify-center gap-2 border w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        My Tickets
                    </a>

                    <a href="{{ route('tickets.index', ['filter' => 'assigned_by_me']) }}"
                       class="{{ request('filter') === 'assigned_by_me' ? 'bg-indigo-600 text-white border-transparent' : 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600' }} px-4 sm:px-5 py-3 sm:py-2.5 rounded-md font-bold transition shadow-sm text-xs sm:text-sm uppercase flex items-center justify-center gap-2 border w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Assigned To Me
                    </a>

                    @php
                        $trashCount = \App\Models\Ticket::onlyTrashed()
                            ->when(Auth::user()->role !== 'admin', function($query) {
                                return $query->where('user_id', Auth::id());
                            })->count();
                    @endphp
                    <a href="{{ route('tickets.trash') }}"
                       class="relative bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-white px-4 sm:px-5 py-3 sm:py-2.5 rounded-md font-bold transition shadow-sm text-xs sm:text-sm uppercase flex items-center justify-center gap-2 border border-gray-300 dark:border-gray-600 w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Trash
                        @if($trashCount > 0)
                            <span class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white shadow-md border-2 border-white dark:border-gray-800 transition-colors">
                                {{ $trashCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('tickets.create') }}"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 sm:px-5 py-3 sm:py-2.5 rounded-md font-bold transition shadow-sm text-xs sm:text-sm uppercase flex items-center justify-center gap-2 border border-transparent w-full sm:w-auto mt-2 sm:mt-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Create Ticket
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="mb-6 flex items-center justify-between p-3 sm:p-4 bg-green-50 dark:bg-green-900/40 border-l-4 border-green-500 rounded-r-lg shadow-sm transition-colors">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 dark:text-green-400 mr-2 sm:mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <p class="text-xs sm:text-sm font-bold text-green-700 dark:text-green-400 uppercase tracking-wide leading-tight">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-500 hover:text-green-700 dark:hover:text-green-300 transition shrink-0 ml-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            @if(session('recent_created'))
                <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl sm:rounded-lg shadow-sm mb-6 sm:mb-8 border border-gray-200 dark:border-gray-700 transition-colors">
                    <h3 class="text-indigo-600 dark:text-indigo-400 font-bold text-[10px] sm:text-xs uppercase tracking-wider mb-3 sm:mb-4 flex items-center gap-2">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Recently Created Tickets (Your Last 3)
                    </h3>
                    <div class="space-y-2 sm:space-y-3">
                        @foreach(session('recent_created') as $recent)
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg border border-gray-100 dark:border-gray-600 transition hover:bg-gray-100 dark:hover:bg-gray-700 gap-1 sm:gap-0">
                                <span class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 font-medium truncate">
                                    #{{ $recent['id'] }} - <span class="font-bold text-gray-900 dark:text-white">{{ $recent['title'] }}</span>
                                </span>
                                <span class="text-[9px] sm:text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase italic">Created on {{ $recent['date'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-2xl sm:rounded-lg shadow-sm mb-6 sm:mb-8 border border-gray-200 dark:border-gray-700 transition-colors">
                <form action="{{ route('tickets.index') }}" method="GET" class="flex flex-col md:flex-row flex-wrap md:flex-nowrap gap-3 sm:gap-4">
                    @if(request('filter'))
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif

                    <div class="flex-grow w-full">
                        <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1">Search Keywords</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID, Name or Issue..." class="w-full text-sm bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    </div>

                    <div class="grid grid-cols-2 md:flex gap-3 sm:gap-4 w-full md:w-auto">
                        <div class="w-full md:w-40">
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1">Status</label>
                            <select name="status" class="w-full text-sm bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="">All</option>
                                <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </div>

                        <div class="w-full md:w-40">
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1">Priority</label>
                            <select name="priority" class="w-full text-sm bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="">All</option>
                                <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                                <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-3 sm:gap-4 w-full md:w-auto mt-1 md:mt-0">
                        <div class="w-full md:w-40">
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1">Sort ID</label>
                            <select name="sort" class="w-full text-sm bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="id_desc" {{ request('sort') == 'id_desc' ? 'selected' : '' }}>Newest</option>
                                <option value="id_asc" {{ request('sort') == 'id_asc' ? 'selected' : '' }}>Oldest</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 sm:px-6 py-2 rounded-md font-bold transition shadow-sm h-[42px] border border-transparent uppercase text-xs w-full sm:w-auto">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl sm:rounded-lg border border-gray-200 dark:border-gray-700 transition-colors">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full min-w-[900px] divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr class="text-[10px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <th class="px-4 sm:px-6 py-3 sm:py-4 text-left">ID</th>
                                <th class="px-4 sm:px-6 py-3 sm:py-4 text-left">Complainant</th>
                                <th class="px-4 sm:px-6 py-3 sm:py-4 text-left">Issue</th>
                                <th class="px-4 sm:px-6 py-3 sm:py-4 text-left">Category</th>
                                <th class="px-4 sm:px-6 py-3 sm:py-4 text-left">Priority</th>
                                <th class="px-4 sm:px-6 py-3 sm:py-4 text-left">Status</th>
                                <th class="px-4 sm:px-6 py-3 sm:py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                            @forelse($tickets as $ticket)
                                <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-500 dark:text-gray-400">#{{ $ticket->id }}</td>
                                    <td class="px-4 sm:px-6 py-4 text-xs sm:text-sm font-bold text-gray-900 dark:text-white">{{ $ticket->reporter_name }}</td>
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex flex-col max-w-xs sm:max-w-md">
                                            <a href="{{ route('tickets.show', $ticket) }}" class="text-indigo-600 dark:text-blue-400 font-bold hover:text-indigo-800 dark:hover:text-blue-300 hover:underline text-xs sm:text-sm transition truncate" title="{{ $ticket->title }}">{{ $ticket->title }}</a>
                                            <span class="text-[9px] sm:text-[10px] mt-1">
                                                @if($ticket->status === 'Resolved')
                                                    <span class="text-gray-500 dark:text-gray-400 font-bold uppercase">Resolved by {{ $ticket->resolver->name ?? 'Staff' }}</span>
                                                @elseif($ticket->assigned_to)
                                                    <span class="text-indigo-600 dark:text-indigo-400 font-bold uppercase">Assigned to {{ $ticket->assignee->name }}</span>
                                                @else
                                                    <span class="text-gray-500 dark:text-gray-500">Currently Unassigned</span>
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-[10px] sm:text-xs font-bold">
                                        <span class="px-2 py-1 rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-400 border border-purple-200 dark:border-purple-700/50 whitespace-nowrap">{{ $ticket->category }}</span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-[10px] sm:text-xs font-bold">
                                        <span class="px-2 py-1 rounded-full border whitespace-nowrap
                                            {{ $ticket->priority == 'High' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-400 border-red-200 dark:border-red-700/50' :
                                              ($ticket->priority == 'Medium' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-400 border-yellow-200 dark:border-yellow-700/50' :
                                              'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-400 border-green-200 dark:border-green-700/50') }}">
                                            {{ $ticket->priority }}
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-[10px] sm:text-xs font-bold">
                                        <span class="px-3 py-1 rounded-full border shadow-sm uppercase whitespace-nowrap
                                            {{ $ticket->status == 'Open' ? 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-700/50' :
                                              ($ticket->status == 'Assigned' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-700/50' :
                                              ($ticket->status == 'On Hold' ? 'bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-700/50' :
                                              'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600')) }}">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-right font-medium flex gap-2 sm:gap-3 justify-end items-center">
                                        @if(Auth::user()->role === 'admin' || Auth::id() === $ticket->user_id)
                                            <a href="{{ route('tickets.edit', $ticket) }}"
                                               class="bg-white dark:bg-blue-600 text-gray-700 dark:text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-md hover:bg-gray-50 dark:hover:bg-blue-500 font-bold transition shadow-sm text-[10px] sm:text-xs uppercase border border-gray-300 dark:border-transparent shrink-0">
                                                Edit
                                            </a>
                                            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Trash this ticket?');" class="inline shrink-0">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="bg-red-50 dark:bg-red-600 text-red-600 dark:text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-md hover:bg-red-100 dark:hover:bg-red-500 font-bold transition shadow-sm text-[10px] sm:text-xs uppercase flex items-center gap-1.5 sm:gap-2 border border-red-200 dark:border-transparent">
                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    <span class="hidden sm:inline">Trash</span>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 text-[9px] sm:text-[11px] pr-2 sm:pr-4 uppercase font-bold tracking-wider whitespace-nowrap">View Only</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-4 sm:px-6 py-12 sm:py-20 text-center text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest text-[10px] sm:text-xs">No tickets found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 rounded-b-lg transition-colors">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
