<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>

<?php
    // Status Logic
    $is_overdue = strtotime($assignment['due_date']) < time();
    $statusBadge = '';
    $statusClass = '';
    
    if ($assignment['grade'] !== null) {
        $statusBadge = '<i class="fas fa-check-circle mr-1"></i> Graded: ' . htmlspecialchars($assignment['grade']) . '/' . htmlspecialchars($assignment['points']);
        $statusClass = 'bg-green-100 text-green-700 border-green-200';
    } elseif ($assignment['submission_id'] !== null) {
        $statusBadge = '<i class="fas fa-paper-plane mr-1"></i> Submitted';
        $statusClass = 'bg-blue-50 text-blue-600 border-blue-200';
    } elseif ($is_overdue) {
        $statusBadge = '<i class="fas fa-exclamation-circle mr-1"></i> Overdue';
        $statusClass = 'bg-red-50 text-red-600 border-red-200';
    } else {
        $statusBadge = '<i class="fas fa-hourglass-half mr-1"></i> Pending';
        $statusClass = 'bg-neutral-100 text-neutral-600 border-neutral-200';
    }
?>

<div class="hunt-card group relative overflow-hidden group-hover:border-primary-300">
    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary-600"></div>

    <div class="hunt-icon-box bg-primary-50 text-primary-600 border border-primary-200">
        <i class="fas fa-tasks"></i>
    </div>

    <div class="flex-1 min-w-0">
        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
            
            <div>
                <h3 class="text-lg font-bold text-neutral-900 leading-tight group-hover:text-primary-700 transition-colors mb-2">
                    <a href="<?php echo site_url('/assignment/' . $assignment['assignment_id']); ?>" class="hover:underline">
                        <?php echo htmlspecialchars($assignment['title']); ?>
                    </a>
                </h3>
                
                <div class="flex flex-wrap items-center gap-2 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-neutral-100 text-neutral-600 border border-neutral-200">
                        <i class="fas fa-book-open mr-1.5 text-neutral-400"></i>
                        <?php echo htmlspecialchars($assignment['course_title']); ?>
                    </span>
                    
                    <span class="flex items-center text-neutral-500 text-xs font-medium bg-white px-2 py-0.5 rounded border border-neutral-100">
                        <i class="far fa-clock mr-1.5 text-neutral-400"></i> 
                        Due: <?php echo date('M d, Y @ g:i A', strtotime($assignment['due_date'])); ?>
                    </span>
                </div>
            </div>

            <div class="flex flex-col sm:items-end gap-2 mt-2 md:mt-0">
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border <?php echo $statusClass; ?>">
                    <?php echo $statusBadge; ?>
                </div>

                <a href="<?php echo site_url('/assignment/' . $assignment['assignment_id']); ?>" class="btn btn-sm w-full sm:w-auto justify-center bg-white border border-neutral-200 text-neutral-600 font-semibold hover:text-primary-700 hover:border-primary-300 shadow-sm transition-all rounded-lg px-4 py-2">
                    <?php echo $assignment['submission_id'] ? 'View Submission' : 'Submit Work'; ?>
                </a>
            </div>
        </div>
    </div>
</div>