<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* Style for the new input field */
    .input-field {
        border: 1px solid #cbd5e1;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input-field:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
        outline: none;
    }

    /* Existing button style from your file */
    .btn-danger-sm {
        background-color: #f3f4f6; color: #DC2626; padding: 0.5rem 0.75rem;
        border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem;
        border: 1px solid #d1d5db; transition: all 0.2s; cursor: pointer;
    }
    .btn-danger-sm:hover { background-color: #fee2e2; border-color: #fca5a5; }
</style>

<div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?php echo $page_title ?? 'My Courses'; ?></h1>
</div>

<div class="bg-white shadow-md rounded-lg border border-gray-200 p-6 mb-6">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">Enroll in a New Course</h2>
    <form action="<?php echo site_url('/courses/enroll'); ?>" method="POST" class="flex flex-col sm:flex-row gap-3">
        <?php echo csrf_field(); ?>
        <label for="enrollment_code" class="sr-only">Enrollment Code</label>
        <input type="text" id="enrollment_code" name="enrollment_code"
               class="input-field flex-grow px-4 py-2 rounded-md"
               placeholder="Enter course enrollment code..." required>
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm transition duration-200">
            <i class="fas fa-plus mr-1"></i> Join Course
        </button>
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

                    <div class="flex items-center gap-3">
                        <?php if ($course['status'] == 'approved'): ?>
                            <a href="<?php echo site_url('/my-courses/' . $course['course_id']); ?>" class="text-sm font-semibold text-white bg-green-600 hover:bg-green-700 py-2 px-4 rounded-md shadow-sm">
                                View Course
                            </a>
                            <form action="<?php echo site_url('/courses/leave/' . $course['enrollment_id']); ?>" method="POST" onsubmit="return confirm('Are you sure you want to leave this course?');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn-danger-sm">Leave Course</button>
                            </form>

                        <?php elseif ($course['status'] == 'pending'): ?>
                            <span class="text-sm font-medium text-yellow-700 bg-yellow-100 px-3 py-2 rounded-full">
                                <i class="fas fa-clock mr-1"></i> Pending Approval
                            </span>
                            <form action="<?php echo site_url('/courses/leave/' . $course['enrollment_id']); ?>" method="POST" onsubmit="return confirm('Are you sure you want to cancel this enrollment request?');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn-danger-sm">Cancel Request</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>