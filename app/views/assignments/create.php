<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<div class="max-w-3xl mx-auto mt-8 mb-8">
    <a href="<?php echo site_url('/courses/show/' . $course['course_id'] . '?tab=assignments'); ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Course
    </a>

    <div class="card p-6 md:p-8">
        <h2 class="text-2xl font-bold text-center text-neutral-800 mb-6"><?php echo $page_title ?? 'Create New Assignment'; ?></h2>

        <?php $validation_errors = lava_instance()->session->flashdata('validation_errors'); ?>
        <?php if (!empty($validation_errors)): ?>
            <div class="notice notice-error mb-4" role="alert" style="display:block;">
                <strong class="font-bold">Please fix the following errors:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    <?php foreach ($validation_errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="<?php echo site_url('/courses/' . $course['course_id'] . '/assignments/store'); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">

            <div>
                <label for="title" class="block text-sm font-medium text-neutral-700 mb-1">Assignment Title <span class="text-error-500">*</span></label>
                <input type="text" id="title" name="title" required class="form-input" placeholder="e.g., Chapter 1 Review">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-neutral-700 mb-1">Instructions (Optional)</label>
                <textarea id="description" name="description" rows="6" class="form-textarea" placeholder="Enter instructions..."></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="points" class="block text-sm font-medium text-neutral-700 mb-1">Points <span class="text-error-500">*</span></label>
                    <input type="number" id="points" name="points" required class="form-input" placeholder="e.g., 100" min="0" value="100">
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-neutral-700 mb-1">Due Date <span class="text-error-500">*</span></label>
                    <input type="datetime-local" id="due_date" name="due_date" required class="form-input">
                </div>
            </div>

            <div>
                <label for="attachment" class="block text-sm font-medium text-neutral-700 mb-1">Attach Files (Optional)</label>
                <input type="file" name="attachments[]" id="attachment" class="form-input-file" multiple>
            </div>

            <div class="text-right pt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Create Assignment
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>