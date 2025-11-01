<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<div class_alias="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?php echo $page_title ?? 'My Courses'; ?></h1>
</div>

<div class="bg-white shadow-md rounded-lg p-6 border border-gray-200 mb-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Enroll in a New Course</h2>
    <form action="<?php echo site_url('/courses/enroll'); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="flex flex-col sm:flex-row gap-3">
            <label for="enrollment_code" class="sr-only">Enrollment Code</label>
            <input type="text" name="enrollment_code" id="enrollment_code" placeholder="Enter course enrollment code" 
                   class="input-field w-full px-4 py-2 rounded-md focus:outline-none" 
                   style="font-family: monospace; text-transform: uppercase;"
                   maxlength="10" required>
            <button type="submit" 
                    class="bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 text-sm">
                <i class="fas fa-plus mr-1"></i> Enroll
            </button>
        </div>
    </form>
</div>
<div class="bg-white shadow-md rounded-lg border border-gray-200">
    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-700">Enrolled Courses</h2>
    </div>
    <div class="divide-y divide-gray-200">
        <?php if (empty($my_courses)): ?>
            <p class="text-gray-500 p-6 text-center">You are not enrolled in any courses yet.</p>
        <?php else: ?>
            <?php foreach ($my_courses as $course): ?>
                <li class="p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-4 list-none">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($course['title']); ?></h3>
                        <p class="text-sm text-gray-500">Code: <?php echo htmlspecialchars($course['enrollment_code']); ?></p>
                    </div>
                    <div>
                        <?php if ($course['status'] == 'approved'): ?>
                            <a href="<?php echo site_url('/my-courses/' . $course['course_id']); ?>" class="text-sm font-semibold text-white bg-green-600 hover:bg-green-700 py-2 px-4 rounded-md shadow-sm">
                                View Course <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        <?php elseif ($course['status'] == 'pending'): ?>
                            <span class="text-sm font-medium text-yellow-700 bg-yellow-100 px-3 py-2 rounded-full">
                                <i class="fas fa-clock mr-1"></i> Pending Approval
                            </span>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>