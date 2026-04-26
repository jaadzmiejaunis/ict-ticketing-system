<x-app-layout>
    @section('title', 'Notification')
    <div class="py-6 sm:py-12" x-data="{ tab: 'all' }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-5 sm:mb-8">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 font-bold transition text-[10px] sm:text-xs uppercase tracking-[0.2em] bg-white dark:bg-gray-800 py-2 px-4 rounded-full shadow-sm border border-gray-200 dark:border-gray-700">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to System Home
                </a>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4 transition-colors">
                <div>
                    <h2 class="text-xl sm:text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight transition-colors">Notification History</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm mt-1 transition-colors">A complete record of your system alerts and discussion mentions.</p>
                </div>

                @if(Auth::user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.readAll') }}" method="POST" class="w-full sm:w-auto shrink-0">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 sm:py-2.5 rounded-lg font-bold transition shadow-md text-xs uppercase tracking-widest active:scale-95 border border-transparent flex items-center">
                            Mark All As Read
                        </button>
                    </form>
                @endif
            </div>

            <div class="flex p-1.5 bg-gray-100 dark:bg-gray-900/50 rounded-xl gap-1 border border-gray-200 dark:border-gray-700 transition-colors mb-6 sm:mb-8 shadow-inner">
                <button @click="tab = 'all'" :class="tab === 'all' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-sm border border-gray-200 dark:border-gray-600' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 border border-transparent'" class="flex-1 py-2.5 sm:py-2.5 text-[10px] sm:text-xs font-black uppercase tracking-widest rounded-lg transition-all">All</button>
                <button @click="tab = 'comments'" :class="tab === 'comments' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-sm border border-gray-200 dark:border-gray-600' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 border border-transparent'" class="flex-1 py-2.5 sm:py-2.5 text-[10px] sm:text-xs font-black uppercase tracking-widest rounded-lg transition-all">Comments</button>
                <button @click="tab = 'system'" :class="tab === 'system' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-sm border border-gray-200 dark:border-gray-600' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 border border-transparent'" class="flex-1 py-2.5 sm:py-2.5 text-[10px] sm:text-xs font-black uppercase tracking-widest rounded-lg transition-all">System</button>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors">
                <div class="divide-y divide-gray-100 dark:divide-gray-700 transition-colors">

                    @php
                        // Paginated notifications from the user
                        $notifications = Auth::user()->notifications()->paginate(15);
                    @endphp

                    @forelse($notifications as $notification)
                        @php
                            $isUnread = $notification->unread();
                            $data = $notification->data;
                            $type = $data['type'] ?? 'ticket';
                            $isComment = in_array($type, ['mention', 'reply']);

                            // Generate condition for Alpine visibility
                            $visibilityCondition = "tab === 'all' || (tab === 'comments' && " . ($isComment ? 'true' : 'false') . ") || (tab === 'system' && " . ($isComment ? 'false' : 'true') . ")";

                            $url = isset($data['ticket_id']) ? route('tickets.show', $data['ticket_id']) : '#';
                            $icon = 'TI';
                            $title = $data['title'] ?? 'Ticket Updated';
                            $message = $data['message'] ?? (isset($data['ticket_id']) ? "#{$data['ticket_id']}: {$data['ticket_title']}" : '');

                            if ($type === 'welcome') {
                                $icon = '🎉';
                                $title = 'Welcome Alert';
                                $url = route('profile.edit');
                            } elseif ($type === 'admin_action') {
                                $icon = '👤';
                                $url = Auth::user()->role === 'admin' ? route('admin.accounts') : '#';
                            }
                        @endphp

                        <div class="relative group" x-show="{{ $visibilityCondition }}">
                            <a href="{{ $url }}" class="flex items-start gap-3 sm:gap-5 p-4 sm:p-6 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-all {{ $isUnread ? 'bg-indigo-50/30 dark:bg-indigo-500/[0.03]' : 'opacity-70' }}">

                                @if($isUnread)
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-600 dark:bg-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.4)]"></div>
                                @endif

                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gray-100 dark:bg-gray-900 flex items-center justify-center text-base sm:text-lg font-black text-gray-400 dark:text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 border border-gray-200 dark:border-gray-700 transition-all shrink-0">
                                    {{ $isComment ? '💬' : $icon }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[9px] sm:text-[10px] font-black {{ $isComment ? ($type === 'mention' ? 'text-red-500' : 'text-indigo-600') : ($isUnread ? 'text-indigo-600' : 'text-gray-500') }} dark:{{ $isComment ? ($type === 'mention' ? 'text-red-400' : 'text-indigo-400') : ($isUnread ? 'text-indigo-400' : 'text-gray-400') }} uppercase tracking-[0.15em] transition-colors truncate pr-2">
                                            {{ $isComment ? ucfirst($type) : $title }}
                                        </span>
                                        <span class="text-[9px] sm:text-[10px] text-gray-400 dark:text-gray-600 font-bold uppercase transition-colors shrink-0">{{ $notification->created_at->diffForHumans(null, true) }}</span>
                                    </div>

                                    @if($isComment)
                                        <h4 class="text-xs sm:text-sm font-bold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-white transition leading-snug">
                                            {{ $data['comment_user'] ?? 'Someone' }} mentioned you in Ticket #{{ $data['ticket_id'] ?? '???' }}
                                        </h4>
                                        <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-500 font-medium truncate mt-1 italic transition-colors">
                                            "{{ $data['ticket_title'] ?? '' }}"
                                        </p>
                                    @else
                                        <p class="text-xs sm:text-sm font-bold text-gray-900 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-white transition leading-snug truncate" title="{{ $message }}">
                                            {{ $message }}
                                        </p>
                                    @endif
                                </div>

                                <div class="hidden sm:flex items-center self-center text-gray-300 dark:text-gray-700 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </a>
                        </div>

                    @empty
                        <div class="py-16 sm:py-24 text-center px-4">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-200 dark:border-gray-700 transition-colors">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <h3 class="text-xs sm:text-sm font-black text-gray-400 dark:text-gray-600 uppercase tracking-[0.2em] transition-colors">No notifications yet</h3>
                            <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-700 mt-2 transition-colors">System and comment alerts will appear here as they happen.</p>
                        </div>
                    @endforelse

                </div>

                @if($notifications->hasPages())
                    <div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-800 transition-colors">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
