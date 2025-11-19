<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8 max-w-2xl">
    <a href="<?php echo site_url('/admin/courses'); ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Courses
    </a>

    <div class="card p-6">
        <h1 class="text-2xl font-bold text-neutral-900 mb-6">Create Course & Appoint Teacher</h1>

        <form action="<?php echo site_url('/admin/courses/store'); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Course Title</label>
                <input type="text" name="title" required class="form-input w-full border rounded-md p-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Description (Optional)</label>
                <textarea name="description" rows="3" class="form-input w-full border rounded-md p-2"></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Appoint Teacher <span class="text-red-500">*</span></label>
                <select name="teacher_id" required class="form-select w-full border rounded-md p-2 bg-white">
                    <option value="">-- Select a Teacher --</option>
                    <?php foreach ($teachers as $t): ?>
                        <option value="<?php echo $t['user_id']; ?>">
                            <?php echo htmlspecialchars($t['first_name'] . ' ' . $t['last_name']); ?> 
                            (<?php echo htmlspecialchars($t['email']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-neutral-500 mt-1">This teacher will manage the course and enrollments.</p>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="<?php echo site_url('/admin/courses'); ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Course</button>
            </div>
        </form>
    </div>
</div>
<?php include 'app/views/layouts/footer.php'; ?>