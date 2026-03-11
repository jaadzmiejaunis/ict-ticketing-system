<x-app-layout>
    <div class="py-12 bg-[#0f172a] min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-black text-white uppercase tracking-tight">System Activity Log</h1>
                    <p class="text-sm text-gray-400 mt-1">Track updates, assignments, and deletions across the system.</p>
                </div>

                <div class="flex gap-3">
                    <form action="{{ route('notifications.readAll') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-bold transition shadow-lg text-xs uppercase tracking-widest active:scale-95">
                            Mark All as Read
                        </button>
                    </form>
                    <a href="{{ route('dashboard') }}" class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-6 py-2.5 rounded-lg font-bold transition text-xs uppercase tracking-widest border border-gray-700">
                        Dashboard
                    </a>
                </div>
            </div>

            <div class="bg-[#111827] rounded-2xl shadow-2xl border border-gray-800 overflow-hidden">
                <div class="divide-y divide-gray-800/50">
                    @forelse($allNotifications as $noti)
                        @php
                            // Logic for unread items and targeting routes
                            $isUnread = $noti->updated_at > (Auth::user()->last_read_notifications_at ?? '1970-01-01 00:00:00');
                            $targetRoute = $noti->trashed() ? route('tickets.trash') : route('tickets.show', $noti->id);
                        @endphp

                        <div onclick="window.location='{{ $targetRoute }}'"
                             class="relative p-6 cursor-pointer hover:bg-gray-800/40 transition group flex gap-6 items-center {{ $isUnread ? 'bg-gray-800/20' : 'opacity-50 grayscale-[0.2]' }}">

                            @if($isUnread)
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 shadow-[2px_0_10px_rgba(59,130,246,0.5)]"></div>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 w-2 h-2 bg-blue-500 rounded-full"></div>
                            @endif

                            <div class="flex-shrink-0 {{ $isUnread ? 'ml-2' : '' }}">
                                @if($noti->assigner && $noti->assigner->avatar)
                                    <img src="{{ asset('storage/' . $noti->assigner->avatar) }}" class="w-12 h-12 rounded-xl object-cover ring-2 ring-gray-700">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-gray-800 border border-gray-700 flex items-center justify-center text-gray-500 font-bold text-sm">
                                        {{ substr($noti->assigner->name ?? 'SY', 0, 2) }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <h3 class="text-sm font-black text-white uppercase tracking-wide">
                                        @if($noti->trashed()) Ticket Trashed
                                        @elseif($noti->created_at->eq($noti->updated_at)) New Ticket Created
                                        @elseif($noti->assigned_to == Auth::id()) Ticket Assigned to You
                                        @else Ticket Details Updated @endif
                                    </h3>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ $noti->updated_at->diffForHumans() }}</span>
                                </div>

                                <p class="text-sm text-gray-400 font-medium mb-3">#{{ $noti->id }}: {{ $noti->title }}</p>

                                <div class="flex items-center gap-3">
                                    <span class="px-2 py-0.5 rounded bg-gray-800 border border-gray-700 text-[9px] font-black text-gray-400 uppercase">System Activity</span>
                                    <span class="text-[10px] text-gray-500">Action by <strong class="text-gray-300">{{ $noti->assigner->name ?? 'System' }}</strong></span>
                                </div>
                            </div>

                            <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition">
                                <span class="text-indigo-400 text-xs font-bold uppercase tracking-widest">View Details &rarr;</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-24 text-center">
                            <p class="text-gray-500 font-bold italic">No activity recorded yet.</p>
                        </div>
                    @endforelse
                </div>

                @if($allNotifications->hasPages())
                    <div class="p-6 bg-gray-900/50 border-t border-gray-800">
                        {{ $allNotifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
