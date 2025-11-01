<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Navigation -->
    <a href="<?php echo site_url('/dashboard'); ?>" class="text-sm text-blue-600 hover:underline mb-4 inline-block"></i>Back to Dashboard
    </a>

    <!-- Course Header -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($course['title']); ?></h1>
        <?php if (!empty($course['description'])): ?>
            <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
        <?php else: ?>
            <p class="text-gray-500 italic">No description provided for this course.</p>
        <?php endif; ?>
    </div>

    <!-- Assignments Section -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-700">Assignments</h2>
        </div>

        <div class="divide-y divide-gray-200">
            <?php if (empty($assignments)): ?>
                <p class="text-gray-500 p-6 text-center">No assignments have been posted yet.</p>
            <?php else: ?>
                <?php foreach ($assignments as $assignment): ?>
                    <div class="p-6 hover:bg-gray-50 transition duration-150">
                        <div class="flex flex-wrap justify-between items-center gap-4">
                            <!-- Assignment Details -->
                            <div class="flex-grow">
                                <h3 class="text-lg font-semibold text-blue-800"><?php echo htmlspecialchars($assignment['title']); ?></h3>
                                <div class="text-sm text-gray-500 mt-1">
                                    <span class="mr-4">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        <strong>Due:</strong> <?php echo date('M d, Y @ g:i A', strtotime($assignment['due_date'])); ?>
                                    </span>
                                    <span>
                                        <i class="fas fa-star mr-1"></i>
                                        <strong>Points:</strong> <?php echo htmlspecialchars($assignment['points']); ?>
                                    </span>
                                </div>
                                <?php if (!empty($assignment['description'])): ?>
                                    <p class="text-gray-600 mt-2 text-sm"><?php echo nl2br(htmlspecialchars($assignment['description'])); ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex-shrink-0 flex items-center gap-3">
                                <?php if (!empty($assignment['attachment_path'])): ?>
                                    <a href="<?php echo base_url() . $assignment['attachment_path']; ?>" download
                                       class="text-sm font-medium text-blue-600 hover:text-blue-800" title="Download attached file">
                                        <i class="fas fa-paperclip mr-1"></i> Download Attachment
                                    </a>
                                <?php endif; ?>
                                <!-- THIS IS THE FIXED LINK -->
                                <a href="<?php echo site_url('/assignment/' . $assignment['assignment_id']); ?>"
                                   class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md shadow text-sm transition duration-200">
                                    <i class="fas fa-upload mr-1"></i> Submit Work
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

