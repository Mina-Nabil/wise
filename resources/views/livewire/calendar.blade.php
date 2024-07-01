<div>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <div class="card">
        <header class=" card-header noborder">
            <h4 class="card-title">
                WISE Calendar
            </h4>
        </header>
        <div class="card-body px-6 pb-6">
            <div wire:ignore id='calendar'></div>

        </div>
    </div>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            console.log(@json($events))
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'today dayGridMonth,timeGridWeek,dayGridDay'
                },
                // timeZone: 'UTC',
                selectable: true,
                eventBorderColor: 'white',
                events: @json($events),
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // don't let the browser navigate
                    if (info.event.url) {
                        window.open(info.event.url);
                    }
                }
            });
            calendar.render();
        });
    </script>
</div>
