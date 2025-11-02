<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>

<div class="p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-4 hover:bg-gray-50">
    <div class="flex-grow">
        <a href="<?php echo site_url('/assignments/' . $assignment['assignment_id'] . '/submissions'); ?>" class="text-lg font-medium text-blue-700 hover:underline">
            <?php echo htmlspecialchars($assignment['title']); ?>
        </a>
        <div class="text-sm text-gray-500 mt-1">
            <i class="fas fa-book-open mr-1"></i>
            <a href="<?php echo site_url('/courses/show/' . $assignment['course_id']); ?>" class="hover:underline">
                <?php echo htmlspecialchars($assignment['course_title']); ?>
            </a>
        </div>
        <div class="text-sm text-gray-500 mt-1">
            <i class="fas fa-calendar-alt mr-1"></i>
            Due: <?php echo date('M d, Y @ g:i A', strtotime($assignment['due_date'])); ?>
        </div>
    </div>
    
    <div class="flex-shrink-0 flex items-center gap-2">
        <a href="<?php echo site_url('/assignments/' . $assignment['assignment_id'] . '/submissions'); ?>" class="text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 py-2 px-4 rounded-md shadow-sm">
            View Submissions
        </a>
    </div>
</div>