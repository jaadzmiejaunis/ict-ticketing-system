<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-white">
                    Ticket Calendar (By Creation Date)
                </h2>
                <p class="text-gray-400 text-sm mt-1">
                    View when complaints were reported. Click any ticket to view details.
                </p>
            </div>

            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="
                    [&_.fc]:text-gray-200
                    [&_.fc-theme-standard_td]:border-gray-700
                    [&_.fc-theme-standard_th]:border-gray-700
                    [&_.fc-theme-standard_.fc-scrollgrid]:border-gray-700
                    [&_.fc-toolbar-title]:text-white [&_.fc-toolbar-title]:font-bold
                    [&_.fc-daygrid-day-number]:text-gray-400 [&_.fc-daygrid-day-number]:no-underline hover:[&_.fc-daygrid-day-number]:text-gray-300
                    [&_.fc-col-header-cell-cushion]:text-gray-300 [&_.fc-col-header-cell-cushion]:py-2
                    [&_.fc-day-today]:!bg-indigo-500/15
                    [&_.fc-button-primary]:!bg-indigo-600 [&_.fc-button-primary]:!border-indigo-600 [&_.fc-button-primary]:capitalize [&_.fc-button-primary]:font-bold
                    hover:[&_.fc-button-primary]:!bg-indigo-700 hover:[&_.fc-button-primary]:!border-indigo-700
                    [&_.fc-button-primary:not(:disabled):active]:!bg-indigo-800 [&_.fc-button-primary:not(:disabled):active]:!border-indigo-900
                    [&_.fc-button-active]:!bg-indigo-800 [&_.fc-button-active]:!border-indigo-900
                    [&_.fc-list-empty]:bg-gray-800
                    [&_.fc-list]:border-gray-700
                    [&_.fc-list-day-cushion]:bg-gray-700
                    [&_.fc-list-event:hover_td]:bg-gray-700
                    [&_.fc-popover]:bg-gray-800 [&_.fc-popover]:border-gray-700 [&_.fc-popover]:shadow-xl [&_.fc-popover]:rounded-lg
                    [&_.fc-popover-header]:bg-gray-700 [&_.fc-popover-header]:text-white [&_.fc-popover-header]:rounded-t-lg
                    [&_.fc-more-link]:text-indigo-400 [&_.fc-more-link]:font-bold
                    [&_.fc-event]:cursor-pointer [&_.fc-event]:border-none [&_.fc-event]:px-1.5 [&_.fc-event]:py-0.5 [&_.fc-event]:text-[0.85em] [&_.fc-event]:rounded
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
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',

                // LIMIT EVENTS: Show 5, then show a "+ more" link
                dayMaxEvents: 5,

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listWeek'
                },
                events: {!! json_encode($events) !!},

                eventDisplay: 'block',
                eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },

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
