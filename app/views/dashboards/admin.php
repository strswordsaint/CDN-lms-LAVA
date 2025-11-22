<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* ... (Styles kept consistent with other dashboards) ... */
    :root { --bg-body: #eef2f6; --primary-soft: #eff6ff; --primary-border: #bfdbfe; --primary-text: #1d4ed8; }
    body { background-color: var(--bg-body); font-family: 'Inter', sans-serif; }
    .page-banner { background: white; position: relative; overflow: hidden; border-bottom: 1px solid #e2e8f0; padding: 2.5rem 0; box-shadow: 0 1px 2px rgba(0,0,0,0.02); margin-bottom: 2rem; }
    .banner-decoration { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.6; z-index: 0; }
    .decoration-1 { top: -60%; left: -10%; width: 500px; height: 500px; background: #fee2e2; }
    .decoration-2 { bottom: -60%; right: -5%; width: 400px; height: 400px; background: #fecaca; }
    .banner-content { position: relative; z-index: 10; }

    .hunt-card { background: white; border-radius: 1rem; border: 1px solid #cbd5e1; box-shadow: 0 2px 5px rgba(0,0,0,0.05); padding: 1.5rem; display: flex; align-items: center; gap: 1.25rem; transition: all 0.2s ease; position: relative; overflow: hidden; height: 100%; }
    .hunt-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.15); }
    .accent-bar { position: absolute; left: 0; top: 0; bottom: 0; width: 6px; }
    .hunt-icon-box { width: 3.5rem; height: 3.5rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; box-shadow: inset 0 2px 4px rgba(255,255,255,0.3); }
    
    .chart-card { @apply bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden p-6 h-full; }
    .action-btn { @apply flex flex-col items-center justify-center p-4 bg-white border border-neutral-200 rounded-xl shadow-sm hover:shadow-md hover:border-primary-300 transition-all group; }
</style>

<div class="page-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-neutral-900 tracking-tight mb-2">Admin Dashboard</h1>
                <p class="text-neutral-500 text-sm">System Overview & Analytics.</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-red-50 text-red-700 px-4 py-2 rounded-xl border border-red-100 text-sm font-bold shadow-sm flex items-center">
                    <i class="fas fa-user-shield mr-2"></i> Administrator Portal
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="hunt-card group hover:border-blue-400">
            <div class="accent-bar bg-blue-500"></div>
            <div class="hunt-icon-box bg-blue-50 text-blue-600 border border-blue-200"><i class="fas fa-users"></i></div>
            <div><div class="text-xs font-bold text-neutral-400 uppercase">Users</div><div class="text-3xl font-extrabold text-neutral-900"><?php echo $stats['total_users']; ?></div></div>
        </div>
        <div class="hunt-card group hover:border-green-400">
            <div class="accent-bar bg-green-500"></div>
            <div class="hunt-icon-box bg-green-50 text-green-600 border border-green-200"><i class="fas fa-user-graduate"></i></div>
            <div><div class="text-xs font-bold text-neutral-400 uppercase">Students</div><div class="text-3xl font-extrabold text-neutral-900"><?php echo $stats['total_students']; ?></div></div>
        </div>
        <div class="hunt-card group hover:border-amber-400">
            <div class="accent-bar bg-amber-500"></div>
            <div class="hunt-icon-box bg-amber-50 text-amber-600 border border-amber-200"><i class="fas fa-chalkboard-teacher"></i></div>
            <div><div class="text-xs font-bold text-neutral-400 uppercase">Teachers</div><div class="text-3xl font-extrabold text-neutral-900"><?php echo $stats['total_teachers']; ?></div></div>
        </div>
        <div class="hunt-card group hover:border-indigo-400">
            <div class="accent-bar bg-indigo-500"></div>
            <div class="hunt-icon-box bg-indigo-50 text-indigo-600 border border-indigo-200"><i class="fas fa-book-open"></i></div>
            <div><div class="text-xs font-bold text-neutral-400 uppercase">Courses</div><div class="text-3xl font-extrabold text-neutral-900"><?php echo $stats['total_courses']; ?></div></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="chart-card">
            <div class="flex justify-between items-center mb-4 border-b border-neutral-100 pb-4">
                <h3 class="text-lg font-bold text-neutral-800">User Registration Trend</h3>
                <span class="text-xs text-neutral-500">Last 6 Months</span>
            </div>
            <div style="height: 250px;">
                <canvas id="registrationChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="flex justify-between items-center mb-4 border-b border-neutral-100 pb-4">
                <h3 class="text-lg font-bold text-neutral-800">Most Popular Courses</h3>
                <span class="text-xs text-neutral-500">Top 5 by Enrollment</span>
            </div>
            <div style="height: 250px;">
                <canvas id="popularCoursesChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6">
        <h2 class="text-xl font-bold text-neutral-800 mb-6 flex items-center"><i class="fas fa-rocket text-primary-600 mr-2"></i> Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="<?php echo site_url('/admin/users'); ?>" class="action-btn">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform"><i class="fas fa-user-cog text-xl"></i></div>
                <span class="font-bold text-neutral-700">Manage Users</span>
            </a>
            <a href="<?php echo site_url('/admin/courses'); ?>" class="action-btn">
                <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform"><i class="fas fa-layer-group text-xl"></i></div>
                <span class="font-bold text-neutral-700">Manage Courses</span>
            </a>
            <a href="<?php echo site_url('/admin/announcements'); ?>" class="action-btn">
                <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform"><i class="fas fa-bullhorn text-xl"></i></div>
                <span class="font-bold text-neutral-700">Announcements</span>
            </a>
            <a href="<?php echo site_url('/admin/reports'); ?>" class="action-btn">
                <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform"><i class="fas fa-file-invoice text-xl"></i></div>
                <span class="font-bold text-neutral-700">System Reports</span>
            </a>
        </div>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // --- 1. Registration Trend Chart ---
    const regData = <?php echo json_encode($registration_trends ?? []); ?>;
    const regLabels = regData.map(item => item.month_label);
    const regCounts = regData.map(item => item.count);

    const ctxReg = document.getElementById('registrationChart').getContext('2d');
    new Chart(ctxReg, {
        type: 'line',
        data: {
            labels: regLabels.length ? regLabels : ['No Data'],
            datasets: [{
                label: 'New Users',
                data: regCounts.length ? regCounts : [0],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { borderDash: [5, 5] } }, x: { grid: { display: false } } }
        }
    });

    // --- 2. Popular Courses Chart ---
    const courseData = <?php echo json_encode($popular_courses ?? []); ?>;
    const courseLabels = courseData.map(item => item.title.length > 12 ? item.title.substring(0,12)+'...' : item.title);
    const courseCounts = courseData.map(item => item.student_count);

    const ctxCourse = document.getElementById('popularCoursesChart').getContext('2d');
    new Chart(ctxCourse, {
        type: 'bar',
        data: {
            labels: courseLabels.length ? courseLabels : ['No Data'],
            datasets: [{
                label: 'Enrollments',
                data: courseCounts.length ? courseCounts : [0],
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444'],
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { borderDash: [5, 5] } }, x: { grid: { display: false } } }
        }
    });
});
</script>