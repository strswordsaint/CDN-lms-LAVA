<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* === Styles for Tabs (from assignment page) === */
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
    /* === END: Styles for Tabs === */

    /* Styles for the file lists */
    .list-item {
        border-bottom: 1px solid #e5e7eb;
    }
    .list-item:last-child { border-bottom: 0; }
    
    /* NEW: A smaller button variant */
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
    }

    /* NEW: Subtle danger link for delete buttons in a list */
    .btn-danger-link {
        color: #dc2626; /* error-600 */
        font-weight: 500;
        font-size: 0.875rem;
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        transition: background-color 0.2s, color 0.2s;
        text-decoration: none;
        display: inline-block;
        border: 1px solid transparent;
    }
    .btn-danger-link:hover {
        background-color: #fef2f2; /* error-50 */
        color: #b91c1c; /* error-700 */
        border-color: #fca5a5;
    }

    .delete-form {
        display: inline-block;
        margin-left: 0.5rem;
    }
</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <div class="lg:col-span-2">
            <a href="<?php echo site_url('/courses'); ?>" class="text-sm text-primary-600 hover:underline">
                <i class="fas fa-arrow-left mr-1"></i> Back to Course List
            </a>
            <h1 class="text-3xl font-bold text-neutral-900 mt-2"><?php echo htmlspecialchars($course['title']); ?></h1>
            <p class="text-neutral-600 text-sm mt-2">Created: <?php echo date('M d, Y', strtotime($course['created_at'])); ?></p>
        </div>

        <div class="lg:col-span-1">
             <div class="card p-4 h-full">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-lg font-semibold text-neutral-700">Course Details</h2>
                    <a href="<?php echo site_url('/courses/edit/' . $course['course_id']); ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                </div>
                <div class="prose prose-sm max-w-none text-neutral-600">
                    <p class="text-sm text-neutral-600"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="border-b border-neutral-300 mb-6">
        <nav class="flex -mb-px">
            <a class="tab-link active" data-tab="assignments">Assignments</a>
            <a class="tab-link" data-tab="materials">Materials</a>
        </nav>
    </div>
    <div>

        <div id="tab-panel-assignments" class="tab-panel active">
            <div class="card">
                <div class="p-6 border-b flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-neutral-700">Assignments</h2>
                    <a href="<?php echo site_url('/courses/' . $course['course_id'] . '/assignments/create'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> New Assignment
                    </a>
                </div>
                <div class="divide-y divide-neutral-200">
                    <?php if (empty($assignments)): ?>
                        <p class="text-neutral-500 p-6 text-center">No assignments have been created yet.</p>
                    <?php else: ?>
                        <?php foreach ($assignments as $assignment): ?>
                            <div class="p-6 hover:bg-neutral-50 list-item">
                                <div class="flex flex-wrap justify-between items-center gap-4">
                                    <div class="flex-grow">
                                        <h3 class="text-lg font-semibold text-primary-800"><?php echo htmlspecialchars($assignment['title']); ?></h3>
                                        <div class="text-sm text-neutral-500 mt-1">
                                            <span class="mr-4"><i class="fas fa-calendar-alt mr-1"></i> <strong>Due:</strong> <?php echo date('M d, Y @ g:i A', strtotime($assignment['due_date'])); ?></span>
                                            <span><i class="fas fa-star mr-1"></i> <strong>Points:</strong> <?php echo htmlspecialchars($assignment['points']); ?></span>
                                        </div>
                                        
                                        <?php if (!empty($assignment['attachments'])): ?>
                                            <ul class="mt-3 space-y-1">
                                                <li class="text-sm font-medium text-neutral-600">Attachments:</li>
                                                <?php foreach ($assignment['attachments'] as $file): ?>
                                                    <li class="ml-4">
                                                        <a href="<?php echo base_url() . $file['file_path']; ?>" download class="text-sm text-primary-600 hover:underline">
                                                            <i class="fas fa-paperclip mr-1"></i>
                                                            <?php echo htmlspecialchars($file['file_name']); ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                        </div>
                                    <div class="flex-shrink-0 flex items-center gap-3">
                                        <a href="<?php echo site_url('/assignments/' . $assignment['assignment_id'] . '/submissions'); ?>" class="btn btn-secondary">
                                            View Submissions
                                        </a>
                                        <a href="<?php echo site_url('/assignments/edit/' . $assignment['assignment_id']); ?>" class="btn btn-secondary">
                                            Edit
                                        </a>
                                        <form action="<?php echo site_url('/assignments/delete/' . $assignment['assignment_id']); ?>" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this assignment? This will also delete all associated attachments and submissions.');">
                                            <?php echo csrf_field(); ?> 
                                            <button type="submit" title="Delete Assignment" class="btn-danger-link">
                                                Delete
                                            </button>
                                        </form>
                                        </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div id="tab-panel-materials" class="tab-panel">
            <div class="card">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold text-neutral-700">Course Materials</h2>
                </div>

                <form action="<?php echo site_url('/courses/' . $course['course_id'] . '/materials/upload'); ?>" method="POST" enctype="multipart/form-data" class="p-6 border-b">
                    <?php echo csrf_field(); ?>
                    <label for="material_file" class="block text-sm font-medium text-neutral-700 mb-2">Upload a New File</label>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="file" id="material_file" name="material_file" class="form-input p-0 form-input-file flex-1" required>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload mr-2"></i>Upload
                        </button>
                    </div>
                    <small class="text-xs text-neutral-500 mt-2 block">Allowed: PDF, DOCX, PPTX, MP4, ZIP, etc.</small>
                </form>

                <div class="divide-y divide-neutral-200">
                    <?php if (empty($materials)): ?>
                        <p class="text-neutral-500 p-6 text-center">No materials have been uploaded yet.</p>
                    <?php else: ?>
                        <?php foreach ($materials as $material): ?>
                            <div class="p-4 flex justify-between items-center hover:bg-neutral-50 list-item">
                                <div class="flex items-center">
                                    <i class="fas fa-file-alt text-neutral-500 mr-3"></i>
                                    <a href="<?php echo base_url() . $material['file_path']; ?>" download class="text-sm font-medium text-primary-600 hover:underline">
                                        <?php echo htmlspecialchars($material['file_name']); ?>
                                    </a>
                                </div>
                                <form action="<?php echo site_url('/materials/delete/' . $material['material_id']); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-danger-sm" title="Delete File">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
    </div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // Use a unique key for this specific course page
    var storageKey = 'courseActiveTab_<?php echo $course['course_id']; ?>';

    // 1. On page load, check for a saved tab
    var savedTab = sessionStorage.getItem(storageKey);
    if (savedTab) {
        // Remove default active state
        $('.tab-link').removeClass('active');
        $('.tab-panel').removeClass('active');
        
        // Apply the saved active state
        $('.tab-link[data-tab="' + savedTab + '"]').addClass('active');
        $('#tab-panel-' + savedTab).addClass('active');
    }

    // 2. On tab click, save the new tab
    $('.tab-link').on('click', function(e) {
        e.preventDefault();
        
        var tab = $(this).data('tab');
        
        // Save the clicked tab to session storage
        sessionStorage.setItem(storageKey, tab);
        
        // Update tab link active state
        $('.tab-link').removeClass('active');
        $(this).addClass('active');
        
        // Show/hide tab panels
        $('.tab-panel').removeClass('active');
        $('#tab-panel-' + tab).addClass('active');
    });
});
</script>