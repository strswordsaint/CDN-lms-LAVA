<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<style type="text/tailwindcss">
    /* ... (Keep Page Banner styles) ... */
    .page-banner { background: white; border-bottom: 1px solid #e2e8f0; padding: 2rem 0; box-shadow: 0 1px 2px rgba(0,0,0,0.02); margin-bottom: 2rem; }

    #calendar-wrapper {
        @apply bg-white rounded-2xl shadow-sm border border-neutral-200 p-6;
    }

    /* === CALENDAR FIXES === */
    .fc-daygrid-day-frame {
        /* Ensure the cell respects height and clips content */
        @apply overflow-hidden !important;
        min-height: 100px; /* Enforce minimum height */
    }
    
    .fc-daygrid-day-events {
        /* Add scrolling if too many events */
        @apply overflow-y-auto !important; 
        max-height: 80px; /* Limit height of event area within cell */
        margin-bottom: 2px;
        /* Hide scrollbar for cleaner look */
        scrollbar-width: none; 
    }
    .fc-daygrid-day-events::-webkit-scrollbar { display: none; }

    /* Event "Chip" Styling */
    .fc-daygrid-event {
        @apply mx-1 mt-1 mb-1 !important; 
        @apply rounded-md shadow-sm border-0 !important;
        @apply text-xs font-medium cursor-pointer !important;
    }
    
    .fc-event-main {
        @apply px-2 py-1 truncate !important;
    }
</style>

<div class="page-banner">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-extrabold text-neutral-900 tracking-tight mb-1">Calendar</h1>
                <p class="text-neutral-500 font-medium">Your schedule.</p>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div id="calendar-wrapper">
        <div id='calendar'></div>
    </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listWeek' },
        height: 'auto',
        dayMaxEvents: false, // We handle overflow with CSS scroll now
        events: '<?php echo site_url('/calendar/events'); ?>',
        eventClick: function(info) {
            info.jsEvent.preventDefault(); 
            if (info.event.url) window.location.href = info.event.url;
        }
    });
    calendar.render();
});
</script>
<?php include 'app/views/layouts/footer.php'; ?>