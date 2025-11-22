<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* === Modern Variables === */
    :root {
        --bg-body: #eef2f6; 
        --primary-soft: #eff6ff;
        --primary-border: #bfdbfe;
        --primary-text: #1d4ed8;
    }
    
    body { background-color: var(--bg-body); font-family: 'Inter', sans-serif; }

    /* === DESIGNED BANNER === */
    .page-banner {
        background: white; position: relative; overflow: hidden; border-bottom: 1px solid #e2e8f0; padding: 2.5rem 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02); margin-bottom: 2rem;
    }
    .banner-decoration { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.6; z-index: 0; }
    .decoration-1 { top: -60%; left: -10%; width: 500px; height: 500px; background: #dcfce7; }
    .decoration-2 { bottom: -60%; right: -5%; width: 400px; height: 400px; background: #e0e7ff; }
    .banner-content { position: relative; z-index: 10; }

    /* === ANALYTICS CARD (Hero Section) === */
    .analytics-card {
        background: white; border-radius: 1rem; border: 1px solid #cbd5e1;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05); padding: 1.5rem; height: 100%;
    }

    /* === HUNTJOBS CARD STYLE === */
    .hunt-card {
        background: white; border-radius: 1rem; border: 1px solid #cbd5e1; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.05); padding: 1.5rem;
        display: flex; align-items: center; gap: 1.25rem;
        transition: all 0.2s ease; position: relative; overflow: hidden; height: 100%;
    }
    .hunt-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.15); }
    
    .accent-bar { position: absolute; left: 0; top: 0; bottom: 0; width: 6px; }
    
    .hunt-icon-box {
        width: 3.5rem; height: 3.5rem; border-radius: 0.75rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; flex-shrink: 0;
        box-shadow: inset 0 2px 4px rgba(255,255,255,0.3);
    }

    /* === LIST ITEMS === */
    .list-item-card {
        background: white; border-radius: 0.75rem; border: 1px solid #e2e8f0;
        padding: 1rem; transition: all 0.2s; margin-bottom: 0.75rem;
    }
    .list-item-card:hover { border-color: #bfdbfe; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
    
    .chart-container { position: relative; height: 180px; width: 100%; }
</style>

<div class="page-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-neutral-900 tracking-tight mb-2">
                    Student Dashboard
                </h1>
                <p class="text-neutral-500 text-sm">Welcome, <?php echo htmlspecialchars($first_name ?? 'Student'); ?>! Keep up the good work.</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/80 backdrop-blur text-neutral-700 px-4 py-2 rounded-xl border border-neutral-200 text-sm font-bold shadow-sm flex items-center">
                    <i class="fas fa-user-graduate mr-2 text-primary-600"></i> Student Portal
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">

    <?php if (!empty($site_announcements)): ?>
        <div class="grid grid-cols-1 gap-4 mb-8">
            <?php foreach (array_slice($site_announcements, 0, 2) as $post): ?>
                <div class="bg-white rounded-xl border-l-4 border-red-500 shadow-sm p-5 flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-neutral-900"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="text-xs text-neutral-400 mb-2">
                            Posted by Admin <?php echo htmlspecialchars($post['first_name']); ?> on <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                        </p>
                        <p class="text-sm text-neutral-600 leading-relaxed">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="lg:col-span-2 analytics-card">
            <div class="flex flex-col sm:flex-row items-center gap-8 h-full">
                
                <div class="w-full sm:w-1/2 flex flex-col gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl border border-blue-100">
                            <i class="fas fa-chalkboard"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-neutral-400 uppercase tracking-wide">Enrolled Courses</p>
                            <h3 class="text-2xl font-extrabold text-neutral-900"><?php echo $stats['joined_courses']; ?></h3>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl border border-amber-100">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-neutral-400 uppercase tracking-wide">Pending Tasks</p>
                            <h3 class="text-2xl font-extrabold text-neutral-900"><?php echo $stats['pending_assignments']; ?></h3>
                        </div>
                    </div>
                </div>

                <div class="flex-1 w-full flex items-center justify-center border-t sm:border-t-0 sm:border-l border-neutral-100 pt-6 sm:pt-0 sm:pl-6">
                    <div class="chart-container">
                        <canvas id="studentWeekChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <?php 
            $next_assignment = !empty($upcoming_assignments) ? $upcoming_assignments[0] : null;
        ?>
        
        <?php if ($next_assignment): ?>
            <a href="<?php echo site_url('/assignment/' . $next_assignment['assignment_id']); ?>" class="hunt-card group hover:border-amber-400 cursor-pointer lg:col-span-1 flex flex-col justify-center items-start relative">
                <span class="absolute top-4 right-4 bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-1 rounded-full border border-amber-200">
                    Next Due
                </span>
                <div class="accent-bar bg-amber-500"></div>
                
                <div class="w-full mb-3">
                    <div class="text-xs font-bold text-neutral-400 uppercase tracking-wide mb-1">Priority Task</div>
                    <h3 class="text-xl font-extrabold text-neutral-900 line-clamp-1 group-hover:text-primary-700 transition-colors">
                        <?php echo htmlspecialchars($next_assignment['title']); ?>
                    </h3>
                    <p class="text-xs text-neutral-500 mt-1 truncate">
                        <?php echo htmlspecialchars($next_assignment['course_title']); ?>
                    </p>
                </div>

                <div class="w-full bg-amber-50 border border-amber-100 rounded-lg p-3 flex items-center justify-between">
                    <div class="text-xs font-bold text-amber-700">
                        <i class="far fa-clock mr-1"></i> 
                        <?php 
                            $due = strtotime($next_assignment['due_date']);
                            // Simple "Due in X days" logic
                            $diff = ceil(($due - time()) / 86400);
                            echo ($diff == 0) ? 'Due Today!' : 'Due in ' . $diff . ' days';
                        ?>
                    </div>
                    <div class="btn btn-sm btn-primary py-1 px-3 text-xs shadow-sm">Submit</div>
                </div>
            </a>
        <?php else: ?>
            <div class="hunt-card group hover:border-green-400 lg:col-span-1 flex flex-col justify-center items-center text-center">
                <div class="accent-bar bg-green-500"></div>
                <div class="hunt-icon-box bg-green-50 text-green-600 border border-green-200 mb-3">
                    <i class="fas fa-check"></i>
                </div>
                <h3 class="text-lg font-bold text-neutral-900">All Caught Up!</h3>
                <p class="text-xs text-neutral-500">No pending assignments.</p>
            </div>
        <?php endif; ?>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden h-full">
                <div class="p-6 border-b border-neutral-100 flex justify-between items-center bg-neutral-50">
                    <h2 class="text-lg font-bold text-neutral-800">Upcoming Deadlines</h2>
                    <a href="<?php echo site_url('/my-assignments'); ?>" class="text-sm font-medium text-primary-600 hover:underline">View All</a>
                </div>
                <div class="p-6">
                    <?php if (empty($upcoming_assignments)): ?>
                        <div class="text-center py-8 text-neutral-400">
                            <i class="fas fa-mug-hot text-3xl mb-2 opacity-50"></i>
                            <p class="text-sm">Relax! You have no upcoming work.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach (array_slice($upcoming_assignments, 0, 5) as $assignment): ?>
                                <?php 
                                    $isActivity = $assignment['type'] == 'activity';
                                    $iconClass = $isActivity ? 'fa-gamepad text-amber-500' : 'fa-tasks text-blue-500';
                                    $bgClass = $isActivity ? 'bg-amber-50 border-amber-100' : 'bg-blue-50 border-blue-100';
                                ?>
                                <div class="list-item-card group border-l-4 border-l-transparent hover:border-l-primary-500 flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg flex-shrink-0 flex items-center justify-center border <?php echo $bgClass; ?>">
                                        <i class="fas <?php echo $iconClass; ?>"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="<?php echo site_url('/assignment/' . $assignment['assignment_id']); ?>" class="block font-bold text-sm text-neutral-800 group-hover:text-primary-700 transition-colors truncate">
                                            <?php echo htmlspecialchars($assignment['title']); ?>
                                        </a>
                                        <div class="text-xs text-neutral-500 truncate">
                                            <?php echo htmlspecialchars($assignment['course_title']); ?>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <div class="text-xs font-bold text-neutral-600 bg-neutral-100 px-2 py-1 rounded border border-neutral-200">
                                            <?php echo date('M d', strtotime($assignment['due_date'])); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden h-full">
                <div class="p-6 border-b border-neutral-100 bg-neutral-50">
                    <h2 class="text-lg font-bold text-neutral-800">Quick Links</h2>
                </div>
                <div class="p-4 space-y-2">
                    <a href="<?php echo site_url('/courses/my'); ?>" class="block p-3 rounded-xl hover:bg-neutral-50 transition-colors flex items-center gap-3 group border border-transparent hover:border-neutral-100">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <span class="font-semibold text-neutral-700 group-hover:text-blue-700">My Courses</span>
                    </a>
                    
                    <a href="<?php echo site_url('/my-activities'); ?>" class="block p-3 rounded-xl hover:bg-neutral-50 transition-colors flex items-center gap-3 group border border-transparent hover:border-neutral-100">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-gamepad"></i>
                        </div>
                        <span class="font-semibold text-neutral-700 group-hover:text-amber-700">My Activities</span>
                    </a>

                    <a href="<?php echo site_url('/my-calendar'); ?>" class="block p-3 rounded-xl hover:bg-neutral-50 transition-colors flex items-center gap-3 group border border-transparent hover:border-neutral-100">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <span class="font-semibold text-neutral-700 group-hover:text-purple-700">My Calendar</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // --- CHART DATA ---
    const weeklyStats = <?php echo json_encode($weekly_stats ?? ['upcoming' => 0, 'completed' => 0, 'past_due' => 0]); ?>;
    const upcomingCount = parseInt(weeklyStats.upcoming, 10);
    const completedCount = parseInt(weeklyStats.completed, 10);
    const pastDueCount = parseInt(weeklyStats.past_due, 10);
    const totalCount = upcomingCount + completedCount + pastDueCount;

    const data = {
        labels: ['Upcoming', 'Completed', 'Past Due'],
        datasets: [{
            data: [upcomingCount, completedCount, pastDueCount],
            backgroundColor: ['#f59e0b', '#16a34a', '#dc2626'],
            borderColor: '#ffffff',
            borderWidth: 2,
            hoverOffset: 4
        }]
    };

    const centerTextPlugin = {
        id: 'centerText',
        beforeDraw: function(chart) {
            if (chart.config.options.plugins.centerText.display) {
                let ctx = chart.ctx;
                let width = chart.width;
                let height = chart.height;
                ctx.restore();
                
                let fontSize = (height / 120).toFixed(2);
                ctx.font = "bold " + fontSize + "em Inter";
                ctx.textBaseline = "middle";
                ctx.fillStyle = "#1e293b";

                let text = totalCount.toString(),
                    textX = Math.round((width - ctx.measureText(text).width) / 2),
                    textY = height / 2;

                ctx.fillText(text, textX, textY);
                ctx.save();
                
                ctx.font = "500 " + (fontSize * 0.35) + "em Inter";
                ctx.fillStyle = "#64748b";
                let subtext = "Active";
                let subtextX = Math.round((width - ctx.measureText(subtext).width) / 2);
                ctx.fillText(subtext, subtextX, textY + 20);
                ctx.save();
            }
        }
    };

    const ctx = document.getElementById('studentWeekChart').getContext('2d');
    // Always render chart, even if empty (looks better than text)
    new Chart(ctx, {
        type: 'doughnut',
        data: data,
        plugins: [centerTextPlugin],
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                centerText: { display: true },
                legend: { 
                    position: 'right', 
                    align: 'center',
                    labels: { usePointStyle: true, padding: 15, font: { family: 'Inter', size: 11 } } 
                }
            }
        }
    });
});
</script>