<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* === Styles for Tabs === */
    .tab-link {
        padding: 0.5rem 1rem;
        font-weight: 600;
        color: #64748b;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }
    .tab-link.active {
        color: #1d4ed8;
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
        @apply mt-4 pt-3 border-t border-neutral-200 flex justify-end items-center;
    }
    .btn-replies {
        @apply btn btn-secondary text-sm;
    }
</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?php echo site_url('/courses/my'); ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to My Courses
    </a>

    <div class="card p-6 mb-6">
        <h1 class="text-3xl font-bold text-neutral-900 mb-2"><?php echo htmlspecialchars($course['title']); ?></h1>
        <p class="text-sm text-neutral-600"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
    </div>

    <div class="border-b border-neutral-300 mb-6">
        <nav class="flex -mb-px">
            <a class="tab-link active" data-tab="announcements">Announcements</a>
            <a class="tab-link" data-tab="activities">Activities</a>
            <a class="tab-link" data-tab="assignments">Assignments</a>
            <a class="tab-link" data-tab="materials">Materials</a>
        </nav>
    </div>
    
    <div>
        
        <div id="tab-panel-announcements" class="tab-panel active">
            <div class="space-y-6">
                <?php if (empty($announcements)): ?>
                     <p class="text-neutral-500 p-6 text-center card">No announcements, activities, or assignments have been posted yet.</p>
                <?php else: ?>
                    <?php foreach ($announcements as $post): ?>
                        <div class="post-card">
                            <?php if ($post['type'] == 'assignment'): ?>
                                <div class="post-icon bg-primary-600"><i class="fas fa-tasks fa-lg"></i></div>
                            <?php elseif ($post['type'] == 'activity'): ?>
                                <div class="post-icon bg-yellow-500"><i class="fas fa-gamepad fa-lg"></i></div>
                            <?php elseif ($post['type'] == 'quiz'): ?>
                                <div class="post-icon bg-purple-600"><i class="fas fa-puzzle-piece fa-lg"></i></div>
                            <?php else: ?>
                                <div class="post-icon bg-neutral-500"><i class="fas fa-bullhorn fa-lg"></i></div>
                            <?php endif; ?>
                            
                            <div class="post-content">
                                <div>
                                    <?php if ($post['type'] == 'assignment' || $post['type'] == 'activity'): ?>
                                        <a href="<?php echo site_url('/assignment/' . $post['assignment_id']); ?>" class="post-title"><?php echo htmlspecialchars($post['title']); ?></a>
                                    <?php elseif ($post['type'] == 'quiz'): ?>
                                        <a href="<?php echo site_url('/quiz/' . $post['assignment_id']); ?>" class="post-title"><?php echo htmlspecialchars($post['title']); ?></a>
                                    <?php else: ?>
                                        <span class="post-title"><?php echo htmlspecialchars($post['title']); ?></span>
                                    <?php endif; ?>

                                    <div class="post-meta">
                                        <?php if ($post['type'] == 'assignment' || $post['type'] == 'activity' || $post['type'] == 'quiz'): ?>
                                            Due: <?php echo date('M d, Y @ g:i A', strtotime($post['due_date'])); ?>
                                            <span class="mx-1">&bull;</span>
                                            <?php echo htmlspecialchars($post['points']); ?> pts
                                        <?php else: ?>
                                            Posted on <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if (!empty($post['description'])): ?>
                                    <div class="post-description prose prose-sm max-w-none"><?php echo $post['description']; ?></div>
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
                                    <?php if ($post['type'] == 'quiz'): ?>
                                        <?php if (!empty($post['submission_id'])): ?>
                                            <div class="flex items-center mr-4">
                                                <span class="text-success-700 font-bold mr-2">
                                                    <i class="fas fa-check-circle"></i> 
                                                    Score: <?php echo floatval($post['grade']); ?> / <?php echo floatval($post['points']); ?>
                                                </span>
                                                <a href="<?php echo site_url('/quiz/' . $post['assignment_id'] . '/results'); ?>" class="text-sm text-blue-600 hover:underline">
                                                    View Details
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <a href="<?php echo site_url('/quiz/' . $post['assignment_id']); ?>" class="btn btn-success mr-2">
                                                <i class="fas fa-clock mr-1"></i> Take Quiz
                                            </a>
                                        <?php endif; ?>
                                    <?php elseif ($post['type'] == 'assignment' || $post['type'] == 'activity'): ?>
                                        <a href="<?php echo site_url('/assignment/' . $post['assignment_id']); ?>" class="btn btn-primary mr-2">
                                            <?php echo !empty($post['submission_id']) ? 'View Submission' : 'Submit Work'; ?>
                                        </a>
                                    <?php endif; ?>

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
        
        <div id="tab-panel-activities" class="tab-panel">
            <div class="space-y-6">
                <?php if (empty($activities)): ?>
                    <p class="text-neutral-500 p-6 text-center card">No activities have been posted yet.</p>
                <?php else: ?>
                    <?php foreach ($activities as $post): ?>
                        <div class="post-card">
                            <div class="post-icon bg-yellow-500"><i class="fas fa-gamepad fa-lg"></i></div>
                            <div class="post-content">
                                <div>
                                    <a href="<?php echo site_url('/assignment/' . $post['assignment_id']); ?>" class="post-title"><?php echo htmlspecialchars($post['title']); ?></a>
                                    <div class="post-meta">
                                        Due: <?php echo date('M d, Y @ g:i A', strtotime($post['due_date'])); ?>
                                        <span class="mx-1">&bull;</span>
                                        <?php echo htmlspecialchars($post['points']); ?> pts
                                    </div>
                                </div>
                                <?php if (!empty($post['description'])): ?>
                                    <div class="post-description prose prose-sm max-w-none"><?php echo $post['description']; ?></div>
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
                                    <?php if ($post['type'] == 'quiz'): ?>
                                        <?php if (!empty($post['submission_id'])): ?>
                                            <div class="flex items-center mr-4">
                                                <span class="text-success-700 font-bold mr-2">
                                                    <i class="fas fa-check-circle"></i> 
                                                    Score: <?php echo floatval($post['grade']); ?> / <?php echo floatval($post['points']); ?>
                                                </span>
                                                <a href="<?php echo site_url('/quiz/' . $post['assignment_id'] . '/results'); ?>" class="text-sm text-blue-600 hover:underline">
                                                    View Details
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <a href="<?php echo site_url('/quiz/' . $post['assignment_id']); ?>" class="btn btn-success mr-2">
                                                <i class="fas fa-clock mr-1"></i> Take Quiz
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="<?php echo site_url('/assignment/' . $post['assignment_id']); ?>" class="btn btn-primary mr-2">
                                            <?php echo !empty($post['submission_id']) ? 'View Submission' : 'Submit Work'; ?>
                                        </a>
                                    <?php endif; ?>

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
            <div class="space-y-6">
                <?php if (empty($assignments)): ?>
                    <p class="text-neutral-500 p-6 text-center card">No formal assignments have been posted yet.</p>
                <?php else: ?>
                    <?php foreach ($assignments as $post): ?>
                        <div class="post-card">
                            <?php if($post['type'] == 'quiz'): ?>
                                <div class="post-icon bg-purple-600"><i class="fas fa-puzzle-piece fa-lg"></i></div>
                            <?php else: ?>
                                <div class="post-icon bg-primary-600"><i class="fas fa-tasks fa-lg"></i></div>
                            <?php endif; ?>

                            <div class="post-content">
                                <div>
                                    <?php if ($post['type'] == 'quiz'): ?>
                                         <a href="<?php echo site_url('/quiz/' . $post['assignment_id']); ?>" class="post-title"><?php echo htmlspecialchars($post['title']); ?></a>
                                    <?php else: ?>
                                         <a href="<?php echo site_url('/assignment/' . $post['assignment_id']); ?>" class="post-title"><?php echo htmlspecialchars($post['title']); ?></a>
                                    <?php endif; ?>
                                    <div class="post-meta">
                                        Due: <?php echo date('M d, Y @ g:i A', strtotime($post['due_date'])); ?>
                                        <span class="mx-1">&bull;</span>
                                        <?php echo htmlspecialchars($post['points']); ?> pts
                                    </div>
                                </div>
                                <?php if (!empty($post['description'])): ?>
                                    <div class="post-description prose prose-sm max-w-none"><?php echo $post['description']; ?></div>
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
                                    <?php if ($post['type'] == 'quiz'): ?>
                                        <?php if (!empty($post['submission_id'])): ?>
                                            <div class="flex items-center mr-4">
                                                <span class="text-success-700 font-bold mr-2">
                                                    <i class="fas fa-check-circle"></i> 
                                                    Score: <?php echo floatval($post['grade']); ?> / <?php echo floatval($post['points']); ?>
                                                </span>
                                                <a href="<?php echo site_url('/quiz/' . $post['assignment_id'] . '/results'); ?>" class="text-sm text-blue-600 hover:underline">
                                                    View Details
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <a href="<?php echo site_url('/quiz/' . $post['assignment_id']); ?>" class="btn btn-success mr-2">
                                                <i class="fas fa-clock mr-1"></i> Take Quiz
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="<?php echo site_url('/assignment/' . $post['assignment_id']); ?>" class="btn btn-primary mr-2">
                                            <?php echo !empty($post['submission_id']) ? 'View Submission' : 'Submit Work'; ?>
                                        </a>
                                    <?php endif; ?>
                                    
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
                <div class="divide-y divide-neutral-200">
                    <?php if (empty($materials)): ?>
                        <p class="text-neutral-500 p-6 text-center">No materials have been uploaded for this course yet.</p>
                    <?php else: ?>
                        <?php foreach ($materials as $material): ?>
                            <div class="p-4 flex justify-between items-center hover:bg-neutral-50">
                                <div class="flex items-center">
                                    <i class="fas fa-file-alt text-neutral-500 mr-3"></i>
                                    <a href="<?php echo base_url() . $material['file_path']; ?>" download class="text-sm font-medium text-primary-600 hover:underline">
                                        <?php echo htmlspecialchars($material['file_name']); ?>
                                    </a>
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
    // --- Main Tab Switching Logic ---
    var storageKey = 'studentCourseActiveTab_<?php echo $course['course_id']; ?>';
    var savedTab = sessionStorage.getItem(storageKey);
    const urlParams = new URLSearchParams(window.location.search);
    const urlTab = urlParams.get('tab');
    
    if (urlTab) {
        savedTab = urlTab;
        sessionStorage.setItem(storageKey, urlTab);
    }

    if (!savedTab) {
        savedTab = 'announcements'; // Default to announcements
    }

    $('.tab-link').removeClass('active');
    $('.tab-panel').removeClass('active');
    $('.tab-link[data-tab="' + savedTab + '"]').addClass('active');
    $('#tab-panel-' + savedTab).addClass('active');

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