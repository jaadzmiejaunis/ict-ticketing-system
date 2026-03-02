<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-white">User Account Management</h2>
                    <p class="text-gray-400 text-sm">View, edit, and manage system access for all users.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.accounts.create') }}" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 font-bold transition text-sm shadow-sm flex items-center">
                        + Add New User
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 font-semibold transition text-sm shadow-sm flex items-center">
                        &larr; Back to Admin Panel
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-3 bg-green-100 text-green-700 rounded text-sm font-semibold border border-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-3 bg-red-100 text-red-700 rounded text-sm font-semibold border border-red-300">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="bg-gray-800 p-4 rounded-lg mb-6 shadow-sm border border-gray-700">
                <form method="GET" action="{{ route('admin.accounts') }}" class="flex flex-col sm:flex-row gap-4 items-end sm:items-center">

                    <div class="w-full sm:w-1/2 flex flex-col">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Search Users</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="rounded-md border-gray-600 bg-gray-700 text-white placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full text-sm">
                    </div>

                    <div class="w-full sm:w-1/4 flex flex-col">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Filter by Role</label>
                        <select name="role" class="rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full text-sm">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins Only</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff Only</option>
                        </select>
                    </div>

                    <div class="w-full sm:w-auto flex gap-2">
                        <button type="submit" class="w-full sm:w-auto bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-bold transition text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Search
                        </button>

                        @if(request('search') || request('role'))
                            <a href="{{ route('admin.accounts') }}" class="w-full sm:w-auto bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-500 font-bold transition text-sm flex items-center justify-center">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div x-data="{ tab: sessionStorage.getItem('adminAccountsTab') || 'active' }">

                <div class="flex space-x-2 mb-6 border-b border-gray-700 pb-2">
                    <button @click="tab = 'active'; sessionStorage.setItem('adminAccountsTab', 'active')"
                            :class="tab === 'active' ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-gray-200'"
                            class="px-4 py-2 rounded-md text-sm font-bold transition duration-150 flex items-center gap-2">
                        Active Accounts
                        <span class="bg-indigo-500/50 text-white py-0.5 px-2 rounded-full text-xs">{{ $activeUsers->total() }}</span>
                    </button>

                    <button @click="tab = 'inactive'; sessionStorage.setItem('adminAccountsTab', 'inactive')"
                            :class="tab === 'inactive' ? 'bg-red-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-gray-200'"
                            class="px-4 py-2 rounded-md text-sm font-bold transition duration-150 flex items-center gap-2">
                        Deactivated
                        <span :class="tab === 'inactive' ? 'bg-red-500/50' : 'bg-gray-700'" class="text-white py-0.5 px-2 rounded-full text-xs transition">{{ $inactiveUsers->total() }}</span>
                    </button>
                </div>

                <div x-show="tab === 'active'">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">User</th>
                                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Role</th>
                                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($activeUsers as $user)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-xs font-bold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                                <a href="{{ route('admin.accounts.edit', $user) }}" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-3 py-1.5 rounded text-sm font-bold transition border border-indigo-200">
                                                    Edit
                                                </a>
                                                @if(Auth::id() !== $user->id)
                                                    <form action="{{ route('admin.accounts.toggle', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to deactivate {{ $user->name }}\'s account? They will be logged out and cannot access the system.');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 px-3 py-1.5 rounded text-sm font-bold transition">
                                                            Deactivate
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="px-3 py-1.5 rounded text-sm font-bold text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed cursor-help" title="You cannot deactivate yourself">
                                                        Deactivate
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">No active users found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        {{ $activeUsers->links() }}
                    </div>
                </div>

                <div x-show="tab === 'inactive'" style="display: none;">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden opacity-90 mb-4">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">User</th>
                                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Role</th>
                                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($inactiveUsers as $user)
                                        <tr class="hover:bg-gray-50 transition bg-gray-50/50">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-gray-900 line-through decoration-gray-400">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-xs font-bold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                                <a href="{{ route('admin.accounts.edit', $user) }}" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-3 py-1.5 rounded text-sm font-bold transition border border-indigo-200">
                                                    Edit
                                                </a>

                                                <form action="{{ route('admin.accounts.toggle', $user) }}" method="POST" class="inline" onsubmit="return confirm('Reactivate {{ $user->name }}\'s account? They will regain system access.');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="bg-green-50 text-green-600 hover:bg-green-100 border border-green-200 px-3 py-1.5 rounded text-sm font-bold transition">
                                                        Activate
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.accounts.delete', $user) }}" method="POST" class="inline" onsubmit="return confirm('CRITICAL WARNING: Are you sure you want to permanently delete {{ $user->name }}? This action cannot be undone and will wipe their account entirely from the database.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-600 text-white hover:bg-red-700 border border-red-700 px-3 py-1.5 rounded text-sm font-bold transition shadow-sm">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">No deactivated accounts.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        {{ $inactiveUsers->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
