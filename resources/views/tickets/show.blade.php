<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">{{ __('Ticket Details #') . $ticket->id }}</h1>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-6 border-b border-gray-200 pb-4 flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $ticket->title }}</h1>
                        <p class="text-sm text-gray-500 mt-1">
                            Reported by <span class="font-medium text-gray-800">{{ $ticket->reporter_name }}</span>
                            on {{ $ticket->created_at->format('d M Y, h:i A') }}
                        </p>
                        <p class="text-[11px] text-gray-400 mt-0.5 italic">
                            Logged in system by: <span class="font-semibold">{{ $ticket->user->name ?? 'System' }}</span>
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

                <div class="bg-white p-6 rounded-lg mt-6 shadow-sm border border-gray-200">
                    <h3 class="text-gray-900 font-bold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Task Management
                    </h3>

                    <div class="mb-6 pb-4 border-b border-gray-100 space-y-2">
                        @if($ticket->status === 'Resolved')
                            <div class="flex flex-col gap-0.5"> <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                                    <p class="text-blue-600 font-bold">Task Resolved By:
                                        <span class="text-gray-900">{{ $ticket->resolver->name ?? 'System Staff' }}</span>
                                    </p>
                                </div>
                                <p class="text-[11px] text-gray-500 ml-4 italic font-medium">
                                    Resolved on: {{ $ticket->updated_at->format('d M Y, h:i A') }}
                                </p>
                            </div>
                        @elseif($ticket->assigned_to)
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-green-500 {{ $ticket->status !== 'On Hold' ? 'animate-pulse' : '' }}"></div>
                                <p class="{{ $ticket->status === 'On Hold' ? 'text-yellow-600' : 'text-indigo-600' }} font-bold">
                                    {{ $ticket->status === 'On Hold' ? 'Task Currently Paused:' : 'Currently Assigned To:' }}
                                    <span class="text-gray-900">{{ $ticket->assignee->name }}</span>
                                </p>
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                                <p class="text-gray-500 font-bold italic">Status: Unassigned / Waiting for Staff</p>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <div class="flex flex-wrap gap-3 items-center">
                            @if($ticket->status === 'Resolved')
                                @if(strtolower(Auth::user()->role) === 'admin' || Auth::id() === $ticket->assigned_to)
                                    <form action="{{ route('tickets.undo-resolve', $ticket) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md">
                                            Undo Resolution
                                        </button>
                                    </form>
                                @endif
                            @else
                                @if($ticket->assigned_to && (Auth::id() === $ticket->assigned_to || strtolower(Auth::user()->role) === 'admin'))
                                    <form action="{{ route('tickets.resolve', $ticket) }}" method="POST" onsubmit="return confirm('Mark as Resolved?')">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md">
                                            Mark as Resolved
                                        </button>
                                    </form>

                                    @if($ticket->status !== 'On Hold')
                                        <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="On Hold">
                                            <input type="hidden" name="reporter_name" value="{{ $ticket->reporter_name }}"><input type="hidden" name="title" value="{{ $ticket->title }}"><input type="hidden" name="description" value="{{ $ticket->description }}"><input type="hidden" name="priority" value="{{ $ticket->priority }}"><input type="hidden" name="category" value="{{ $ticket->category }}">
                                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md">
                                                Put On Hold
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('tickets.unassign', $ticket->id) }}" method="POST" onsubmit="return confirm('Drop this task?')">
                                        @csrf
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md">
                                            Drop Task
                                        </button>
                                    </form>
                                @elseif(!$ticket->assigned_to)
                                    <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md">
                                            Claim This Task
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>

                        @if($ticket->status !== 'Resolved')
                            @php
                                $isAdmin = strtolower(Auth::user()->role) === 'admin';
                                $isAssignee = Auth::id() === $ticket->assigned_to;
                                $isOnHold = $ticket->status === 'On Hold';
                            @endphp

                            @if($isAdmin || ($isAssignee && $isOnHold))
                                <div class="p-5 bg-gray-50 rounded-xl border border-gray-200 shadow-inner mt-4">
                                    <form action="{{ route('tickets.transfer', $ticket->id) }}" method="POST">
                                        @csrf
                                        <label class="block text-[10px] text-indigo-600 font-black uppercase tracking-widest mb-3">
                                            {{ $isAdmin ? 'Administrative Reassign' : 'Transfer My Task' }}
                                        </label>
                                        <div class="flex flex-col md:flex-row gap-3 items-stretch">
                                            <select name="new_user_id" id="staff-search" class="w-full" required>
                                                <option value="" disabled selected>Search for staff...</option>
                                                @foreach($users as $user)
                                                    @if($user->id !== $ticket->assigned_to)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <button type="submit" class="h-11 px-8 bg-purple-600 text-white rounded-md font-bold shadow-md hover:bg-purple-700 transition">
                                                {{ $isAdmin ? 'Assign' : 'Move Task' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                    <div class="flex items-center gap-4 mt-8 pt-6 border-t border-gray-100">
                        @if(Auth::user()->role === 'admin' || Auth::id() === $ticket->user_id)
                            <a href="{{ route('tickets.edit', $ticket) }}"
                            class="bg-indigo-600 text-white px-6 py-2.5 rounded-md hover:bg-indigo-700 font-bold transition shadow-md text-sm uppercase tracking-wider">
                                Edit Ticket
                            </a>

                            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Move this ticket to the Recycle Bin?');">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 text-white px-6 py-2.5 rounded-md hover:bg-red-700 font-bold transition shadow-md text-sm uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Trash
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('tickets.index') }}" class="ml-auto text-gray-500 hover:text-gray-900 font-bold transition flex items-center gap-1">
                            &larr; Back to Dashboard
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#staff-search').select2({
                placeholder: "Search for a staff member...",
                allowClear: true,
                width: '100%',
                selectionCssClass: '!bg-white !border-gray-300 !h-11 !flex !items-center !text-gray-900 !px-4 !rounded-md',
                dropdownCssClass: '!bg-white !border-gray-300 !text-gray-900 !shadow-2xl'
            });
        });
    </script>
</x-app-layout>
