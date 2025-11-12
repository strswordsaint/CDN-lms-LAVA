<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* Re-using post styles from your course page */
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
    .post-meta {
        @apply text-xs text-neutral-500 mt-1;
    }
    .post-description {
        @apply text-sm text-neutral-700 mt-3;
    }
    .delete-form {
        display: inline-block;
        margin-left: 0.5rem;
    }
</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?php echo site_url('/dashboard'); ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
    </a>
    <h1 class="text-2xl font-bold text-neutral-900 mb-6">Manage Site Announcements</h1>

    <?php if (!empty($success_message)): ?>
        <div class="notice notice-success mb-4" role="alert" style="display:block;">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error_message)): ?>
         <div class="notice notice-error mb-4" role="alert" style="display:block;">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <div class="card p-6 mb-6">
        <form action="<?php echo site_url('/admin/announcements/store'); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div classs="space-y-4">
                <div>
                    <label for="title" class="block text-lg font-semibold text-neutral-700 mb-2">New Site Announcement</label>
                    <input type="text" id="title" name="title" class="form-input" required placeholder="Announcement Title">
                </div>
                
                <div class="mt-4">
                    <label for="content" class="block text-sm font-medium text-neutral-700 mb-1">Content</label>
                    <textarea id="content" name="content" rows="5" class="form-textarea" placeholder="Write your announcement..."></textarea>
                </div>
                
                <div class="text-right mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane mr-2"></i>Post to Everyone
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        <?php if (empty($announcements)): ?>
            <p class="text-neutral-500 p-6 text-center card">No site-wide announcements have been posted yet.</p>
        <?php else: ?>
            <?php foreach ($announcements as $post): ?>
                <div class="post-card">
                    <div class="post-icon bg-red-600"><i class="fas fa-broadcast-tower fa-lg"></i></div>
                    
                    <div class="post-content">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="post-title"><?php echo htmlspecialchars($post['title']); ?></span>
                                <div class="post-meta">
                                    Posted by <?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?>
                                    on <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                                </div>
                            </div>
                            <form action="<?php echo site_url('/admin/announcements/delete/' . $post['announcement_id']); ?>" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                <?php echo csrf_field(); ?> 
                                <button type="submit" title="Delete Post" class="text-neutral-400 hover:text-error-600"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                        <?php if (!empty($post['content'])): ?>
                            <div class="post-description"><?php echo nl2br(htmlspecialchars($post['content'])); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>