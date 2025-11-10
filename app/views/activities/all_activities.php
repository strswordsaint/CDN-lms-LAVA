<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* Styles for the active and inactive tabs */
    .tab-link {
        padding: 0.5rem 1rem;
        font-weight: 600;
        color: #64748b; /* neutral-500 */
        border-bottom: 2px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }
    .tab-link.active {
        color: #1d4ed8; /* primary-700 */
        border-bottom-color: #1d4ed8;
    }
    .tab-panel {
        display: none; /* Hide all panels by default */
    }
    .tab-panel.active {
        display: block; /* Show only the active panel */
    }
    .tab-dot {
        display: inline-block;
        width: 0.5rem; /* 8px */
        height: 0.5rem; /* 8px */
        background-color: #ef4444; /* error-500 */
        border-radius: 9999px;
        margin-left: 0.375rem; /* 6px */
    }
</style>

<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold text-neutral-900"><?php echo $page_title ?? 'All Activities'; ?></h1>
</div>

<div class="border-b border-neutral-300 mb-6">
    <nav class="flex -mb-px">
        <a class="tab-link active" data-tab="upcoming">
            Upcoming
            <?php if (count($activities_upcoming) > 0): ?>
                <span class="tab-dot"></span>
            <?php endif; ?>
        </a>
        <a class="tab-link" data-tab="past-due">
            Past due
            <?php if (count($activities_past_due) > 0): ?>
                <span class="tab-dot"></span>
            <?php endif; ?>
        </a>
        <a class="tab-link" data-tab="completed">
            Completed
        </a>
    </nav>
</div>

<div>
    <div id="tab-panel-upcoming" class="tab-panel active">
        <div class="card overflow-hidden">
            <div class="divide-y divide-neutral-200">
                <?php if (empty($activities_upcoming)): ?>
                    <p class="text-neutral-500 p-6 text-center">No upcoming activities.</p>
                <?php else: ?>
                    <?php foreach ($activities_upcoming as $assignment): // Use 'assignment' variable to match the include ?>
                        <?php include 'app/views/activities/all_activity_item.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="tab-panel-past-due" class="tab-panel">
        <div class="card overflow-hidden">
            <div class="divide-y divide-neutral-200">
                <?php if (empty($activities_past_due)): ?>
                    <p class="text-neutral-500 p-6 text-center">No activities are past due.</p>
                <?php else: ?>
                    <?php foreach ($activities_past_due as $assignment): ?>
                         <?php include 'app/views/activities/all_activity_item.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="tab-panel-completed" class="tab-panel">
        <div class="card overflow-hidden">
            <div class="divide-y divide-neutral-200">
                <p class="text-xs text-neutral-500 p-4 bg-neutral-50">This list shows all activities that are past due.</p>
                <?php if (empty($activities_completed)): ?>
                    <p class="text-neutral-500 p-6 text-center">No activities are completed.</p>
                <?php else: ?>
                    <?php foreach ($activities_completed as $assignment): ?>
                         <?php include 'app/views/activities/all_activity_item.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var storageKey = 'teacherActivityTab'; // A new unique key

    var savedTab = sessionStorage.getItem(storageKey);
    if (savedTab) {
        $('.tab-link').removeClass('active');
        $('.tab-panel').removeClass('active');
        $('.tab-link[data-tab="' + savedTab + '"]').addClass('active');
        $('#tab-panel-' + savedTab).addClass('active');
    }

    $('.tab-link').on('click', function(e) {
        e.preventDefault();
        var tab = $(this).data('tab');
        sessionStorage.setItem(storageKey, tab);
        
        $('.tab-link').removeClass('active');
        $(this).addClass('active');
        
        $('.tab-panel').removeClass('active');
        $('#tab-panel-' + tab).addClass('active');
    });
});
</script>
<?php include 'app/views/layouts/footer.php'; ?>