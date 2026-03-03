<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Home</h1>
            </div>
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

                    <a href="{{ route('tickets.index', ['filter' => 'assigned_by_me']) }}" class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition group">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-purple-50 text-purple-600 rounded-lg group-hover:bg-purple-600 group-hover:text-white transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Assigned by Me</h3>
                                <p class="text-sm text-gray-500">Track tickets you've given to other staff.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
