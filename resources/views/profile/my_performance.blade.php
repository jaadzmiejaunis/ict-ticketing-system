<x-app-layout>
    @section('title', 'My Performance')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight transition-colors">Personal Performance Profile</h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1 transition-colors">Official metrics for your assigned tasks</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-6 flex justify-between items-center border border-gray-200 dark:border-gray-700 transition-colors">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white transition-colors">{{ $user->name }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm transition-colors">{{ $user->email }} • Joined {{ $user->created_at->format('d M Y') }}</p>
                </div>
                <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 border border-blue-300 dark:border-blue-700/50 uppercase shadow-sm transition-colors">
                    {{ $user->role }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 border-b-4 border-b-gray-400 dark:border-b-gray-500 transition-colors">
                    <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest block transition-colors">Total Assigned</span>
                    <div class="text-4xl font-black text-gray-900 dark:text-white mt-2 tracking-tighter transition-colors">{{ $totalAssigned }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 border-b-4 border-b-yellow-500 transition-colors">
                    <span class="text-[10px] font-bold text-yellow-600 dark:text-yellow-500 uppercase tracking-widest block transition-colors">Pending Tasks</span>
                    <div class="text-4xl font-black text-gray-900 dark:text-white mt-2 tracking-tighter transition-colors">{{ $pendingCount }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 border-b-4 border-b-green-500 transition-colors">
                    <span class="text-[10px] font-bold text-green-600 dark:text-green-500 uppercase tracking-widest block transition-colors">Resolved Tasks</span>
                    <div class="text-4xl font-black text-gray-900 dark:text-white mt-2 tracking-tighter transition-colors">{{ $resolvedCount }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors">
                    <h4 class="text-center font-bold text-gray-600 dark:text-gray-300 mb-4 uppercase text-xs tracking-wider transition-colors">Resolution Status</h4>
                    <div class="h-64"><canvas id="statusChart"></canvas></div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors">
                    <h4 class="text-center font-bold text-gray-600 dark:text-gray-300 mb-4 uppercase text-xs tracking-wider transition-colors">Issue Categories</h4>
                    <div class="h-64"><canvas id="categoryChart"></canvas></div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors">
                    <h4 class="text-center font-bold text-gray-600 dark:text-gray-300 mb-4 uppercase text-xs tracking-wider transition-colors">Priority Levels</h4>
                    <div class="h-64"><canvas id="priorityChart"></canvas></div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6 transition-colors">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700 transition-colors">
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm uppercase tracking-tight transition-colors">Recently Resolved Activity</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border-collapse">
                        <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors">
                            <tr>
                                <th class="px-6 py-3 font-bold text-gray-500 dark:text-gray-400 uppercase text-[10px] tracking-wider transition-colors">Ticket Details</th>
                                <th class="px-6 py-3 font-bold text-gray-500 dark:text-gray-400 uppercase text-[10px] tracking-wider text-right transition-colors">Date Resolved</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 font-medium text-gray-600 dark:text-gray-300 transition-colors">
                            @forelse($recentTasks as $task)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 dark:text-white transition-colors">#{{ $task->id }} - {{ $task->title ?? 'Untitled Ticket' }}</div>
                                        <div class="text-[10px] text-gray-500 dark:text-gray-500 mt-1 uppercase tracking-wide transition-colors">
                                            Category: <span class="text-indigo-600 dark:text-indigo-400">{{ $task->category }}</span> |
                                            Priority: <span class="{{ $task->priority == 'High' ? 'text-red-500 dark:text-red-400' : ($task->priority == 'Medium' ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">{{ $task->priority }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-gray-500 dark:text-gray-400 font-bold text-xs uppercase transition-colors">
                                        {{ $task->updated_at->format('d M Y, h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-10 text-center text-gray-500 italic font-medium transition-colors">No recently resolved tasks recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Check current theme to set initial Chart colors
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#9ca3af' : '#4b5563'; // gray-400 vs gray-600
        const gridColor = isDark ? '#374151' : '#e5e7eb'; // gray-700 vs gray-200
        const borderColor = isDark ? '#1f2937' : '#ffffff'; // gray-800 vs white

        Chart.defaults.color = textColor;
        Chart.defaults.font.family = "'Instrument Sans', sans-serif";

        const chartData = @json($chartData);

        // 1. Status Chart
        const statusChart = new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: ['Open', 'Assigned', 'On Hold', 'Resolved'],
                datasets: [{
                    data: [chartData.status['Open'], chartData.status['Assigned'], chartData.status['On Hold'], chartData.status['Resolved']],
                    backgroundColor: ['#22c55e', '#3b82f6', '#eab308', '#6b7280'],
                    borderColor: borderColor,
                    borderWidth: 2
                }]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });

        // 2. Category Chart
        const categoryChart = new Chart(document.getElementById('categoryChart'), {
            type: 'bar',
            data: {
                labels: ['Hardware', 'Software', 'Network'],
                datasets: [{
                    label: 'Tickets',
                    data: [chartData.categories['Hardware'], chartData.categories['Software'], chartData.categories['Network']],
                    backgroundColor: ['#ef4444', '#3b82f6', '#eab308'],
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: gridColor }, beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 3. Priority Chart
        const priorityChart = new Chart(document.getElementById('priorityChart'), {
            type: 'doughnut',
            data: {
                labels: ['High', 'Medium', 'Low'],
                datasets: [{
                    data: [chartData.priorities['High'], chartData.priorities['Medium'], chartData.priorities['Low']],
                    backgroundColor: ['#ef4444', '#f59e0b', '#22c55e'],
                    borderColor: borderColor,
                    borderWidth: 2
                }]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { position: 'top' } } }
        });

        // Dynamic theme switching observer for charts
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    const isDarkNow = document.documentElement.classList.contains('dark');
                    const newText = isDarkNow ? '#9ca3af' : '#4b5563';
                    const newGrid = isDarkNow ? '#374151' : '#e5e7eb';
                    const newBorder = isDarkNow ? '#1f2937' : '#ffffff';

                    Chart.defaults.color = newText;

                    [statusChart, categoryChart, priorityChart].forEach(chart => {
                        chart.data.datasets[0].borderColor = newBorder;
                        if (chart.options.scales && chart.options.scales.y) {
                            chart.options.scales.y.grid.color = newGrid;
                        }
                        chart.update();
                    });
                }
            });
        });

        observer.observe(document.documentElement, { attributes: true });
    </script>
</x-app-layout>
