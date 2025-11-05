<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?php echo site_url('/dashboard'); ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
    </a>

    <h1 class="text-2xl font-bold text-neutral-900 mb-6"><?php echo $page_title ?? 'Manage All Courses'; ?></h1>

    <?php if (!empty($success_message)): ?>
        <div class="notice notice-success mb-4" role="alert" style="display:block;">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
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
                        <th class_A="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Course Title
                        </th>
                        <th class_A="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Teacher
                        </th>
                        <th class_A="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Students
                        </th>
                         <th class_A="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    <?php if (empty($all_courses)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-neutral-500">
                                No courses found in the system.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($all_courses as $course): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                    <?php echo htmlspecialchars($course['title']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                    <?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-neutral-500">
                                    <?php echo $course['student_count'] ?? 0; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                    <a href="<?php echo site_url('/admin/courses/view/' . $course['course_id']); ?>" title="View Details" class="btn btn-secondary py-1 px-3">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                    <a href="<?php echo site_url('/admin/courses/edit/' . $course['course_id']); ?>" title="Edit Course" class="btn btn-primary py-1 px-3">
                                        <i class="fas fa-edit mr-1"></i> Edit
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