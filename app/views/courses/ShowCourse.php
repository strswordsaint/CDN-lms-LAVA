<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?php echo site_url('/courses'); ?>" class="text-sm text-blue-600 hover:underline mb-4 inline-block">
        &larr; Back to Course List
    </a>

    <?php if (empty($course)): ?>
        <div class="notice notice-error mb-4" role="alert" style="display:block;">
            Course not found or you do not have permission to view it.
        </div>
    <?php else: ?>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($course['title']); ?></h1>
            <p class="text-gray-600 text-sm mb-4">Created: <?php echo date('M d, Y', strtotime($course['created_at'])); ?></p>
            <?php if (!empty($course['description'])): ?>
                <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
            <?php else: ?>
                 <p class="text-gray-500 italic">No description provided.</p>
            <?php endif; ?>
        </div>

        <!-- Section for Course Content (Modules, Lessons, Quizzes etc.) -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Course Content</h2>
            <p class="text-gray-500">
                (add modules, lessons, quizzes, assignments, etc. for this course.)
            </p>
            <div class="mt-4">
                 <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow text-sm transition duration-200">
                     <i class="fas fa-plus mr-1"></i> Add Module
                 </button>
                    <a href="<?php echo site_url('/courses/' . $course['course_id'] . '/assignments/create'); ?>" class="btn-primary ...">
                        <i class="fas fa-plus mr-1"></i> New Assignment
                    </a>
            </div>
            
            <!-- Example: List of modules would go here -->
        </div>

    <?php endif; ?>

</div>

<?php include 'app/views/layouts/footer.php'; ?>
