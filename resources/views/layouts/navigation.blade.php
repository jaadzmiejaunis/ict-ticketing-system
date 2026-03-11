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
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Home') }}
                    </x-nav-link>

                    <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')">
                        {{ __('Ticket System') }}
                    </x-nav-link>

                    <x-nav-link :href="route('statistics')" :active="request()->routeIs('statistics')">
                        {{ __('Statistics') }}
                    </x-nav-link>

                    <x-nav-link :href="route('calendar')" :active="request()->routeIs('calendar')">
                        {{ __('Calendar') }}
                    </x-nav-link>

                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Admin Panel') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">

                <div x-data="{ open: false }" class="relative flex items-center mr-4">
                    <button @click="open = !open"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition focus:outline-none relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700/50 active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>

                        @php
                            // Merged unread count logic
                            $dbUnreadCount = Auth::user()->unreadNotifications->count();
                            $activityCount = isset($globalNotifications) ? $globalNotifications->filter(fn($n) => $n->updated_at > (Auth::user()->last_read_notifications_at ?? '1970-01-01'))->count() : 0;
                            $unreadCount = $dbUnreadCount + $activityCount;
                        @endphp

                        @if($unreadCount > 0)
                            <span class="absolute top-2.5 right-2.5 flex h-2 w-2 rounded-full bg-red-600 border-2 border-white dark:border-gray-800"></span>
                        @endif
                    </button>

                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 top-full mt-2 w-96 bg-[#111827] rounded-xl shadow-2xl border border-gray-700 z-[100] overflow-hidden" style="display: none;">

                        <div class="p-4 border-b border-gray-800 flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-bold text-white">Notifications</h3>
                                <p class="text-[10px] text-gray-400">{{ $unreadCount }} unread</p>
                            </div>
                            <form action="{{ route('notifications.readAll') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-[10px] font-bold text-indigo-400 hover:text-indigo-300 uppercase tracking-tight">Mark All as Read</button>
                            </form>
                        </div>

                        <div class="max-h-[450px] overflow-y-auto custom-scrollbar">
                            @foreach(Auth::user()->unreadNotifications as $notification)
                                @php $isMention = ($notification->data['type'] ?? 'reply') === 'mention'; @endphp

                                <a href="{{ route('tickets.show', $notification->data['ticket_id']) }}"
                                class="flex flex-col p-4 border-b border-gray-800 transition group relative {{ $isMention ? 'bg-red-500/5' : 'bg-indigo-500/5' }}">

                                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $isMention ? 'bg-red-500' : 'bg-indigo-600' }}"></div>

                                    <div class="flex justify-between items-start mb-1">
                                        <span class="text-[10px] font-black uppercase tracking-widest {{ $isMention ? 'text-red-400' : 'text-indigo-400' }}">
                                            {{ $notification->data['comment_user'] }}
                                            {{ $isMention ? 'MENTIONED YOU' : 'replied' }}
                                        </span>
                                        <span class="text-[9px] text-gray-500 uppercase">{{ $notification->created_at->diffForHumans(null, true) }}</span>
                                    </div>
                                    <p class="text-xs text-gray-200 font-bold">#{{ $notification->data['ticket_id'] }}: {{ $notification->data['ticket_title'] }}</p>
                                </a>
                            @endforeach

                            @if(isset($globalNotifications))
                                @forelse($globalNotifications as $noti)
                                    @php
                                        $isUnread = $noti->updated_at > (Auth::user()->last_read_notifications_at ?? '1970-01-01 00:00:00');
                                        $targetRoute = $noti->trashed() ? route('tickets.trash') : route('tickets.show', $noti->id);
                                    @endphp
                                    <a href="{{ $targetRoute }}"
                                       class="relative p-4 border-b border-gray-800/50 hover:bg-gray-800/40 transition group block {{ $isUnread ? 'bg-gray-800/20' : 'opacity-60 grayscale-[0.3]' }}">

                                        @if($isUnread && $noti->updated_at > (Auth::user()->last_read_notifications_at ?? '1970-01-01'))
                                            <div class="absolute left-3 top-1/2 -translate-y-1/2 w-2 h-2 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.5)]"></div>
                                        @endif

                                        <div class="flex gap-4 {{ ($isUnread && $noti->updated_at > (Auth::user()->last_read_notifications_at ?? '1970-01-01')) ? 'ml-4' : '' }}">
                                            <div class="flex-shrink-0">
                                                @if($noti->assigner && $noti->assigner->avatar)
                                                    <img src="{{ asset('storage/' . $noti->assigner->avatar) }}" class="w-10 h-10 rounded-lg object-cover ring-1 ring-gray-700">
                                                @else
                                                    <div class="w-10 h-10 rounded-lg bg-gray-700 flex items-center justify-center text-gray-400 font-bold text-xs">
                                                        {{ substr($noti->assigner->name ?? 'SY', 0, 2) }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <div class="flex justify-between items-start mb-1">
                                                    <h4 class="text-xs font-bold text-gray-200 truncate pr-4">
                                                        @if($noti->trashed()) Ticket Trashed
                                                        @elseif($noti->created_at->eq($noti->updated_at)) New Ticket Created
                                                        @elseif($noti->assigned_to == Auth::id()) Ticket Assigned to You
                                                        @else Ticket Updated @endif
                                                    </h4>
                                                    <span class="text-[9px] font-medium text-gray-500 whitespace-nowrap">{{ $noti->updated_at->diffForHumans(null, true) }}</span>
                                                </div>
                                                <p class="text-[11px] text-gray-400 line-clamp-1 mb-2">#{{ $noti->id }}: {{ $noti->title }}</p>
                                                <div class="flex items-center gap-2">
                                                    <span class="px-1.5 py-0.5 rounded bg-gray-700 text-[8px] font-black text-gray-300 uppercase tracking-wider">TICKET</span>
                                                    <span class="text-[9px] text-gray-500 italic">by {{ $noti->assigner->name ?? 'System' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-12 text-center text-gray-500 italic text-xs">No activity found.</div>
                                @endforelse
                            @endif
                        </div>

                        <a href="{{ route('notifications.index') }}" class="block p-4 text-center text-xs font-bold text-white bg-gray-800/50 hover:bg-gray-800 transition border-t border-gray-700">
                            Show All Notifications
                        </a>
                    </div>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition">
                            <div class="flex items-center gap-2">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="h-8 w-8 rounded-full object-cover border border-gray-500" alt="{{ Auth::user()->name }}">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=ffffff" class="h-8 w-8 rounded-full object-cover border border-gray-500" alt="{{ Auth::user()->name }}">
                                @endif
                                <span>{{ Auth::user()->name }}</span>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <x-dropdown-link :href="route('my.performance')">{{ __('My Performance') }}</x-dropdown-link>
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
