<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .input-field {
        border: 1px solid #cbd5e1; /* cn-border */
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input-field:focus {
        border-color: #3b82f6; /* cn-blue-light */
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
        outline: none;
    }
</style>

<div class="max-w-2xl mx-auto mt-8 mb-8 bg-white p-6 md:p-8 rounded-lg shadow-lg border border-gray-200">
    <?php
        // Set the correct back URL based on user role
        $back_url = ($user_role == 'admin') 
            ? site_url('/admin/courses') 
            : site_url('/courses');
    ?>
    <a href="<?php echo $back_url; ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> 
        <?php echo ($user_role == 'admin') ? 'Back to All Courses' : 'Back to Course List'; ?>
    </a>
    
    <h2 class="text-2xl font-bold text-center text-neutral-900 mb-6"><?php echo $page_title ?? 'Edit Course'; ?></h2>

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
     <?php $error_message = lava_instance()->session->flashdata('error'); ?>
     <?php if (!empty($error_message)): ?>
        <div class="notice notice-error mb-4" role="alert" style="display:block;">
            <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    <?php endif; ?>

    <?php if (empty($course)): ?>
        <div class="notice notice-error mb-4" role="alert" style="display:block;">
            Course not found or you do not have permission to edit it.
        </div>
         <a href="<?php echo $back_url; ?>" class="text-sm text-primary-600 hover:underline">Return to Course List</a>
    <?php else: ?>
        <?php
            // Set the correct update URL based on user role
            $update_url = ($user_role == 'admin')
                ? site_url('/admin/courses/update/' . $course['course_id'])
                : site_url('/courses/update/' . $course['course_id']);
        ?>
        
        <form action="<?php echo $update_url; ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="mb-5">
                <label for="title" class="block text-sm font-medium text-neutral-700 mb-1">Course Title <span class="text-error-500">*</span></label>
                <input type="text" id="title" name="title" required maxlength="255"
                       class="input-field w-full px-4 py-2 rounded-md focus:outline-none"
                       value="<?php echo htmlspecialchars($course['title'] ?? ''); ?>" placeholder="e.g., Introduction to Programming">
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-neutral-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="5" maxlength="5000"
                          class="input-field w-full px-4 py-2 rounded-md focus:outline-none"
                          placeholder="Enter a brief summary of the course content..."
                ><?php echo htmlspecialchars($course['description'] ?? ''); ?></textarea>
                <small class="text-xs text-neutral-500">Optional. A summary helps students understand the course.</small>
            </div>

            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save mr-1"></i> Update Course
                </button>
                
                <?php if ($user_role == 'teacher'): ?>
                    <a href="<?php echo $back_url; ?>" class="btn btn-danger">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                <?php endif; ?>
            </div>
        </form>
        <?php if ($user_role == 'admin'): ?>
            <div class="mt-6 border-t border-error-200 pt-6">
                <h3 class="text-lg font-semibold text-error-700">Danger Zone</h3>
                <p class="text-sm text-neutral-600 mb-4">Deleting this course will permanently remove all associated assignments, submissions, and enrollments.</p>
                <form action="<?php echo site_url('/admin/courses/delete/' . $course['course_id']); ?>" method="POST" onsubmit="return confirm('Are you sure you want to PERMANENTLY delete this course? This will also delete all assignments, submissions, and enrolled students. This action cannot be undone.');">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt mr-1"></i> Delete This Course
                    </button>
                </form>
            </div>
        <?php endif; ?>
        <?php endif; ?>
</div>

<?php include 'app/views/layouts/footer.php'; ?>