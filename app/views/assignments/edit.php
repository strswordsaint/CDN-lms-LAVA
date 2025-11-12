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
    .file-input {
        display: block; width: 100%; padding: 0.75rem 1rem; font-size: 0.875rem;
        color: #374151; background-color: #f9fafb; border: 1px solid #d1d5db;
        border-radius: 0.375rem; cursor: pointer;
    }
</style>

<div class="max-w-3xl mx-auto mt-8 mb-8">
    <a href="<?php echo site_url('/courses/show/' . $assignment['course_id']); ?>" class="text-sm text-blue-600 hover:underline mb-4 inline-block">
        &larr; Back to Course
    </a>

    <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg border border-gray-200">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6"><?php echo $page_title ?? 'Edit Assignment'; ?></h2>

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
        <form action="<?php echo site_url('/assignments/update/' . $assignment['assignment_id']); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="mb-5">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Assignment Title <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" required
                       class="input-field w-full px-4 py-2 rounded-md"
                       value="<?php echo htmlspecialchars($assignment['title'] ?? ''); ?>">
            </div>

            <div class="mb-5">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
                <textarea id="description" name="description" rows="6"
                    class="input-field w-full px-4 py-2 rounded-md"
                ><?php echo $assignment['description'] ?? ''; ?></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="mb-5 md:mb-0">
                    <label for="points" class="block text-sm font-medium text-gray-700 mb-1">Points <span class="text-red-500">*</span></label>
                    <input type="number" id="points" name="points" required
                           class="input-field w-full px-4 py-2 rounded-md"
                           value="<?php echo htmlspecialchars($assignment['points'] ?? '100'); ?>" min="0">
                </div>

                <div class="mb-5 md:mb-0">
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date <span class="text-red-500">*</span></label>
                    <?php 
                        $due_date_formatted = '';
                        if (!empty($assignment['due_date'])) {
                            $due_date_formatted = date('Y-m-d\TH:i', strtotime($assignment['due_date']));
                        }
                    ?>
                    <input type="datetime-local" id="due_date" name="due_date" required
                           class="input-field w-full px-4 py-2 rounded-md"
                           value="<?php echo $due_date_formatted; ?>">
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Attachments</label>
                <?php if (empty($attachments)): ?>
                    <p class="text-sm text-gray-500">No files are currently attached.</p>
                <?php else: ?>
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($attachments as $file): ?>
                            <li class="text-sm">
                                <a href="<?php echo base_url() . $file['file_path']; ?>" download class="text-blue-600 hover:underline">
                                    <i class="fas fa-paperclip mr-1"></i>
                                    <?php echo htmlspecialchars($file['file_name']); ?>
                                </a>
                                </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="mb-5">
                <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">Add More Files (Optional)</label>
                <input type="file" name="attachments[]" id="attachment" class="file-input" multiple>
                <small class="text-xs text-gray-500">Adding new files will append them to the existing list.</small>
            </div>

            <div class="text-right mt-6">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-5 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-save mr-1"></i> Update Assignment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    tinymce.init({
        selector: 'textarea#description', // Targets the "description" textarea
        plugins: 'lists link media',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link media',
        media_dimensions: false,
        media_live_embeds: true,
        height: 300,
        license_key: 'gpl'
    });
</script>

<?php include 'app/views/layouts/footer.php'; ?>