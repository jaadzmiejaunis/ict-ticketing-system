<x-app-layout>
    <div class="min-h-[calc(100vh-65px)] flex flex-col items-center justify-center p-6 bg-gray-900">

        <div class="mb-8">
            <div class="p-4 bg-gray-800 rounded-2xl border border-gray-700 shadow-xl">
                <svg class="w-12 h-12 text-indigo-500" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M61.8548 14.6253L31.3537 0.170654L0.852539 14.6253L31.3537 29.0799L61.8548 14.6253Z" fill="currentColor"/>
                    <path d="M61.8548 32.25L31.3537 46.7046L0.852539 32.25V46.7046L31.3537 61.1593L61.8548 46.7046V32.25Z" fill="currentColor"/>
                    <path d="M31.3537 29.0799V46.7046L61.8548 32.25V14.6253L31.3537 29.0799Z" fill="currentColor" fill-opacity="0.5"/>
                </svg>
            </div>
        </div>

        <div class="w-full max-w-[580px] bg-gray-800 border border-gray-700 shadow-2xl rounded-xl p-8 sm:p-12 text-center">

            <header class="mb-10">
                <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-[0.4em] mb-4 block">
                    IPG KAMPUS GAYA
                </span>
                <h1 class="text-3xl sm:text-4xl font-bold text-white tracking-tight mb-4 uppercase">
                    ICT Support <span class="text-gray-500 font-medium lowercase italic">System</span>
                </h1>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm mx-auto">
                    The official centralized platform for reporting technical issues and tracking maintenance resolution progress.
                </p>
            </header>

            <div class="flex flex-col items-center justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-widest py-4 rounded-md transition-all active:scale-95 shadow-lg border border-indigo-500">
                        Enter Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-widest py-4 rounded-md transition-all active:scale-95 shadow-lg border border-indigo-500">
                        Log In to System
                    </a>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest mt-2">
                        Contact Admin for Account Registration
                    </p>
                @endauth
            </div>

            <div class="mt-12 pt-8 border-t border-gray-700 flex justify-center gap-10">
                <div class="text-left">
                    <span class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest">Version</span>
                    <span class="text-xs font-mono text-gray-400">2.1.0-STABLE</span>
                </div>
                <div class="text-left">
                    <span class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest">System</span>
                    <span class="flex items-center gap-2 text-xs font-bold text-green-500 uppercase">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Encrypted
                    </span>
                </div>
            </div>
        </div>

        <p class="mt-10 text-[9px] font-bold text-gray-700 uppercase tracking-[0.5em]">
            ICT SUPPORT TICKETING SYSTEM &copy; {{ date('Y') }}
        </p>
    </div>
</x-app-layout>
