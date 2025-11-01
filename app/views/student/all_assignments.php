<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<h1 class="text-2xl font-bold text-gray-800 mb-6"><?php echo $page_title ?? 'All Assignments'; ?></h1>

<div class="bg-white shadow-md rounded-lg border border-gray-200">
    <div class="divide-y divide-gray-200">
        <?php if (empty($assignments)): ?>
            <p class="text-gray-500 p-6 text-center">You have no assignments from any of your courses.</p>
        <?php else: ?>
            <?php foreach ($assignments as $assignment): ?>
                <div class="p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-4 hover:bg-gray-50">
                    <div class="flex-grow">
                        <a href="<?php echo site_url('/assignment/' . $assignment['assignment_id']); ?>" class="text-lg font-medium text-blue-700 hover:underline">
                            <?php echo htmlspecialchars($assignment['title']); ?>
                        </a>
                        <div class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-book-open mr-1"></i>
                            <?php echo htmlspecialchars($assignment['course_title']); ?>
                        </div>
                        <div class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Due: <?php echo date('M d, Y @ g:i A', strtotime($assignment['due_date'])); ?>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 flex flex-col sm:items-end gap-2">
                        <div>
                            <?php
                                $is_overdue = strtotime($assignment['due_date']) < time();
                                if ($assignment['grade'] !== null): ?>
                                    <span class="text-sm font-medium text-green-700 bg-green-100 px-3 py-1 rounded-full">
                                        <i class="fas fa-check-circle mr-1"></i> Graded: <?php echo htmlspecialchars($assignment['grade']); ?>/<?php echo htmlspecialchars($assignment['points']); ?>
                                    </span>
                            <?php elseif ($assignment['submission_id'] !== null): ?>
                                    <span class="text-sm font-medium text-blue-700 bg-blue-100 px-3 py-1 rounded-full">
                                        <i class="fas fa-check mr-1"></i> Submitted
                                    </span>
                            <?php elseif ($is_overdue): ?>
                                    <span class="text-sm font-medium text-red-700 bg-red-100 px-3 py-1 rounded-full">
                                        <i class="fas fa-times-circle mr-1"></i> Overdue
                                    </span>
                            <?php else: ?>
                                    <span class="text-sm font-medium text-gray-700 bg-gray-100 px-3 py-1 rounded-full">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </span>
                            <?php endif; ?>
                        </div>

                        <div>
                            <a href="<?php echo site_url('/assignment/' . $assignment['assignment_id']); ?>" class="text-sm font-semibold text-white <?php echo $assignment['submission_id'] ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700'; ?> py-2 px-4 rounded-md shadow-sm">
                                <?php echo $assignment['submission_id'] ? 'View Submission' : 'Submit Work'; ?>
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>