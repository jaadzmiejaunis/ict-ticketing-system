<x-app-layout>
    <div class="py-12 bg-[#0f172a] min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-black text-white uppercase tracking-tight">System Activity Log</h1>
                    <p class="text-sm text-gray-400 mt-1">Track updates, assignments, and admin actions.</p>
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
                            // Handle both Object (Model) and Array formats safely
                            $isModel = is_object($noti);

                            // Extract Data Safely
                            $rawData = $isModel ? $noti->data : ($noti['data'] ?? []);
                            $data = is_string($rawData) ? json_decode($rawData, true) : $rawData;

                            // Extract Status Safely
                            $isUnread = $isModel ? $noti->unread() : is_null($noti['read_at'] ?? null);

                            // Extract Date Safely
                            $createdAt = $isModel ? $noti->created_at : \Carbon\Carbon::parse($noti['created_at'] ?? now());

                            // Determine route based on data
                            $targetRoute = isset($data['ticket_id'])
                                ? route('tickets.show', $data['ticket_id'])
                                : route('admin.accounts');

                            // Determine Icon
                            $icon = 'TI';
                            if (($data['type'] ?? '') === 'welcome') $icon = '🎉';
                            if (($data['type'] ?? '') === 'admin_action') $icon = '👤';
                        @endphp

                        <div onclick="window.location='{{ $targetRoute }}'"
                             class="relative p-6 cursor-pointer hover:bg-gray-800/40 transition group flex gap-6 items-center {{ $isUnread ? 'bg-gray-800/20' : 'opacity-50 grayscale-[0.2]' }}">

                            @if($isUnread)
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 shadow-[2px_0_10px_rgba(59,130,246,0.5)]"></div>
                            @endif

                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-xl bg-gray-800 border border-gray-700 flex items-center justify-center text-gray-500 font-bold text-sm">
                                    {{ $icon }}
                                </div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <h3 class="text-sm font-black text-white uppercase tracking-wide">
                                        @if(($data['type'] ?? '') === 'welcome') Welcome Alert
                                        @elseif(($data['type'] ?? '') === 'admin_action') Admin Activity
                                        @else Ticket Update @endif
                                    </h3>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ $createdAt->diffForHumans() }}</span>
                                </div>

                                <p class="text-sm text-gray-400 font-medium mb-3">{{ $data['message'] ?? 'Action performed' }}</p>

                                <div class="flex items-center gap-3">
                                    <span class="px-2 py-0.5 rounded bg-gray-800 border border-gray-700 text-[9px] font-black text-gray-400 uppercase">
                                        {{ strtoupper(str_replace('_', ' ', $data['type'] ?? 'System')) }}
                                    </span>
                                    @if(isset($data['admin_name']))
                                        <span class="text-[10px] text-gray-500">Action by <strong class="text-gray-300">{{ $data['admin_name'] }}</strong></span>
                                    @endif
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
            </div>
        </div>
    </div>
</x-app-layout>
