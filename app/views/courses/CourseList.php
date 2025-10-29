<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* Simple icon styling */
    .action-icon {
        display: inline-block;
        width: 1.5rem; /* Adjust size as needed */
        text-align: center;
    }
    .action-icon:hover {
        opacity: 0.7;
    }
    .text-green-600 { color: #16A34A; }
    .text-blue-600 { color: #2563EB; }
    .text-red-600 { color: #DC2626; }
    .text-yellow-600 { color: #CA8A04; } /* NEW: For enrollments button */

    /* Style for delete form */
    .delete-form {
        display: inline; /* Keep delete button inline */
    }
    .delete-button {
        background: none;
        border: none;
        padding: 0;
        margin: 0;
        cursor: pointer;
        color: #DC2626; /* Red color */
    }
     .delete-button:hover {
         opacity: 0.7;
     }

</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800"><?php echo $page_title ?? 'My Courses'; ?></h1>
        <a href="<?php echo site_url('/courses/create'); ?>" class="bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 text-sm">
            <i class="fas fa-plus mr-1"></i> Create New Course
        </a>
    </div>

    <!-- Display Flash Messages -->
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
    <!-- End Flash Messages -->

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Title
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                            Description
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                            Enrollment Code
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                            Created On
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($courses)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                You haven't created any courses yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-800">
                                    <a href="<?php echo site_url('/courses/show/' . $course['course_id']); ?>" class="hover:underline">
                                        <?php echo htmlspecialchars($course['title']); ?>
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-600 max-w-sm truncate hidden sm:table-cell">
                                    <?php echo htmlspecialchars(substr($course['description'] ?? '', 0, 80)) . (strlen($course['description'] ?? '') > 80 ? '...' : ''); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-mono hidden md:table-cell">
                                    <?php echo htmlspecialchars($course['enrollment_code']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                    <?php echo date('M d, Y', strtotime($course['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                    <!-- Manage Course Content -->
                                    <a href="<?php echo site_url('/courses/show/' . $course['course_id']); ?>" title="Manage Course Content" class="action-icon text-green-600">
                                        <i class="fas fa-folder-open"></i>
                                    </a>
                                    
                                    <!-- NEW: Manage Enrollments Button -->
                                    <a href="<?php echo site_url('/courses/' . $course['course_id'] . '/enrollments'); ?>" title="Manage Enrollments" class="action-icon text-yellow-600">
                                        <i class="fas fa-users"></i>
                                    </a>

                                    <!-- Edit Course Details -->
                                    <a href="<?php echo site_url('/courses/edit/' . $course['course_id']); ?>" title="Edit Course Details" class="action-icon text-blue-600">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Delete Course -->
                                    <form action="<?php echo site_url('/courses/delete/' . $course['course_id']); ?>" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this course? This action cannot be undone.');">
                                        <?php echo csrf_field(); ?> 
                                        <button type="submit" title="Delete Course" class="delete-button">
                                            <i class="fas fa-trash-alt action-icon"></i>
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