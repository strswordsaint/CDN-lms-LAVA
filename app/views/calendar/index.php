<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<style>
    /* Custom styles for FullCalendar */
    #calendar {
        max-width: 1100px;
        margin: 0 auto;
        background: #ffffff;
        padding: 1.5rem;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    /* Style the event links */
    .fc-event {
        cursor: pointer;
        border: none !important;
        font-weight: 500;
    }
    .fc-event:hover {
        opacity: 0.8;
    }
    /* Header (Jan 2024, etc) */
    .fc-toolbar-title {
        color: #1e293b;
    }
    /* Buttons (prev, next, today) */
    .fc .fc-button-primary {
        background-color: #1d4ed8;
        border-color: #1d4ed8;
        font-weight: 600;
    }
    .fc .fc-button-primary:hover {
        background-color: #1e3a8a;
        border-color: #1e3a8a;
    }
</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-neutral-900 mb-6"><?php echo $page_title ?? 'Calendar'; ?></h1>
    
    <div id='calendar'></div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth', // Start with month view
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek' // Add week and list views
        },
        // 3. This is where we load your data!
        events: '<?php echo site_url('/calendar/events'); ?>',
        
        // 4. Handle clicking an event
        eventClick: function(info) {
            info.jsEvent.preventDefault(); // don't let the browser navigate
            if (info.event.url) {
                window.open(info.event.url); // Open the link in a new tab
            }
        },
        
        editable: false, // Don't allow dragging
        dayMaxEvents: true, // allow "more" link when too many events
    });
    
    calendar.render();
});
</script>

<?php include 'app/views/layouts/footer.php'; ?>