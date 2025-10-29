<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; // ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* ... styles ... */
    .input-field { /* */
        border: 1px solid #cbd5e1;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input-field:focus { /* */
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
        outline: none;
    }
</style>

<div class="max-w-2xl mx-auto mt-8 mb-8 bg-white p-6 md:p-8 rounded-lg shadow-lg border border-gray-200">
    <!-- ADDED: Back Button -->
    <a href="<?php echo site_url('/courses'); ?>" class="text-sm text-blue-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Course List
    </a>

    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6"><?php echo $page_title ?? 'Create New Course'; ?></h2>

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

    <form action="<?php echo site_url('/courses/store'); ?>" method="POST">
        <?php echo csrf_field(); // Add CSRF protection ?>

        <div class="mb-5">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Course Title <span class="text-red-500">*</span></label>
            <input type="text" id="title" name="title" required maxlength="255"
                   class="input-field w-full px-4 py-2 rounded-md focus:outline-none"
                   value="" placeholder="e.g., Introduction to Programming">
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea id="description" name="description" rows="5" maxlength="5000"
                      class="input-field w-full px-4 py-2 rounded-md focus:outline-none"
                      placeholder="Enter a brief summary of the course content..."
            ></textarea>
            <small class="text-xs text-gray-500">Optional. A summary helps students understand the course.</small>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                <i class="fas fa-plus mr-1"></i> Create Course
            </button>
            <a href="<?php echo site_url('/courses'); ?>" class="text-sm text-gray-600 hover:text-blue-700 transition duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php include 'app/views/layouts/footer.php'; // ?>