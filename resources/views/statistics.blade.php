<x-app-layout>
    @section('title', 'Statistic')
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-5 sm:mb-6 gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight transition-colors">
                        Visual Statistics Dashboard
                        <span class="text-indigo-600 dark:text-indigo-400 text-base sm:text-lg ml-1 sm:ml-2">({{ $stats['month_name'] }})</span>
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm mt-1 transition-colors">
                        Review analytical charts and monthly metrics. Choose a month to generate a detailed performance report.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">

                    <form method="GET" action="{{ route('statistics') }}" class="flex items-center m-0 w-full sm:w-auto">
                        <div class="flex items-stretch w-full sm:w-auto bg-white dark:bg-gray-800 rounded-md border border-gray-300 dark:border-gray-600 overflow-hidden shadow-sm focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition-all h-[42px]">

                            <label for="month-picker" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 cursor-pointer transition-colors border-r border-blue-700">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-xs sm:text-sm font-bold whitespace-nowrap hidden sm:inline">Select Month:</span>
                                <span class="text-xs font-bold whitespace-nowrap sm:hidden">Month:</span>
                            </label>

                            <input type="month" id="month-picker" name="month" value="{{ $stats['selected_month'] }}"
                                    onchange="this.form.submit()"
                                    onclick="this.showPicker()"
                                    class="bg-transparent text-gray-900 dark:text-white border-none focus:ring-0 text-sm font-bold px-3 sm:px-4 cursor-pointer outline-none w-full sm:w-[140px] [&::-webkit-calendar-picker-indicator]:hidden transition-colors flex-grow">
                        </div>
                    </form>

                    <button type="button" id="downloadPdfBtn" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-4 rounded-md shadow-sm transition-colors h-[42px] flex items-center justify-center gap-2 text-xs sm:text-sm uppercase tracking-wide active:scale-95">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download PDF Report
                    </button>

                    <form id="pdfForm" action="{{ route('statistics.pdf') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="dashboard_image" id="dashboard_image">
                        <input type="hidden" name="month" value="{{ $stats['selected_month'] }}">
                    </form>
                </div>
            </div>

            <div id="pdf-dashboard-content" class="rounded-2xl sm:rounded-lg">

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-6 sm:mb-8">
                    <div class="col-span-2 sm:col-span-1 lg:col-span-1 bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl sm:rounded-lg p-3 sm:p-4 text-center border border-gray-200 dark:border-gray-700 border-t-4 border-t-gray-500 transition-colors">
                        <dt class="text-[10px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase transition-colors tracking-widest">Total</dt>
                        <dd class="mt-1 text-xl sm:text-2xl font-black text-gray-900 dark:text-white transition-colors">{{ $stats['total'] }}</dd>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl sm:rounded-lg p-3 sm:p-4 text-center border border-gray-200 dark:border-gray-700 border-t-4 border-t-green-500 transition-colors">
                        <dt class="text-[10px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase transition-colors tracking-widest">Open</dt>
                        <dd class="mt-1 text-xl sm:text-2xl font-black text-green-600 dark:text-green-400 transition-colors">{{ $stats['open'] }}</dd>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl sm:rounded-lg p-3 sm:p-4 text-center border border-gray-200 dark:border-gray-700 border-t-4 border-t-blue-500 transition-colors">
                        <dt class="text-[10px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase transition-colors tracking-widest">Assigned</dt>
                        <dd class="mt-1 text-xl sm:text-2xl font-black text-blue-600 dark:text-blue-400 transition-colors">{{ $stats['assigned'] }}</dd>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl sm:rounded-lg p-3 sm:p-4 text-center border border-gray-200 dark:border-gray-700 border-t-4 border-t-yellow-500 transition-colors">
                        <dt class="text-[10px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase transition-colors tracking-widest">On Hold</dt>
                        <dd class="mt-1 text-xl sm:text-2xl font-black text-yellow-600 dark:text-yellow-400 transition-colors">{{ $stats['on_hold'] }}</dd>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl sm:rounded-lg p-3 sm:p-4 text-center border border-gray-200 dark:border-gray-700 border-t-4 border-t-gray-400 transition-colors">
                        <dt class="text-[10px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase transition-colors tracking-widest">Resolved</dt>
                        <dd class="mt-1 text-xl sm:text-2xl font-black text-gray-600 dark:text-gray-300 transition-colors">{{ $stats['resolved'] }}</dd>
                    </div>
                </div>

                <div id="charts-capture-area" class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 p-4 sm:p-6 bg-gray-50 dark:bg-[#1a1d24] border border-gray-200 dark:border-gray-700 rounded-2xl sm:rounded-lg transition-colors">

                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl sm:rounded-lg p-4 sm:p-6 flex flex-col items-center border border-gray-100 dark:border-gray-700 transition-colors">
                        <h3 class="text-sm sm:text-lg font-bold text-gray-900 dark:text-white mb-2 sm:mb-4 text-center transition-colors">Resolution Status</h3>
                        <div class="relative h-56 sm:h-64 w-full flex justify-center transition-colors"><canvas id="statusChart"></canvas></div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl sm:rounded-lg p-4 sm:p-6 flex flex-col items-center border border-gray-100 dark:border-gray-700 transition-colors">
                        <h3 class="text-sm sm:text-lg font-bold text-gray-900 dark:text-white mb-2 sm:mb-4 text-center transition-colors">Issue Categories</h3>
                        <div class="relative h-56 sm:h-64 w-full flex justify-center transition-colors"><canvas id="categoryChart"></canvas></div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl sm:rounded-lg p-4 sm:p-6 flex flex-col items-center border border-gray-100 dark:border-gray-700 transition-colors">
                        <h3 class="text-sm sm:text-lg font-bold text-gray-900 dark:text-white mb-2 sm:mb-4 text-center transition-colors">Priority Levels</h3>
                        <div class="relative h-56 sm:h-64 w-full flex justify-center transition-colors"><canvas id="priorityChart"></canvas></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const isDark = document.documentElement.classList.contains('dark');
        Chart.defaults.color = isDark ? '#9ca3af' : '#4b5563';
        const chartBorderColor = isDark ? '#1f2937' : '#ffffff';
        const gridColor = isDark ? '#374151' : '#e5e7eb';

        const ctxStatus = document.getElementById('statusChart');
        new Chart(ctxStatus, {
            type: 'pie',
            data: {
                labels: ['Open', 'Assigned', 'On Hold', 'Resolved'],
                datasets: [{
                    data: [
                        {{ $stats['open'] }},
                        {{ $stats['assigned'] }},
                        {{ $stats['on_hold'] }},
                        {{ $stats['resolved'] }}
                    ],
                    backgroundColor: ['#22c55e', '#3b82f6', '#eab308', '#6b7280'],
                    borderColor: chartBorderColor,
                    borderWidth: 2
                }]
            },
            options: { maintainAspectRatio: false }
        });

        const ctxCategory = document.getElementById('categoryChart');
        new Chart(ctxCategory, {
            type: 'bar',
            data: {
                labels: ['Hardware', 'Software', 'Network'],
                datasets: [{
                    label: 'Tickets',
                    data: [{{ $stats['hardware'] }}, {{ $stats['software'] }}, {{ $stats['network'] }}],
                    backgroundColor: ['#ef4444', '#3b82f6', '#eab308'],
                    borderColor: chartBorderColor,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: Chart.defaults.color }, grid: { color: gridColor } },
                    x: { ticks: { color: Chart.defaults.color }, grid: { color: gridColor } }
                },
                plugins: { legend: { display: false } }
            }
        });

        const ctxPriority = document.getElementById('priorityChart');
        new Chart(ctxPriority, {
            type: 'doughnut',
            data: {
                labels: ['High', 'Medium', 'Low'],
                datasets: [{
                    data: [{{ $stats['high'] }}, {{ $stats['medium'] }}, {{ $stats['low'] }}],
                    backgroundColor: ['#ef4444', '#f59e0b', '#22c55e'],
                    borderColor: chartBorderColor,
                    borderWidth: 2
                }]
            },
            options: { maintainAspectRatio: false }
        });
    </script>
    <script>
        document.getElementById('downloadPdfBtn').addEventListener('click', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const dashboardElement = document.getElementById('charts-capture-area');

            const btn = this;
            const originalHTML = btn.innerHTML;

            // Replaced FontAwesome icon with SVG for loading spinner to prevent dependency issues
            btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generating...`;

            html2canvas(dashboardElement, {
                backgroundColor: isDark ? '#1a1d24' : '#f9fafb',
                scale: 2
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                document.getElementById('dashboard_image').value = imgData;
                document.getElementById('pdfForm').submit();

                setTimeout(() => { btn.innerHTML = originalHTML; }, 2000);
            });
        });
    </script>
</x-app-layout>
