<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard</h1>
<p class="text-lg text-gray-600 mb-8">Welcome, <?php echo htmlspecialchars($first_name); ?>!</p>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex items-center">
        <i class="fas fa-users text-3xl text-blue-500 mr-4"></i>
        <div>
            <div class="text-sm text-gray-500">Total Users</div>
            <div class="text-2xl font-bold text-gray-800"><?php echo $stats['total_users'] ?? 0; ?></div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex items-center">
        <i class="fas fa-user-graduate text-3xl text-green-500 mr-4"></i>
        <div>
            <div class="text-sm text-gray-500">Students</div>
            <div class="text-2xl font-bold text-gray-800"><?php echo $stats['total_students'] ?? 0; ?></div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex items-center">
        <i class="fas fa-chalkboard-teacher text-3xl text-yellow-500 mr-4"></i>
        <div>
            <div class="text-sm text-gray-500">Teachers</div>
            <div class="text-2xl font-bold text-gray-800"><?php echo $stats['total_teachers'] ?? 0; ?></div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex items-center">
        <i class="fas fa-book-open text-3xl text-indigo-500 mr-4"></i>
        <div>
            <div class="text-sm text-gray-500">Total Courses</div>
            <div class="text-2xl font-bold text-gray-800"><?php echo $stats['total_courses'] ?? 0; ?></div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md border border-gray-200">
    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-700">Quick Links</h2>
    </div>
    <div class="divide-y divide-gray-200">
        <a href="<?php echo site_url('/admin/users'); ?>" class="p-4 flex justify-between items-center hover:bg-gray-50">
            <div>
                <h3 class="font-medium text-gray-800">Manage Users</h3>
                <p class="text-sm text-gray-500">Edit, delete, and view all users in the system.</p>
            </div>
            <i class="fas fa-arrow-right text-gray-400"></i>
        </a>
        
        <a href="<?php echo site_url('/admin/courses'); ?>" class="p-4 flex justify-between items-center hover:bg-gray-50">
            <div>
                <h3 class="font-medium text-gray-800">Manage Courses</h3>
                <p class="text-sm text-gray-500">View all courses, manage enrollments, and edit details.</p>
            </div>
            <i class="fas fa-arrow-right text-gray-400"></i>
        </a>

        <a href="#" class="p-4 flex justify-between items-center hover:bg-gray-50">
            <div>
                <h3 class="font-medium text-gray-800">Post Announcements</h3>
                <p class="text-sm text-gray-500">Create site-wide announcements.</p>
            </div>
            <i class="fas fa-arrow-right text-gray-400"></i>
        </a>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>