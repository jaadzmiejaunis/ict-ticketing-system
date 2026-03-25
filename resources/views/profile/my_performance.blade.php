<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-white tracking-tight">Personal Performance Profile</h2>
                    <p class="text-gray-400 text-sm italic">Official metrics for your assigned tasks</p>
                </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-sm mb-6 flex justify-between items-center border border-gray-700">
                <div>
                    <h3 class="text-xl font-bold text-white">{{ $user->name }}</h3>
                    <p class="text-gray-400 text-sm">{{ $user->email }} • Joined {{ $user->created_at->format('d M Y') }}</p>
                </div>
                <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-900/40 text-blue-400 border border-blue-700/50 uppercase shadow-sm">
                    {{ $user->role }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-center">
                <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700 border-b-4 border-b-gray-500">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Total Assigned</span>
                    <div class="text-4xl font-black text-white mt-2 tracking-tighter">{{ $totalAssigned }}</div>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700 border-b-4 border-b-yellow-500">
                    <span class="text-[10px] font-bold text-yellow-500 uppercase tracking-widest block">Pending Tasks</span>
                    <div class="text-4xl font-black text-white mt-2 tracking-tighter">{{ $pendingCount }}</div>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700 border-b-4 border-b-green-500">
                    <span class="text-[10px] font-bold text-green-500 uppercase tracking-widest block">Resolved Tasks</span>
                    <div class="text-4xl font-black text-white mt-2 tracking-tighter">{{ $resolvedCount }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700">
                    <h4 class="text-center font-bold text-gray-300 mb-4 uppercase text-xs tracking-wider">Resolution Status</h4>
                    <div class="h-64"><canvas id="statusChart"></canvas></div>
                </div>

                <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700">
                    <h4 class="text-center font-bold text-gray-300 mb-4 uppercase text-xs tracking-wider">Issue Categories</h4>
                    <div class="h-64"><canvas id="categoryChart"></canvas></div>
                </div>

                <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700">
                    <h4 class="text-center font-bold text-gray-300 mb-4 uppercase text-xs tracking-wider">Priority Levels</h4>
                    <div class="h-64"><canvas id="priorityChart"></canvas></div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-sm border border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gray-900/50 border-b border-gray-700">
                    <h3 class="font-bold text-white text-sm uppercase tracking-tight">Recently Resolved Activity</h3>
                </div>
                <table class="min-w-full text-sm text-left border-collapse">
                    <thead class="bg-gray-800 border-b border-gray-700">
                        <tr>
                            <th class="px-6 py-3 font-bold text-gray-400 uppercase text-[10px] tracking-wider">Ticket Details</th>
                            <th class="px-6 py-3 font-bold text-gray-400 uppercase text-[10px] tracking-wider text-right">Date Resolved</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 font-medium text-gray-300">
                        @forelse($recentTasks as $task)
                            <tr class="hover:bg-gray-700 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white">#{{ $task->id }} - {{ $task->title ?? 'Untitled Ticket' }}</div>
                                    <div class="text-[10px] text-gray-500 mt-1 uppercase tracking-wide">
                                        Category: <span class="text-indigo-400">{{ $task->category }}</span> |
                                        Priority: <span class="{{ $task->priority == 'High' ? 'text-red-400' : ($task->priority == 'Medium' ? 'text-yellow-400' : 'text-green-400') }}">{{ $task->priority }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-400 font-bold text-xs uppercase">
                                    {{ $task->updated_at->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-10 text-center text-gray-500 italic font-medium">No recently resolved tasks recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        // Set global Chart.js text color for dark mode
        Chart.defaults.color = '#9ca3af';

        const chartData = @json($chartData);

        // 1. Status Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: ['Open', 'Assigned', 'On Hold', 'Resolved'],
                datasets: [{
                    data: [chartData.status['Open'], chartData.status['Assigned'], chartData.status['On Hold'], chartData.status['Resolved']],
                    backgroundColor: ['#22c55e', '#3b82f6', '#eab308', '#6b7280'],
                    borderColor: '#1f2937', // Match bg-gray-800
                    borderWidth: 2
                }]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });

        // 2. Category Chart
        new Chart(document.getElementById('categoryChart'), {
            type: 'bar',
            data: {
                labels: ['Hardware', 'Software', 'Network'],
                datasets: [{
                    label: 'Tickets',
                    data: [chartData.categories['Hardware'], chartData.categories['Software'], chartData.categories['Network']],
                    backgroundColor: ['#ef4444', '#3b82f6', '#eab308'],
                    borderColor: '#1f2937',
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#374151' }, beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 3. Priority Chart
        new Chart(document.getElementById('priorityChart'), {
            type: 'doughnut',
            data: {
                labels: ['High', 'Medium', 'Low'],
                datasets: [{
                    data: [chartData.priorities['High'], chartData.priorities['Medium'], chartData.priorities['Low']],
                    backgroundColor: ['#ef4444', '#f59e0b', '#22c55e'],
                    borderColor: '#1f2937',
                    borderWidth: 2
                }]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { position: 'top' } } }
        });
    </script>
</x-app-layout>
