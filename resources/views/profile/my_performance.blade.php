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

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 flex justify-between items-center border border-gray-200">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-gray-500 text-sm">{{ $user->email }} • Joined {{ $user->created_at->format('d M Y') }}</p>
                </div>
                <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 uppercase shadow-sm">
                    {{ $user->role }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-center">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Total Assigned</span>
                    <div class="text-4xl font-black text-gray-900 mt-2 tracking-tighter">{{ $totalAssigned }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 border-b-4 border-b-yellow-400">
                    <span class="text-[10px] font-bold text-yellow-600 uppercase tracking-widest block">Pending Tasks</span>
                    <div class="text-4xl font-black text-gray-900 mt-2 tracking-tighter">{{ $pendingCount }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 border-b-4 border-b-green-500">
                    <span class="text-[10px] font-bold text-green-600 uppercase tracking-widest block">Resolved Tasks</span>
                    <div class="text-4xl font-black text-gray-900 mt-2 tracking-tighter">{{ $resolvedCount }}</div>
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

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-tight">Recently Resolved Activity</h3>
                </div>
                <table class="min-w-full text-sm text-left border-collapse">
                    <thead class="bg-white border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 font-bold text-gray-400 uppercase text-[10px] tracking-wider">Ticket Details</th>
                            <th class="px-6 py-3 font-bold text-gray-400 uppercase text-[10px] tracking-wider text-right">Date Resolved</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium text-gray-900">
                        @forelse($recentTasks as $task)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold">#{{ $task->id }} - {{ $task->title ?? 'Untitled Ticket' }}</div>
                                    <div class="text-[10px] text-gray-500 mt-1 uppercase tracking-wide">Category: {{ $task->category }} | Priority: {{ $task->priority }}</div>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-600 font-bold text-xs uppercase">
                                    {{ $task->updated_at->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-10 text-center text-gray-400 italic font-medium">No recently resolved tasks recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
