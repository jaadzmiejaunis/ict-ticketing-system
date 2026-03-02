<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-gray-600 mb-8">You are logged into the ICT Ticketing System. What would you like to do today?</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('tickets.create') }}" class="group p-6 border border-gray-100 rounded-xl hover:border-indigo-200 hover:shadow-xl transition-all flex items-center gap-5">
                        <div class="p-4 bg-indigo-50 rounded-full text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">Submit a New Complaint</h3>
                            <p class="text-gray-500 text-sm">Report a hardware or software issue.</p>
                        </div>
                    </a>

                    <a href="{{ route('tickets.index') }}" class="group p-6 border border-gray-100 rounded-xl hover:border-blue-200 hover:shadow-xl transition-all flex items-center gap-5">
                        <div class="p-4 bg-blue-50 rounded-full text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">Manage Tickets</h3>
                            <p class="text-gray-500 text-sm">View, edit, and resolve existing complaints.</p>
                        </div>
                    </a>

                    <a href="{{ route('tickets.index', ['filter' => 'assigned_by_me']) }}" class="group p-6 border border-gray-100 rounded-xl hover:border-purple-200 hover:shadow-xl transition-all flex items-center gap-5">
                        <div class="p-4 bg-purple-50 rounded-full text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">Assigned by Me</h3>
                            <p class="text-gray-500 text-sm">Track tickets you've given to other staff.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
