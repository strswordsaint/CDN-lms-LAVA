<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?php echo site_url('/assignments/all'); ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to All Assignments
    </a>

    <h1 class="text-2xl font-bold text-neutral-900 mb-6"><?php echo $page_title ?? 'View Submissions'; ?></h1>

    <?php $success_message = lava_instance()->session->flashdata('success'); ?>
    <?php if (!empty($success_message)): ?>
        <div class="notice notice-success mb-4" role="alert" style="display:block;">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
     <?php $error_message = lava_instance()->session->flashdata('error'); ?>
     <?php if (!empty($error_message)): ?>
        <div class="notice notice-error mb-4" role="alert" style="display:block;">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Student Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Submitted On
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            File
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Status
                        </th>
                         <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    <?php if (empty($submissions)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-neutral-500">
                                No submissions received yet for this assignment.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($submissions as $submission): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                    <?php echo htmlspecialchars($submission['first_name'] . ' ' . $submission['last_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                    <?php echo date('M d, Y @ g:i A', strtotime($submission['submitted_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-primary-600 hover:text-primary-800">
                                    <?php 
                                        $files = json_decode($submission['file_path'], true);
                                        if (is_array($files)) {
                                            foreach($files as $index => $file) {
                                                echo '<a href="'.base_url() . $file['file_path'].'" download class="hover:underline"><i class="fas fa-download mr-1"></i> '.htmlspecialchars($file['file_name']).'</a>';
                                                if ($index < count($files) - 1) echo '<br>'; // Add a line break if more than one file
                                            }
                                        } else if (!empty($submission['file_path'])) {
                                            // Fallback for old single-file string
                                            echo '<a href="'.base_url() . $submission['file_path'].'" download class="hover:underline"><i class="fas fa-download mr-1"></i> Download Submission</a>';
                                        }
                                    ?>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <?php if ($submission['grade'] !== null): ?>
                                        <span class="flex items-center text-success-600">
                                            <i class="fas fa-check-circle w-5 mr-2"></i>
                                            Graded: <?php echo htmlspecialchars($submission['grade']); ?>/<?php echo htmlspecialchars($assignment['points']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="flex items-center text-warning-600">
                                            <i class="fas fa-clock w-5 mr-2"></i>
                                            Awaiting Grade
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="<?php echo site_url('/submissions/' . $submission['submission_id'] . '/grade'); ?>" 
                                       class="btn <?php echo $submission['grade'] !== null ? 'btn-secondary' : 'btn-primary'; ?> py-1 px-3">
                                        <i class="fas fa-edit mr-1"></i> 
                                        <?php echo $submission['grade'] !== null ? 'Edit Grade' : 'Grade'; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>