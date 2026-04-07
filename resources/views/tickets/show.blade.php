@use('Illuminate\Support\Str')
<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white uppercase tracking-tight">{{ __('Ticket Details #') . $ticket->id }}</h1>
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 sm:p-8 border border-gray-700">
                <div class="mb-6 border-b border-gray-700 pb-4 flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-white leading-tight">{{ $ticket->title }}</h1>
                        <p class="text-sm text-gray-400 mt-1">
                            Reported by <span class="font-medium text-gray-300">{{ $ticket->reporter_name }}</span>
                            on {{ $ticket->created_at->format('d M Y, h:i A') }}
                        </p>
                        <p class="text-[11px] text-gray-500 mt-0.5 italic">
                            Logged in system by: <span class="font-semibold text-gray-400">{{ $ticket->user->name ?? 'System' }}</span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold border
                            {{ $ticket->priority === 'High' ? 'bg-red-900/30 text-red-400 border-red-800/50' :
                              ($ticket->priority === 'Medium' ? 'bg-yellow-900/30 text-yellow-400 border-yellow-800/50' : 'bg-green-900/30 text-green-400 border-green-800/50') }}">
                            {{ $ticket->priority }} Priority
                        </span>
                        <span class="px-4 py-1 rounded-full text-sm font-bold border shadow-sm
                            {{ $ticket->status === 'Open' ? 'bg-green-900/30 text-green-400 border-green-800/50' :
                            ($ticket->status === 'Assigned' ? 'bg-blue-900/30 text-blue-400 border-blue-800/50' :
                            ($ticket->status === 'On Hold' ? 'bg-yellow-900/30 text-yellow-400 border-yellow-800/50' : 'bg-gray-700 text-gray-300 border-gray-600')) }}">
                            {{ $ticket->status }}
                        </span>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-sm font-bold text-gray-400 mb-2 uppercase tracking-widest">Issue Description</h3>
                    <div class="bg-gray-700 p-4 rounded-lg border border-gray-600 text-gray-200 whitespace-pre-wrap leading-relaxed shadow-inner">
                        {{ $ticket->description }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-400 mb-6">
                    <div><span class="font-bold text-gray-500 uppercase text-[10px] tracking-wider">Category:</span> <span class="text-gray-300">{{ $ticket->category }}</span></div>
                    <div><span class="font-bold text-gray-500 uppercase text-[10px] tracking-wider">Last Updated:</span> <span class="text-gray-300">{{ $ticket->updated_at->diffForHumans() }}</span></div>
                </div>

                <div class="bg-gray-900 p-6 rounded-lg mt-6 shadow-sm border border-gray-700">
                    <h3 class="text-white font-bold mb-4 flex items-center gap-2 uppercase tracking-widest text-sm">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Task Management
                    </h3>

                    <div class="mb-6 pb-4 border-b border-gray-800 space-y-2">
                        @if($ticket->status === 'Resolved')
                            <div class="flex flex-col gap-0.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                    <p class="text-blue-500 font-bold uppercase text-xs">Task Resolved By: <span class="text-white">{{ $ticket->resolver->name ?? 'System Staff' }}</span></p>
                                </div>
                                <p class="text-[10px] text-gray-500 ml-4 italic font-medium">Resolved on: {{ $ticket->updated_at->format('d M Y, h:i A') }}</p>
                            </div>
                        @elseif($ticket->assigned_to)
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-green-500 {{ $ticket->status !== 'On Hold' ? 'animate-pulse' : '' }}"></div>
                                <p class="{{ $ticket->status === 'On Hold' ? 'text-yellow-500' : 'text-indigo-400' }} font-bold uppercase text-xs">
                                    {{ $ticket->status === 'On Hold' ? 'Task Currently Paused:' : 'Currently Assigned To:' }}
                                    <span class="text-white">{{ $ticket->assignee->name }}</span>
                                </p>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-gray-500 font-bold italic uppercase text-xs">
                                <div class="w-2 h-2 rounded-full bg-yellow-600"></div>
                                <p>Status: Unassigned / Waiting for Staff</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-3 items-center mb-6">
                        @if($ticket->status === 'Resolved')
                            @if(strtolower(Auth::user()->role) === 'admin' || Auth::id() === $ticket->assigned_to)
                                <form action="{{ route('tickets.undo-resolve', $ticket) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-500 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md text-xs uppercase border border-yellow-500">Undo Resolution</button>
                                </form>
                            @endif
                        @else
                            @if($ticket->assigned_to && (Auth::id() === $ticket->assigned_to || strtolower(Auth::user()->role) === 'admin'))
                                <form action="{{ route('tickets.resolve', $ticket) }}" method="POST" onsubmit="return confirm('Mark as Resolved?')">
                                    @csrf
                                    <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md text-xs uppercase border border-green-500">Mark as Resolved</button>
                                </form>
                                @if($ticket->status !== 'On Hold')
                                    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="On Hold">
                                        <input type="hidden" name="reporter_name" value="{{ $ticket->reporter_name }}"><input type="hidden" name="title" value="{{ $ticket->title }}"><input type="hidden" name="description" value="{{ $ticket->description }}"><input type="hidden" name="priority" value="{{ $ticket->priority }}"><input type="hidden" name="category" value="{{ $ticket->category }}">
                                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-500 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md text-xs uppercase border border-yellow-500">Put On Hold</button>
                                    </form>
                                @endif
                                <form action="{{ route('tickets.unassign', $ticket->id) }}" method="POST" onsubmit="return confirm('Drop this task?')">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md text-xs uppercase border border-red-500">Drop Task</button>
                                </form>
                            @elseif(!$ticket->assigned_to)
                                <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-md font-bold transition h-11 shadow-md text-xs uppercase border border-indigo-500">Claim This Task</button>
                                </form>
                            @endif
                        @endif
                    </div>

                    @if($ticket->status !== 'Resolved')
                        @php $isAdmin = strtolower(Auth::user()->role) === 'admin'; $isAssignee = Auth::id() === $ticket->assigned_to; $isOnHold = $ticket->status === 'On Hold'; @endphp
                        @if($isAdmin || ($isAssignee && $isOnHold))
                            <div class="p-5 bg-gray-800 rounded-xl border border-gray-700 shadow-inner mt-4">
                                <form action="{{ route('tickets.transfer', $ticket->id) }}" method="POST">
                                    @csrf
                                    <label class="block text-[10px] text-indigo-400 font-black uppercase tracking-widest mb-3">{{ $isAdmin ? 'Administrative Reassign' : 'Transfer My Task' }}</label>
                                    <div class="flex flex-col md:flex-row gap-3 items-stretch">
                                        <select name="new_user_id" id="staff-search" class="w-full" required>
                                            <option value="" disabled selected>Search for staff...</option>
                                            @foreach($users as $user)
                                                @if($user->id !== $ticket->assigned_to) <option value="{{ $user->id }}">{{ $user->name }}</option> @endif
                                            @endforeach
                                        </select>
                                        <button type="submit" class="h-11 px-8 bg-purple-600 text-white rounded-md font-bold shadow-md hover:bg-purple-500 transition uppercase text-xs border border-purple-500">Assign</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>

                <div x-data="{ replyName: '', parentId: '', replyingTo: '' }"
                     @set-reply.window="parentId = $event.detail.id; replyingTo = $event.detail.name; replyName = '@' + $event.detail.name + ' '; $refs.commentBox.focus()"
                     class="mt-8 bg-gray-900 rounded-xl shadow-sm border border-gray-700 overflow-hidden">

                    <div class="p-6 border-b border-gray-800">
                        <h3 class="text-sm font-black text-white uppercase tracking-widest">Internal Discussion</h3>
                    </div>

                    <div class="p-6 space-y-10 max-h-[700px] overflow-y-auto bg-[#111827] custom-scrollbar">
                        @forelse($ticket->comments as $comment)
                            @php $isMyComment = $comment->user_id == Auth::id(); @endphp

                            <div class="relative group">
                                <div class="flex gap-4">
                                    <div class="flex-shrink-0 z-10">
                                        @if($comment->user->avatar)
                                            <img src="{{ asset('storage/' . $comment->user->avatar) }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-700">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold uppercase">{{ substr($comment->user->name, 0, 2) }}</div>
                                        @endif
                                    </div>

                                    <div class="flex-1 relative">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-xs font-bold text-gray-200">{{ $comment->user->name }}</span>
                                            <div class="flex items-center gap-3">
                                                <span class="text-[10px] text-gray-500 italic">{{ $comment->created_at->diffForHumans() }}</span>
                                                <button @click="$dispatch('set-reply', {id: {{ $comment->id }}, name: '{{ $comment->user->name }}'})" class="text-[10px] font-black text-indigo-400 uppercase hover:underline">Reply</button>
                                            </div>
                                        </div>

                                        @if(Str::contains($comment->comment, Auth::user()->name) && !$isMyComment)
                                            <span class="absolute -top-1 -right-1 flex h-2 w-2 z-10">
                                                <span class="animate-ping absolute h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                <span class="relative rounded-full h-2 w-2 bg-red-500"></span>
                                            </span>
                                        @endif

                                        <div class="p-3 text-sm rounded-lg border shadow-sm leading-relaxed {{ $isMyComment ? 'bg-indigo-900/40 border-indigo-500/30 text-gray-300' : 'bg-gray-800 border-gray-700 text-gray-300' }}">
                                            {{ $comment->comment }}
                                        </div>
                                    </div>
                                </div>

                                @if($comment->replies->count() > 0)
                                    <div class="ml-5 mt-4 space-y-6 border-l-2 border-gray-700 pl-9 pb-2">
                                        @foreach($comment->replies as $reply)
                                            @php $isMyReply = $reply->user_id == Auth::id(); @endphp
                                            <div class="flex gap-4 relative">
                                                <div class="absolute -left-9 top-5 w-6 h-0.5 bg-gray-700"></div>

                                                <div class="flex-shrink-0 z-10">
                                                    @if($reply->user->avatar)
                                                        <img src="{{ asset('storage/' . $reply->user->avatar) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-800 shadow-lg">
                                                    @else
                                                        <div class="w-9 h-9 rounded-full bg-gray-700 flex items-center justify-center text-[10px] text-white font-black uppercase">{{ substr($reply->user->name, 0, 2) }}</div>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="text-[11px] font-black text-gray-400">{{ $reply->user->name }}</span>
                                                        <div class="flex items-center gap-3">
                                                            <span class="text-[9px] text-gray-500 font-bold uppercase">{{ $reply->created_at->diffForHumans() }}</span>
                                                            <button @click="$dispatch('set-reply', {id: {{ $comment->id }}, name: '{{ $reply->user->name }}'})"
                                                                    class="text-[9px] font-black text-indigo-400 hover:text-indigo-300 uppercase">
                                                                Reply
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="p-3 text-xs rounded-xl border shadow-md {{ $isMyReply ? 'bg-indigo-900/30 border-indigo-500/30 text-gray-300' : 'bg-gray-800/60 border-gray-700 text-gray-400' }}">
                                                        {{ $reply->comment }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="py-12 text-center text-gray-500 italic text-xs">No discussion yet.</div>
                        @endforelse
                    </div>

                    <div class="p-6 bg-gray-800 border-t border-gray-700">
                        <form action="{{ route('tickets.comments.store', $ticket->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="parent_id" x-model="parentId">

                            <textarea x-ref="commentBox" x-model="replyName" name="comment" rows="3" required
                                      class="w-full rounded-lg border-gray-600 bg-gray-700 text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400"
                                      placeholder="Write an update..."></textarea>

                            <div class="mt-4 flex justify-between items-center">
                                <div class="flex items-center gap-4">
                                    <div x-show="parentId" class="flex items-center gap-2 bg-indigo-500/10 border border-indigo-500/30 px-3 py-1.5 rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                                        <p class="text-[10px] text-indigo-400 font-black uppercase tracking-widest">
                                            Replying to <span x-text="replyingTo"></span>
                                        </p>
                                    </div>

                                    <button x-show="parentId" @click="parentId = ''; replyName = ''; replyingTo = ''" type="button"
                                            class="bg-red-900/30 text-red-400 hover:bg-red-600 hover:text-white px-6 py-2.5 rounded-lg text-xs font-black uppercase tracking-widest transition border border-red-500/30">
                                        Cancel Reply
                                    </button>
                                </div>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-10 py-3 rounded-lg text-xs font-black uppercase tracking-widest transition active:scale-95 shadow-lg border border-indigo-500">
                                    Post Comment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-center gap-4 mt-8 pt-6 border-t border-gray-700">
                    @if(Auth::user()->role === 'admin' || Auth::id() === $ticket->user_id)
                        <div class="flex gap-3">
                            <a href="{{ route('tickets.edit', $ticket) }}" class="bg-indigo-600 text-white px-6 py-2.5 rounded-md hover:bg-indigo-500 font-bold transition shadow-md text-xs uppercase tracking-widest border border-indigo-500">Edit Ticket</a>
                            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Move this ticket to trash?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-6 py-2.5 rounded-md hover:bg-red-500 font-bold transition shadow-md text-xs uppercase tracking-widest flex items-center gap-2 border border-red-500">Trash</button>
                            </form>
                        </div>
                    @endif
                    <a href="{{ route('dashboard') }}" class="ml-auto text-gray-400 hover:text-white font-bold transition flex items-center gap-1 uppercase text-xs tracking-widest">&larr; Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#staff-search').select2({
                placeholder: "Search for a staff member...", allowClear: true, width: '100%',
                selectionCssClass: '!bg-gray-700 !border-gray-600 !h-11 !flex !items-center !text-white !px-4 !rounded-lg !shadow-sm !font-bold',
                dropdownCssClass: '!bg-gray-700 !border-gray-600 !text-white !shadow-2xl'
            });
        });
    </script>
</x-app-layout>
