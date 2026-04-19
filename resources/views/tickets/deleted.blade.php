<x-app-layout>
    @section('title', 'Ticket Recycle Bin')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight transition-colors">Ticket Recycle Bin</h1>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-1 transition-colors">
                        Restore accidentally deleted complaints or permanently purge them.
                    </p>
                </div>

                <a href="{{ route('tickets.index') }}"
                   class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-5 py-2.5 rounded-md font-bold transition duration-200 shadow-sm text-sm uppercase flex items-center gap-2 active:scale-95 border border-gray-300 dark:border-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                    </svg>
                    Back to List
                </a>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="mb-6 flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/40 border-l-4 border-green-500 rounded-r-lg shadow-sm transition-colors duration-300">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-bold text-green-800 dark:text-green-400 uppercase tracking-wide transition-colors">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-500 hover:text-green-700 dark:hover:text-green-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('recent_purges'))
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-8 border border-gray-200 dark:border-gray-700 transition-colors">
                    <h3 class="text-red-600 dark:text-red-400 font-bold text-xs uppercase tracking-wider mb-4 flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Recently Permanent Deleted (Last 3)
                    </h3>
                    <div class="space-y-3">
                        @foreach(session('recent_purges') as $purge)
                            <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg border border-gray-200 dark:border-gray-600 transition hover:bg-gray-100 dark:hover:bg-gray-700">
                                <span class="text-sm text-gray-700 dark:text-gray-300 font-medium transition-colors">#{{ $purge['id'] }} - <span class="font-bold text-gray-900 dark:text-white">{{ $purge['title'] }}</span></span>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase italic transition-colors">Purged on {{ $purge['date'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm mb-8 border border-gray-200 dark:border-gray-700 transition-colors">
                <form action="{{ route('tickets.trash') }}" method="GET" class="flex flex-wrap md:flex-nowrap gap-4">
                    <div class="flex-grow">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1 transition-colors">Search Trashed Tickets</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID, Name or Issue..." class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors">
                    </div>
                    <div class="w-full md:w-64">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1 transition-colors">Sort by Date Deleted</label>
                        <select name="sort" onchange="this.form.submit()" class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm text-sm font-medium transition-colors">
                            <option value="deleted_desc" {{ request('sort') == 'deleted_desc' ? 'selected' : '' }}>Recent Deleted (Newest)</option>
                            <option value="deleted_asc" {{ request('sort') == 'deleted_asc' ? 'selected' : '' }}>Oldest Deleted</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2 rounded-md font-bold transition h-[42px] text-sm uppercase shadow-sm active:scale-95 border border-transparent">
                            Filter Bin
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 transition-colors">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 transition-colors">
                            <tr class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors">
                                <th class="px-6 py-4 text-left">ID</th>
                                <th class="px-6 py-4 text-left">Issue Details</th>
                                <th class="px-6 py-4 text-left">Deleted Date</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700 transition-colors">
                            @forelse($deletedTickets as $ticket)
                                <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 transition-colors">#{{ $ticket->id }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 dark:text-white text-sm transition-colors">{{ $ticket->title }}</div>
                                        <div class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase mt-1 transition-colors">Reporter: {{ $ticket->reporter_name }} | Category: {{ $ticket->category }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 transition-colors">{{ $ticket->deleted_at->format('d M Y, h:i A') }}</td>
                                    <td class="px-6 py-4 text-right flex gap-3 justify-end items-center">
                                        <form action="{{ route('tickets.restore', $ticket->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-md font-bold text-xs uppercase shadow-sm transition active:scale-95 border border-transparent">
                                                Restore
                                            </button>
                                        </form>

                                        @if(Auth::user()->role === 'admin')
                                            <form action="{{ route('tickets.force-delete', $ticket->id) }}" method="POST" onsubmit="return confirm('PERMANENTLY DELETE THIS TICKET? This cannot be undone.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-md font-bold text-xs uppercase shadow-sm transition active:scale-95 border border-transparent">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-20 text-center text-gray-400 dark:text-gray-500 font-bold italic transition-colors">No tickets in the recycle bin.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($deletedTickets->hasPages())
                    <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 transition-colors">
                        {{ $deletedTickets->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
