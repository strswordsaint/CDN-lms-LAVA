<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* === Styles for Tabs === */
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
        display: none;
    }
    .tab-panel.active {
        display: block;
    }
    
    /* === Styles for Stream Posts === */
    .post-card {
        @apply card flex space-x-4 p-5;
    }
    .post-icon {
        @apply flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-white;
    }
    .post-content {
        @apply flex-1;
    }
    .post-title {
        @apply text-lg font-semibold text-neutral-900;
    }
    .post-title a:hover {
        @apply underline;
    }
    .post-meta {
        @apply text-xs text-neutral-500 mt-1;
    }
    .post-description {
        @apply text-sm text-neutral-700 mt-3;
    }
    .post-attachments {
        list-style: none;
        padding-left: 0;
        margin-top: 0.75rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .post-attachment-item {
        font-size: 0.875rem;
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        padding: 0.25rem 0.75rem;
    }
    .post-attachment-item a {
        color: #1d4ed8;
        font-weight: 500;
        text-decoration: none;
    }
    .post-attachment-item a:hover {
        text-decoration: underline;
    }
    .post-attachment-item i {
        color: #64748b;
        margin-right: 0.375rem;
    }
    .post-footer {
        @apply mt-4 pt-3 border-t border-neutral-200 flex justify-end;
    }
    .btn-replies {
        @apply btn btn-secondary text-sm;
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
                <p class="text-sm text-neutral-600"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
            </div>
        </div>
    </div>
    
    <div class="border-b border-neutral-300 mb-6">
        <nav class="flex -mb-px">
            <a class="tab-link active" data-tab="announcements">Announcements</a>
            <a class="tab-link" data-tab="assignments">Assignments</a>
            <a class="tab-link" data-tab="materials">Materials</a>
        </nav>
    </div>
    
    <div>
    
        <div id="tab-panel-announcements" class="tab-panel active">
            <div class="card p-4 mb-6">
                <form action="<?php echo site_url('/courses/' . $course['course_id'] . '/announcement/store'); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-neutral-700 mb-1">New Announcement <span class="text-error-500">*</span></label>
                            <input type="text" id="title" name="title" class="form-input" required placeholder="What's the title?">
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-neutral-700 mb-1">Details</label>
                            <textarea id="description" name="description" rows="4" class="form-textarea" placeholder="Add details..."></textarea>
                        </div>
                        <div>
                            <label for="attachments" class="block text-sm font-medium text-neutral-700 mb-1">Attach Files</label>
                            <input type="file" name="attachments[]" id="attachments" class="form-input-file" multiple>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane mr-2"></i>Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="space-y-6">
                <?php if (empty($announcements)): ?>
                    <p class="text-neutral-500 p-6 text-center">No announcements have been posted yet.</p>
                <?php else: ?>
                    <?php foreach ($announcements as $post): ?>
                        <div class="post-card">
                            <div class="post-icon bg-neutral-500"><i class="fas fa-bullhorn fa-lg"></i></div>
                            <div class="post-content">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="post-title"><?php echo htmlspecialchars($post['title']); ?></span>
                                        <div class="post-meta">Posted on <?php echo date('M d, Y', strtotime($post['created_at'])); ?></div>
                                    </div>
                                    <form action="<?php echo site_url('/assignments/delete/' . $post['assignment_id']); ?>" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        <?php echo csrf_field(); ?> 
                                        <button type="submit" title="Delete Post" class="text-neutral-400 hover:text-error-600"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                                <?php if (!empty($post['description'])): ?>
                                    <div class="post-description"><?php echo nl2br(htmlspecialchars($post['description'])); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($post['attachments'])): ?>
                                    <ul class="post-attachments">
                                        <?php foreach ($post['attachments'] as $file): ?>
                                            <li class="post-attachment-item">
                                                <a href="<?php echo base_url() . $file['file_path']; ?>" download><i class="fas fa-paperclip"></i> <?php echo htmlspecialchars($file['file_name']); ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                                <div class="post-footer">
                                    <button type="button" class="btn-replies" data-post-id="<?php echo $post['assignment_id']; ?>" data-post-title="<?php echo htmlspecialchars($post['title']); ?>">
                                        <i class="fas fa-comments mr-2"></i> Replies (<?php echo $post['reply_count']; ?>)
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div id="tab-panel-assignments" class="tab-panel">
            <div class="text-right mb-4">
                <a href="<?php echo site_url('/courses/' . $course['course_id'] . '/assignments/create'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Create New Assignment
                </a>
            </div>
            
            <div class="space-y-6">
                <?php if (empty($assignments)): ?>
                    <p class="text-neutral-500 p-6 text-center card">No assignments have been created yet.</p>
                <?php else: ?>
                    <?php foreach ($assignments as $post): ?>
                        <div class="post-card">
                            <div class="post-icon bg-primary-600"><i class="fas fa-tasks fa-lg"></i></div>
                            <div class="post-content">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <a href="<?php echo site_url('/assignments/' . $post['assignment_id'] . '/submissions'); ?>" class="post-title"><?php echo htmlspecialchars($post['title']); ?></a>
                                        <div class="post-meta">
                                            Due: <?php echo date('M d, Y @ g:i A', strtotime($post['due_date'])); ?>
                                            <span class="mx-1">&bull;</span>
                                            <?php echo htmlspecialchars($post['points']); ?> pts
                                        </div>
                                    </div>
                                    <form action="<?php echo site_url('/assignments/delete/' . $post['assignment_id']); ?>" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                        <?php echo csrf_field(); ?> 
                                        <button type="submit" title="Delete Post" class="text-neutral-400 hover:text-error-600"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                                <?php if (!empty($post['description'])): ?>
                                    <div class="post-description"><?php echo nl2br(htmlspecialchars($post['description'])); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($post['attachments'])): ?>
                                    <ul class="post-attachments">
                                        <?php foreach ($post['attachments'] as $file): ?>
                                            <li class="post-attachment-item">
                                                <a href="<?php echo base_url() . $file['file_path']; ?>" download><i class="fas fa-paperclip"></i> <?php echo htmlspecialchars($file['file_name']); ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                                <div class="post-footer">
                                    <a href="<?php echo site_url('/assignments/' . $post['assignment_id'] . '/submissions'); ?>" class="btn btn-secondary mr-2">
                                        View Submissions
                                    </a>
                                    <button type="button" class="btn-replies" data-post-id="<?php echo $post['assignment_id']; ?>" data-post-title="<?php echo htmlspecialchars($post['title']); ?>">
                                        <i class="fas fa-comments mr-2"></i> Replies (<?php echo $post['reply_count']; ?>)
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
                        <button type="submit" class="btn btn-success"><i class="fas fa-upload mr-2"></i>Upload</button>
                    </div>
                </form>
                <div class="divide-y divide-neutral-200">
                    <?php if (empty($materials)): ?>
                        <p class="text-neutral-500 p-6 text-center">No materials have been uploaded yet.</p>
                    <?php else: ?>
                        <?php foreach ($materials as $material): ?>
                            <div class="p-4 flex justify-between items-center hover:bg-neutral-50">
                                <div class="flex items-center">
                                    <i class="fas fa-file-alt text-neutral-500 mr-3"></i>
                                    <a href="<?php echo base_url() . $material['file_path']; ?>" download class="text-sm font-medium text-primary-600 hover:underline">
                                        <?php echo htmlspecialchars($material['file_name']); ?>
                                    </a>
                                </div>
                                <form action="<?php echo site_url('/materials/delete/' . $material['material_id']); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-danger-sm" title="Delete File"><i class="fas fa-trash-alt"></i></button>
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
    // --- Main Tab Switching Logic ---
    var storageKey = 'courseActiveTab_<?php echo $course['course_id']; ?>';
    var savedTab = sessionStorage.getItem(storageKey);
    const urlParams = new URLSearchParams(window.location.search);
    const urlTab = urlParams.get('tab');
    
    if (urlTab) {
        savedTab = urlTab; 
        sessionStorage.setItem(storageKey, urlTab);
    }

    // Default to 'announcements' if no tab is saved or found
    if (!savedTab) {
        savedTab = 'announcements';
    }

    // Apply the active state
    $('.tab-link').removeClass('active');
    $('.tab-panel').removeClass('active');
    $('.tab-link[data-tab="' + savedTab + '"]').addClass('active');
    $('#tab-panel-' + savedTab).addClass('active');

    // 2. On tab click, save the new tab
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