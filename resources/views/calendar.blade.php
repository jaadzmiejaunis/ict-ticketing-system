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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div id="calendar"></div>
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

    <style>
        .fc-event {
            cursor: pointer;
            border: none;
            padding: 2px 4px;
            font-size: 0.85em;
            border-radius: 4px;
        }
        .fc-daygrid-event-dot { border-width: 4px; }
    </style>
</x-app-layout>
