<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<h1 class="text-3xl font-bold text-gray-800 mb-6">Teacher Dashboard</h1>
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
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex items-center">
        <i class="fas fa-book-open text-3xl text-blue-500 mr-4"></i>
        <div>
            <div class="text-sm text-gray-500">Total Courses</div>
            <div class="text-2xl font-bold text-gray-800"><?php echo $stats['course_count']; ?></div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex items-center">
        <i class="fas fa-users text-3xl text-green-500 mr-4"></i>
        <div>
            <div class="text-sm text-gray-500">Total Students</div>
            <div class="text-2xl font-bold text-gray-800"><?php echo $stats['student_count']; ?></div>
        </div>
    </div>

    <a href="<?php echo site_url('/submissions/ungraded'); ?>" class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex items-center hover:shadow-lg transition-shadow">
        <i class="fas fa-exclamation-circle text-3xl <?php echo $stats['ungraded_count'] > 0 ? 'text-yellow-500' : 'text-gray-400'; ?> mr-4"></i>
        <div>
            <div class="text-sm text-gray-500">Ungraded Submissions</div>
            <div class="text-2xl font-bold text-gray-800"><?php echo $stats['ungraded_count']; ?></div>
        </div>
        <i class="fas fa-arrow-right text-gray-400 ml-auto"></i>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 bg-white rounded-lg shadow-md border border-gray-200">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-700">My Courses</h2>
            <a href="<?php echo site_url('/courses'); ?>" class="text-sm text-blue-600 hover:underline">View All &rarr;</a>
        </div>
        <div class="divide-y divide-gray-200">
            <?php if (empty($courses_list)): ?>
                <p class="text-gray-500 p-6 text-center">You haven't created any courses yet.</p>
                <div class="p-6 text-center">
                    <a href="<?php echo site_url('/courses/create'); ?>" class="bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-md shadow">
                        <i class="fas fa-plus mr-1"></i> Create Your First Course
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($courses_list as $course): ?>
                    <div class="p-4 flex justify-between items-center hover:bg-gray-50">
                        <div>
                            <a href="<?php echo site_url('/courses/show/' . $course['course_id']); ?>" class="text-lg font-medium text-blue-700 hover:underline">
                                <?php echo htmlspecialchars($course['title']); ?>
                            </a>
                            <div class="text-sm text-gray-500">
                                <?php echo $course['student_count']; ?> Student(s) Enrolled
                            </div>
                        </div>
                        <a href="<?php echo site_url('/courses/show/' . $course['course_id']); ?>" class="text-sm text-white bg-blue-600 hover:bg-blue-700 py-2 px-3 rounded-md shadow-sm">
                            Manage
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-700">Recent Assignments</h2>
        </div>
        <div class="divide-y divide-gray-200">
            <?php if (empty($recent_assignments)): ?>
                <p class="text-gray-500 p-6 text-center">No recent assignments found.</p>
            <?php else: ?>
                <?php foreach ($recent_assignments as $assignment): ?>
                    <div class="p-4 hover:bg-gray-50">
                        <a href="<?php echo site_url('/assignments/' . $assignment['assignment_id'] . '/submissions'); ?>" class="font-medium text-gray-800 hover:underline">
                            <?php echo htmlspecialchars($assignment['title']); ?>
                        </a>
                        <div class="text-sm text-gray-500">
                            <span class="text-blue-600"><?php echo htmlspecialchars($assignment['course_title']); ?></span>
                        </div>
                        <div class="text-sm text-gray-500">
                            Due: <?php echo date('M d, Y', strtotime($assignment['due_date'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include 'app/views/layouts/footer.php'; ?>