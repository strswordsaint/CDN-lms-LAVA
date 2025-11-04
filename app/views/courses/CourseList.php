<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
        margin: 0; /* Fix for form spacing */
    }

    /* === STYLES FOR COLORED ICON BUTTONS === */
    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem; /* 32px */
        height: 2rem; /* 32px */
        border-radius: 0.375rem; /* 6px */
        font-size: 0.875rem; /* 14px */
        color: white;
        transition: opacity 0.2s;
        text-decoration: none;
        border: none; /* Reset border for button element */
        cursor: pointer; /* Ensure button elements are clickable */
    }
    .btn-icon:hover {
        opacity: 0.8;
    }
    .btn-icon-success { background-color: #16a34a; } /* success-600 */
    .btn-icon-warning { background-color: #f59e0b; } /* warning-500 */
    .btn-icon-primary { background-color: #2563eb; } /* primary-600 */
    .btn-icon-danger { background-color: #dc2626; } /* error-600 */

    /* === STYLES FOR COPY BUTTON === */
    .btn-copy {
        background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;
        padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem;
        margin-left: 0.5rem; cursor: pointer; transition: all 0.2s;
    }
    .btn-copy:hover { background-color: #e2e8f0; color: #1e293b; }
    .btn-copy.copied {
        background-color: #dcfce7; color: #15803d; border-color: #a3e6b6;
    }
    .code-snippet {
        background-color: #f8fafc; border: 1px solid #e2e8f0;
        padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-family: monospace;
    }
</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-neutral-900"><?php echo $page_title ?? 'My Courses'; ?></h1>
        <a href="<?php echo site_url('/courses/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Create New Course
        </a>
    </div>

    <?php $success_message = lava_instance()->session->flashdata('success'); ?>
    <?php if (!empty($success_message)): ?>
        <div class="notice notice-success mb-4" role="alert" style="display:block;">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
    <?php $error_message = lava_instance()->session->flashdata('error'); ?>
    <?php if (!empty($error_message)): ?>
         <div class="notice notice-error mb-4" role="alert" style="display:block;">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Title
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider hidden md:table-cell">
                            Enrollment Code
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Students
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider hidden md:table-cell">
                            Created On
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php if (empty($courses)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-neutral-500">
                                You haven't created any courses yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                            
                            <tr class="course-toggle-row border-b border-neutral-200" 
                                data-target="#actions-row-<?php echo $course['course_id']; ?>" 
                                data-href="<?php echo site_url('/courses/show/' . $course['course_id']); ?>">
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary-700">
                                    <?php echo htmlspecialchars($course['title']); ?>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700 hidden md:table-cell">
                                    <div class="flex items-center">
                                        <span class="code-snippet" id="code-<?php echo $course['course_id']; ?>">
                                            <?php echo htmlspecialchars($course['enrollment_code']); ?>
                                        </span>
                                        <button class="btn-copy copy-btn" data-clipboard-target="#code-<?php echo $course['course_id']; ?>" title="Copy code">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center text-lg font-semibold text-primary-700">
                                    <?php echo $course['student_count'] ?? 0; ?>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 hidden md:table-cell">
                                    <?php echo date('M d, Y', strtotime($course['created_at'])); ?>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-neutral-400">
                                    <i class="fas fa-chevron-down fa-xs"></i>
                                </td>
                            </tr>

                            <tr class="actions-row" id="actions-row-<?php echo $course['course_id']; ?>">
                                <td colspan="5">
                                    <div class="actions-container">
                                        <a href="<?php echo site_url('/courses/show/' . $course['course_id']); ?>" title="Manage Course Content" class="btn-icon btn-icon-success">
                                            <i class="fas fa-folder-open"></i>
                                        </a>
                                        <a href="<?php echo site_url('/courses/' . $course['course_id'] . '/enrollments'); ?>" title="Manage Enrollments" class="btn-icon btn-icon-warning">
                                            <i class="fas fa-users"></i>
                                        </a>
                                        <a href="<?php echo site_url('/courses/edit/' . $course['course_id']); ?>" title="Edit Course Details" class="btn-icon btn-icon-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo site_url('/courses/delete/' . $course['course_id']); ?>" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this course? This action cannot be undone.');">
                                            <?php echo csrf_field(); ?> 
                                            <button type="submit" title="Delete Course" class="btn-icon btn-icon-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // 1. Single-click on the ROW to toggle
    $('.course-toggle-row').on('click', function() {
        var $thisRow = $(this);
        var targetId = $thisRow.data('target');
        var $targetRow = $(targetId);
        
        $thisRow.toggleClass('row-active');
        $thisRow.find('i.fa-chevron-down, i.fa-chevron-up').toggleClass('fa-chevron-down fa-chevron-up');
        $targetRow.slideToggle(200);
    });
    
    // 2. NEW: Double-click on the ROW to navigate
    $('.course-toggle-row').on('dblclick', function() {
        var href = $(this).data('href');
        if (href) {
            window.location.href = href;
        }
    });

    // 3. NEW: Stop clicks on buttons/links from triggering the row's click
    // This prevents the row from toggling when you click a button.
    $('.copy-btn, .actions-container a, .actions-container button').on('click', function(e) {
        e.stopPropagation();
    });

    // 4. NEW: Also stop double-clicks on buttons from bubbling
    $('.copy-btn, .actions-container a, .actions-container button').on('dblclick', function(e) {
        e.stopPropagation();
    });
});
</script>