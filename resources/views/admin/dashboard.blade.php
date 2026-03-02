<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white">Admin Control Panel</h2>
                    <p class="text-gray-400 text-sm">System overview and recent activity.</p>
                </div>
                <a href="{{ route('admin.accounts') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-bold transition text-sm shadow-sm flex items-center gap-2">
                    Manage All Users &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center gap-4 border border-gray-100">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Active Staff</p>
                        <p class="text-3xl font-black text-gray-900">{{ $staffMembers->count() }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center gap-4 border border-gray-100">
                    <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">System Admins</p>
                        <p class="text-3xl font-black text-gray-900">{{ $adminMembers->count() }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center gap-4 border border-gray-100">
                    <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Recent Logins</p>
                        <p class="text-3xl font-black text-gray-900">{{ $logs->count() }}</p>
                    </div>
                </div>

            </div>

            <div class="bg-white rounded-lg shadow-sm w-full overflow-hidden border border-gray-200">
                <div class="p-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Recent User Activity</h3>
                </div>

                <div class="overflow-y-auto max-h-[500px]">
                    <table class="w-full table-fixed divide-y divide-gray-200 relative">
                        <thead class="bg-gray-50 sticky top-0 shadow-sm z-10">
                            <tr>
                                <th class="w-1/3 px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">User</th>
                                <th class="w-1/6 px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="w-1/4 px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Login</th>
                                <th class="w-1/4 px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Logout</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 truncate">
                                        {{ $log->user->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ strtolower($log->user->role ?? '') === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($log->user->role ?? 'N/A') }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-3">
                                        @if($log->login_at)
                                            <div class="text-sm font-bold text-green-600">{{ \Carbon\Carbon::parse($log->login_at)->format('M d') }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($log->login_at)->format('g:i A') }}</div>
                                        @else
                                            <span class="text-sm text-gray-500">--</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-3">
                                        @if($log->logout_at)
                                            <div class="text-sm font-bold text-red-500">{{ \Carbon\Carbon::parse($log->logout_at)->format('M d') }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($log->logout_at)->format('g:i A') }}</div>
                                        @else
                                            <span class="text-xs font-bold text-indigo-500 bg-indigo-50 px-2 py-1 rounded-full border border-indigo-200">Active</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">No login activity recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
