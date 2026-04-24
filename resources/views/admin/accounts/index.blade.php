<x-app-layout>
    @section('title', 'User Account Management')

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 transition-colors">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight transition-colors">User Account Management</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm transition-colors mt-1">View, edit, and manage system access for all users.</p>
                </div>

                <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 w-full md:w-auto">
                    <a href="{{ route('admin.accounts.create') }}" class="w-full sm:w-auto justify-center bg-indigo-600 text-white px-5 py-3 sm:py-2.5 rounded-md hover:bg-indigo-700 font-bold transition text-xs sm:text-sm shadow-md flex items-center uppercase tracking-wider active:scale-95 border border-transparent">
                        + Add New User
                    </a>
                    <a href="{{ route('admin.accounts.deletions') }}" class="w-full sm:w-auto justify-center bg-red-600 text-white px-5 py-3 sm:py-2.5 rounded-md hover:bg-red-700 font-bold transition text-xs sm:text-sm shadow-md flex items-center gap-2 uppercase tracking-wider active:scale-95 border border-transparent">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Deletion Logs
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto justify-center bg-white dark:bg-gray-700 text-gray-700 dark:text-white px-5 py-3 sm:py-2.5 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 font-bold transition text-xs sm:text-sm shadow-md flex items-center uppercase tracking-wider active:scale-95 border border-gray-300 dark:border-gray-600">
                        &larr; Back
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="mb-6 flex items-center justify-between p-3 sm:p-4 bg-green-50 dark:bg-green-900/40 border-l-4 border-green-500 rounded-r-lg shadow-sm transition-colors">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 dark:text-green-400 mr-2 sm:mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <p class="text-xs sm:text-sm font-bold text-green-700 dark:text-green-400 uppercase tracking-wide transition-colors leading-tight">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-500 hover:text-green-700 dark:hover:text-green-300 transition shrink-0 ml-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl sm:rounded-lg shadow-sm mb-6 sm:mb-8 border border-gray-200 dark:border-gray-700 transition-colors">
                <form method="GET" action="{{ route('admin.accounts') }}" id="searchForm" class="flex flex-col md:flex-row flex-wrap md:flex-nowrap gap-3 sm:gap-4 items-stretch md:items-end">

                    <div class="flex-grow w-full">
                        <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1 transition-colors">Search Users</label>
                        <div class="relative">
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Search by name or email..."
                                   class="w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors">
                            @if(request('search'))
                                <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:flex gap-3 sm:gap-4 w-full md:w-auto">
                        <div class="w-full md:w-40">
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1 transition-colors">Sort By</label>
                            <select name="sort" onchange="this.form.submit()" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Recent</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Name (Z-A)</option>
                            </select>
                        </div>

                        <div class="w-full md:w-48">
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1 transition-colors">Role</label>
                            <select name="role" onchange="this.form.submit()" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="">All Roles</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Technicians</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-end w-full md:w-auto mt-1 md:mt-0">
                        <button type="submit" class="w-full md:w-auto justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2 rounded-md font-bold transition h-[42px] text-xs sm:text-sm uppercase shadow-sm border border-transparent active:scale-95 flex items-center">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <div x-data="{ tab: sessionStorage.getItem('adminAccountsTab') || 'active' }">
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 mb-6 border-b border-gray-200 dark:border-gray-700 pb-3 sm:pb-2 transition-colors">
                    <button @click="tab = 'active'; sessionStorage.setItem('adminAccountsTab', 'active')"
                            :class="tab === 'active' ? 'bg-indigo-600 text-white shadow-md border-indigo-500' : 'bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white'"
                            class="w-full sm:w-auto justify-center px-5 py-2.5 sm:py-2 rounded-md text-[10px] sm:text-sm font-bold transition-all uppercase tracking-wider flex items-center gap-2 border">
                        Active Accounts <span class="bg-indigo-500/50 text-white py-0.5 px-2 rounded-full text-[10px]">{{ $activeUsers->total() }}</span>
                    </button>

                    <button @click="tab = 'inactive'; sessionStorage.setItem('adminAccountsTab', 'inactive')"
                            :class="tab === 'inactive' ? 'bg-red-600 text-white shadow-md border-red-500' : 'bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-white'"
                            class="w-full sm:w-auto justify-center px-5 py-2.5 sm:py-2 rounded-md text-[10px] sm:text-sm font-bold transition-all uppercase tracking-wider flex items-center gap-2 border">
                        Deactivated <span :class="tab === 'inactive' ? 'bg-red-500/50 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="py-0.5 px-2 rounded-full text-[10px] transition-all">{{ $inactiveUsers->total() }}</span>
                    </button>
                </div>

                <div x-show="tab === 'active'">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 mb-4 transition-colors">

                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full min-w-[700px] text-left">
                                <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700 transition-colors">
                                    <tr class="text-[10px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <th class="px-4 sm:px-6 py-4">User Details</th>
                                        <th class="px-4 sm:px-6 py-4">Role</th>
                                        <th class="px-4 sm:px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                                    @forelse($activeUsers as $user)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="px-4 sm:px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=4f46e5&color=fff' }}"
                                                         class="w-8 h-8 sm:w-10 sm:h-10 shrink-0 rounded-full object-cover border border-gray-200 dark:border-gray-700 shadow-sm" alt="Avatar">
                                                    <div class="min-w-0">
                                                        <a href="{{ route('admin.accounts.performance', $user) }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline text-xs sm:text-sm transition-colors truncate block">
                                                            {{ $user->name }}
                                                        </a>
                                                        <div class="text-[10px] sm:text-[11px] text-gray-500 dark:text-gray-500 font-bold uppercase transition-colors truncate">{{ $user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4">
                                                <span class="px-2 sm:px-3 py-1 text-[9px] sm:text-[10px] font-bold rounded-full uppercase border transition-colors whitespace-nowrap {{ $user->role === 'admin' ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-700/50' : 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-700/50' }}">
                                                    {{ $user->role === 'admin' ? 'Admin' : 'Technician' }}
                                                </span>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 text-right flex justify-end gap-2 sm:gap-3 items-center">
                                                <a href="{{ route('admin.accounts.edit', $user) }}" class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 px-3 sm:px-4 py-2 rounded-md text-[10px] sm:text-xs font-bold transition-all border border-indigo-200 dark:border-indigo-700/50 uppercase tracking-wider shrink-0">Edit</a>
                                                <button onclick="openHistoryModal({{ $user->id }}, '{{ addslashes($user->name) }}')" class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 px-3 sm:px-4 py-2 rounded-md text-[10px] sm:text-xs font-bold transition-all border border-gray-300 dark:border-gray-600 uppercase tracking-wider shrink-0">History</button>
                                                @if(Auth::id() !== $user->id)
                                                    <form action="{{ route('admin.accounts.toggle', $user) }}" method="POST" onsubmit="return confirm('Deactivate account?');" class="inline shrink-0">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 border border-red-200 dark:border-red-700/50 px-3 sm:px-4 py-2 rounded-md text-[10px] sm:text-xs font-bold transition-all uppercase tracking-wider">Deactivate</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-4 sm:px-6 py-12 sm:py-20 text-center text-gray-500 font-bold italic transition-colors text-xs sm:text-sm">No active users found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 transition-colors">
                            {{ $activeUsers->links() }}
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'inactive'" style="display: none;">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 mb-4 transition-colors">

                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full min-w-[700px] text-left">
                                <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700 transition-colors">
                                    <tr class="text-[10px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <th class="px-4 sm:px-6 py-4">User</th>
                                        <th class="px-4 sm:px-6 py-4">Role</th>
                                        <th class="px-4 sm:px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                                    @forelse($inactiveUsers as $user)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors bg-gray-50/50 dark:bg-gray-900/30">
                                            <td class="px-4 sm:px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=4f46e5&color=fff' }}"
                                                         class="w-8 h-8 sm:w-10 sm:h-10 shrink-0 rounded-full object-cover border border-gray-200 dark:border-gray-700 shadow-sm opacity-60" alt="Avatar">
                                                    <div class="min-w-0">
                                                        <a href="{{ route('admin.accounts.performance', $user) }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline text-xs sm:text-sm transition-colors truncate block">{{ $user->name }}</a>
                                                        <div class="text-[10px] sm:text-[11px] text-gray-500 dark:text-gray-500 font-bold uppercase transition-colors truncate">{{ $user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4">
                                                <span class="px-2 sm:px-3 py-1 text-[9px] sm:text-[10px] font-bold rounded-full uppercase border transition-colors whitespace-nowrap {{ $user->role === 'admin' ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-700/50' : 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-700/50' }}">
                                                    {{ $user->role === 'admin' ? 'Admin' : 'Technician' }}
                                                </span>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 text-right flex justify-end gap-2 sm:gap-3 items-center">
                                                <a href="{{ route('admin.accounts.edit', $user) }}" class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 px-3 sm:px-4 py-2 rounded-md text-[10px] sm:text-xs font-bold transition-all border border-indigo-200 dark:border-indigo-700/50 uppercase tracking-wider shrink-0">Edit</a>
                                                <button onclick="openHistoryModal({{ $user->id }}, '{{ addslashes($user->name) }}')" class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 px-3 sm:px-4 py-2 rounded-md text-[10px] sm:text-xs font-bold transition-all border border-gray-300 dark:border-gray-600 uppercase tracking-wider shrink-0">History</button>
                                                <form action="{{ route('admin.accounts.toggle', $user) }}" method="POST" class="inline shrink-0">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/50 border border-green-200 dark:border-green-700/50 px-3 sm:px-4 py-2 rounded-md text-[10px] sm:text-xs font-bold transition-all uppercase tracking-wider">Activate</button>
                                                </form>
                                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.accounts.delete', $user) }}" method="POST" class="inline shrink-0">
                                                    @csrf @method('DELETE')
                                                    <input type="hidden" name="reason" id="reason-{{ $user->id }}">
                                                    <button type="button" onclick="openDeleteUserModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                            class="bg-red-600 text-white hover:bg-red-700 px-3 sm:px-4 py-2 rounded-md text-[10px] sm:text-xs font-bold transition-all shadow-sm uppercase tracking-wider border border-transparent">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-4 sm:px-6 py-12 sm:py-20 text-center text-gray-500 font-bold italic transition-colors text-xs sm:text-sm">No deactivated accounts.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 transition-colors">
                            {{ $inactiveUsers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="historyModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/80 transition-opacity" onclick="closeHistoryModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl sm:rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full sm:p-6 transition-colors w-full">
                <div class="flex justify-between items-center mb-5 border-b border-gray-200 dark:border-gray-700 pb-3 transition-colors">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white transition-colors truncate pr-4">Audit Trail: <span id="userNamePlaceholder" class="text-indigo-600 dark:text-indigo-400"></span></h3>
                    <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-2xl sm:text-3xl font-light transition-colors shrink-0">&times;</button>
                </div>
                <div class="max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    <table class="min-w-full text-xs sm:text-sm text-left">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50 text-[9px] sm:text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest transition-colors">
                                <th class="px-3 sm:px-4 py-3 border-b border-gray-200 dark:border-gray-700 rounded-tl-md">Action</th>
                                <th class="px-3 sm:px-4 py-3 border-b border-gray-200 dark:border-gray-700">Performed By</th>
                                <th class="px-3 sm:px-4 py-3 border-b border-gray-200 dark:border-gray-700 rounded-tr-md">Date</th>
                            </tr>
                        </thead>
                        <tbody id="historyContent" class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteUserModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/80 transition-opacity" onclick="closeDeleteUserModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl sm:rounded-lg text-left overflow-hidden shadow-2xl border border-gray-200 dark:border-gray-700 transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full transition-colors w-full">
                <div class="bg-gray-50 dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 text-center transition-colors">
                    <h3 class="text-lg sm:text-xl font-bold text-red-600 uppercase tracking-widest transition-colors">Critical Warning</h3>
                </div>
                <div class="bg-white dark:bg-gray-800 px-4 pt-4 pb-4 sm:p-6 text-gray-600 dark:text-gray-300 transition-colors text-sm sm:text-base">
                    Purging <span id="deleteUserNamePlaceholder" class="font-bold text-indigo-600 dark:text-indigo-400"></span> cannot be undone. All associated logs will be lost.
                    <div class="mt-4 text-left">
                        <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1 transition-colors">Reason for deletion (Required):</label>
                        <textarea id="reason_text" rows="3" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md text-xs sm:text-sm focus:ring-red-500 focus:border-red-500 placeholder-gray-400 transition-colors" placeholder="Type reason..."></textarea>
                        <p id="delete-error" class="hidden text-red-600 text-[10px] mt-1 font-bold uppercase transition-colors">A reason is required.</p>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 sm:px-6 flex flex-col sm:flex-row-reverse border-t border-gray-200 dark:border-gray-700 transition-colors gap-3 sm:gap-0">
                    <button type="button" onclick="submitCustomDeleteForm()" class="w-full sm:w-auto inline-flex justify-center rounded-md px-6 py-3 sm:py-2 bg-red-600 text-white font-bold text-xs uppercase hover:bg-red-700 transition border border-transparent sm:ml-3 active:scale-95 tracking-widest">Confirm Purge</button>
                    <button type="button" onclick="closeDeleteUserModal()" class="w-full sm:w-auto inline-flex justify-center rounded-md px-6 py-3 sm:py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-xs uppercase hover:bg-gray-50 dark:hover:bg-gray-600 transition border border-gray-300 dark:border-gray-600 active:scale-95 tracking-widest">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchForm').submit();
        }

        function openHistoryModal(userId, userName) {
            document.getElementById('userNamePlaceholder').innerText = userName;
            const content = document.getElementById('historyContent');
            content.innerHTML = '<tr><td colspan="3" class="px-4 py-8 text-center text-gray-500 italic text-xs">Loading...</td></tr>';
            document.getElementById('historyModal').classList.remove('hidden');

            fetch(`/admin/accounts/${userId}/history`)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = '<tr><td colspan="3" class="px-4 py-8 text-center text-gray-500 italic text-xs">No logs found.</td></tr>';
                    } else {
                        data.forEach(log => {
                            const statusClass = log.new_status
                                ? 'text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/40 border-green-200 dark:border-green-700/50'
                                : 'text-red-700 dark:text-red-400 bg-red-100 dark:bg-red-900/40 border-red-200 dark:border-red-700/50';

                            html += `<tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-3 sm:px-4 py-4"><span class="px-2 py-0.5 rounded-md font-bold text-[8px] sm:text-[9px] uppercase border ${statusClass}">${log.new_status ? 'Active' : 'Inactive'}</span></td>
                                <td class="px-3 sm:px-4 py-4">
                                    <div class="font-bold text-gray-900 dark:text-gray-200 text-[10px] sm:text-xs">${log.admin.name}</div>
                                    <div class="text-[8px] sm:text-[9px] text-gray-500 dark:text-gray-500 uppercase font-bold mt-0.5">${log.reason}</div>
                                </td>
                                <td class="px-3 sm:px-4 py-4 text-[10px] sm:text-xs text-gray-600 dark:text-gray-400">${new Date(log.created_at).toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                            </tr>`;
                        });
                    }
                    content.innerHTML = html;
                })
                .catch(() => { content.innerHTML = '<tr><td colspan="3" class="px-4 py-8 text-center text-red-600 font-bold text-xs">Error loading logs.</td></tr>'; });
        }

        function closeHistoryModal() { document.getElementById('historyModal').classList.add('hidden'); }

        let targetUserIdForDelete;
        function openDeleteUserModal(userId, userName) {
            targetUserIdForDelete = userId;
            document.getElementById('deleteUserNamePlaceholder').innerText = userName;
            document.getElementById('reason_text').value = '';
            document.getElementById('delete-error').classList.add('hidden');

            const reasonInput = document.getElementById('reason_text');
            reasonInput.classList.remove('border-red-500');
            const isDark = document.documentElement.classList.contains('dark');
            reasonInput.classList.add(isDark ? 'border-gray-600' : 'border-gray-300');

            document.getElementById('deleteUserModal').classList.remove('hidden');
        }

        function closeDeleteUserModal() { document.getElementById('deleteUserModal').classList.add('hidden'); }

        function submitCustomDeleteForm() {
            const reasonInput = document.getElementById('reason_text');
            const reason = reasonInput.value.trim();
            if (reason === "") {
                document.getElementById('delete-error').classList.remove('hidden');
                reasonInput.classList.remove('border-gray-300', 'border-gray-600');
                reasonInput.classList.add('border-red-500');
                return;
            }
            document.getElementById(`reason-${targetUserIdForDelete}`).value = reason;
            document.getElementById(`delete-form-${targetUserIdForDelete}`).submit();
        }
    </script>
</x-app-layout>
