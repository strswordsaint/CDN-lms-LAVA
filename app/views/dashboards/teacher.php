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
        background: white; border-bottom: 1px solid #e2e8f0; padding: 2.5rem 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02); margin-bottom: 2rem; position: relative; overflow: hidden;
    }
    .banner-decoration { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.6; z-index: 0; }
    .decoration-1 { top: -60%; left: -10%; width: 500px; height: 500px; background: #e0e7ff; }
    .decoration-2 { bottom: -60%; right: -5%; width: 400px; height: 400px; background: #f3e8ff; }
    .banner-content { position: relative; z-index: 10; }

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
    
    .chart-container { position: relative; height: 250px; width: 100%; }
</style>

<div class="page-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-neutral-900 tracking-tight mb-2">
                    Teacher Dashboard
                </h1>
                <p class="text-neutral-500 text-sm">Welcome back, <?php echo htmlspecialchars($first_name); ?>! Here's your classroom overview.</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/80 backdrop-blur text-neutral-700 px-4 py-2 rounded-xl border border-neutral-200 text-sm font-bold shadow-sm flex items-center">
                    <i class="fas fa-chalkboard-teacher mr-2 text-primary-600"></i> Instructor Portal
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="hunt-card group hover:border-blue-400">
            <div class="accent-bar bg-blue-500"></div>
            <div class="hunt-icon-box bg-blue-50 text-blue-600 border border-blue-200">
                <i class="fas fa-book-open"></i>
            </div>
            <div>
                <div class="text-xs font-bold text-neutral-400 uppercase tracking-wide">Active Courses</div>
                <div class="text-3xl font-extrabold text-neutral-900"><?php echo $stats['course_count']; ?></div>
            </div>
        </div>
        
        <div class="hunt-card group hover:border-green-400">
            <div class="accent-bar bg-green-500"></div>
            <div class="hunt-icon-box bg-green-50 text-green-600 border border-green-200">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="text-xs font-bold text-neutral-400 uppercase tracking-wide">Total Students</div>
                <div class="text-3xl font-extrabold text-neutral-900"><?php echo $stats['student_count']; ?></div>
            </div>
        </div>

        <a href="<?php echo site_url('/submissions/ungraded'); ?>" class="hunt-card group hover:border-amber-400 cursor-pointer">
            <div class="accent-bar <?php echo $stats['ungraded_count'] > 0 ? 'bg-amber-500' : 'bg-neutral-300'; ?>"></div>
            <div class="hunt-icon-box <?php echo $stats['ungraded_count'] > 0 ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-neutral-50 text-neutral-400 border-neutral-200'; ?> border">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="flex-1">
                <div class="text-xs font-bold text-neutral-400 uppercase tracking-wide">Needs Grading</div>
                <div class="text-3xl font-extrabold text-neutral-900"><?php echo $stats['ungraded_count']; ?></div>
            </div>
            <i class="fas fa-arrow-right text-neutral-300 group-hover:text-primary-600 transition-colors"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="p-6 border-b border-neutral-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-neutral-800">Submission Activity</h2>
                <span class="text-xs text-neutral-500">Last 7 Days</span>
            </div>
            <div class="p-6">
                <div class="chart-container">
                    <canvas id="submissionActivityChart"></canvas>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="p-6 border-b border-neutral-100 bg-amber-50">
                <h2 class="text-lg font-bold text-amber-900">Priority Grading</h2>
            </div>
            <div class="p-4">
                <?php if(empty($priority_grading)): ?>
                    <div class="text-center py-10 text-neutral-400">
                        <i class="fas fa-check-double text-3xl mb-2 text-green-400"></i>
                        <p class="text-sm">All caught up!</p>
                    </div>
                <?php else: ?>
                    <?php foreach($priority_grading as $item): ?>
                        <a href="<?php echo site_url('/assignments/' . $item['assignment_id'] . '/submissions'); ?>" class="block mb-3 last:mb-0">
                            <div class="list-item-card flex justify-between items-center hover:border-amber-300 transition-colors">
                                <div class="flex-1 min-w-0 mr-3">
                                    <h4 class="font-bold text-sm text-neutral-800 truncate"><?php echo htmlspecialchars($item['title']); ?></h4>
                                    <p class="text-xs text-neutral-500 truncate"><?php echo htmlspecialchars($item['course_title']); ?></p>
                                </div>
                                <div class="flex-shrink-0 bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full">
                                    <?php echo $item['ungraded_count']; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                    <div class="text-center mt-4">
                        <a href="<?php echo site_url('/submissions/ungraded'); ?>" class="text-xs font-bold text-primary-600 hover:text-primary-700 hover:underline">View All Ungraded &rarr;</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="p-6 border-b border-neutral-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-neutral-800">My Courses</h2>
                <a href="<?php echo site_url('/courses'); ?>" class="text-sm font-medium text-primary-600 hover:underline">View All</a>
            </div>
            <div class="p-6">
                <?php if (empty($courses_list)): ?>
                    <div class="text-center py-6 text-neutral-400">No courses yet.</div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach (array_slice($courses_list, 0, 4) as $course): ?>
                            <div class="list-item-card flex justify-between items-center group py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-primary-50 text-primary-600 flex items-center justify-center border border-primary-100">
                                        <i class="fas fa-book text-xs"></i>
                                    </div>
                                    <div>
                                        <a href="<?php echo site_url('/courses/show/' . $course['course_id']); ?>" class="block font-bold text-sm text-neutral-800 group-hover:text-primary-700 transition-colors">
                                            <?php echo htmlspecialchars($course['title']); ?>
                                        </a>
                                        <span class="text-xs text-neutral-400"><?php echo $course['student_count']; ?> Students</span>
                                    </div>
                                </div>
                                <a href="<?php echo site_url('/courses/show/' . $course['course_id']); ?>" class="text-xs font-bold text-neutral-500 hover:text-primary-600">Manage</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="p-6 border-b border-neutral-100">
                <h2 class="text-lg font-bold text-neutral-800">Recent Assignments</h2>
            </div>
            <div class="p-6">
                <?php if (empty($recent_assignments)): ?>
                    <p class="text-neutral-500 text-sm text-center py-8">No recent assignments.</p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($recent_assignments as $assignment): ?>
                            <div class="list-item-card group border-l-4 border-l-transparent hover:border-l-primary-500">
                                <a href="<?php echo site_url('/assignments/' . $assignment['assignment_id'] . '/submissions'); ?>" class="block">
                                    <h4 class="font-bold text-sm text-neutral-800 group-hover:text-primary-700 transition-colors mb-1">
                                        <?php echo htmlspecialchars($assignment['title']); ?>
                                    </h4>
                                    <div class="text-xs text-neutral-500 mb-1">
                                        <?php echo htmlspecialchars($assignment['course_title']); ?>
                                    </div>
                                    <div class="text-xs font-medium text-neutral-400 flex items-center">
                                        <i class="far fa-clock mr-1.5"></i> Due: <?php echo date('M d', strtotime($assignment['due_date'])); ?>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // --- SUBMISSION ACTIVITY CHART ---
    const rawData = <?php echo json_encode($activity_data ?? []); ?>;
    
    // Process data (ensure last 7 days are represented even if empty)
    const labels = [];
    const counts = [];
    const today = new Date();
    
    for(let i=6; i>=0; i--) {
        const d = new Date(today);
        d.setDate(today.getDate() - i);
        const dateString = d.toISOString().split('T')[0]; // YYYY-MM-DD
        const labelString = d.toLocaleDateString('en-US', {weekday: 'short'}); // Mon, Tue
        
        labels.push(labelString);
        
        // Find count for this date
        const found = rawData.find(item => item.activity_date === dateString);
        counts.push(found ? parseInt(found.submission_count) : 0);
    }

    const ctx = document.getElementById('submissionActivityChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Submissions',
                data: counts,
                borderColor: '#3b82f6', // Primary Blue
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#3b82f6',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // Smooth curve
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { family: 'Inter' } },
                    grid: { borderDash: [5, 5], color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Inter' } }
                }
            }
        }
    });
});
</script>