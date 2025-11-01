<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<h1 class="text-3xl font-bold text-gray-800 mb-6">Student Dashboard</h1>
<p class="text-lg text-gray-600 mb-8">Welcome, <?php echo htmlspecialchars($first_name ?? 'Student'); ?>!</p>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <a href="<?php echo site_url('/courses/my'); ?>" class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex items-center hover:shadow-lg transition-shadow">
        <i class="fas fa-chalkboard text-3xl text-blue-500 mr-4"></i>
        <div>
            <div class="text-sm text-gray-500">Joined Courses</div>
            <div class="text-2xl font-bold text-gray-800"><?php echo $stats['joined_courses']; ?></div>
        </div>
        <i class="fas fa-arrow-right text-gray-400 ml-auto"></i>
    </a>
    
    <a href="<?php echo site_url('/courses/my'); ?>" class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex items-center hover:shadow-lg transition-shadow">
        <i class="fas fa-file-alt text-3xl <?php echo $stats['pending_assignments'] > 0 ? 'text-yellow-500' : 'text-gray-400'; ?> mr-4"></i>
        <div>
            <div class="text-sm text-gray-500">Pending Assignments</div>
            <div class="text-2xl font-bold text-gray-800"><?php echo $stats['pending_assignments']; ?></div>
        </div>
        <i class="fas fa-arrow-right text-gray-400 ml-auto"></i>
    </a>
</div>

<div class="bg-white rounded-lg shadow-md border border-gray-200">
    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-700">Upcoming Deadlines</h2>
    </div>
    <div class="divide-y divide-gray-200">
        <?php if (empty($upcoming_assignments)): ?>
            <p class="text-gray-500 p-6 text-center">No upcoming assignments. You're all caught up!</p>
        <?php else: ?>
            <?php foreach ($upcoming_assignments as $assignment): ?>
                <div class="p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-2 hover:bg-gray-50">
                    <div>
                        <a href="<?php echo site_url('/assignment/' . $assignment['assignment_id']); ?>" class="text-base font-medium text-blue-700 hover:underline">
                            <?php echo htmlspecialchars($assignment['title']); ?>
                        </a>
                        <div class="text-sm text-gray-500">
                            <?php echo htmlspecialchars($assignment['course_title']); ?>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600 font-medium text-left sm:text-right">
                        Due: <?php echo date('M d, Y @ g:i A', strtotime($assignment['due_date'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>