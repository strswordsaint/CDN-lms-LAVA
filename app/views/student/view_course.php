<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

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
    
    /* A smaller button variant */
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
    }
</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?php echo site_url('/courses/my'); ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to My Courses
    </a>

    <div class="card p-6 mb-6">
        <h1 class="text-3xl font-bold text-neutral-900 mb-2"><?php echo htmlspecialchars($course['title']); ?></h1>
        <div class="prose prose-sm max-w-none text-neutral-700">
            <p class="text-sm text-neutral-600"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
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
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold text-neutral-700">Assignments</h2>
                </div>
                <div class="divide-y divide-neutral-200">
                    <?php if (empty($assignments)): ?>
                        <p class="text-neutral-500 p-6 text-center">No assignments have been posted yet.</p>
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
                                        <?php if (!empty($assignment['description'])): ?>
                                            <p class="text-neutral-600 mt-2 text-sm"><?php echo nl2br(htmlspecialchars($assignment['description'])); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-shrink-0 flex items-center gap-3">
                                        <a href="<?php echo site_url('/assignment/' . $assignment['assignment_id']); ?>" class="btn btn-primary">
                                            <i class="fas fa-upload mr-1"></i> View/Submit Work
                                        </a>
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
                <div class="divide-y divide-neutral-200">
                    <?php if (empty($materials)): ?>
                        <p class="text-neutral-500 p-6 text-center">No materials have been uploaded for this course yet.</p>
                    <?php else: ?>
                        <?php foreach ($materials as $material): ?>
                            <div class="p-4 flex justify-between items-center hover:bg-neutral-50 list-item">
                                <div class="flex items-center">
                                    <i class="fas fa-file-alt text-neutral-500 mr-3"></i>
                                    <span class="text-sm font-medium text-neutral-800"><?php echo htmlspecialchars($material['file_name']); ?></span>
                                </div>
                                <a href="<?php echo base_url() . $material['file_path']; ?>" download class="btn btn-success btn-sm">
                                    <i class="fas fa-download mr-1"></i> Download
                                </a>
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
    // Use a unique key for this specific student course page
    var storageKey = 'studentCourseActiveTab_<?php echo $course['course_id']; ?>';

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