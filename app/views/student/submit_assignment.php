<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
/* Style for file input from create assignment view */
.file-input {
    display: block; width: 100%; padding: 0.75rem 1rem; font-size: 0.875rem;
    color: #374151; background-color: #f9fafb; border: 1px solid #d1d5db;
    border-radius: 0.375rem; cursor: pointer;
}
.file-input:hover { background-color: #f3f4f6; }
.file-input::file-selector-button {
    padding: 0.5rem 1rem; margin-right: 0.75rem; font-weight: 500; color: #fff;
    background-color: #2563eb; border: none; border-radius: 0.25rem; cursor: pointer;
    transition: background-color 0.2s;
}
.file-input::file-selector-button:hover { background-color: #1d4ed8; }

/* Styles for submitted file info */
.submitted-info {
    background-color: #f3f4f6; /* gray-100 */
    border: 1px solid #d1d5db; /* gray-300 */
    padding: 1rem;
    border-radius: 0.375rem;
    margin-top: 1rem;
}
</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?php echo site_url('/my-assignments'); ?>" class="text-sm text-blue-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to All Assignments
    </a>

    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($assignment['title']); ?></h1>
        <div class="text-sm text-gray-500 mb-4">
            <span class="mr-4"><i class="fas fa-calendar-alt mr-1"></i> Due: <?php echo date('M d, Y @ g:i A', strtotime($assignment['due_date'])); ?></span>
            <span><i class="fas fa-star mr-1"></i> Points: <?php echo htmlspecialchars($assignment['points']); ?></span>
        </div>
        <?php if (!empty($assignment['description'])): ?>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Instructions</h3>
            <div class="prose prose-sm max-w-none text-neutral-700"><?php echo $assignment['description']; ?></div>        <?php endif; ?>
        
        <?php // --- FIX for multiple attachments ---
        if (!empty($assignment['attachments'])): ?>
            <div class="mt-4">
                 <h4 class="text-sm font-medium text-gray-600 mb-1">Attached Files:</h4>
                 <ul class="list-disc list-inside space-y-1">
                    <?php foreach ($assignment['attachments'] as $file): ?>
                        <li class="ml-4">
                            <a href="<?php echo base_url() . $file['file_path']; ?>" download class="text-sm text-blue-600 hover:underline">
                                <i class="fas fa-paperclip mr-1"></i>
                                <?php echo htmlspecialchars($file['file_name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Your Submission</h2>

        <?php if (!empty($error_message)): ?>
            <div class="notice notice-error mb-4" role="alert" style="display:block;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
         <?php $success_message = lava_instance()->session->flashdata('success'); ?>
         <?php if (!empty($success_message)): ?>
            <div class="notice notice-success mb-4" role="alert" style="display:block;">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($submission): // If already submitted ?>
            <div class="submitted-info">
                <p class="font-semibold text-green-700"><i class="fas fa-check-circle mr-2"></i>Submitted On: <?php echo date('M d, Y @ g:i A', strtotime($submission['submitted_at'])); ?></p>
                
                <?php // --- NEW: Handle JSON-encoded file paths ---
                    $files = json_decode($submission['file_path'], true);
                    
                    if (is_array($files)) {
                        echo '<p class="mt-2 font-medium text-sm text-gray-700">Submitted Files:</p><ul class="list-disc list-inside space-y-1 mt-1">';
                        foreach($files as $file) {
                            echo '<li class="ml-4"><a href="'.base_url() . $file['file_path'].'" download class="text-sm font-medium text-blue-600 hover:text-blue-800"><i class="fas fa-download mr-1"></i> '.htmlspecialchars($file['file_name']).'</a></li>';
                        }
                        echo '</ul>';
                    } else if (!empty($submission['file_path'])) {
                        // Fallback for old single-file string
                        echo '<p class="mt-2"><a href="'.base_url() . $submission['file_path'].'" download class="text-sm font-medium text-blue-600 hover:text-blue-800"><i class="fas fa-download mr-1"></i> Download Your Submission</a></p>';
                    }
                // --- END NEW FILE LOGIC ---
                ?>

                <?php if ($submission['grade'] !== null): ?>
                    <p class="mt-2 font-semibold">Grade: <?php echo htmlspecialchars($submission['grade']); ?> / <?php echo htmlspecialchars($assignment['points']); ?></p>
                     <?php if (!empty($submission['feedback'])): ?>
                        <p class="mt-2 text-sm text-gray-700"><strong>Feedback:</strong> <?php echo nl2br(htmlspecialchars($submission['feedback'])); ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="mt-4 text-sm text-gray-600">Your work is awaiting grading.</p>
                    
                    <form action="<?php echo site_url('/assignment/unsubmit/' . $submission['submission_id']); ?>" method="POST" class="mt-4" onsubmit="return confirm('Are you sure you want to unsubmit? This will delete your files and allow you to re-upload.');">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium p-2 rounded-md bg-red-100 hover:bg-red-200 transition-colors">
                            <i class="fas fa-times-circle mr-1"></i> Unsubmit Assignment
                        </button>
                    </form>
                    <?php endif; ?>
            </div>

        <?php else: // If not yet submitted ?>
            <form action="<?php echo site_url('/assignment/' . $assignment['assignment_id'] . '/submit'); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                
                <div class="mb-4">
                    <label for="submission_file" class="block text-sm font-medium text-gray-700 mb-1">Select File(s) to Upload <span class="text-red-500">*</span></label>
                    <input type="file" name="submission_files[]" id="submission_file" class="file-input" multiple required>
                    <small class="text-xs text-gray-500">You can select multiple files. Allowed types: PDF, DOCX, DOC, TXT, JPG, PNG, ZIP, PPT, PPTX</small>
                </div>

                <div class="text-right">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                        <i class="fas fa-upload mr-1"></i> Submit Assignment
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>

</div>

<?php include 'app/views/layouts/footer.php'; ?>