<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-white">Visual Statistics Dashboard <span class="text-indigo-400 text-lg ml-2">({{ $stats['month_name'] }})</span></h2>
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">

                    <form method="GET" action="{{ route('statistics') }}" class="flex items-center m-0">
                        <div class="flex items-stretch bg-gray-800 rounded-md border border-gray-600 overflow-hidden shadow-sm focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition-all h-[42px]">

                            <label for="month-picker" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 cursor-pointer transition-colors border-r border-blue-700">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-sm font-bold whitespace-nowrap">Select Month:</span>
                            </label>

                            <input type="month" id="month-picker" name="month" value="{{ $stats['selected_month'] }}"
                                    onchange="this.form.submit()"
                                    onclick="this.showPicker()"
                                    class="bg-transparent text-white border-none focus:ring-0 text-sm font-bold px-4 cursor-pointer outline-none w-[140px] [&::-webkit-calendar-picker-indicator]:hidden">
                        </div>
                    </form>

                    <button type="button" id="downloadPdfBtn" class="bg-white text-gray-800 font-bold py-2 px-4 rounded shadow hover:bg-gray-100 transition-colors h-[42px] flex items-center gap-2">
                        <i class="fas fa-download"></i> Download PDF Report
                    </button>

                    <form id="pdfForm" action="{{ route('statistics.pdf') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="dashboard_image" id="dashboard_image">
                        <input type="hidden" name="month" value="{{ $stats['selected_month'] }}">
                    </form>
                </div>
            </div>

            <div id="pdf-dashboard-content" style="padding: 20px; border-radius: 8px;">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-center border-t-4 border-gray-800">
                        <dt class="text-xs font-bold text-gray-500 uppercase">Total</dt>
                        <dd class="mt-1 text-2xl font-black text-gray-900">{{ $stats['total'] }}</dd>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-center border-t-4 border-green-500">
                        <dt class="text-xs font-bold text-gray-500 uppercase">Open</dt>
                        <dd class="mt-1 text-2xl font-black text-green-600">{{ $stats['open'] }}</dd>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-center border-t-4 border-blue-500">
                        <dt class="text-xs font-bold text-gray-500 uppercase">Assigned</dt>
                        <dd class="mt-1 text-2xl font-black text-blue-600">{{ $stats['assigned'] }}</dd>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-center border-t-4 border-yellow-500">
                        <dt class="text-xs font-bold text-gray-500 uppercase">On Hold</dt>
                        <dd class="mt-1 text-2xl font-black text-yellow-600">{{ $stats['on_hold'] }}</dd>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-center border-t-4 border-gray-400">
                        <dt class="text-xs font-bold text-gray-500 uppercase">Resolved</dt>
                        <dd class="mt-1 text-2xl font-black text-gray-400">{{ $stats['resolved'] }}</dd>
                    </div>
                </div>

                <div id="charts-capture-area" class="grid grid-cols-1 md:grid-cols-3 gap-6" style="padding: 20px; background-color: #1a1d24; border-radius: 8px;">
                    <div class="bg-white shadow-sm sm:rounded-lg p-6 flex flex-col items-center">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 text-center">Resolution Status</h3>
                        <div class="relative h-64 w-full flex justify-center"><canvas id="statusChart"></canvas></div>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-lg p-6 flex flex-col items-center">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 text-center">Issue Categories</h3>
                        <div class="relative h-64 w-full flex justify-center"><canvas id="categoryChart"></canvas></div>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-lg p-6 flex flex-col items-center">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 text-center">Priority Levels</h3>
                        <div class="relative h-64 w-full flex justify-center"><canvas id="priorityChart"></canvas></div>
                    </div>
                </div>
            </div>
            </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Status Chart (Pie)
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
                    backgroundColor: ['#22c55e', '#3b82f6', '#eab308', '#9ca3af'],
                    borderWidth: 1
                }]
            }
        });

        const ctxCategory = document.getElementById('categoryChart');
        new Chart(ctxCategory, { type: 'bar', data: { labels: ['Hardware', 'Software', 'Network'], datasets: [{ label: 'Tickets', data: [{{ $stats['hardware'] }}, {{ $stats['software'] }}, {{ $stats['network'] }}], backgroundColor: ['#ef4444', '#3b82f6', '#eab308'], borderWidth: 1 }] }, options: { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }, plugins: { legend: { display: false } } } });

        const ctxPriority = document.getElementById('priorityChart');
        new Chart(ctxPriority, { type: 'doughnut', data: { labels: ['High', 'Medium', 'Low'], datasets: [{ data: [{{ $stats['high'] }}, {{ $stats['medium'] }}, {{ $stats['low'] }}], backgroundColor: ['#ef4444', '#f59e0b', '#22c55e'], borderWidth: 1 }] } });
    </script>
    <script>
        document.getElementById('downloadPdfBtn').addEventListener('click', function() {

            const dashboardElement = document.getElementById('charts-capture-area');

            // Change button text to show it's loading
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';

            html2canvas(dashboardElement, {
                backgroundColor: '#1a1d24', // Matches the dark background
                scale: 2 // Increases resolution
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                document.getElementById('dashboard_image').value = imgData;
                document.getElementById('pdfForm').submit();

                // Restore button text after a short delay
                setTimeout(() => { btn.innerHTML = originalText; }, 2000);
            });
        });
    </script>
</x-app-layout>
