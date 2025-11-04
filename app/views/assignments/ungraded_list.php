<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?php echo site_url('/dashboard'); ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
    </a>

    <h1 class="text-2xl font-bold text-neutral-900 mb-6"><?php echo $page_title ?? 'Ungraded Submissions'; ?></h1>

    <?php if (empty($grouped_submissions)): ?>
        <div class="card p-6 text-center text-neutral-500">
            <i class="fas fa-check-circle text-success-500 text-4xl mb-4"></i>
            <h2 class="text-xl font-semibold text-neutral-700">All caught up!</h2>
            <p>You have no ungraded submissions.</p>
        </div>
    <?php else: ?>
        <?php foreach ($grouped_submissions as $course_title => $data): ?>
            <div class="card overflow-hidden mb-8">
                <div class="p-6 border-b bg-neutral-50">
                    <h2 class="text-xl font-semibold text-neutral-800">
                        <a href="<?php echo site_url('/courses/show/' . $data['course_id']); ?>" class="hover:underline text-primary-700">
                            <?php echo htmlspecialchars($course_title); ?>
                        </a>
                    </h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Assignment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Submitted On</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            <?php foreach ($data['submissions'] as $submission): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                        <?php echo htmlspecialchars($submission['first_name'] . ' ' . $submission['last_name']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                        <?php echo htmlspecialchars($submission['assignment_title']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                        <?php echo date('M d, Y @ g:i A', strtotime($submission['submitted_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="<?php echo site_url('/submissions/' . $submission['submission_id'] . '/grade'); ?>" class="btn btn-primary py-1 px-3">
                                            <i class="fas fa-edit mr-1"></i> Grade
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'app/views/layouts/footer.php'; ?>