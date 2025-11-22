<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?php echo site_url('/admin/courses'); ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to All Courses
    </a>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h1 class="text-3xl font-bold text-neutral-900"><?php echo $page_title ?? 'View Course'; ?></h1>
        <a href="<?php echo site_url('/admin/courses/edit/'. $course['course_id']); ?>" class="btn btn-primary">
            <i class="fas fa-edit mr-2"></i> Edit Course
        </a>
    </div>
    
    <?php if (!empty($success_message)): ?>
        <div class="notice notice-success mb-4" role="alert" style="display:block;">
            <i class="fas fa-check-circle mr-2"></i> <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error_message)): ?>
        <div class="notice notice-error mb-4" role="alert" style="display:block;">
            <i class="fas fa-exclamation-circle mr-2"></i> <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <div class="card overflow-hidden mb-8 border border-neutral-200 shadow-md rounded-lg bg-white">
        <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50 flex items-center">
            <i class="fas fa-info-circle text-primary-600 mr-2"></i>
            <h2 class="text-lg font-bold text-neutral-800">Course Information</h2>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">
                        <i class="fas fa-book mr-1"></i> Course Title
                    </p>
                    <p class="text-xl font-bold text-neutral-900">
                        <?php echo htmlspecialchars($course['title']); ?>
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">
                        <i class="fas fa-chalkboard-teacher mr-1"></i> Assigned Teacher
                    </p>
                    <div class="flex items-center mt-2">
                        <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 mr-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-neutral-900">
                                <?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?>
                            </p>
                            <p class="text-xs text-neutral-500">
                                <?php echo htmlspecialchars($teacher['email']); ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                 <div>
                    <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">
                        <i class="fas fa-key mr-1"></i> Enrollment Code
                    </p>
                    <div class="inline-flex items-center px-3 py-1.5 rounded-md bg-neutral-100 border border-neutral-200">
                        <span class="font-mono text-lg font-bold text-primary-700 tracking-widest">
                            <?php echo htmlspecialchars($course['enrollment_code']); ?>
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">
                    <i class="fas fa-align-left mr-1"></i> Description
                </p>
                <div class="bg-neutral-50 rounded-lg p-4 border border-neutral-100 h-full min-h-[150px]">
                    <?php if(!empty($course['description'])): ?>
                        <div class="prose prose-sm text-neutral-700 max-w-none">
                            <?php echo nl2br(htmlspecialchars($course['description'])); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-neutral-400 italic text-sm">No description provided for this course.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card overflow-hidden border border-neutral-200 shadow-md rounded-lg bg-white">
        <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50 flex justify-between items-center">
            <div class="flex items-center">
                <i class="fas fa-users text-primary-600 mr-2"></i>
                <h2 class="text-lg font-bold text-neutral-800">Enrolled Students</h2>
                <span class="ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                    <?php echo count($students); ?>
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-neutral-500 uppercase tracking-wider">Student Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-neutral-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-neutral-500 uppercase tracking-wider">Enrolled On</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-neutral-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-user-graduate text-neutral-300 text-4xl mb-3"></i>
                                    <p class="text-lg font-medium">No students enrolled yet</p>
                                    <p class="text-sm">Share the enrollment code to invite students.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr class="hover:bg-neutral-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-neutral-200 flex items-center justify-center text-neutral-500 mr-3 font-bold text-xs">
                                            <?php echo strtoupper(substr($student['first_name'], 0, 1) . substr($student['last_name'], 0, 1)); ?>
                                        </div>
                                        <div class="text-sm font-medium text-neutral-900">
                                            <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                    <?php echo htmlspecialchars($student['email']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                    <i class="far fa-calendar-alt mr-1 text-neutral-400"></i>
                                    <?php echo date('M d, Y', strtotime($student['enrolled_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <form action="<?php echo site_url('/admin/courses/remove_student/' . $student['enrollment_id']); ?>" method="POST" onsubmit="return confirm('Are you sure you want to remove this student from the course?');" class="inline-block">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-error-600 hover:text-error-800 bg-error-50 hover:bg-error-100 px-3 py-1.5 rounded-md transition-colors duration-200 border border-transparent hover:border-error-200" title="Remove Student">
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