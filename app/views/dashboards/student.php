<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<h1 class="text-3xl font-bold text-gray-800 mb-6">Student Dashboard</h1>
<p class="text-lg text-gray-600 mb-8">Welcome, <?php echo htmlspecialchars($first_name ?? 'Student'); ?>!</p>

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

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <a href="<?php echo site_url('/courses/my'); ?>" class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex items-center hover:shadow-lg transition-shadow">
        <i class="fas fa-chalkboard text-3xl text-blue-500 mr-4"></i>
        <div>
            <div class="text-sm text-gray-500">Joined Courses</div>
            <div class="text-2xl font-bold text-gray-800"><?php echo $stats['joined_courses']; ?></div>
        </div>
        <i class="fas fa-arrow-right text-gray-400 ml-auto"></i>
    </a>
    
    <a href="<?php echo site_url('/my-assignments'); ?>" class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex items-center hover:shadow-lg transition-shadow">
        <i class="fas fa-file-alt text-3xl <?php echo $stats['pending_assignments'] > 0 ? 'text-yellow-500' : 'text-gray-400'; ?> mr-4"></i>
        <div>
            <div class="text-sm text-gray-500">Pending Items</div>
            <div class="text-2xl font-bold text-gray-800"><?php echo $stats['pending_assignments']; ?></div>
        </div>
        <i class="fas fa-arrow-right text-gray-400 ml-auto"></i>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-1">
        <div class="card">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-700">My Week at a Glance</h2>
            </div>
            <div class="p-6">
                <canvas id="studentWeekChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-700">Upcoming Deadlines</h2>
            </div>
            <div class="divide-y divide-gray-200">
                <?php if (empty($upcoming_assignments)): ?>
                    <p class="text-gray-500 p-6 text-center">No upcoming deadlines. You're all caught up!</p>
                <?php else: ?>
                    <?php foreach ($upcoming_assignments as $assignment): ?>
                        <div class="p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-2 hover:bg-gray-50">
                            <div>
                                <a href="<?php echo site_url('/assignment/' . $assignment['assignment_id']); ?>" class="text-base font-medium text-blue-700 hover:underline">
                                    <?php if($assignment['type'] == 'activity'): ?>
                                        <i class="fas fa-gamepad text-yellow-500 mr-1" title="Activity"></i>
                                    <?php else: ?>
                                        <i class="fas fa-tasks text-primary-600 mr-1" title="Assignment"></i>
                                    <?php endif; ?>
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
    </div>
</div>
<?php include 'app/views/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // 1. Get the data from PHP
    // We use '??' to provide a default value in case $weekly_stats is null
    const weeklyStats = <?php echo json_encode($weekly_stats ?? [
        'upcoming' => 0,
        'completed' => 0,
        'past_due' => 0,
    ]); ?>;

    const upcomingCount = parseInt(weeklyStats.upcoming, 10);
    const completedCount = parseInt(weeklyStats.completed, 10);
    const pastDueCount = parseInt(weeklyStats.past_due, 10);
    const totalCount = upcomingCount + completedCount + pastDueCount;

    // 2. Set up the data for the chart
    const data = {
        labels: [
            `Upcoming (${upcomingCount})`,
            `Completed (${completedCount})`,
            `Past Due (${pastDueCount})`
        ],
        datasets: [{
            label: 'This Week',
            // The actual data
            data: [upcomingCount, completedCount, pastDueCount],
            // Colors from your example (Yellow, Green, Red)
            backgroundColor: [
                '#f59e0b', // warning-500
                '#16a34a', // success-600
                '#dc2626'  // error-600
            ],
            borderColor: '#ffffff', // White border between segments
            borderWidth: 2
        }]
    };

    // 3. Plugin to draw the total in the center
    const centerTextPlugin = {
        id: 'centerText',
        beforeDraw: function(chart) {
            if (chart.config.options.plugins.centerText.display) {
                let ctx = chart.ctx;
                ctx.save();
                
                // Get the center of the chart
                let cx = (chart.chartArea.left + chart.chartArea.right) / 2;
                let cy = (chart.chartArea.top + chart.chartArea.bottom) / 2;

                // Set styles
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                
                // Total number
                ctx.font = 'bold 2rem Inter, sans-serif'; // Big number
                ctx.fillStyle = '#1e293b'; // neutral-800
                ctx.fillText(totalCount, cx, cy);

                // "Total" label
                ctx.font = '500 0.875rem Inter, sans-serif'; // Small label
                ctx.fillStyle = '#64748b'; // neutral-500
                ctx.fillText('Total Items', cx, cy + 28); // Position below number
                
                ctx.restore();
            }
        }
    };
    
    // 4. Chart Configuration
    const config = {
        type: 'doughnut',
        data: data,
        plugins: [centerTextPlugin], // Add our custom plugin
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%', // Makes it a doughnut chart
            plugins: {
                // Show the total in the center
                centerText: {
                    display: true
                },
                // Legend configuration
                legend: {
                    position: 'bottom', // Position legend at the bottom
                    align: 'start', // Align labels to the left
                    labels: {
                        usePointStyle: true, // Use colored dots instead of boxes
                        boxWidth: 8,
                        padding: 16,
                        font: {
                            family: 'Inter, sans-serif',
                            size: 14
                        }
                    }
                },
                tooltip: {
                    enabled: true // Show tooltips on hover
                }
            }
        }
    };

    // 5. Render the chart
    const ctx = document.getElementById('studentWeekChart').getContext('2d');
    if (totalCount > 0) {
        new Chart(ctx, config);
    } else {
        // If there's no data, show a message instead of an empty chart
        const canvas = document.getElementById('studentWeekChart');
        const container = canvas.parentNode;
        $(container).html('<p class="text-center text-neutral-500 py-10">No weekly activity found.</p>');
    }
});
</script>