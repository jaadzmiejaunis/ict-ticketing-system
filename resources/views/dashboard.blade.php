<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Home</h1>
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h1 class="text-3xl font-bold text-white mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-gray-400 mb-8">You are logged into the ICT Ticketing System. What would you like to do today?</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <a href="{{ route('tickets.create') }}" class="group p-6 bg-gray-700 border border-gray-600 rounded-xl hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-900/20 transition-all flex items-center gap-5">
                        <div class="p-4 bg-gray-800 text-indigo-400 rounded-full group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-white">Submit a New Complaint</h3>
                            <p class="text-gray-400 text-sm">Report a hardware or software issue.</p>
                        </div>
                    </a>

                    <a href="{{ route('tickets.index') }}" class="group p-6 bg-gray-700 border border-gray-600 rounded-xl hover:border-blue-500 hover:shadow-lg hover:shadow-blue-900/20 transition-all flex items-center gap-5">
                        <div class="p-4 bg-gray-800 text-blue-400 rounded-full group-hover:bg-blue-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-white">Manage Tickets</h3>
                            <p class="text-gray-400 text-sm">View, edit, and resolve existing complaints.</p>
                        </div>
                    </a>

                    <a href="{{ route('tickets.index', ['filter' => 'assigned_by_me']) }}" class="group p-6 bg-gray-700 border border-gray-600 rounded-xl hover:border-purple-500 hover:shadow-lg hover:shadow-purple-900/20 transition-all flex items-center gap-5">
                        <div class="p-4 bg-gray-800 text-purple-400 rounded-full group-hover:bg-purple-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-white">Assigned by Me</h3>
                            <p class="text-sm text-gray-400">Track tickets you've given to other staff.</p>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
