<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Ticket Details #') . $ticket->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-6 border-b border-gray-200 pb-4 flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $ticket->title }}</h1>
                        <p class="text-sm text-gray-500 mt-1">
                            Reported by <span class="font-medium text-gray-800">{{ $ticket->reporter_name }}</span>
                            on {{ $ticket->created_at->format('d M Y, h:i A') }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $ticket->priority === 'High' ? 'bg-red-100 text-red-800' :
                              ($ticket->priority === 'Medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                            {{ $ticket->priority }} Priority
                        </span>
                        <span class="px-4 py-1 rounded-full text-sm font-bold border shadow-sm
                            {{ $ticket->status === 'Open' ? 'bg-green-50 text-green-700 border-green-200' :
                            ($ticket->status === 'Assigned' ? 'bg-blue-50 text-blue-700 border-blue-200' :
                            ($ticket->status === 'On Hold' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-gray-50 text-gray-700 border-gray-200')) }}">
                            {{ $ticket->status }}
                        </span>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Issue Description</h3>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 text-gray-700 whitespace-pre-wrap leading-relaxed">
                        {{ $ticket->description }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 mb-6">
                    <div><span class="font-bold text-gray-800">Category:</span> {{ $ticket->category }}</div>
                    <div><span class="font-bold text-gray-800">Last Updated:</span> {{ $ticket->updated_at->diffForHumans() }}</div>
                </div>

                <div class="bg-gray-800 p-6 rounded-lg mt-6 shadow-inner border border-gray-700">
                    <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Task Management
                    </h3>

                    <div class="mb-6 pb-4 border-b border-gray-700">
                        @if($ticket->assigned_to)
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                <p class="text-blue-400 font-bold">Currently Assigned To: <span class="text-white">{{ $ticket->assignee->name }}</span></p>
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                                <p class="text-gray-400 font-bold italic">Status: Unassigned / Waiting for Staff</p>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <div class="flex flex-wrap gap-3 items-center">
                            @if(!$ticket->assigned_to)
                                <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-lg">
                                        Claim This Task
                                    </button>
                                </form>
                            @elseif(strtolower(Auth::user()->role) === 'admin' && Auth::id() !== $ticket->assigned_to)
                                <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md">
                                        Take Over Task
                                    </button>
                                </form>
                            @endif

                            @if($ticket->assigned_to && (Auth::id() === $ticket->assigned_to || strtolower(Auth::user()->role) === 'admin') && $ticket->status !== 'Resolved')
                                <form action="{{ route('tickets.resolve', $ticket->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md">
                                        Mark as Resolved
                                    </button>
                                </form>

                                @if($ticket->status !== 'On Hold')
                                    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="On Hold">
                                        <input type="hidden" name="reporter_name" value="{{ $ticket->reporter_name }}">
                                        <input type="hidden" name="title" value="{{ $ticket->title }}">
                                        <input type="hidden" name="description" value="{{ $ticket->description }}">
                                        <input type="hidden" name="priority" value="{{ $ticket->priority }}">
                                        <input type="hidden" name="category" value="{{ $ticket->category }}">
                                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md">
                                            Put On Hold
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('tickets.unassign', $ticket->id) }}" method="POST" onsubmit="return confirm('Drop this task?')">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md">
                                        Drop Task (Unassign)
                                    </button>
                                </form>
                            @endif

                            @if(strtolower(Auth::user()->role) === 'admin')
                                <a href="{{ route('tickets.index', ['filter' => 'assigned_by_me']) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md flex items-center justify-center">
                                    Tickets Assigned by Me
                                </a>
                            @endif
                        </div>

                        @if(strtolower(Auth::user()->role) === 'admin' && $ticket->status !== 'Resolved')
                            <div class="p-5 bg-gray-900/50 rounded-xl border border-gray-700 shadow-inner">
                                <form action="{{ route('tickets.transfer', $ticket->id) }}" method="POST">
                                    @csrf
                                    <label class="block text-[10px] text-indigo-400 font-black uppercase tracking-widest mb-3">
                                        {{ $ticket->assigned_to ? 'Administrative Reassignment' : 'Direct Assignment to Staff' }}
                                    </label>
                                    <div class="flex flex-col md:flex-row gap-3 items-stretch">
                                        <div class="flex-grow">
                                            <select name="new_user_id" id="staff-search" class="w-full" required>
                                                <option value="" disabled selected>Search for staff member...</option>
                                                @foreach($users as $user)
                                                    @if($user->id !== $ticket->assigned_to)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="h-11 px-8 bg-purple-600 hover:bg-purple-700 text-white rounded-md font-bold transition-all shadow-lg flex items-center justify-center gap-2 flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                            {{ $ticket->assigned_to ? 'Move Task' : 'Assign Staff' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @elseif($ticket->status === 'On Hold' && Auth::id() === $ticket->assigned_to)
                            <div class="p-5 bg-gray-900/50 rounded-xl border border-gray-700 shadow-inner">
                                <form action="{{ route('tickets.transfer', $ticket->id) }}" method="POST">
                                    @csrf
                                    <label class="block text-[10px] text-indigo-400 font-black uppercase tracking-widest mb-3">Transfer to Staff Member</label>
                                    <div class="flex flex-col md:flex-row gap-3 items-stretch">
                                        <div class="flex-grow">
                                            <select name="new_user_id" id="staff-search-staff" class="w-full staff-select" required>
                                                <option value="" disabled selected>Search for staff...</option>
                                                @foreach($users as $user)
                                                    @if($user->id !== Auth::id())
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="h-11 px-8 bg-purple-600 hover:bg-purple-700 text-white rounded-md font-bold h-11 transition shadow-lg flex items-center justify-center gap-2">
                                            Move Task
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('tickets.edit', $ticket) }}" class="bg-indigo-600 text-white px-5 py-2 rounded-md hover:bg-indigo-700 font-bold transition shadow-sm">
                        Edit Ticket
                    </a>

                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Delete this ticket?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-50 text-red-600 px-5 py-2 rounded-md hover:bg-red-100 border border-red-200 font-bold transition shadow-sm">
                            Delete
                        </button>
                    </form>

                    <a href="{{ route('tickets.index') }}" class="ml-auto text-gray-500 hover:text-gray-900 font-bold transition flex items-center gap-1">
                        &larr; Back to Dashboard
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const staffSearch = $('#staff-search, .staff-select').select2({
                placeholder: "Search for a staff member...",
                allowClear: true,
                width: '100%',
                selectionCssClass: '!bg-gray-900 !border-gray-700 !h-11 !flex !items-center !text-gray-100 !px-4 !rounded-md',
                dropdownCssClass: '!bg-gray-800 !border-gray-700 !text-white !shadow-2xl'
            });

            staffSearch.on('select2:open', function() {
                setTimeout(() => {
                    const searchBox = document.querySelector('.select2-search__field');
                    if(searchBox) {
                        searchBox.style.backgroundColor = '#111827';
                        searchBox.style.color = '#ffffff';
                        searchBox.style.border = '1px solid #374151';
                    }
                }, 0);
            });
        });
    </script>
</x-app-layout>
