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

                <div x-data="{ open: false }" class="relative flex items-center mr-2">
                    <button @click="open = !open"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition focus:outline-none relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700/50 active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>

                        @if(isset($globalNotifications) && $globalNotifications->count() > 0)
                            <span class="absolute top-2.5 right-2.5 flex h-2 w-2 rounded-full bg-red-600 border-2 border-white dark:border-gray-800"></span>
                        @endif
                    </button>

                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-12 w-80 bg-white rounded-lg shadow-2xl border border-gray-100 z-[100] overflow-hidden">

                        <div class="p-3 bg-gray-50 border-b border-gray-100 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                            System Alerts
                        </div>

                        <div class="max-h-96 overflow-y-auto">
                            @if(isset($globalNotifications))
                                @forelse($globalNotifications as $noti)
                                    <div class="p-4 border-b border-gray-50 hover:bg-gray-50 transition cursor-pointer">
                                        <p class="text-xs text-gray-800 leading-relaxed">
                                            @if($noti->assigned_to === Auth::id())
                                                <span class="text-indigo-600 font-bold">{{ $noti->assigner->name ?? 'Admin' }}</span>
                                                assigned task <span class="font-bold">#{{ $noti->id }}</span> to you
                                            @elseif($noti->assigned_by === Auth::id() && Auth::user()->role === 'admin')
                                                You assigned <span class="font-bold">#{{ $noti->id }}</span> to
                                                <span class="text-blue-600 font-bold">{{ $noti->assignee->name ?? 'Staff' }}</span>
                                            @else
                                                Activity on ticket <span class="font-bold">#{{ $noti->id }}</span>
                                            @endif
                                        </p>
                                        <p class="text-[10px] text-gray-400 mt-1 font-bold uppercase tracking-tighter">
                                            {{ $noti->updated_at->diffForHumans() }}
                                        </p>
                                    </div>
                                @empty
                                    <div class="p-8 text-center text-gray-400 text-xs italic">No new alerts.</div>
                                @endforelse
                            @endif
                        </div>

                        <a href="{{ route('tickets.index') }}" class="block p-3 text-center text-[10px] font-bold text-indigo-600 uppercase hover:bg-indigo-50 transition">
                            Go to Ticket Center &rarr;
                        </a>
                    </div>
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center gap-2">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="h-8 w-8 rounded-full object-cover border border-gray-500" alt="{{ Auth::user()->name }}">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=ffffff" class="h-8 w-8 rounded-full object-cover border border-gray-500" alt="{{ Auth::user()->name }}">
                                @endif
                                <span class="ml-1">{{ Auth::user()->name }}</span>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('my.performance')">
                            {{ __('My Performance') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('my.performance')" :active="request()->routeIs('my.performance')">
                    {{ __('My Performance') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
