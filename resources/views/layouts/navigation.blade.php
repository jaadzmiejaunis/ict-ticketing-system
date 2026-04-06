<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Home') }}</x-nav-link>
                    <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')">{{ __('Ticket System') }}</x-nav-link>
                    <x-nav-link :href="route('statistics')" :active="request()->routeIs('statistics')">{{ __('Statistics') }}</x-nav-link>
                    <x-nav-link :href="route('calendar')" :active="request()->routeIs('calendar')">{{ __('Calendar') }}</x-nav-link>
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">{{ __('Admin Panel') }}</x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">

                <div x-data="{ open: false, tab: 'all' }" class="relative flex items-center mr-4">
                    <button @click="open = !open"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition focus:outline-none relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700/50 active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>

                        @php
                            $unreadCount = Auth::user()->unreadNotifications->count();
                        @endphp

                        @if($unreadCount > 0)
                            <span class="absolute top-2.5 right-2.5 flex h-2 w-2 rounded-full bg-red-600 border-2 border-white dark:border-gray-800"></span>
                        @endif
                    </button>

                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 top-full mt-2 w-96 bg-[#111827] rounded-2xl shadow-2xl border border-gray-700 z-[100] overflow-hidden" style="display: none;">

                        <div class="p-6 border-b border-gray-800">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="text-sm font-black text-white uppercase tracking-widest">Notifications</h3>
                                    <p class="text-[10px] text-gray-500 font-bold">{{ $unreadCount }} unread messages</p>
                                </div>
                                <form action="{{ route('notifications.readAll') }}" method="POST" @submit.stop>
                                    @csrf
                                    <button type="submit" @click.stop class="text-[10px] font-black text-indigo-400 hover:text-indigo-300 uppercase tracking-widest">Mark All Read</button>
                                </form>
                            </div>

                            <div class="flex p-1 bg-gray-900/50 rounded-xl gap-1">
                                <button @click="tab = 'all'" :class="tab === 'all' ? 'bg-white text-gray-900' : 'text-gray-400 hover:text-gray-200'" class="flex-1 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">All</button>
                                <button @click="tab = 'comments'" :class="tab === 'comments' ? 'bg-white text-gray-900' : 'text-gray-400 hover:text-gray-200'" class="flex-1 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">Comments</button>
                                <button @click="tab = 'system'" :class="tab === 'system' ? 'bg-white text-gray-900' : 'text-gray-400 hover:text-gray-200'" class="flex-1 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">System</button>
                            </div>
                        </div>

                        <div class="max-h-96 overflow-y-auto custom-scrollbar bg-[#111827]">
                            @php
                                $allNotifications = Auth::user()->notifications()->take(30)->get();
                                $commentAlerts = $allNotifications->filter(fn($n) => in_array($n->data['type'] ?? '', ['mention', 'reply']));
                                $systemAlerts = $allNotifications->filter(fn($n) => !in_array($n->data['type'] ?? '', ['mention', 'reply']));
                            @endphp

                            <template x-if="tab === 'all' || tab === 'comments'">
                                <div>
                                    @foreach($commentAlerts as $notification)
                                        @php $isUnread = $notification->unread(); @endphp
                                        <a href="{{ route('tickets.show', $notification->data['ticket_id']) }}"
                                           class="flex flex-col p-5 hover:bg-gray-800/50 transition border-b border-gray-800/50 group relative {{ $isUnread ? 'bg-indigo-500/[0.02]' : 'opacity-60' }}">
                                            @if($isUnread)
                                                <div class="absolute left-0 top-5 bottom-5 w-1 bg-indigo-500 rounded-full shadow-[0_0_10px_rgba(99,102,241,0.5)]"></div>
                                            @endif
                                            <div class="flex justify-between items-start mb-1.5">
                                                <span class="text-[10px] font-black {{ ($notification->data['type'] ?? '') === 'mention' ? 'text-red-400' : 'text-indigo-400' }} uppercase tracking-widest">
                                                    {{ $notification->data['comment_user'] }} {{ ($notification->data['type'] ?? '') === 'mention' ? 'mentioned you' : 'replied' }}
                                                </span>
                                                <span class="text-[9px] text-gray-600 font-bold">{{ $notification->created_at->diffForHumans(null, true) }}</span>
                                            </div>
                                            <p class="text-xs {{ $isUnread ? 'text-gray-200' : 'text-gray-500' }} font-bold group-hover:text-white truncate">#{{ $notification->data['ticket_id'] }}: {{ $notification->data['ticket_title'] }}</p>
                                        </a>
                                    @endforeach
                                </div>
                            </template>

                            <template x-if="tab === 'all' || tab === 'system'">
                                <div>
                                    @foreach($systemAlerts as $notification)
                                        @php
                                            $isUnread = $notification->unread();
                                            $data = $notification->data;
                                            $type = $data['type'] ?? 'ticket';

                                            // Handle dynamic variables based on notification type
                                            $url = isset($data['ticket_id']) ? route('tickets.show', $data['ticket_id']) : '#';
                                            $icon = 'TI';
                                            $title = $data['title'] ?? 'Ticket Updated';
                                            $message = isset($data['ticket_id']) ? "#{$data['ticket_id']}: {$data['ticket_title']}" : ($data['message'] ?? '');

                                            if ($type === 'welcome') {
                                                $icon = '🎉';
                                                $title = 'Welcome Alert';
                                                $message = "Account created by {$data['admin_name']}";
                                                $url = route('profile.edit');
                                            } elseif ($type === 'admin_action') {
                                                $icon = '👤';
                                                $title = "Admin Activity: {$data['action']}";
                                                $message = $data['message'];
                                                $url = Auth::user()->role === 'admin' ? route('admin.accounts') : '#';
                                            }
                                        @endphp
                                        <a href="{{ $url }}"
                                           class="flex items-center gap-4 p-5 hover:bg-gray-800/50 transition border-b border-gray-800/50 group {{ $isUnread ? '' : 'opacity-60' }}">
                                            <div class="w-10 h-10 rounded-xl bg-gray-800 flex items-center justify-center text-[10px] font-black text-gray-500 group-hover:text-indigo-400 border border-transparent transition uppercase">{{ $icon }}</div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs font-black {{ $isUnread ? 'text-gray-200' : 'text-gray-500' }} group-hover:text-white uppercase tracking-tight">{{ $title }}</span>
                                                    <span class="text-[9px] text-gray-600 font-bold">{{ $notification->created_at->diffForHumans(null, true) }}</span>
                                                </div>
                                                <p class="text-[10px] text-gray-500 font-bold truncate mt-0.5" title="{{ $message }}">{{ $message }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </template>

                            @if($allNotifications->count() == 0)
                                <div class="py-16 text-center text-gray-600 italic text-[10px] uppercase tracking-[0.3em] font-black">All caught up</div>
                            @endif
                        </div>

                        <a href="{{ route('notifications.index') }}" @click.stop class="block py-4 text-center text-[10px] font-black text-white bg-gray-800 hover:bg-gray-700 transition uppercase tracking-[0.2em]">
                            View all history
                        </a>
                    </div>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 transition">
                            <div class="flex items-center gap-3">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="h-8 w-8 rounded-full object-cover border border-gray-600 shadow-sm">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=ffffff" class="h-8 w-8 rounded-full object-cover border border-gray-600 shadow-sm">
                                @endif
                                <span class="font-bold">{{ Auth::user()->name }}</span>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile Settings') }}</x-dropdown-link>
                        <x-dropdown-link :href="route('my.performance')">{{ __('My Performance') }}</x-dropdown-link>
                        <div class="border-t border-gray-800"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
