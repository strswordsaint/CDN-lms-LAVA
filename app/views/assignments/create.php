<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .input-field {
        border: 1px solid #cbd5e1;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input-field:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
        outline: none;
    }
    
    /* Style for file input */
    .file-input {
        display: block;
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        color: #374151;
        background-color: #f9fafb;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        cursor: pointer;
    }
    .file-input:hover {
        background-color: #f3f4f6;
    }
    .file-input::file-selector-button {
        padding: 0.5rem 1rem;
        margin-right: 0.75rem;
        font-weight: 500;
        color: #fff;
        background-color: #2563eb;
        border: none;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .file-input::file-selector-button:hover {
        background-color: #1d4ed8;
    }
</style>

<div class="max-w-3xl mx-auto mt-8 mb-8">
    <a href="<?php echo site_url('/courses/show/' . $course['course_id']); ?>" class="text-sm text-blue-600 hover:underline mb-4 inline-block">
        &larr; Back to Course (<?php echo htmlspecialchars($course['title']); ?>)
    </a>

    <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg border border-gray-200">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6"><?php echo $page_title ?? 'Create New Assignment'; ?></h2>

        <?php if (!empty($error_message)): ?>
            <div class="notice notice-error mb-4" role="alert" style="display:block;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
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
        <form action="<?php echo site_url('/courses/' . $course['course_id'] . '/assignments/store'); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
                <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">

            <div class="mb-5">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Assignment Title <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" required
                       class="input-field w-full px-4 py-2 rounded-md"
                       placeholder="e.g., Chapter 1 Review">
            </div>

            <div class="mb-5">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Instructions (Optional)</label>
                <textarea id="description" name="description" rows="6"
                          class="input-field w-full px-4 py-2 rounded-md"
                          placeholder="Enter instructions, questions, or a summary..."></textarea>
            </div>
            
            <div class="mb-5">
                <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">Attach File (Optional)</label>
                <input type="file" name="attachments[]" id="attachment" class="file-input" multiple>
                <small class="text-xs text-gray-500">Attach instructions, a template, or reading material (e.g., PDF, DOCX, PPTX).</small>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="mb-5 md:mb-0">
                    <label for="points" class="block text-sm font-medium text-gray-700 mb-1">Points <span class="text-red-500">*</span></label>
                    <input type="number" id="points" name="points" required
                           class="input-field w-full px-4 py-2 rounded-md"
                           placeholder="e.g., 100" min="0">
                </div>

                <div class="mb-5 md:mb-0">
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date <span class="text-red-500">*</span></label>
                    <input type="datetime-local" id="due_date" name="due_date" required
                           class="input-field w-full px-4 py-2 rounded-md">
                </div>
            </div>

            <div class="text-right mt-6">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-save mr-1"></i> Create Assignment
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>