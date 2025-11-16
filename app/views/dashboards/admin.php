<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard</h1>
<p class="text-lg text-gray-600 mb-8">Welcome, <?php echo htmlspecialchars($first_name); ?>!</p>

<?php if (!empty($site_announcements)): ?>
    <div class="space-y-4 mb-8">
        <?php foreach (array_slice($site_announcements, 0, 2) as $post): // Show newest 2 ?>
             <div class="card p-5 flex space-x-4 border-l-4 border-red-600">
                <div class="post-icon bg-red-600 flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-white">
                    <i class="fas fa-broadcast-tower fa-lg"></i>
                </div>
                <div class="post-content flex-1">
                    <span class="post-title text-lg font-semibold text-neutral-900"><?php echo htmlspecialchars($post['title']); ?></span>
                    <div class="post-meta text-xs text-neutral-500 mt-1">
                        Posted by Admin <?php echo htmlspecialchars($post['first_name']); ?>
                        on <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                    </div>
                    <div class="post-description text-sm text-neutral-700 mt-2"><?php echo nl2br(htmlspecialchars($post['content'])); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
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

        <a href="<?php echo site_url('/admin/reports'); ?>" class="p-4 flex justify-between items-center hover:bg-gray-50">
            <div>
                <h3 class="font-medium text-gray-800">General Reports</h3>
                <p class="text-sm text-gray-500">View site-wide totals and master lists.</p>
            </div>
            <i class="fas fa-arrow-right text-gray-400"></i>
        </a>

        <a href="<?php echo site_url('/admin/announcements'); ?>" class="p-4 flex justify-between items-center hover:bg-gray-50">
            <div>
                <h3 class="font-medium text-gray-800">Post Announcements</h3>
                <p class="text-sm text-gray-500">Create site-wide announcements.</p>
            </div>
            <i class="fas fa-arrow-right text-gray-400"></i>
        </a>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>