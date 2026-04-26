<x-app-layout>
    @section('title', 'Calendar')
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-4 sm:mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white transition-colors">
                    Ticket Calendar (By Creation Date)
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1 transition-colors">
                    View when complaints were reported. Click any ticket to view details.
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm rounded-2xl sm:rounded-lg p-4 sm:p-6 transition-colors">

                <div class="
                    [&_.fc-toolbar]:flex-wrap [&_.fc-toolbar]:gap-y-3 sm:[&_.fc-toolbar]:gap-y-0
                    [&_.fc-toolbar-chunk]:flex-wrap [&_.fc-toolbar-chunk]:gap-2 sm:[&_.fc-toolbar-chunk]:gap-0
                    [&_.fc]:text-gray-700 dark:[&_.fc]:text-gray-200
                    [&_.fc-theme-standard_td]:border-gray-200 dark:[&_.fc-theme-standard_td]:border-gray-700
                    [&_.fc-theme-standard_th]:border-gray-200 dark:[&_.fc-theme-standard_th]:border-gray-700
                    [&_.fc-theme-standard_.fc-scrollgrid]:border-gray-200 dark:[&_.fc-theme-standard_.fc-scrollgrid]:border-gray-700
                    [&_.fc-toolbar-title]:text-gray-900 dark:[&_.fc-toolbar-title]:text-white [&_.fc-toolbar-title]:font-bold [&_.fc-toolbar-title]:text-lg sm:[&_.fc-toolbar-title]:text-xl
                    [&_.fc-daygrid-day-number]:text-gray-500 dark:[&_.fc-daygrid-day-number]:text-gray-400 [&_.fc-daygrid-day-number]:no-underline hover:[&_.fc-daygrid-day-number]:text-indigo-600 dark:hover:[&_.fc-daygrid-day-number]:text-gray-300
                    [&_.fc-col-header-cell-cushion]:text-gray-600 dark:[&_.fc-col-header-cell-cushion]:text-gray-300 [&_.fc-col-header-cell-cushion]:py-2 [&_.fc-col-header-cell-cushion]:text-xs sm:[&_.fc-col-header-cell-cushion]:text-sm
                    [&_.fc-day-today]:!bg-indigo-500/10 dark:[&_.fc-day-today]:!bg-indigo-500/15
                    [&_.fc-button-primary]:!bg-indigo-600 [&_.fc-button-primary]:!border-indigo-600 [&_.fc-button-primary]:capitalize [&_.fc-button-primary]:font-bold [&_.fc-button-primary]:text-xs sm:[&_.fc-button-primary]:text-sm
                    hover:[&_.fc-button-primary]:!bg-indigo-700 hover:[&_.fc-button-primary]:!border-indigo-700
                    [&_.fc-button-primary:not(:disabled):active]:!bg-indigo-800 [&_.fc-button-primary:not(:disabled):active]:!border-indigo-900
                    [&_.fc-button-active]:!bg-indigo-800 [&_.fc-button-active]:!border-indigo-900
                    [&_.fc-list-empty]:bg-white dark:[&_.fc-list-empty]:bg-gray-800 [&_.fc-list-empty]:text-xs sm:[&_.fc-list-empty]:text-sm
                    [&_.fc-list]:border-gray-200 dark:[&_.fc-list]:border-gray-700
                    [&_.fc-list-day-cushion]:bg-gray-50 dark:[&_.fc-list-day-cushion]:bg-gray-700 [&_.fc-list-day-cushion]:text-xs sm:[&_.fc-list-day-cushion]:text-sm
                    [&_.fc-list-event:hover_td]:bg-gray-50 dark:[&_.fc-list-event:hover_td]:bg-gray-700
                    [&_.fc-list-event-title]:text-xs sm:[&_.fc-list-event-title]:text-sm
                    [&_.fc-list-event-time]:text-xs sm:[&_.fc-list-event-time]:text-sm
                    [&_.fc-popover]:bg-white dark:[&_.fc-popover]:bg-gray-800 [&_.fc-popover]:border-gray-200 dark:[&_.fc-popover]:border-gray-700 [&_.fc-popover]:shadow-xl [&_.fc-popover]:rounded-lg
                    [&_.fc-popover-header]:bg-gray-50 dark:[&_.fc-popover-header]:bg-gray-700 [&_.fc-popover-header]:text-gray-900 dark:[&_.fc-popover-header]:text-white [&_.fc-popover-header]:rounded-t-lg
                    [&_.fc-more-link]:text-indigo-600 dark:[&_.fc-more-link]:text-indigo-400 [&_.fc-more-link]:font-bold [&_.fc-more-link]:text-xs
                    [&_.fc-event]:cursor-pointer [&_.fc-event]:border-none [&_.fc-event]:px-1.5 [&_.fc-event]:py-0.5 [&_.fc-event]:text-[0.75em] sm:[&_.fc-event]:text-[0.85em] [&_.fc-event]:rounded
                    [&_.fc-daygrid-event-dot]:border-4
                ">
                    <div id="calendar"></div>
                </div>

            </div>

        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var isMobile = window.innerWidth < 768;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: isMobile ? 'listWeek' : 'dayGridMonth',
                dayMaxEvents: isMobile ? 2 : 5,

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listWeek'
                },

                // 👇 --- THIS IS THE FIX --- 👇
                // We remove the old height check and force the content height to 'auto'
                // This stops it from stretching off the screen and shows everything fully!
                contentHeight: 'auto',
                // 👆 ----------------------- 👆

                events: {!! json_encode($events) !!},

                eventDisplay: 'block',
                eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },

                windowResize: function(arg) {
                    var newIsMobile = window.innerWidth < 768;
                    if (newIsMobile !== isMobile) {
                        isMobile = newIsMobile;
                        calendar.setOption('dayMaxEvents', isMobile ? 2 : 5);
                    }
                },

                eventClick: function(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault();
                    }
                }
            });
            calendar.render();
        });
    </script>
</x-app-layout>
