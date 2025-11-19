<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<style type="text/tailwindcss">
    #calendar {
        @apply max-w-7xl mx-auto bg-white p-6 rounded-lg border border-neutral-200 shadow-md;
    }

    /* === NEW: Use your global .btn classes === */
    .fc .fc-button-primary {
        @apply btn btn-primary; /* Uses your class from header.php */
        padding: 0.5rem 1rem !important; /* Add padding for a better look */
    }
    
    /* Make the 'today' button secondary */
    .fc .fc-today-button {
        @apply btn btn-secondary;
        padding: 0.5rem 1rem !important;
        /* We use !important to override FullCalendar's defaults */
        background-color: #fff !important;
        color: #334155 !important;
        border: 1px solid #cbd5e1 !important;
    }
    .fc .fc-today-button:hover {
        background-color: #f8fafc !important; /* neutral-50 */
    }
    
    /* Header (Jan 2024, etc) */
    .fc-toolbar-title {
        @apply text-2xl font-bold text-neutral-800;
    }

    /* === NEW: Style list view === */
    .fc-list-event {
        @apply cursor-pointer;
    }
    .fc-list-event:hover {
        @apply bg-neutral-50;
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
        // --- 1. CHANGE: Start with 'listWeek' view ---
        initialView: 'listWeek', 
        
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: '<?php echo site_url('/calendar/events'); ?>',
        
        eventClick: function(info) {
            info.jsEvent.preventDefault(); 
            if (info.event.url) {
                window.open(info.event.url);
            }
        },

        // --- 2. NEW: Add these options to clean up the 'Month' view ---
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            meridiem: 'short' // This will show "11:59p"
        },
        eventDisplay: 'list-item', // Use 'dot' style for timed events in month view
        dayMaxEvents: true, // This is good! It creates the "+ more" link
        
        editable: false
    });
    
    calendar.render();
});
</script>

<?php include 'app/views/layouts/footer.php'; ?>