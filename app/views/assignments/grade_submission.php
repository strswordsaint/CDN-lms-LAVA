<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Navigation -->
    <a href="<?php echo site_url('/assignments/' . $submission['assignment_id'] . '/submissions'); ?>" class="text-sm text-blue-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Submissions List (<?php echo htmlspecialchars($submission['assignment_title']); ?>)
    </a>

    <h1 class="text-2xl font-bold text-gray-800 mb-6"><?php echo $page_title ?? 'Grade Submission'; ?></h1>

    <!-- Display Validation Errors -->
    <?php if (!empty($validation_errors)): ?>
        <div class="notice notice-error mb-4" role="alert" style="display:block;">
            <p class="font-bold mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside">
                <?php foreach ($validation_errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <!-- End Validation Errors -->

    <!-- Submission Details -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Submission Details</h2>
        <p><strong>Student:</strong> <?php echo htmlspecialchars($submission['first_name'] . ' ' . $submission['last_name']); ?> (<?php echo htmlspecialchars($submission['email']); ?>)</p>
        <p><strong>Submitted On:</strong> <?php echo date('M d, Y @ g:i A', strtotime($submission['submitted_at'])); ?></p>
        <p class="mt-3">
            <a href="<?php echo base_url() . $submission['file_path']; ?>" download class="text-sm font-medium text-blue-600 hover:text-blue-800">
                <i class="fas fa-download mr-1"></i> Download Submitted File
            </a>
        </p>
    </div>

    <!-- Grading Form -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Enter Grade and Feedback</h2>
        <form action="<?php echo site_url('/submissions/' . $submission['submission_id'] . '/grade'); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">
                    Grade <span class="text-red-500">*</span> (Out of <?php echo htmlspecialchars($submission['assignment_points']); ?>)
                </label>
                <input type="number" id="grade" name="grade"
                       value="<?php echo htmlspecialchars($submission['grade'] ?? ''); ?>"
                       min="0" max="<?php echo htmlspecialchars($submission['assignment_points']); ?>"
                       class="form-input w-full sm:w-1/4" required>
            </div>

            <div class="mb-4">
                <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1">
                    Feedback (Optional)
                </label>
                <textarea id="feedback" name="feedback" rows="5"
                          class="form-textarea w-full"><?php echo htmlspecialchars($submission['feedback'] ?? ''); ?></textarea>
            </div>

            <div class="text-right">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-5 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-save mr-1"></i> Save Grade
                </button>
            </div>
        </form>
    </div>

</div>

<?php include 'app/views/layouts/footer.php'; ?>