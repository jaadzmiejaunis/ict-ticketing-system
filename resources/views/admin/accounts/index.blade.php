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
                    <a href="{{ route('admin.accounts.deletions') }}" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 font-bold transition text-sm shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        View Deletion Logs
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

            <div class="bg-gray-800 p-4 rounded-lg mb-6 shadow-sm border border-gray-700">
                <form method="GET" action="{{ route('admin.accounts') }}" id="searchForm" class="flex flex-col lg:flex-row gap-4 items-end">

                    <div class="w-full lg:flex-1 flex flex-col">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Search Users</label>
                        <div class="relative">
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Search by name or email..."
                                   class="rounded-md border-gray-600 bg-gray-700 text-white placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full text-sm pr-10">
                            @if(request('search'))
                                <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="w-full lg:w-40 flex flex-col">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Sort By</label>
                        <select name="sort" onchange="this.form.submit()" class="rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full text-sm">
                            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Recent</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Name (Z-A)</option>
                        </select>
                    </div>

                    <div class="w-full lg:w-48 flex flex-col">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Filter by Role</label>
                        <select name="role" onchange="this.form.submit()" class="rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full text-sm">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins Only</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff Only</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full lg:w-auto bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 font-bold transition text-sm flex items-center justify-center gap-2 h-[38px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Search
                    </button>
                </form>
            </div>

            <div x-data="{ tab: sessionStorage.getItem('adminAccountsTab') || 'active' }">
                <div class="flex space-x-2 mb-6 border-b border-gray-700 pb-2">
                    <button @click="tab = 'active'; sessionStorage.setItem('adminAccountsTab', 'active')"
                            :class="tab === 'active' ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-gray-200'"
                            class="px-4 py-2 rounded-md text-sm font-bold transition duration-150 flex items-center gap-2">
                        Active Accounts <span class="bg-indigo-500/50 text-white py-0.5 px-2 rounded-full text-xs">{{ $activeUsers->total() }}</span>
                    </button>

                    <button @click="tab = 'inactive'; sessionStorage.setItem('adminAccountsTab', 'inactive')"
                            :class="tab === 'inactive' ? 'bg-red-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-gray-200'"
                            class="px-4 py-2 rounded-md text-sm font-bold transition duration-150 flex items-center gap-2">
                        Deactivated <span :class="tab === 'inactive' ? 'bg-red-500/50' : 'bg-gray-700'" class="text-white py-0.5 px-2 rounded-full text-xs transition">{{ $inactiveUsers->total() }}</span>
                    </button>
                </div>

                <div x-show="tab === 'active'">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
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
                                            <a href="{{ route('admin.accounts.performance', $user) }}" class="font-bold text-indigo-600 hover:text-indigo-800 hover:underline transition">
                                                {{ $user->name }}
                                            </a>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-bold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right flex justify-end gap-2 items-center">
                                            <a href="{{ route('admin.accounts.edit', $user) }}" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-3 py-1.5 rounded text-sm font-bold transition border border-indigo-200">
                                                Edit
                                            </a>

                                            <button onclick="openPerformanceModal({{ $user->id }}, '{{ $user->name }}')" class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded text-sm font-bold transition border border-blue-200">
                                                Stats
                                            </button>

                                            <button onclick="openHistoryModal({{ $user->id }}, '{{ $user->name }}')" class="bg-gray-100 text-gray-600 hover:bg-gray-200 px-3 py-1.5 rounded text-sm font-bold transition border border-gray-300">
                                                History
                                            </button>

                                            @if(Auth::id() !== $user->id)
                                                <form action="{{ route('admin.accounts.toggle', $user) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Deactivate {{ $user->name }}\'s account?');">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 px-3 py-1.5 rounded text-sm font-bold transition">
                                                        Deactivate
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">No active users found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div>{{ $activeUsers->links() }}</div>
                </div>

                <div x-show="tab === 'inactive'" style="display: none;">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden opacity-90 mb-4">
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
                                            <a href="{{ route('admin.accounts.performance', $user) }}" class="font-bold text-indigo-600 hover:text-indigo-800 hover:underline transition">
                                                {{ $user->name }}
                                            </a>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-bold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right flex justify-end gap-2 items-center">
                                            <a href="{{ route('admin.accounts.edit', $user) }}" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-3 py-1.5 rounded text-sm font-bold transition border border-indigo-200">Edit</a>
                                            <button onclick="openHistoryModal({{ $user->id }}, '{{ $user->name }}')" class="bg-gray-100 text-gray-600 hover:bg-gray-200 px-3 py-1.5 rounded text-sm font-bold transition border border-gray-300">History</button>

                                            <form action="{{ route('admin.accounts.toggle', $user) }}" method="POST" class="inline m-0 p-0">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="bg-green-50 text-green-600 hover:bg-green-100 border border-green-200 px-3 py-1.5 rounded text-sm font-bold transition">Activate</button>
                                            </form>

                                            @if(Auth::id() !== $user->id)
                                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.accounts.delete', $user) }}" method="POST" class="inline m-0 p-0">
                                                    @csrf @method('DELETE')
                                                    <input type="hidden" name="reason" id="reason-{{ $user->id }}">
                                                    <button type="button" onclick="openDeleteUserModal({{ $user->id }}, '{{ $user->name }}')"
                                                            class="bg-red-600 text-white hover:bg-red-700 border border-red-700 px-3 py-1.5 rounded text-sm font-bold transition shadow-sm">Delete</button>
                                                </form>
                                            @else
                                                <span class="px-3 py-1.5 rounded text-sm font-bold text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed">Delete</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">No deactivated accounts.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div>{{ $inactiveUsers->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div id="historyModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeHistoryModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full sm:p-6">
                <div class="flex justify-between items-center mb-5 border-b pb-3">
                    <h3 class="text-xl leading-6 font-bold text-gray-900">Audit Trail: <span id="userNamePlaceholder" class="text-indigo-600"></span></h3>
                    <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-600 text-3xl font-light">&times;</button>
                </div>
                <div class="mt-4 max-h-[400px] overflow-y-auto">
                    <table class="min-w-full border-collapse text-sm text-left">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider">
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

    <div id="performanceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closePerformanceModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-gray-50 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6 border border-gray-200">
                <div class="flex justify-between items-center mb-5 border-b border-gray-200 pb-3">
                    <h3 class="text-xl leading-6 font-bold text-gray-900">
                        Performance Stats: <span id="perfUserNamePlaceholder" class="text-blue-600"></span>
                    </h3>
                    <button onclick="closePerformanceModal()" class="text-gray-400 hover:text-gray-600 text-3xl font-light">&times;</button>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wide">Total Assigned</div>
                        <div class="text-3xl font-bold text-gray-900 mt-1" id="perfTotal">-</div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center border-b-4 border-b-yellow-400">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wide">Pending</div>
                        <div class="text-3xl font-bold text-yellow-600 mt-1" id="perfPending">-</div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center border-b-4 border-b-gray-400">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wide">Resolved</div>
                        <div class="text-3xl font-bold text-gray-600 mt-1" id="perfResolved">-</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-100 border-b border-gray-200 font-bold text-sm text-gray-700">
                        Recently Resolved Tasks
                    </div>
                    <div class="max-h-[250px] overflow-y-auto">
                        <table class="min-w-full text-sm text-left border-collapse">
                            <tbody id="perfTasksContent" class="divide-y divide-gray-100">
                                </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-5 sm:mt-6">
                    <button type="button" onclick="closePerformanceModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 transition sm:text-sm">
                        Close Dashboard
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-80 transition-opacity" aria-hidden="true" onclick="closeDeleteUserModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl leading-6 font-bold text-red-600">CRITICAL WARNING</h3>
                        <button onclick="closeDeleteUserModal()" class="text-gray-400 hover:text-gray-600 text-3xl font-light">&times;</button>
                    </div>
                </div>
                <div class="bg-white px-4 pt-4 pb-4 sm:p-6 text-gray-700">
                    Purging <span id="deleteUserNamePlaceholder" class="font-bold text-indigo-600"></span> cannot be undone.
                    <div class="mt-4">
                        <label for="reason_text" class="block text-sm font-bold text-gray-700 mb-1">Reason for deletion (Required):</label>
                        <textarea id="reason_text" rows="3" class="w-full rounded-md border border-gray-300 bg-white text-gray-900 text-sm focus:ring-indigo-500" placeholder="Type reason..."></textarea>
                        <p id="delete-error" class="hidden text-red-500 text-xs mt-1 font-bold">A reason is required.</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                    <button type="button" onclick="submitCustomDeleteForm()" class="w-full inline-flex justify-center rounded-md px-4 py-2 bg-red-600 text-white font-medium hover:bg-red-700 sm:ml-3 sm:w-auto text-sm transition">Confirm Deletion</button>
                    <button type="button" onclick="closeDeleteUserModal()" class="mt-3 w-full inline-flex justify-center rounded-md px-4 py-2 bg-white text-gray-700 font-medium hover:bg-gray-50 sm:mt-0 sm:w-auto text-sm transition">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search & Filter Logic
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchForm').submit();
        }

        // History Modal Logic
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
                                <td class="px-4 py-4"><span class="px-2 py-1 rounded-md font-bold text-xs uppercase ${statusColor}">${log.new_status ? 'Active' : 'Inactive'}</span></td>
                                <td class="px-4 py-4"><div class="font-semibold text-gray-900">${log.admin.name}</div><div class="text-[10px] text-gray-400 uppercase font-bold">${log.reason}</div></td>
                                <td class="px-4 py-4 text-gray-500">${new Date(log.created_at).toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                            </tr>`;
                        });
                    }
                    content.innerHTML = html;
                })
                .catch(() => { content.innerHTML = '<tr><td colspan="3" class="px-4 py-8 text-center text-red-500">Error loading logs.</td></tr>'; });
        }

        function closeHistoryModal() { document.getElementById('historyModal').classList.add('hidden'); }

        // Custom Delete Logic
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

        // PERFORMANCE MODAL LOGIC
        function openPerformanceModal(userId, userName) {
            document.getElementById('perfUserNamePlaceholder').innerText = userName;

            // Reset numbers to loading state
            document.getElementById('perfTotal').innerText = '...';
            document.getElementById('perfPending').innerText = '...';
            document.getElementById('perfResolved').innerText = '...';

            const content = document.getElementById('perfTasksContent');
            content.innerHTML = '<tr><td class="px-4 py-6 text-center text-gray-400 italic">Loading tasks...</td></tr>';

            document.getElementById('performanceModal').classList.remove('hidden');

            fetch(`/admin/accounts/${userId}/performance`)
                .then(response => response.json())
                .then(data => {
                    // Update Dashboard Cards
                    document.getElementById('perfTotal').innerText = data.stats.total;
                    document.getElementById('perfPending').innerText = data.stats.pending;
                    document.getElementById('perfResolved').innerText = data.stats.resolved;

                    // Update Recent Tasks Table
                    let html = '';
                    if (data.recentTasks.length === 0) {
                        html = '<tr><td class="px-4 py-6 text-center text-gray-400 italic">No resolved tasks found for this user.</td></tr>';
                    } else {
                        data.recentTasks.forEach(task => {
                            html += `
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-gray-900">Task #${task.id} - ${task.title}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">Resolved on: ${new Date(task.updated_at).toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-[10px] font-bold uppercase border border-gray-200">Resolved</span>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    content.innerHTML = html;
                })
                .catch(error => {
                    content.innerHTML = '<tr><td class="px-4 py-6 text-center text-red-500 font-bold">Failed to load performance data.</td></tr>';
                });
        }

        function closePerformanceModal() {
            document.getElementById('performanceModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
