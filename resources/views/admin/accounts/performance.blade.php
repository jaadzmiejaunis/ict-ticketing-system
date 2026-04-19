<x-app-layout>
    @section('title', 'Staff Performance Profile')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight transition-colors uppercase">Staff Performance Profile</h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm italic transition-colors">Viewing official task metrics for <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $user->name }}</span></p>
                </div>
                <a href="{{ route('admin.accounts') }}" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-5 py-2.5 rounded-md font-bold transition shadow-sm text-sm uppercase flex items-center gap-2 border border-gray-300 dark:border-gray-600 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path></svg>
                    Back to Accounts
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-6 flex justify-between items-center border border-gray-200 dark:border-gray-700 transition-colors">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white transition-colors">{{ $user->name }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm transition-colors">{{ $user->email }} • Joined {{ $user->created_at->format('d M Y') }}</p>
                </div>
                <span class="px-3 py-1 text-xs font-black rounded-full bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-700/50 uppercase shadow-sm transition-colors">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700 transition-colors">
                        <h3 class="font-bold text-gray-900 dark:text-white text-xs uppercase tracking-widest transition-colors">Recently Resolved by {{ $user->name }}</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left border-collapse">
                            <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors">
                                <tr>
                                    <th class="px-6 py-3 font-bold text-gray-600 dark:text-gray-400 uppercase text-[10px] tracking-wider transition-colors">Ticket Details</th>
                                    <th class="px-6 py-3 font-bold text-gray-600 dark:text-gray-400 uppercase text-[10px] tracking-wider text-right transition-colors">Date Resolved</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 font-medium text-gray-700 dark:text-gray-300 transition-colors">
                                @forelse($recentTasks as $task)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900 dark:text-white transition-colors">#{{ $task->id }} - {{ $task->title ?? 'Untitled Ticket' }}</div>
                                            <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wide transition-colors">
                                                Category: <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $task->category }}</span> |
                                                Priority: <span class="font-bold {{ $task->priority == 'High' ? 'text-red-600 dark:text-red-400' : ($task->priority == 'Medium' ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">{{ $task->priority }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right text-gray-500 dark:text-gray-400 font-bold text-xs uppercase transition-colors">
                                            {{ $task->updated_at->format('d M Y, h:i A') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-10 text-center text-gray-400 dark:text-gray-500 italic font-bold transition-colors uppercase text-xs">No recently resolved tasks recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-indigo-600 dark:bg-indigo-900/60 rounded-lg shadow-sm p-8 text-white flex flex-col justify-center border border-transparent dark:border-indigo-800/50 transition-colors">
                    <div class="mb-4">
                        <svg class="w-10 h-10 text-indigo-200 dark:text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <h4 class="font-black text-xl uppercase tracking-tighter">Performance Tip</h4>
                    </div>
                    <p class="text-indigo-100 dark:text-indigo-200 text-sm italic leading-relaxed">
                        "Great job! {{ $user->name }} has resolved {{ $resolvedCount }} tickets. This contributes to maintaining the efficiency and reliability of the GayaCare Support system."
                    </p>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Dynamically set Chart.js colors based on active theme
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#9ca3af' : '#4b5563';
        const gridColor = isDark ? '#374151' : '#e5e7eb';
        const chartBorderColor = isDark ? '#1f2937' : '#ffffff';

        Chart.defaults.color = textColor;

        const chartData = @json($chartData);

        // 1. Status Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: ['Open', 'Assigned', 'On Hold', 'Resolved'],
                datasets: [{
                    data: [chartData.status['Open'], chartData.status['Assigned'], chartData.status['On Hold'], chartData.status['Resolved']],
                    backgroundColor: ['#22c55e', '#3b82f6', '#eab308', '#6366f1'],
                    borderColor: chartBorderColor,
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
                    borderColor: chartBorderColor,
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
        new Chart(document.getElementById('priorityChart'), {
            type: 'doughnut',
            data: {
                labels: ['High', 'Medium', 'Low'],
                datasets: [{
                    data: [chartData.priorities['High'], chartData.priorities['Medium'], chartData.priorities['Low']],
                    backgroundColor: ['#ef4444', '#f59e0b', '#22c55e'],
                    borderColor: chartBorderColor,
                    borderWidth: 2
                }]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { position: 'top' } } }
        });
    </script>
</x-app-layout>
