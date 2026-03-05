<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-white">Staff Performance Profile</h2>
                    <p class="text-gray-400 text-sm">Viewing task statistics for {{ $user->name }}</p>
                </div>
                <a href="{{ route('admin.accounts') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 font-semibold transition text-sm flex items-center shadow-sm">
                    &larr; Back to Accounts
                </a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 flex justify-between items-center border border-gray-200">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-gray-500 text-sm">{{ $user->email }} • Joined {{ $user->created_at->format('d M Y') }}</p>
                </div>
                <span class="px-3 py-1 text-sm font-bold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-center">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wide">Total Assigned</div>
                    <div class="text-4xl font-black text-gray-900 mt-2">{{ $totalAssigned }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-center border-b-4 border-b-yellow-400">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wide">Pending Tasks</div>
                    <div class="text-4xl font-black text-yellow-600 mt-2">{{ $pendingCount }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-center border-b-4 border-b-green-500">
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wide">Resolved Tasks</div>
                    <div class="text-4xl font-black text-green-600 mt-2">{{ $resolvedCount }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h4 class="text-center font-bold text-gray-700 mb-4 uppercase text-xs tracking-wider">Resolution Status</h4>
                    <div class="h-64"><canvas id="statusChart"></canvas></div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h4 class="text-center font-bold text-gray-700 mb-4 uppercase text-xs tracking-wider">Issue Categories</h4>
                    <div class="h-64"><canvas id="categoryChart"></canvas></div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h4 class="text-center font-bold text-gray-700 mb-4 uppercase text-xs tracking-wider">Priority Levels</h4>
                    <div class="h-64"><canvas id="priorityChart"></canvas></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="font-bold text-gray-800">Recently Resolved by {{ $user->name }}</h3>
                    </div>
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-white border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 font-bold text-gray-500 uppercase text-xs">Ticket Details</th>
                                <th class="px-6 py-3 font-bold text-gray-500 uppercase text-xs text-right">Date Resolved</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentTasks as $task)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">#{{ $task->id }} - {{ $task->title ?? 'Ticket Name' }}</div>
                                        <div class="text-xs text-gray-500 mt-1">Category: {{ $task->category }} | Priority: {{ $task->priority }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-gray-600 font-medium">
                                        {{ $task->updated_at->format('d M Y, h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="px-6 py-8 text-center text-gray-500 italic">No resolved tasks found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-indigo-600 rounded-lg shadow-sm p-6 text-white flex flex-col justify-center">
                    <div class="mb-4">
                        <svg class="w-10 h-10 text-indigo-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <h4 class="font-bold text-lg">Performance Tip</h4>
                    </div>
                    <p class="text-indigo-100 text-sm italic">
                        "Great job! You have resolved {{ $resolvedCount }} tickets. Keep up the efficiency to maintain a high resolution rate!"
                    </p>
                </div>
            </div>

        </div>
    </div>

    <script>
        const chartData = @json($chartData);

        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: ['Open', 'Assigned', 'On Hold', 'Resolved'],
                datasets: [{
                    data: [chartData.status['Open'], chartData.status['Assigned'], chartData.status['On Hold'], chartData.status['Resolved']],
                    backgroundColor: ['#22c55e', '#3b82f6', '#eab308', '#9ca3af']
                }]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });

        new Chart(document.getElementById('categoryChart'), {
            type: 'bar',
            data: {
                labels: ['Hardware', 'Software', 'Network'],
                datasets: [{
                    label: 'Tickets',
                    data: [chartData.categories['Hardware'], chartData.categories['Software'], chartData.categories['Network']],
                    backgroundColor: ['#ef4444', '#3b82f6', '#eab308']
                }]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        new Chart(document.getElementById('priorityChart'), {
            type: 'doughnut',
            data: {
                labels: ['High', 'Medium', 'Low'],
                datasets: [{
                    data: [chartData.priorities['High'], chartData.priorities['Medium'], chartData.priorities['Low']],
                    backgroundColor: ['#ef4444', '#f59e0b', '#22c55e']
                }]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { position: 'top' } } }
        });
    </script>
</x-app-layout>
