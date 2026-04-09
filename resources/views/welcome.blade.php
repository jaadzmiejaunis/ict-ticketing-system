<x-app-layout>
    <div class="min-h-screen flex flex-col items-center justify-start pt-20 pb-12 p-6 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

        <div class="mb-12 transition-transform hover:scale-105 duration-300">
            <div class="p-5 bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-2xl transition-colors">
                <svg class="w-16 h-16 text-indigo-600 dark:text-indigo-500" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M61.8548 14.6253L31.3537 0.170654L0.852539 14.6253L31.3537 29.0799L61.8548 14.6253Z" fill="currentColor"/>
                    <path d="M61.8548 32.25L31.3537 46.7046L0.852539 32.25V46.7046L31.3537 61.1593L61.8548 46.7046V32.25Z" fill="currentColor"/>
                    <path d="M31.3537 29.0799V46.7046L61.8548 32.25V14.6253L31.3537 29.0799Z" fill="currentColor" fill-opacity="0.5"/>
                </svg>
            </div>
        </div>

        <div class="w-full max-w-[640px] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl dark:shadow-[0_20px_50px_rgba(0,0,0,0.3)] rounded-2xl p-10 sm:p-16 text-center transition-colors">

            <header class="mb-12">
                <span class="text-[11px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-[0.5em] mb-6 block opacity-80 transition-colors">
                    IPG KAMPUS GAYA
                </span>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-6 transition-colors">
                    GayaCare <span class="text-gray-400 dark:text-gray-500 font-light italic transition-colors">Support</span>
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-base leading-relaxed max-w-md mx-auto transition-colors">
                    The official centralized platform for reporting technical issues and tracking maintenance resolution progress across the campus.
                </p>
            </header>

            <div class="flex flex-col items-center justify-center gap-5">
                @auth
                    <a href="{{ url('/dashboard') }}" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs uppercase tracking-[0.2em] py-5 rounded-xl transition-all active:scale-95 shadow-lg border border-indigo-500">
                        Enter Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs uppercase tracking-[0.2em] py-5 rounded-xl transition-all active:scale-95 shadow-lg border border-indigo-500">
                        Log In to System
                    </a>
                    <div class="mt-4 flex items-center gap-2 justify-center">
                        <span class="w-8 h-px bg-gray-200 dark:bg-gray-700 transition-colors"></span>
                        <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest transition-colors">
                            Official Staff Access Only
                        </p>
                        <span class="w-8 h-px bg-gray-200 dark:bg-gray-700 transition-colors"></span>
                    </div>
                @endauth
            </div>
        </div>

        <p class="mt-16 text-[10px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-[0.6em] hover:text-gray-600 dark:hover:text-gray-400 transition cursor-default">
            GAYACARE SUPPORT SYSTEM &copy; {{ date('Y') }}
        </p>
    </div>
</x-app-layout>
