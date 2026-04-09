<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors">
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

                    @auth
                        <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')">{{ __('Ticket System') }}</x-nav-link>
                        <x-nav-link :href="route('statistics')" :active="request()->routeIs('statistics')">{{ __('Statistics') }}</x-nav-link>
                        <x-nav-link :href="route('calendar')" :active="request()->routeIs('calendar')">{{ __('Calendar') }}</x-nav-link>

                        @if(Auth::user()->role === 'admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">{{ __('Admin Panel') }}</x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="flex items-center mr-4 border-r border-gray-200 dark:border-gray-700 pr-4 transition-colors">
                    <x-theme-toggle />
                </div>

                @auth
                    <div x-data="{ open: false, tab: 'all' }" class="relative flex items-center mr-4">
                        <button @click="open = !open"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition focus:outline-none relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700/50 active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>

                            @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="absolute top-2.5 right-2.5 flex h-2 w-2 rounded-full bg-red-600 border-2 border-white dark:border-gray-800"></span>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 top-full mt-2 w-96 bg-white dark:bg-[#111827] rounded-2xl shadow-xl dark:shadow-2xl border border-gray-200 dark:border-gray-700 z-[100] overflow-hidden transition-colors" style="display: none;">

                            <div class="p-6 border-b border-gray-100 dark:border-gray-800 transition-colors">
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest transition-colors">Notifications</h3>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold transition-colors">{{ $unreadCount }} unread messages</p>
                                    </div>
                                    <form action="{{ route('notifications.readAll') }}" method="POST" @submit.stop>
                                        @csrf
                                        <button type="submit" @click.stop class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 uppercase tracking-widest transition-colors">Mark All Read</button>
                                    </form>
                                </div>

                                <div class="flex p-1 bg-gray-100 dark:bg-gray-900/50 rounded-xl gap-1 border border-gray-200 dark:border-transparent transition-colors">
                                    <button @click="tab = 'all'" :class="tab === 'all' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="flex-1 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">All</button>
                                    <button @click="tab = 'comments'" :class="tab === 'comments' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="flex-1 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">Comments</button>
                                    <button @click="tab = 'system'" :class="tab === 'system' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="flex-1 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">System</button>
                                </div>
                            </div>

                            <div class="max-h-96 overflow-y-auto custom-scrollbar bg-white dark:bg-[#111827] transition-colors">
                                @php
                                    $allNotifications = Auth::user()->notifications()->take(30)->get();
                                @endphp

                                @forelse($allNotifications as $notification)
                                    @php
                                        $isUnread = $notification->unread();
                                        $data = $notification->data;
                                        $type = $data['type'] ?? 'ticket';
                                        $isComment = in_array($type, ['mention', 'reply']);

                                        // Visibility based on Active Tab
                                        $visibilityCondition = "tab === 'all' || (tab === 'comments' && " . ($isComment ? 'true' : 'false') . ") || (tab === 'system' && " . ($isComment ? 'false' : 'true') . ")";

                                        $url = isset($data['ticket_id']) ? route('tickets.show', $data['ticket_id']) : '#';
                                        $icon = 'TI';
                                        $title = $data['title'] ?? 'Ticket Updated';
                                        $message = $data['message'] ?? (isset($data['ticket_id']) ? "#{$data['ticket_id']}: {$data['ticket_title']}" : '');

                                        if ($type === 'welcome') { $icon = '🎉'; $url = route('profile.edit'); }
                                        elseif ($type === 'admin_action') { $icon = '👤'; $url = Auth::user()->role === 'admin' ? route('admin.accounts') : '#'; }
                                    @endphp

                                    <div x-show="{{ $visibilityCondition }}">
                                        @if($isComment)
                                            <a href="{{ route('tickets.show', $data['ticket_id'] ?? '#') }}"
                                               class="flex items-start gap-4 p-5 hover:bg-gray-50 dark:hover:bg-gray-800/40 transition border-b border-gray-100 dark:border-gray-800/50 group relative {{ $isUnread ? 'bg-indigo-50/30 dark:bg-indigo-500/[0.03]' : 'opacity-60' }}">
                                                @if($isUnread)
                                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-600 dark:bg-indigo-500 transition-colors shadow-[0_0_15px_rgba(99,102,241,0.4)]"></div>
                                                @endif
                                                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-[10px] font-black text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 border border-gray-200 dark:border-gray-700 transition uppercase shrink-0">💬</div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="text-[10px] font-black {{ $type === 'mention' ? 'text-red-500' : 'text-indigo-600' }} dark:{{ $type === 'mention' ? 'text-red-400' : 'text-indigo-400' }} uppercase tracking-widest transition-colors">{{ ucfirst($type) }}</span>
                                                        <span class="text-[9px] text-gray-400 dark:text-gray-600 font-bold uppercase transition-colors">{{ $notification->created_at->diffForHumans(null, true) }}</span>
                                                    </div>
                                                    <h4 class="text-xs font-bold {{ $isUnread ? 'text-gray-900' : 'text-gray-500' }} dark:{{ $isUnread ? 'text-gray-100' : 'text-gray-400' }} group-hover:text-indigo-600 dark:group-hover:text-white transition leading-snug">
                                                        {{ $data['comment_user'] ?? 'Staff' }} in Ticket #{{ $data['ticket_id'] ?? '???' }}
                                                    </h4>
                                                    <p class="text-[10px] text-gray-500 dark:text-gray-500 font-bold truncate mt-1 transition-colors">{{ $data['ticket_title'] ?? '' }}</p>
                                                </div>
                                            </a>
                                        @else
                                            <a href="{{ $url }}"
                                               class="flex items-start gap-4 p-5 hover:bg-gray-50 dark:hover:bg-gray-800/40 transition border-b border-gray-100 dark:border-gray-800/50 group relative {{ $isUnread ? 'bg-indigo-50/30 dark:bg-indigo-500/[0.03]' : 'opacity-60' }}">
                                                @if($isUnread)
                                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-600 dark:bg-indigo-500 transition-colors shadow-[0_0_15px_rgba(99,102,241,0.4)]"></div>
                                                @endif
                                                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-[10px] font-black text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 border border-gray-200 dark:border-gray-700 transition uppercase shrink-0">{{ $icon }}</div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="text-[10px] font-black {{ $isUnread ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-500' }} uppercase tracking-widest transition-colors">{{ $title }}</span>
                                                        <span class="text-[9px] text-gray-400 dark:text-gray-600 font-bold uppercase transition-colors">{{ $notification->created_at->diffForHumans(null, true) }}</span>
                                                    </div>
                                                    <p class="text-xs font-bold {{ $isUnread ? 'text-gray-900' : 'text-gray-500' }} dark:{{ $isUnread ? 'text-gray-200' : 'text-gray-400' }} group-hover:text-indigo-600 dark:group-hover:text-white transition leading-snug truncate" title="{{ $message }}">{{ $message }}</p>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                @empty
                                    <div class="py-20 text-center">
                                        <p class="text-[10px] text-gray-400 dark:text-gray-600 uppercase tracking-[0.3em] font-black transition-colors">All caught up</p>
                                    </div>
                                @endforelse
                            </div>

                            <a href="{{ route('notifications.index') }}" @click.stop class="block py-4 text-center text-[10px] font-black text-gray-700 dark:text-white bg-gray-50 dark:bg-gray-900/80 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors uppercase tracking-[0.2em] border-t border-gray-100 dark:border-gray-800">
                                View all history
                            </a>
                        </div>
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 transition">
                                <div class="flex items-center gap-3">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="h-8 w-8 rounded-full object-cover border border-gray-300 dark:border-gray-600 shadow-sm">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=ffffff" class="h-8 w-8 rounded-full object-cover border border-gray-300 dark:border-gray-600 shadow-sm">
                                    @endif
                                    <span class="font-bold">{{ Auth::user()->name }}</span>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile Settings') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('my.performance')">{{ __('My Performance') }}</x-dropdown-link>
                            <div class="border-t border-gray-100 dark:border-gray-800 transition-colors"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-4">
                        <x-nav-link :href="route('login')">{{ __('Log In') }}</x-nav-link>
                    </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
