<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* Styles for the assignment list */
    .assignment-item {
        border-bottom: 1px solid #e5e7eb; /* border-gray-200 */
    }
    .assignment-item:last-child {
        border-bottom: 0;
    }
    .btn-primary {
        background-color: #2563EB; /* blue-600 */
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 600;
        font-size: 0.875rem;
        transition: background-color 0.2s;
    }
    .btn-primary:hover { background-color: #1D4ED8; } /* blue-700 */

    .btn-secondary {
        background-color: #f3f4f6; /* gray-100 */
        color: #374151; /* gray-700 */
        padding: 0.3rem 0.75rem;
        border-radius: 0.375rem;
        font-weight: 500;
        font-size: 0.875rem;
        border: 1px solid #d1d5db; /* gray-300 */
        transition: background-color 0.2s;
    }
    .btn-secondary:hover { background-color: #e5e7eb; } /* gray-200 */
</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header & Navigation -->
    <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
        <div>
            <a href="<?php echo site_url('/courses'); ?>" class="text-sm text-blue-600 hover:underline">
                <i class="fas fa-arrow-left mr-1"></i> Back to Course List
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mt-2"><?php echo htmlspecialchars($course['title']); ?></h1>
        </div>
        <a href="<?php echo site_url('/courses/' . $course['course_id'] . '/enrollments'); ?>" class="btn-primary">
            <i class="fas fa-users mr-1"></i> Manage Enrollments
        </a>
    </div>

    <!-- Course Details -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-3">Course Details</h2>
        <p class="text-gray-600 text-sm mb-4">Created: <?php echo date('M d, Y', strtotime($course['created_at'])); ?></p>
        <?php if (!empty($course['description'])): ?>
            <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
        <?php else: ?>
             <p class="text-gray-500 italic">No description provided.</p>
        <?php endif; ?>
    </div>

    <!-- ========== THIS IS THE SECTION MISSING FROM YOUR FILE ========== -->
    <!-- Assignments Section -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-700">Assignments</h2>
            <!-- Button to create a new assignment -->
            <a href="<?php echo site_url('/courses/' . $course['course_id'] . '/assignments/create'); ?>" class="btn-primary">
                <i class="fas fa-plus mr-1"></i> New Assignment
            </a>
        </div>

        <div class="divide-y divide-gray-200">
            <?php if (empty($assignments)): ?>
                <p class="text-gray-500 p-6 text-center">No assignments have been created for this course yet.</p>
            <?php else: ?>
                <?php foreach ($assignments as $assignment): ?>
                    <div class="p-6 hover:bg-gray-50 transition duration-150 assignment-item">
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
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex-shrink-0 flex items-center gap-3">
                                <a href="<?php echo site_url('/assignments/' . $assignment['assignment_id'] . '/submissions'); ?>" class="btn-secondary">
                                    View Submissions
                                </a>
                                <a href="<?php echo site_url('/assignments/edit/' . $assignment['assignment_id']); ?>" class="btn-secondary">
                                    Edit
                                </a>
                                <!-- We can add a Delete button here later -->
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <!-- ========== END OF MISSING SECTION ========== -->

    <!-- Other sections like Quizzes, Discussions, etc. can be added here later -->

</div>

<?php include 'app/views/layouts/footer.php'; ?>