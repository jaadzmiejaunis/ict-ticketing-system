<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6 bg-[#111827] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-white uppercase tracking-tight">Personal Performance Profile</h2>
                <p class="text-gray-400 text-sm">Official metrics for your assigned tasks</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 flex justify-between items-center border border-gray-200">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-gray-500 text-sm">{{ $user->email }} • Joined {{ $user->created_at->format('d M Y') }}</p>
                </div>
                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ ucfirst($user->role) }}
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

            <div class="space-y-6 mb-6">
                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
                    <h4 class="text-center font-bold text-gray-700 mb-8 uppercase text-xs tracking-widest">Resolution Status</h4>
                    <div class="h-80 w-full relative flex justify-center">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
                    <h4 class="text-center font-bold text-gray-700 mb-8 uppercase text-xs tracking-widest">Issue Categories</h4>
                    <div class="h-64 w-full relative">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
                    <h4 class="text-center font-bold text-gray-700 mb-8 uppercase text-xs tracking-widest">Priority Levels</h4>
                    <div class="h-80 w-full relative flex justify-center">
                        <canvas id="priorityChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-tight">Recently Resolved Activity</h3>
                </div>
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-white border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 font-bold text-gray-400 uppercase text-[10px] tracking-wider">Ticket Details</th>
                            <th class="px-6 py-3 font-bold text-gray-400 uppercase text-[10px] tracking-wider text-right">Date Resolved</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($recentTasks as $task)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">#{{ $task->id }} - {{ $task->title ?? 'Untitled Ticket' }}</div>
                                    <div class="text-[10px] text-gray-500 mt-1 uppercase tracking-wide">Category: {{ $task->category }} | Priority: {{ $task->priority }}</div>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-600 font-bold text-xs uppercase">
                                    {{ $task->updated_at->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-6 py-10 text-center text-gray-400 italic">No recently resolved tasks recorded.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        window.onload = function() {
            const chartData = @json($chartData);

            const options = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 25, font: { weight: 'bold', size: 11 } }
                    }
                }
            };

            // Status Chart (Pie)
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'pie',
                data: {
                    labels: ['Open', 'Assigned', 'On Hold', 'Resolved'],
                    datasets: [{
                        data: [chartData.status.Open, chartData.status.Assigned, chartData.status.On Hold, chartData.status.Resolved],
                        backgroundColor: ['#2ecc71', '#3498db', '#f1c40f', '#95a5a6'],
                        borderWidth: 0
                    }]
                },
                options: options
            });

            // Category Chart (Bar)
            const ctxCategory = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctxCategory, {
                type: 'bar',
                data: {
                    labels: ['Hardware', 'Software', 'Network'],
                    datasets: [{
                        data: [chartData.categories.Hardware, chartData.categories.Software, chartData.categories.Network],
                        backgroundColor: ['#e74c3c', '#3498db', '#f1c40f'],
                        borderRadius: 6
                    }]
                },
                options: {
                    ...options,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { display: false }, ticks: { stepSize: 1 } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Priority Chart (Doughnut)
            const ctxPriority = document.getElementById('priorityChart').getContext('2d');
            new Chart(ctxPriority, {
                type: 'doughnut',
                data: {
                    labels: ['High', 'Medium', 'Low'],
                    datasets: [{
                        data: [chartData.priorities.High, chartData.priorities.Medium, chartData.priorities.Low],
                        backgroundColor: ['#e74c3c', '#f39c12', '#2ecc71'],
                        borderWidth: 0,
                        cutout: '75%'
                    }]
                },
                options: options
            });
        };
    </script>
</x-app-layout>
