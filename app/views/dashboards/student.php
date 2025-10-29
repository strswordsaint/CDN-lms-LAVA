<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<!-- This container comes from header.php -->
<!-- <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8"> -->

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Student Dashboard</h1>
    <p class="text-gray-700 mb-4">Welcome, <?php echo htmlspecialchars($first_name ?? 'Student'); ?>!</p>
    
    <!-- NEW: Enrollment Form -->
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
    <!-- End Enrollment Form -->

    <div class="mt-8 bg-white shadow-md rounded-lg p-6 border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">My Courses</h2>
        
        <?php $my_courses = lava_instance()->Enrollment_Model->get_student_courses(lava_instance()->session->userdata('user_id')); ?>

        <?php if (empty($my_courses)): ?>
            <p class="text-gray-500">You are not enrolled in any courses yet.</p>
        <?php else: ?>
            <ul class="divide-y divide-gray-200">
                <?php foreach ($my_courses as $course): ?>
                    <li class="py-4 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($course['title']); ?></h3>
                            <p class="text-sm text-gray-500">Code: <?php echo htmlspecialchars($course['enrollment_code']); ?></p>
                        </div>
                        <div>
                            <?php if ($course['status'] == 'approved'): ?>
                                <a href="<?php echo site_url('/my-courses/' . $course['course_id']); ?>" class="text-sm font-semibold text-green-600 hover:text-green-800">
                                    View Course <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            <?php elseif ($course['status'] == 'pending'): ?>
                                <span class="text-sm font-medium text-yellow-600 bg-yellow-100 px-3 py-1 rounded-full">
                                    Pending Approval
                                </span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
<!-- This container is closed in footer.php -->
<!-- </div> -->
<?php include 'app/views/layouts/footer.php'; ?>