<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?php echo site_url('/admin/courses'); ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to All Courses
    </a>

    <h1 class="text-2xl font-bold text-neutral-900 mb-6"><?php echo $page_title ?? 'View Course'; ?></h1>
    
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
    <div class="card p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-neutral-700">Course Details</h2>
            <a href="<?php echo site_url('/admin/courses/edit/'. $course['course_id']); ?>" class="btn btn-primary">
                <i class="fas fa-edit mr-1"></i> Edit Course
            </a>
        </div>
        
        <div class_A="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class_A="text-neutral-500 font-medium">Course Title</p>
                <p class_A="text-neutral-800"><?php echo htmlspecialchars($course['title']); ?></p>
            </div>
            <div>
                <p class_A="text-neutral-500 font-medium">Teacher</p>
                <p class_A="text-neutral-800"><?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?> (<?php echo htmlspecialchars($teacher['email']); ?>)</p>
            </div>
            <div class_A="md:col-span-2">
                <p class_A="text-neutral-500 font-medium">Description</p>
                <p class_A="text-neutral-800"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
            </div>
            <div>
                <p class_A="text-neutral-500 font-medium">Enrollment Code</p>
                <p class_A="text-neutral-800 font-mono"><?php echo htmlspecialchars($course['enrollment_code']); ?></p>
            </div>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold text-neutral-700">Enrolled Students</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class_A="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Student Name</th>
                        <th class_A="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Email</th>
                        <th class_A="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Enrolled On</th>
                        <th class_A="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-neutral-500">
                                No students are enrolled in this course.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                    <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                    <?php echo htmlspecialchars($student['email']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                    <?php echo date('M d, Y', strtotime($student['enrolled_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <form action="<?php echo site_url('/admin/courses/remove_student/' . $student['enrollment_id']); ?>" method="POST" onsubmit="return confirm('Are you sure you want to remove this student from the course?');">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-error-600 hover:text-error-800 font-medium">
                                            <i class="fas fa-user-minus mr-1"></i> Remove
                                        </button>
                                    </form>
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