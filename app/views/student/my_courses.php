<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* === STYLES FOR CLICKABLE ROWS === */
    .course-toggle-row {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .course-toggle-row:hover {
        background-color: #f8fafc; /* neutral-50 */
    }
    .course-toggle-row.row-active {
        background-color: #ecf5ff; /* primary-50 */
        box-shadow: inset 4px 0 0 0 #1d4ed8; /* primary-700 */
    }
    .actions-row {
        display: none;
        background-color: #f8fafc; /* neutral-50 */
    }
    .actions-container {
        display: flex;
        gap: 0.75rem; /* 12px */
        padding: 1rem 1.5rem; /* 16px 24px */
        align-items: center;
        justify-content: flex-end; /* Align buttons to the right */
        border-bottom: 1px solid #e2e8f0; /* neutral-200 */
    }
    .delete-form {
        display: inline-block;
        margin: 0;
    }
    /* === END STYLES === */
</style>

<div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold text-neutral-900"><?php echo $page_title ?? 'My Courses'; ?></h1>
</div>

<div class="card p-6 mb-6">
    <h2 class="text-xl font-semibold text-neutral-700 mb-4">Enroll in a New Course</h2>
    <form action="<?php echo site_url('/courses/enroll'); ?>" method="POST" class="flex flex-col sm:flex-row gap-3">
        <?php echo csrf_field(); ?>
        <label for="enrollment_code" class="sr-only">Enrollment Code</label>
        <input type="text" id="enrollment_code" name="enrollment_code"
               class="form-input flex-grow"
               placeholder="Enter course enrollment code..." required>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Join Course
        </button>
    </form>
</div>

<div class="card overflow-hidden">
    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold text-neutral-700">Enrolled Courses</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                        Course Title
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">
                        </th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <?php if (empty($my_courses)): ?>
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-neutral-500">
                            You are not enrolled in any courses yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($my_courses as $course): ?>
                        
                        <tr class="course-toggle-row border-b border-neutral-200"
                            data-target="#actions-row-<?php echo $course['enrollment_id']; ?>"
                            data-href="<?php echo ($course['status'] == 'approved') ? site_url('/my-courses/' . $course['course_id']) : '#'; ?>">
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($course['status'] == 'approved'): ?>
                                    <a href="<?php echo site_url('/my-courses/' . $course['course_id']); ?>" class="course-title-link text-sm font-medium text-primary-700 hover:underline">
                                        <?php echo htmlspecialchars($course['title']); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-sm font-medium text-neutral-900">
                                        <?php echo htmlspecialchars($course['title']); ?>
                                    </span>
                                <?php endif; ?>
                                <p class="text-sm text-neutral-500">Code: <?php echo htmlspecialchars($course['enrollment_code']); ?></p>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <?php if ($course['status'] == 'approved'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-success-100 text-success-800">
                                        Approved
                                    </span>
                                <?php elseif ($course['status'] == 'pending'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-warning-100 text-warning-800">
                                        Pending Approval
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-neutral-400">
                                <i class="fas fa-chevron-down fa-xs"></i>
                            </td>
                        </tr>

                        <tr class="actions-row" id="actions-row-<?php echo $course['enrollment_id']; ?>">
                            <td colspan="3">
                                <div class="actions-container">
                                    <?php if ($course['status'] == 'approved'): ?>
                                        <a href="<?php echo site_url('/my-courses/' . $course['course_id']); ?>" class="btn btn-success">
                                            <i class="fas fa-eye mr-2"></i> View Course
                                        </a>
                                        <form action="<?php echo site_url('/courses/leave/' . $course['enrollment_id']); ?>" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to leave this course?');">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-sign-out-alt mr-2"></i> Leave Course
                                            </button>
                                        </form>

                                    <?php elseif ($course['status'] == 'pending'): ?>
                                        <form action="<?php echo site_url('/courses/leave/' . $course['enrollment_id']); ?>" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to cancel this enrollment request?');">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-times mr-2"></i> Cancel Request
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // 1. Single-click on the ROW to toggle
    $('.course-toggle-row').on('click', function(e) {
        var $thisRow = $(this);
        var targetId = $thisRow.data('target');
        var $targetRow = $(targetId);
        
        $thisRow.toggleClass('row-active');
        $thisRow.find('i.fa-chevron-down, i.fa-chevron-up').toggleClass('fa-chevron-down fa-chevron-up');
        $targetRow.slideToggle(200);
    });
    
    // 2. Prevent single-click on the title link from navigating
    $('.course-title-link').on('click', function(e) {
        e.preventDefault();
        // The event will still bubble up to the row and trigger the toggle
    });

    // 3. Double-click on the ROW to navigate (if approved)
    $('.course-toggle-row').on('dblclick', function() {
        var href = $(this).data('href');
        if (href && href !== '#') { // Only navigate if href is valid
            window.location.href = href;
        }
    });

    // 4. Stop clicks on buttons/links inside from triggering the row
    $('.actions-container a, .actions-container button').on('click dblclick', function(e) {
        e.stopPropagation();
    });
});
</script>