@use('Illuminate\Support\Str')
<x-app-layout>
    @section('title', 'Ticket Info')
    <div class="py-6 sm:py-12" x-data="{
        lightboxOpen: false,
        lightboxSrc: '',
        scale: 1,
        isDragging: false,
        startX: 0,
        startY: 0,
        translateX: 0,
        translateY: 0,
        resetZoom() {
            this.scale = 1;
            this.translateX = 0;
            this.translateY = 0;
        },
        startDrag(e) {
            // Only allow dragging if zoomed in
            if (this.scale <= 1) return;
            this.isDragging = true;
            // Support both mouse and touch events
            let clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
            let clientY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
            this.startX = clientX - this.translateX;
            this.startY = clientY - this.translateY;
        },
        doDrag(e) {
            if (!this.isDragging) return;
            let clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
            let clientY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
            this.translateX = clientX - this.startX;
            this.translateY = clientY - this.startY;
        },
        stopDrag() {
            this.isDragging = false;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-4 sm:mb-6">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-tight transition-colors">{{ __('Ticket Details #') . $ticket->id }}</h1>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl sm:rounded-lg p-5 sm:p-8 border border-gray-200 dark:border-gray-700 transition-colors">

                <div class="mb-5 sm:mb-6 border-b border-gray-200 dark:border-gray-700 pb-4 flex flex-col md:flex-row justify-between items-start gap-4">
                    <div class="w-full md:w-auto">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white leading-tight transition-colors">{{ $ticket->title }}</h1>
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1 transition-colors">
                            Reported by <span class="font-medium text-gray-800 dark:text-gray-300">{{ $ticket->reporter_name }}</span>
                            on {{ $ticket->created_at->format('d M Y, h:i A') }}
                        </p>
                        <p class="text-[10px] sm:text-[11px] text-gray-500 dark:text-gray-500 mt-0.5 italic transition-colors">
                            Logged in system by: <span class="font-semibold text-gray-700 dark:text-gray-400">{{ $ticket->user->name ?? 'System' }}</span>
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2 w-full md:w-auto">
                        <span class="px-3 py-1 rounded-full text-xs sm:text-sm font-semibold border transition-colors
                            {{ $ticket->priority === 'High' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800/50' :
                              ($ticket->priority === 'Medium' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800/50' :
                              'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-800/50') }}">
                            {{ $ticket->priority }} Priority
                        </span>
                        <span class="px-3 sm:px-4 py-1 rounded-full text-xs sm:text-sm font-bold border shadow-sm transition-colors
                            {{ $ticket->status === 'Open' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-700/50' :
                            ($ticket->status === 'Assigned' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-700/50' :
                            ($ticket->status === 'On Hold' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-700/50' :
                            'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600')) }}">
                            {{ $ticket->status }}
                        </span>
                    </div>
                </div>

                <div class="mb-5 sm:mb-6">
                    <h3 class="text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest transition-colors">Issue Description</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 text-sm sm:text-base text-gray-800 dark:text-gray-200 whitespace-pre-wrap leading-relaxed shadow-inner transition-colors">
                        {{ $ticket->description }}
                    </div>
                </div>

                @if($ticket->media_path)
                    <div class="mt-4 mb-6 sm:mb-8">
                        <h3 class="text-[10px] sm:text-xs font-bold text-gray-500 uppercase mb-2">Attached Media</h3>

                        @if(Str::endsWith($ticket->media_path, ['.mp4', '.mov', '.avi']))
                            <div class="max-w-2xl">
                                <video controls class="w-full rounded-lg border dark:border-gray-700 shadow-sm bg-black">
                                    <source src="{{ asset('storage/' . $ticket->media_path) }}">
                                </video>
                            </div>
                        @else
                            <div class="relative group max-w-sm">
                                <img src="{{ asset('storage/' . $ticket->media_path) }}"
                                     @click="lightboxOpen = true; lightboxSrc = '{{ asset('storage/' . $ticket->media_path) }}'; resetZoom();"
                                     class="w-full h-48 sm:h-64 object-cover rounded-lg shadow-md border dark:border-gray-700 cursor-zoom-in hover:scale-[1.02] hover:opacity-95 transition duration-300">

                                <div class="absolute bottom-3 right-3 bg-black/60 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4 text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-6 transition-colors">
                    <div><span class="font-bold text-gray-500 dark:text-gray-500 uppercase text-[9px] sm:text-[10px] tracking-wider">Category:</span> <span class="text-gray-800 dark:text-gray-300">{{ $ticket->category }}</span></div>
                    <div><span class="font-bold text-gray-500 dark:text-gray-500 uppercase text-[9px] sm:text-[10px] tracking-wider">Last Updated:</span> <span class="text-gray-800 dark:text-gray-300">{{ $ticket->updated_at->diffForHumans() }}</span></div>
                </div>

                <div class="bg-gray-100 dark:bg-gray-900 p-4 sm:p-6 rounded-xl sm:rounded-lg mt-6 shadow-sm border border-gray-200 dark:border-gray-700 transition-colors">
                    <h3 class="text-gray-900 dark:text-white font-bold mb-4 flex items-center gap-2 uppercase tracking-widest text-xs sm:text-sm transition-colors">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600 dark:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 022 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Task Management
                    </h3>

                    <div class="mb-5 sm:mb-6 pb-4 border-b border-gray-200 dark:border-gray-800 space-y-2 transition-colors">
                        @if($ticket->status === 'Resolved')
                            <div class="flex flex-col gap-0.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-blue-600 dark:bg-blue-500"></div>
                                    <p class="text-blue-600 dark:text-blue-500 font-bold uppercase text-[10px] sm:text-xs">Task Resolved By: <span class="text-gray-900 dark:text-white">{{ $ticket->resolver->name ?? 'System Staff' }}</span></p>
                                </div>
                                <p class="text-[9px] sm:text-[10px] text-gray-500 dark:text-gray-500 ml-4 italic font-medium">Resolved on: {{ $ticket->updated_at->format('d M Y, h:i A') }}</p>
                            </div>
                        @elseif($ticket->assigned_to)
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-green-500 {{ $ticket->status !== 'On Hold' ? 'animate-pulse' : '' }}"></div>
                                <p class="{{ $ticket->status === 'On Hold' ? 'text-yellow-600 dark:text-yellow-500' : 'text-indigo-600 dark:text-indigo-400' }} font-bold uppercase text-[10px] sm:text-xs transition-colors">
                                    {{ $ticket->status === 'On Hold' ? 'Task Currently Paused:' : 'Currently Assigned To:' }}
                                    <span class="text-gray-900 dark:text-white">{{ $ticket->assignee->name }}</span>
                                </p>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-gray-500 font-bold italic uppercase text-[10px] sm:text-xs transition-colors">
                                <div class="w-2 h-2 rounded-full bg-yellow-600"></div>
                                <p>Status: Unassigned / Waiting for Staff</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 items-stretch sm:items-center mb-5 sm:mb-6">
                        @if($ticket->status === 'Resolved')
                            @if(strtolower(Auth::user()->role) === 'admin' || Auth::id() === $ticket->assigned_to)
                                <form action="{{ route('tickets.undo-resolve', $ticket) }}" method="POST" class="w-full sm:w-auto">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 sm:py-2.5 rounded-md font-bold transition h-auto sm:h-11 shadow-md text-xs uppercase border border-transparent">Undo Resolution</button>
                                </form>
                            @endif
                        @elseif($ticket->status === 'On Hold')
                            <form action="{{ route('tickets.resume', $ticket) }}" method="POST" class="w-full sm:w-auto">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 sm:py-2.5 rounded-md font-bold transition h-auto sm:h-11 shadow-md text-xs uppercase border border-transparent">Resume Task</button>
                            </form>
                            <form action="{{ route('tickets.unassign', $ticket->id) }}" method="POST" onsubmit="return confirm('Drop this task?')" class="w-full sm:w-auto">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-6 py-3 sm:py-2.5 rounded-md font-bold transition h-auto sm:h-11 shadow-md text-xs uppercase border border-transparent">Drop Task</button>
                            </form>
                        @else
                            @if($ticket->assigned_to && (Auth::id() === $ticket->assigned_to || strtolower(Auth::user()->role) === 'admin'))
                                <form action="{{ route('tickets.resolve', $ticket) }}" method="POST" onsubmit="return confirm('Mark as Resolved?')" class="w-full sm:w-auto">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-3 sm:py-2.5 rounded-md font-bold transition h-auto sm:h-11 shadow-md text-xs uppercase border border-transparent">Mark as Resolved</button>
                                </form>
                                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" class="w-full sm:w-auto">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="On Hold">
                                    <input type="hidden" name="reporter_name" value="{{ $ticket->reporter_name }}"><input type="hidden" name="title" value="{{ $ticket->title }}"><input type="hidden" name="description" value="{{ $ticket->description }}"><input type="hidden" name="priority" value="{{ $ticket->priority }}"><input type="hidden" name="category" value="{{ $ticket->category }}">
                                    <button type="submit" class="w-full sm:w-auto bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 sm:py-2.5 rounded-md font-bold transition h-auto sm:h-11 shadow-md text-xs uppercase border border-transparent">Put On Hold</button>
                                </form>
                                <form action="{{ route('tickets.unassign', $ticket->id) }}" method="POST" onsubmit="return confirm('Drop this task?')" class="w-full sm:w-auto">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-6 py-3 sm:py-2.5 rounded-md font-bold transition h-auto sm:h-11 shadow-md text-xs uppercase border border-transparent">Drop Task</button>
                                </form>
                            @elseif(!$ticket->assigned_to)
                                <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST" class="w-full sm:w-auto">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 sm:py-2.5 rounded-md font-bold transition h-auto sm:h-11 shadow-md text-xs uppercase border border-transparent">Claim This Task</button>
                                </form>
                            @endif
                        @endif
                    </div>

                    @if($ticket->status !== 'Resolved')
                        @php $isAdmin = strtolower(Auth::user()->role) === 'admin'; $isAssignee = Auth::id() === $ticket->assigned_to; $isOnHold = $ticket->status === 'On Hold'; @endphp
                        @if($isAdmin || ($isAssignee && $isOnHold))
                            <div class="p-4 sm:p-5 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-inner mt-4 transition-colors">
                                <form action="{{ route('tickets.transfer', $ticket->id) }}" method="POST">
                                    @csrf
                                    <label class="block text-[10px] text-indigo-600 dark:text-indigo-400 font-black uppercase tracking-widest mb-3 transition-colors">{{ $isAdmin ? 'Administrative Reassign' : 'Transfer My Task' }}</label>
                                    <div class="flex flex-col sm:flex-row gap-3 items-stretch">
                                        <select name="new_user_id" id="staff-search" class="w-full bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 rounded-md transition-colors" required>
                                            <option value="" disabled selected>Search for staff...</option>
                                            @foreach($users as $user)
                                                @if($user->id !== $ticket->assigned_to) <option value="{{ $user->id }}">{{ $user->name }}</option> @endif
                                            @endforeach
                                        </select>
                                        <button type="submit" class="h-11 px-8 bg-purple-600 text-white rounded-md font-bold shadow-md hover:bg-purple-700 transition uppercase text-xs border border-transparent w-full sm:w-auto">Assign</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>

                <div x-data="{ replyName: '', parentId: '', replyingTo: '' }"
                     @set-reply.window="parentId = $event.detail.id; replyingTo = $event.detail.name; replyName = '@' + $event.detail.name + ' '; $refs.commentBox.focus()"
                     class="mt-8 bg-gray-100 dark:bg-gray-900 rounded-2xl sm:rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors">

                    <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-800 transition-colors">
                        <h3 class="text-xs sm:text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest transition-colors">Internal Discussion</h3>
                    </div>

                    <div class="p-4 sm:p-6 space-y-8 sm:space-y-10 max-h-[700px] overflow-y-auto bg-white dark:bg-[#111827] custom-scrollbar transition-colors">
                        @forelse($ticket->comments as $comment)
                            @php $isMyComment = $comment->user_id == Auth::id(); @endphp

                            <div class="relative group">
                                <div class="flex gap-3 sm:gap-4">
                                    <div class="flex-shrink-0 z-10">
                                        @if($comment->user->avatar)
                                            <img src="{{ asset('storage/' . $comment->user->avatar) }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-700">
                                        @else
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white text-[10px] sm:text-xs font-bold uppercase">{{ substr($comment->user->name, 0, 2) }}</div>
                                        @endif
                                    </div>

                                    <div class="flex-1 relative min-w-0">
                                        <div class="flex flex-wrap justify-between items-center mb-1 gap-1">
                                            <span class="text-[11px] sm:text-xs font-bold text-gray-900 dark:text-gray-200 transition-colors truncate pr-2">{{ $comment->user->name }}</span>
                                            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                                                <span class="text-[9px] sm:text-[10px] text-gray-500 italic transition-colors">{{ $comment->created_at->diffForHumans() }}</span>
                                                <button @click="$dispatch('set-reply', {id: {{ $comment->id }}, name: '{{ $comment->user->name }}'})" class="text-[9px] sm:text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase hover:underline transition-colors">Reply</button>
                                            </div>
                                        </div>

                                        @if(Str::contains($comment->comment, Auth::user()->name) && !$isMyComment)
                                            <span class="absolute -top-1 -right-1 flex h-2 w-2 z-10">
                                                <span class="animate-ping absolute h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                <span class="relative rounded-full h-2 w-2 bg-red-500"></span>
                                            </span>
                                        @endif

                                        <div class="p-3 sm:p-4 text-xs sm:text-sm rounded-lg border shadow-sm leading-relaxed transition-colors {{ $isMyComment ? 'bg-indigo-50 dark:bg-indigo-900/40 border-indigo-200 dark:border-indigo-500/30 text-gray-800 dark:text-gray-200' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-300' }}">

                                            @if($comment->media_path)
                                                <div class="mb-3 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-black max-w-md shadow-sm">
                                                    @if(Str::endsWith($comment->media_path, ['.mp4', '.mov', '.avi']))
                                                        <video controls class="w-full max-h-80 object-contain">
                                                            <source src="{{ asset('storage/' . $comment->media_path) }}">
                                                        </video>
                                                    @else
                                                        <img src="{{ asset('storage/' . $comment->media_path) }}"
                                                             @click="lightboxOpen = true; lightboxSrc = '{{ asset('storage/' . $comment->media_path) }}'; resetZoom();"
                                                             class="w-full max-h-80 object-cover cursor-zoom-in hover:opacity-90 transition">
                                                    @endif
                                                </div>
                                            @endif

                                            <p class="break-words">{{ $comment->comment }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($comment->replies->count() > 0)
                                    <div class="ml-3 sm:ml-5 mt-4 space-y-4 sm:space-y-6 border-l-2 border-gray-200 dark:border-gray-700 pl-4 sm:pl-9 pb-2 transition-colors">
                                        @foreach($comment->replies as $reply)
                                            @php $isMyReply = $reply->user_id == Auth::id(); @endphp
                                            <div class="flex gap-3 sm:gap-4 relative">
                                                <div class="absolute -left-4 sm:-left-9 top-4 sm:top-5 w-3 sm:w-6 h-0.5 bg-gray-200 dark:bg-gray-700 transition-colors"></div>

                                                <div class="flex-shrink-0 z-10">
                                                    @if($reply->user->avatar)
                                                        <img src="{{ asset('storage/' . $reply->user->avatar) }}" class="w-7 h-7 sm:w-9 sm:h-9 rounded-full object-cover ring-2 ring-white dark:ring-gray-800 shadow-lg transition-colors">
                                                    @else
                                                        <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-[9px] sm:text-[10px] text-gray-700 dark:text-white font-black uppercase transition-colors">{{ substr($reply->user->name, 0, 2) }}</div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex flex-wrap justify-between items-center mb-1 gap-1">
                                                        <span class="text-[10px] sm:text-[11px] font-black text-gray-700 dark:text-gray-400 transition-colors truncate pr-2">{{ $reply->user->name }}</span>
                                                        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                                                            <span class="text-[8px] sm:text-[9px] text-gray-500 font-bold uppercase transition-colors">{{ $reply->created_at->diffForHumans() }}</span>
                                                            <button @click="$dispatch('set-reply', {id: {{ $comment->id }}, name: '{{ $reply->user->name }}'})"
                                                                    class="text-[8px] sm:text-[9px] font-black text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 uppercase transition-colors">
                                                                Reply
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="p-2 sm:p-3 text-[11px] sm:text-xs rounded-xl border shadow-md transition-colors {{ $isMyReply ? 'bg-indigo-50 dark:bg-indigo-900/30 border-indigo-200 dark:border-indigo-500/30 text-gray-800 dark:text-gray-200' : 'bg-gray-100/60 dark:bg-gray-800/60 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300' }}">

                                                        @if($reply->media_path)
                                                            <div class="mb-2 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-black max-w-sm shadow-sm">
                                                                @if(Str::endsWith($reply->media_path, ['.mp4', '.mov', '.avi']))
                                                                    <video controls class="w-full max-h-60 object-contain">
                                                                        <source src="{{ asset('storage/' . $reply->media_path) }}">
                                                                    </video>
                                                                @else
                                                                    <img src="{{ asset('storage/' . $reply->media_path) }}"
                                                                         @click="lightboxOpen = true; lightboxSrc = '{{ asset('storage/' . $reply->media_path) }}'; resetZoom();"
                                                                         class="w-full max-h-60 object-cover cursor-zoom-in hover:opacity-90 transition">
                                                                @endif
                                                            </div>
                                                        @endif

                                                        <p class="break-words">{{ $reply->comment }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="py-12 text-center text-gray-500 italic text-[10px] sm:text-xs transition-colors">No discussion yet.</div>
                        @endforelse
                    </div>

                    <div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 transition-colors">
                        <form action="{{ route('tickets.comments.store', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="parent_id" x-model="parentId">

                            <textarea x-ref="commentBox" x-model="replyName" name="comment" rows="3" required
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 transition-colors"
                                      placeholder="Write an update..."></textarea>

                            <input type="file" id="comment-media-upload" name="media" accept=".jpg,.jpeg,.png,.mp4"
                                   class="mt-3 block w-full text-[10px] sm:text-xs text-gray-500 dark:text-gray-400
                                          file:mr-3 sm:file:mr-4 file:py-2 file:px-3 sm:file:px-4
                                          file:rounded-full file:border-0
                                          file:text-[10px] sm:file:text-xs file:font-bold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100
                                          dark:file:bg-indigo-900/30 dark:file:text-indigo-400 transition">

                            <p id="comment-media-error" class="text-red-500 text-[10px] sm:text-xs font-bold mt-2 uppercase tracking-wide hidden"></p>
                            <p class="text-gray-500 text-[10px] sm:text-xs mt-1 italic">Allowed formats: JPEG, PNG, MP4. Max size: 20MB</p>

                            <div class="mt-4 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4">
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                                    <div x-show="parentId" class="flex items-center justify-center gap-2 bg-indigo-100 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/30 px-3 py-2 sm:py-1.5 rounded-lg transition-colors">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 dark:bg-indigo-400 animate-pulse"></span>
                                        <p class="text-[9px] sm:text-[10px] text-indigo-700 dark:text-indigo-400 font-black uppercase tracking-widest transition-colors truncate">
                                            Replying to <span x-text="replyingTo"></span>
                                        </p>
                                    </div>

                                    <button x-show="parentId" @click="parentId = ''; replyName = ''; replyingTo = ''" type="button"
                                            class="w-full sm:w-auto bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 hover:bg-red-600 dark:hover:bg-red-600 hover:text-white px-6 py-3 sm:py-2.5 rounded-lg text-[10px] sm:text-xs font-black uppercase tracking-widest transition border border-red-200 dark:border-red-500/30">
                                        Cancel Reply
                                    </button>
                                </div>
                                <button type="submit" id="comment-submit-btn" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-8 sm:px-10 py-3 rounded-lg text-[10px] sm:text-xs font-black uppercase tracking-widest transition active:scale-95 shadow-lg border border-transparent">
                                    Post Comment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 transition-colors">
                    @if(Auth::user()->role === 'admin' || Auth::id() === $ticket->user_id)
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('tickets.edit', $ticket) }}" class="w-full sm:w-auto text-center bg-indigo-600 text-white px-6 py-3 sm:py-2.5 rounded-md hover:bg-indigo-700 font-bold transition shadow-md text-xs uppercase tracking-widest border border-transparent">Edit Ticket</a>
                            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Move this ticket to trash?');" class="w-full sm:w-auto">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full sm:w-auto justify-center bg-red-600 text-white px-6 py-3 sm:py-2.5 rounded-md hover:bg-red-700 font-bold transition shadow-md text-xs uppercase tracking-widest flex items-center gap-2 border border-transparent">Trash</button>
                            </form>
                        </div>
                    @endif
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto sm:ml-auto text-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white font-bold transition flex items-center justify-center gap-1 uppercase text-xs tracking-widest py-3 sm:py-0">&larr; Back to Dashboard</a>
                </div>
            </div>
        </div>

        <template x-teleport="body">
            <div x-show="lightboxOpen"
                 @keydown.escape.window="lightboxOpen = false"
                 class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-black/95 backdrop-blur-md"
                 style="display: none;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100">

                <div class="fixed top-0 left-0 right-0 p-4 md:p-8 flex justify-end items-center z-[110] pointer-events-none">
                    <button @click="lightboxOpen = false" class="pointer-events-auto text-white bg-white/10 hover:bg-red-600 transition-all duration-300 p-3 rounded-full border border-white/30 shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 active:scale-95 group">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-white shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="fixed bottom-6 md:bottom-12 left-1/2 -translate-x-1/2 z-[110] flex items-center gap-3 md:gap-5 bg-gray-900/90 border border-white/20 p-3 md:p-4 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.8)] backdrop-blur-xl">
                    <button @click="scale = Math.max(scale - 0.25, 0.5)" class="p-3 md:p-4 bg-white/5 text-white hover:bg-white/20 hover:text-indigo-400 rounded-xl md:rounded-2xl transition cursor-pointer border border-transparent hover:border-white/10" title="Zoom Out">
                        <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                    </button>

                    <div class="flex flex-col items-center px-4 md:px-8 w-24 md:w-32">
                        <span class="text-white text-xs md:text-sm font-black uppercase tracking-widest drop-shadow-md" x-text="(scale * 100).toFixed(0) + '%'"></span>
                        <button @click="resetZoom()" class="text-[10px] md:text-xs font-black text-indigo-400 hover:text-white uppercase tracking-widest transition mt-1 py-1 px-3 bg-white/5 rounded-full hover:bg-white/10">Reset</button>
                    </div>

                    <button @click="scale = Math.min(scale + 0.25, 4)" class="p-3 md:p-4 bg-white/5 text-white hover:bg-white/20 hover:text-indigo-400 rounded-xl md:rounded-2xl transition cursor-pointer border border-transparent hover:border-white/10" title="Zoom In">
                        <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                </div>

                <div class="w-full h-full overflow-hidden flex items-center justify-center p-0" @click.self="lightboxOpen = false">
                    <img :src="lightboxSrc"
                         @mousedown.prevent="startDrag($event)"
                         @mousemove.window="doDrag($event)"
                         @mouseup.window="stopDrag()"
                         @touchstart.prevent="startDrag($event)"
                         @touchmove.window="doDrag($event)"
                         @touchend.window="stopDrag()"
                         class="shadow-[0_0_50px_rgba(0,0,0,0.9)] rounded-lg select-none bg-white/5"
                         :class="isDragging ? 'cursor-grabbing' : 'transition-transform duration-200 ease-out cursor-grab'"
                         :style="'transform: translate(' + translateX + 'px, ' + translateY + 'px) scale(' + scale + '); min-width: 50vw; max-height: 85vh; object-fit: contain;'">
                </div>
            </div>
        </template>
        </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#staff-search').select2({
                placeholder: "Search for a staff member...",
                allowClear: true,
                width: '100%',
                selectionCssClass: '!bg-gray-50 dark:!bg-gray-700 !border-gray-300 dark:!border-gray-600 !h-11 !flex !items-center !text-gray-900 dark:!text-white !px-4 !rounded-lg !shadow-sm !font-bold',
                dropdownCssClass: 'dark-dropdown !bg-white dark:!bg-gray-700 !border-gray-300 dark:!border-gray-600 !text-gray-900 dark:!text-white !shadow-2xl'
            });

            const mediaUpload = document.getElementById('comment-media-upload');
            const mediaError = document.getElementById('comment-media-error');
            const submitBtn = document.getElementById('comment-submit-btn');

            if (mediaUpload) {
                mediaUpload.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const validTypes = ['image/jpeg', 'image/png', 'video/mp4'];
                        if (!validTypes.includes(file.type)) {
                            mediaError.textContent = '❌ ERROR: Invalid format! Please upload only JPEG, PNG, or MP4 files.';
                            mediaError.classList.remove('hidden');
                            mediaUpload.value = '';
                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                            submitBtn.disabled = true;
                            return;
                        }
                        if (file.size > 20971520) {
                            mediaError.textContent = '❌ ERROR: File is too large! Maximum allowed size is 20MB.';
                            mediaError.classList.remove('hidden');
                            mediaUpload.value = '';
                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                            submitBtn.disabled = true;
                            return;
                        }
                        mediaError.classList.add('hidden');
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        submitBtn.disabled = false;
                    } else {
                        mediaError.classList.add('hidden');
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        submitBtn.disabled = false;
                    }
                });
            }
        });
    </script>
</x-app-layout>
