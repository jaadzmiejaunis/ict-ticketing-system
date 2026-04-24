<x-app-layout>
    @section('title', 'My Performance')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight transition-colors">Personal Performance Profile</h2>
                    <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm mt-1 transition-colors">Official metrics for your assigned tasks</p>
                </div>

                <form action="{{ route('my.performance') }}" method="GET" class="w-full md:w-auto flex items-center bg-white dark:bg-gray-800 rounded-md border border-gray-300 dark:border-gray-600 shadow-sm transition-colors focus-within:ring-1 focus-within:ring-indigo-500 focus-within:border-indigo-500">
                    <label for="time-filter" class="px-3 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 font-bold text-xs uppercase tracking-widest border-r border-gray-300 dark:border-gray-600 transition-colors shrink-0">
                        Period
                    </label>
                    <select name="filter" id="time-filter" onchange="this.form.submit()" class="w-full md:w-48 bg-transparent text-gray-900 dark:text-white border-none focus:ring-0 text-sm font-bold transition-colors cursor-pointer outline-none pl-3 pr-8 py-2">
                        <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Time</option>
                        <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ $filter === 'year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl sm:rounded-lg shadow-sm mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0 border border-gray-200 dark:border-gray-700 transition-colors">
                <div class="flex items-center gap-4">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 shrink-0">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=ffffff" class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 shrink-0">
                    @endif
                    <div class="min-w-0">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white transition-colors truncate">{{ $user->name }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm transition-colors truncate">{{ $user->email }} • Joined {{ $user->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                <span class="self-start sm:self-auto px-3 py-1 text-[10px] sm:text-xs font-bold rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 border border-blue-300 dark:border-blue-700/50 uppercase tracking-widest shadow-sm transition-colors shrink-0">
                    {{ $user->role }}
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 mb-6 text-center">
                <div class="col-span-2 md:col-span-1 bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-xl sm:rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 border-t-4 border-t-gray-400 dark:border-t-gray-500 transition-colors flex flex-col justify-center">
                    <span class="text-[10px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest block transition-colors">Total Assigned</span>
                    <div class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white mt-1 sm:mt-2 tracking-tighter transition-colors">{{ $totalAssigned }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-xl sm:rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 border-t-4 border-t-yellow-500 transition-colors flex flex-col justify-center">
                    <span class="text-[10px] sm:text-xs font-bold text-yellow-600 dark:text-yellow-500 uppercase tracking-widest block transition-colors">Pending Tasks</span>
                    <div class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white mt-1 sm:mt-2 tracking-tighter transition-colors">{{ $pendingCount }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-xl sm:rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 border-t-4 border-t-green-500 transition-colors flex flex-col justify-center">
                    <span class="text-[10px] sm:text-xs font-bold text-green-600 dark:text-green-500 uppercase tracking-widest block transition-colors">Resolved Tasks</span>
                    <div class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white mt-1 sm:mt-2 tracking-tighter transition-colors">{{ $resolvedCount }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl sm:rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors flex flex-col items-center">
                    <h4 class="text-center font-bold text-gray-600 dark:text-gray-300 mb-2 sm:mb-4 uppercase text-[10px] sm:text-xs tracking-widest transition-colors">Resolution Status</h4>
                    <div class="h-48 sm:h-64 w-full flex justify-center"><canvas id="statusChart"></canvas></div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl sm:rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors flex flex-col items-center">
                    <h4 class="text-center font-bold text-gray-600 dark:text-gray-300 mb-2 sm:mb-4 uppercase text-[10px] sm:text-xs tracking-widest transition-colors">Issue Categories</h4>
                    <div class="h-48 sm:h-64 w-full flex justify-center"><canvas id="categoryChart"></canvas></div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl sm:rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors flex flex-col items-center">
                    <h4 class="text-center font-bold text-gray-600 dark:text-gray-300 mb-2 sm:mb-4 uppercase text-[10px] sm:text-xs tracking-widest transition-colors">Priority Levels</h4>
                    <div class="h-48 sm:h-64 w-full flex justify-center"><canvas id="priorityChart"></canvas></div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6 transition-colors">
                <div class="px-4 sm:px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700 transition-colors">
                    <h3 class="font-bold text-gray-900 dark:text-white text-xs sm:text-sm uppercase tracking-tight transition-colors">Recently Resolved Activity</h3>
                </div>
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="min-w-full text-left border-collapse">
                        <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 font-bold text-gray-500 dark:text-gray-400 uppercase text-[9px] sm:text-[10px] tracking-wider transition-colors">Ticket Details</th>
                                <th class="px-4 sm:px-6 py-3 font-bold text-gray-500 dark:text-gray-400 uppercase text-[9px] sm:text-[10px] tracking-wider text-right transition-colors whitespace-nowrap">Date Resolved</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 font-medium text-gray-600 dark:text-gray-300 transition-colors">
                            @forelse($recentTasks as $task)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="font-bold text-gray-900 dark:text-white transition-colors text-xs sm:text-sm truncate max-w-[200px] sm:max-w-md">#{{ $task->id }} - {{ $task->title ?? 'Untitled Ticket' }}</div>
                                        <div class="text-[9px] sm:text-[10px] text-gray-500 dark:text-gray-500 mt-1 uppercase tracking-wide transition-colors">
                                            Cat: <span class="text-indigo-600 dark:text-indigo-400">{{ $task->category }}</span> |
                                            Pri: <span class="{{ $task->priority == 'High' ? 'text-red-500 dark:text-red-400' : ($task->priority == 'Medium' ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">{{ $task->priority }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-right text-gray-500 dark:text-gray-400 font-bold text-[10px] sm:text-xs uppercase transition-colors whitespace-nowrap">
                                        {{ $task->updated_at->format('d M Y, h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-10 text-center text-gray-500 text-xs sm:text-sm italic font-medium transition-colors">No recently resolved tasks recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#9ca3af' : '#4b5563';
        const gridColor = isDark ? '#374151' : '#e5e7eb';
        const borderColor = isDark ? '#1f2937' : '#ffffff';

        Chart.defaults.color = textColor;
        Chart.defaults.font.family = "'Instrument Sans', sans-serif";

        const chartData = @json($chartData);

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
