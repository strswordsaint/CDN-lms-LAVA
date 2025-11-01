<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* Styles for the active and inactive tabs */
    .tab-link {
        padding: 0.5rem 1rem;
        font-weight: 600;
        color: #6b7280; /* gray-500 */
        border-bottom: 2px solid transparent;
        cursor: pointer;
    }
    .tab-link.active {
        color: #6264A7; /* teams-primary */
        border-bottom-color: #6264A7;
    }
    .tab-panel {
        display: none; /* Hide all panels by default */
    }
    .tab-panel.active {
        display: block; /* Show only the active panel */
    }
</style>

<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold text-gray-800"><?php echo $page_title ?? 'All Assignments'; ?></h1>
</div>

<div class="border-b border-gray-300 mb-6">
    <nav class="flex -mb-px">
        <a class="tab-link active" data-tab="upcoming">Upcoming</a>
        <a class="tab-link" data-tab="past-due">Past due</a>
        <a class="tab-link" data-tab="completed">Completed</a>
    </nav>
</div>

<div>
    <div id="tab-panel-upcoming" class="tab-panel active">
        <div class="bg-white shadow-md rounded-lg border border-gray-200">
            <div class="divide-y divide-gray-200">
                <?php if (empty($assignments_upcoming)): ?>
                    <p class="text-gray-500 p-6 text-center">No upcoming assignments.</p>
                <?php else: ?>
                    <?php foreach ($assignments_upcoming as $assignment): ?>
                        <?php include 'app/views/assignments/all_assignment_item.php'; // We will create this reusable item ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="tab-panel-past-due" class="tab-panel">
        <div class="bg-white shadow-md rounded-lg border border-gray-200">
            <div class="divide-y divide-gray-200">
                <?php if (empty($assignments_past_due)): ?>
                    <p class="text-gray-500 p-6 text-center">No assignments are past due.</p>
                <?php else: ?>
                    <?php foreach ($assignments_past_due as $assignment): ?>
                         <?php include 'app/views/assignments/all_assignment_item.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="tab-panel-completed" class="tab-panel">
        <div class="bg-white shadow-md rounded-lg border border-gray-200">
            <div class="divide-y divide-gray-200">
                <p class="text-xs text-gray-500 p-4 bg-gray-50">This list shows all assignments that are past due.</p>
                <?php if (empty($assignments_completed)): ?>
                    <p class="text-gray-500 p-6 text-center">No assignments are completed.</p>
                <?php else: ?>
                    <?php foreach ($assignments_completed as $assignment): ?>
                         <?php include 'app/views/assignments/all_assignment_item.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.tab-link').on('click', function(e) {
        e.preventDefault();
        
        var tab = $(this).data('tab');
        
        // Update tab link active state
        $('.tab-link').removeClass('active');
        $(this).addClass('active');
        
        // Show/hide tab panels
        $('.tab-panel').removeClass('active');
        $('#tab-panel-' + tab).addClass('active');
    });
});
</script>

<?php include 'app/views/layouts/footer.php'; ?>