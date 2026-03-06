<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-white tracking-tight">User Account Management</h2>
                    <p class="text-gray-400 text-sm">View, edit, and manage system access for all users.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.accounts.create') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-md hover:bg-indigo-700 font-bold transition text-sm shadow-md flex items-center uppercase tracking-wider active:scale-95">
                        + Add New User
                    </a>
                    <a href="{{ route('admin.accounts.deletions') }}" class="bg-red-600 text-white px-5 py-2.5 rounded-md hover:bg-red-700 font-bold transition text-sm shadow-md flex items-center gap-2 uppercase tracking-wider active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Deletion Logs
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 font-bold transition text-sm shadow-md flex items-center uppercase tracking-wider active:scale-95">
                        &larr; Back
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="mb-6 flex items-center justify-between p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <p class="text-sm font-bold text-green-800 uppercase tracking-wide">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-500 hover:text-green-700 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
            @endif

            <div class="bg-white p-4 rounded-lg shadow-sm mb-8 border border-gray-100">
                <form method="GET" action="{{ route('admin.accounts') }}" id="searchForm" class="flex flex-wrap md:flex-nowrap gap-4 items-end">
                    <div class="flex-grow">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Search Users</label>
                        <div class="relative">
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Search by name or email..."
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            @if(request('search'))
                                <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-900 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="w-full md:w-40">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sort By</label>
                        <select name="sort" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md shadow-sm text-sm font-medium">
                            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Recent</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Name (Z-A)</option>
                        </select>
                    </div>

                    <div class="w-full md:w-48">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Role</label>
                        <select name="role" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md shadow-sm text-sm font-medium">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2 rounded-md font-bold transition h-[42px] text-sm uppercase shadow-sm active:scale-95">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <div x-data="{ tab: sessionStorage.getItem('adminAccountsTab') || 'active' }">
                <div class="flex space-x-2 mb-6 border-b border-gray-700 pb-2">
                    <button @click="tab = 'active'; sessionStorage.setItem('adminAccountsTab', 'active')"
                            :class="tab === 'active' ? 'bg-indigo-600 text-white shadow-md' : 'bg-gray-800 text-gray-400 hover:bg-gray-700'"
                            class="px-5 py-2 rounded-md text-sm font-bold transition uppercase tracking-wider flex items-center gap-2">
                        Active Accounts <span class="bg-indigo-500/50 text-white py-0.5 px-2 rounded-full text-[10px]">{{ $activeUsers->total() }}</span>
                    </button>

                    <button @click="tab = 'inactive'; sessionStorage.setItem('adminAccountsTab', 'inactive')"
                            :class="tab === 'inactive' ? 'bg-red-600 text-white shadow-md' : 'bg-gray-800 text-gray-400 hover:bg-gray-700'"
                            class="px-5 py-2 rounded-md text-sm font-bold transition uppercase tracking-wider flex items-center gap-2">
                        Deactivated <span :class="tab === 'inactive' ? 'bg-red-500/50' : 'bg-gray-700'" class="text-white py-0.5 px-2 rounded-full text-[10px] transition">{{ $inactiveUsers->total() }}</span>
                    </button>
                </div>

                <div x-show="tab === 'active'">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200 mb-4">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    <th class="px-6 py-4">User Details</th>
                                    <th class="px-6 py-4">Role</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($activeUsers as $user)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('admin.accounts.performance', $user) }}" class="font-bold text-indigo-600 hover:underline text-sm">
                                                {{ $user->name }}
                                            </a>
                                            <div class="text-[11px] text-gray-400 font-bold uppercase">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right flex justify-end gap-3 items-center">
                                            <a href="{{ route('admin.accounts.edit', $user) }}" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-4 py-2 rounded-md text-xs font-bold transition border border-indigo-200 uppercase tracking-wider">Edit</a>
                                            <button onclick="openHistoryModal({{ $user->id }}, '{{ $user->name }}')" class="bg-gray-100 text-gray-600 hover:bg-gray-200 px-4 py-2 rounded-md text-xs font-bold transition border border-gray-300 uppercase tracking-wider">History</button>
                                            @if(Auth::id() !== $user->id)
                                                <form action="{{ route('admin.accounts.toggle', $user) }}" method="POST" onsubmit="return confirm('Deactivate account?');">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 px-4 py-2 rounded-md text-xs font-bold transition uppercase tracking-wider">Deactivate</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-20 text-center text-gray-400 font-bold italic">No active users found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div>{{ $activeUsers->links() }}</div>
                </div>

                <div x-show="tab === 'inactive'" style="display: none;">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200 mb-4">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    <th class="px-6 py-4">User</th>
                                    <th class="px-6 py-4">Role</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($inactiveUsers as $user)
                                    <tr class="hover:bg-gray-50 transition bg-gray-50/30">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('admin.accounts.performance', $user) }}" class="font-bold text-indigo-600 hover:underline text-sm">{{ $user->name }}</a>
                                            <div class="text-[11px] text-gray-400 font-bold uppercase">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right flex justify-end gap-3 items-center">
                                            <a href="{{ route('admin.accounts.edit', $user) }}" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-4 py-2 rounded-md text-xs font-bold transition border border-indigo-200 uppercase tracking-wider">Edit</a>
                                            <button onclick="openHistoryModal({{ $user->id }}, '{{ $user->name }}')" class="bg-gray-100 text-gray-600 hover:bg-gray-200 px-4 py-2 rounded-md text-xs font-bold transition border border-gray-300 uppercase tracking-wider">History</button>
                                            <form action="{{ route('admin.accounts.toggle', $user) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="bg-green-50 text-green-600 hover:bg-green-100 border border-green-200 px-4 py-2 rounded-md text-xs font-bold transition uppercase tracking-wider">Activate</button>
                                            </form>
                                            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.accounts.delete', $user) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <input type="hidden" name="reason" id="reason-{{ $user->id }}">
                                                <button type="button" onclick="openDeleteUserModal({{ $user->id }}, '{{ $user->name }}')"
                                                        class="bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-md text-xs font-bold transition shadow-sm uppercase tracking-wider">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-20 text-center text-gray-400 font-bold italic">No deactivated accounts.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div>{{ $inactiveUsers->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div id="historyModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeHistoryModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full sm:p-6">
                <div class="flex justify-between items-center mb-5 border-b pb-3">
                    <h3 class="text-xl font-bold text-gray-900">Audit Trail: <span id="userNamePlaceholder" class="text-indigo-600"></span></h3>
                    <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-600 text-3xl font-light">&times;</button>
                </div>
                <div class="max-h-[400px] overflow-y-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="bg-gray-50 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                <th class="px-4 py-3 border-b">Action</th>
                                <th class="px-4 py-3 border-b">Performed By</th>
                                <th class="px-4 py-3 border-b">Date</th>
                            </tr>
                        </thead>
                        <tbody id="historyContent" class="divide-y divide-gray-100"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-80 transition-opacity" onclick="closeDeleteUserModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b text-center">
                    <h3 class="text-xl font-bold text-red-600 uppercase tracking-widest">Critical Warning</h3>
                </div>
                <div class="bg-white px-4 pt-4 pb-4 sm:p-6 text-gray-700">
                    Purging <span id="deleteUserNamePlaceholder" class="font-bold text-indigo-600"></span> cannot be undone. All associated logs will be lost.
                    <div class="mt-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Reason for deletion (Required):</label>
                        <textarea id="reason_text" rows="3" class="w-full rounded-md border-gray-300 text-sm focus:ring-red-500 focus:border-red-500" placeholder="Type reason..."></textarea>
                        <p id="delete-error" class="hidden text-red-600 text-[10px] mt-1 font-bold uppercase">A reason is required.</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                    <button type="button" onclick="submitCustomDeleteForm()" class="w-full inline-flex justify-center rounded-md px-6 py-2 bg-red-600 text-white font-bold text-xs uppercase hover:bg-red-700 transition sm:ml-3 sm:w-auto active:scale-95">Confirm Purge</button>
                    <button type="button" onclick="closeDeleteUserModal()" class="mt-3 w-full inline-flex justify-center rounded-md px-6 py-2 bg-white text-gray-700 font-bold text-xs uppercase hover:bg-gray-50 transition border sm:mt-0 sm:w-auto active:scale-95">Cancel</button>
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
            content.innerHTML = '<tr><td colspan="3" class="px-4 py-8 text-center text-gray-400 italic">Loading...</td></tr>';
            document.getElementById('historyModal').classList.remove('hidden');

            fetch(`/admin/accounts/${userId}/history`)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = '<tr><td colspan="3" class="px-4 py-8 text-center text-gray-400 italic">No logs found.</td></tr>';
                    } else {
                        data.forEach(log => {
                            const statusColor = log.new_status ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50';
                            html += `<tr class="hover:bg-gray-50/50">
                                <td class="px-4 py-4"><span class="px-2 py-0.5 rounded-md font-bold text-[9px] uppercase ${statusColor}">${log.new_status ? 'Active' : 'Inactive'}</span></td>
                                <td class="px-4 py-4"><div class="font-bold text-gray-900 text-xs">${log.admin.name}</div><div class="text-[9px] text-gray-400 uppercase font-bold">${log.reason}</div></td>
                                <td class="px-4 py-4 text-xs text-gray-500">${new Date(log.created_at).toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                            </tr>`;
                        });
                    }
                    content.innerHTML = html;
                })
                .catch(() => { content.innerHTML = '<tr><td colspan="3" class="px-4 py-8 text-center text-red-500">Error loading logs.</td></tr>'; });
        }

        function closeHistoryModal() { document.getElementById('historyModal').classList.add('hidden'); }

        let targetUserIdForDelete;
        function openDeleteUserModal(userId, userName) {
            targetUserIdForDelete = userId;
            document.getElementById('deleteUserNamePlaceholder').innerText = userName;
            document.getElementById('reason_text').value = '';
            document.getElementById('delete-error').classList.add('hidden');
            document.getElementById('deleteUserModal').classList.remove('hidden');
        }

        function closeDeleteUserModal() { document.getElementById('deleteUserModal').classList.add('hidden'); }

        function submitCustomDeleteForm() {
            const reasonInput = document.getElementById('reason_text');
            const reason = reasonInput.value.trim();
            if (reason === "") {
                document.getElementById('delete-error').classList.remove('hidden');
                reasonInput.classList.add('border-red-500');
                return;
            }
            document.getElementById(`reason-${targetUserIdForDelete}`).value = reason;
            document.getElementById(`delete-form-${targetUserIdForDelete}`).submit();
        }
    </script>
</x-app-layout>
