<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Print Styles */
    @media print {
        body > nav, body > footer, #app-sidebar, #print-controls, .back-link { display: none !important; }
        body, html { background: #fff !important; color: #000 !important; height: auto !important; overflow: visible !important; }
        .container { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
        .page-banner { box-shadow: none !important; border-bottom: 2px solid #000 !important; padding: 1rem 0 !important; margin-bottom: 1rem !important; }
        .bg-white { border: 1px solid #ccc !important; }
        .text-indigo-700 { color: black !important; }
        /* Hide elements that might clutter print */
        .form-select, form { display: none !important; } 
    }
</style>

<div class="bg-white border-b border-neutral-200 py-8 mb-8 shadow-sm" id="page-banner">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-neutral-900 tracking-tight mb-1">System Analytics</h1>
                <p class="text-neutral-500 text-sm">Performance metrics and growth insights.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <form method="GET" class="flex items-center gap-2 bg-neutral-100 p-1 rounded-lg border border-neutral-200">
                    <?php $range = $selected_range ?? 'all'; ?>
                    
                    <button type="submit" name="range" value="weekly" class="px-4 py-1.5 text-sm font-medium rounded-md transition-all <?php echo $range == 'weekly' ? 'bg-white text-primary-700 shadow-sm' : 'text-neutral-500 hover:text-neutral-800'; ?>">
                        Weekly
                    </button>
                    <button type="submit" name="range" value="monthly" class="px-4 py-1.5 text-sm font-medium rounded-md transition-all <?php echo $range == 'monthly' ? 'bg-white text-primary-700 shadow-sm' : 'text-neutral-500 hover:text-neutral-800'; ?>">
                        Monthly
                    </button>
                    <button type="submit" name="range" value="yearly" class="px-4 py-1.5 text-sm font-medium rounded-md transition-all <?php echo $range == 'yearly' ? 'bg-white text-primary-700 shadow-sm' : 'text-neutral-500 hover:text-neutral-800'; ?>">
                        Yearly
                    </button>
                    <button type="submit" name="range" value="all" class="px-4 py-1.5 text-sm font-medium rounded-md transition-all <?php echo $range == 'all' ? 'bg-white text-primary-700 shadow-sm' : 'text-neutral-500 hover:text-neutral-800'; ?>">
                        All Time
                    </button>
                </form>
                
                <div id="print-controls" class="relative inline-block text-left">
                    <div>
                        <button type="button" class="btn btn-secondary flex items-center gap-2 bg-white border border-neutral-300 text-neutral-700 hover:bg-neutral-50 px-4 py-2 rounded-lg shadow-sm font-medium transition-colors" id="print-menu-button" aria-expanded="true" aria-haspopup="true" onclick="document.getElementById('print-menu').classList.toggle('hidden');">
                            <i class="fas fa-print"></i> Print Reports
                            <i class="fas fa-chevron-down text-xs ml-1"></i>
                        </button>
                    </div>

                    <div class="hidden absolute right-0 z-10 mt-2 w-64 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="print-menu-button" tabindex="-1" id="print-menu">
                        <div class="py-1" role="none">
                            <a href="#" onclick="window.print(); return false;" class="text-gray-700 block px-4 py-3 text-sm hover:bg-gray-50 border-b border-gray-100" role="menuitem">
                                <div class="font-bold"><i class="fas fa-chart-pie mr-2 text-blue-500"></i> Print Analytics</div>
                                <span class="text-xs text-gray-500 ml-6">Current screen & charts</span>
                            </a>
                            
                            <a href="<?php echo site_url('/admin/reports/print_users/all'); ?>" target="_blank" class="text-gray-700 block px-4 py-3 text-sm hover:bg-gray-50" role="menuitem">
                                <div class="font-bold"><i class="fas fa-users mr-2 text-indigo-500"></i> Master User List</div>
                                <span class="text-xs text-gray-500 ml-6">Full registry of all users</span>
                            </a>
                            
                            <a href="<?php echo site_url('/admin/reports/print_users/student'); ?>" target="_blank" class="text-gray-700 block px-4 py-3 text-sm hover:bg-gray-50" role="menuitem">
                                <div class="font-bold"><i class="fas fa-user-graduate mr-2 text-green-500"></i> Student Report</div>
                                <span class="text-xs text-gray-500 ml-6">Grades & performance data</span>
                            </a>
                            
                            <a href="<?php echo site_url('/admin/reports/print_users/teacher'); ?>" target="_blank" class="text-gray-700 block px-4 py-3 text-sm hover:bg-gray-50" role="menuitem">
                                <div class="font-bold"><i class="fas fa-chalkboard-teacher mr-2 text-amber-500"></i> Teacher Registry</div>
                                <span class="text-xs text-gray-500 ml-6">List of all instructors</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    
    <a href="<?php echo site_url('/dashboard'); ?>" class="back-link inline-flex items-center text-sm font-semibold text-neutral-500 hover:text-neutral-800 mb-6 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
    </a>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-xl border border-neutral-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-neutral-400 uppercase tracking-wide mb-1">
                    <?php echo ucfirst($range); ?> Signups
                </div>
                <div class="text-3xl font-extrabold text-neutral-900"><?php echo $stats['users']; ?></div>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xl">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-neutral-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-neutral-400 uppercase tracking-wide mb-1">
                    <?php echo ucfirst($range); ?> Courses
                </div>
                <div class="text-3xl font-extrabold text-neutral-900"><?php echo $stats['courses']; ?></div>
            </div>
            <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fas fa-book"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-neutral-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-neutral-400 uppercase tracking-wide mb-1">
                    <?php echo ucfirst($range); ?> Submissions
                </div>
                <div class="text-3xl font-extrabold text-neutral-900"><?php echo $stats['submissions']; ?></div>
            </div>
            <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fas fa-file-upload"></i>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl border border-neutral-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-neutral-400 uppercase tracking-wide mb-1">
                    Total Assignments
                </div>
                <div class="text-3xl font-extrabold text-neutral-900"><?php echo $stats['assignments']; ?></div>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fas fa-tasks"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white p-6 rounded-xl border border-neutral-200 shadow-sm">
            <h3 class="text-lg font-bold text-neutral-800 mb-4">Submission Trends (Last 6 Months)</h3>
            <div class="h-64">
                <canvas id="submissionChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-neutral-200 shadow-sm">
            <h3 class="text-lg font-bold text-neutral-800 mb-4">User Growth (Last 6 Months)</h3>
            <div class="h-64">
                <canvas id="growthChart"></canvas>
            </div>
        </div>
    </div>

    <div class="space-y-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden h-full">
                <div class="p-6 border-b border-neutral-100 flex items-center gap-3 bg-yellow-50">
                    <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-neutral-800">Top Performing Students</h2>
                        <p class="text-xs text-neutral-500">Highest average grades across all courses</p>
                    </div>
                </div>
                <div class="p-0">
                    <table class="min-w-full text-left text-sm">
                        <tbody class="divide-y divide-neutral-100">
                            <?php if(empty($top_students)): ?>
                                <tr><td class="p-6 text-center text-neutral-400 italic">No grading data available yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($top_students as $index => $student): ?>
                                    <tr class="hover:bg-neutral-50">
                                        <td class="px-6 py-4 font-medium text-neutral-900">
                                            <span class="mr-3 font-bold text-neutral-400">#<?php echo $index + 1; ?></span>
                                            <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                                <?php echo number_format($student['percentage'], 1); ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden h-full">
                <div class="p-6 border-b border-neutral-100 flex items-center gap-3 bg-indigo-50">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-neutral-800">Course Performance</h2>
                        <p class="text-xs text-neutral-500">Highest average class scores</p>
                    </div>
                </div>
                <div class="p-0">
                    <table class="min-w-full text-left text-sm">
                        <tbody class="divide-y divide-neutral-100">
                            <?php if(empty($top_courses)): ?>
                                <tr><td class="p-6 text-center text-neutral-400 italic">No grading data available yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($top_courses as $course): ?>
                                    <tr class="hover:bg-neutral-50">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-neutral-900"><?php echo htmlspecialchars($course['title']); ?></div>
                                            <div class="text-xs text-neutral-500">Instr: <?php echo htmlspecialchars($course['last_name']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="text-lg font-bold text-indigo-700"><?php echo number_format($course['average_percentage'], 1); ?>%</div>
                                            <div class="text-xs text-neutral-400">Avg. Score</div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="p-6 border-b border-neutral-100 flex justify-between items-center bg-neutral-50">
                <h2 class="text-lg font-bold text-neutral-800">Master Course Registry</h2>
                <span class="text-xs font-medium bg-white border border-neutral-200 px-2 py-1 rounded text-neutral-500">
                    <?php echo count($master_course_list ?? []); ?> Records
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-white text-neutral-500 font-semibold border-b border-neutral-200">
                        <tr>
                            <th class="px-6 py-4">Course Title</th>
                            <th class="px-6 py-4">Instructor</th>
                            <th class="px-6 py-4 text-center">Students</th>
                            <th class="px-6 py-4 text-center">Assignments</th>
                            <th class="px-6 py-4 text-right">Date Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        <?php if(empty($master_course_list)): ?>
                            <tr><td colspan="5" class="px-6 py-8 text-center text-neutral-400 italic">No courses found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($master_course_list as $course): ?>
                                <tr class="hover:bg-neutral-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-neutral-900"><?php echo htmlspecialchars($course['title']); ?></td>
                                    <td class="px-6 py-4 text-neutral-600"><?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <?php echo $course['student_count']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-neutral-500"><?php echo $course['assignment_count']; ?></td>
                                    <td class="px-6 py-4 text-right text-neutral-400 text-xs"><?php echo date('M d, Y', strtotime($course['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="p-6 border-b border-neutral-100 flex justify-between items-center bg-neutral-50">
                <h2 class="text-lg font-bold text-neutral-800">User Registry</h2>
                <span class="text-xs font-medium bg-white border border-neutral-200 px-2 py-1 rounded text-neutral-500">
                    <?php echo count($master_user_list ?? []); ?> Records
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-white text-neutral-500 font-semibold border-b border-neutral-200">
                        <tr>
                            <th class="px-6 py-4">User Name</th>
                            <th class="px-6 py-4">Email Address</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        <?php if(empty($master_user_list)): ?>
                            <tr><td colspan="5" class="px-6 py-8 text-center text-neutral-400 italic">No users found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($master_user_list as $user): ?>
                                <tr class="hover:bg-neutral-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-neutral-900">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center text-xs font-bold border border-primary-100">
                                                <?php echo strtoupper(substr($user['first_name'],0,1).substr($user['last_name'],0,1)); ?>
                                            </div>
                                            <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-neutral-600"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="capitalize inline-block px-2 py-0.5 rounded text-xs font-medium border <?php echo $user['role']=='admin'?'bg-purple-50 text-purple-700 border-purple-100':($user['role']=='teacher'?'bg-amber-50 text-amber-700 border-amber-100':'bg-blue-50 text-blue-700 border-blue-100'); ?>">
                                            <?php echo $user['role']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if ($user['status'] == 'approved'): ?>
                                            <span class="text-green-600 font-bold text-xs"><i class="fas fa-check-circle mr-1"></i> Active</span>
                                        <?php elseif ($user['status'] == 'suspended'): ?>
                                            <span class="text-red-600 font-bold text-xs"><i class="fas fa-ban mr-1"></i> Suspended</span>
                                        <?php else: ?>
                                            <span class="text-amber-600 font-bold text-xs"><i class="fas fa-clock mr-1"></i> Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right text-neutral-400 text-xs"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
    // Dropdown Menu Logic
    window.addEventListener('click', function(e) {
        if (!document.getElementById('print-controls').contains(e.target)) {
            document.getElementById('print-menu').classList.add('hidden');
        }
    });

    // Prepare Data from PHP
    const subData = <?php echo json_encode($submission_trends ?? []); ?>;
    const regData = <?php echo json_encode($registration_trends ?? []); ?>;

    // 1. Submission Trend Chart
    if (document.getElementById('submissionChart')) {
        new Chart(document.getElementById('submissionChart'), {
            type: 'line',
            data: {
                labels: subData.map(d => d.month),
                datasets: [{
                    label: 'Submissions',
                    data: subData.map(d => d.count),
                    borderColor: '#f59e0b', // Amber
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    }

    // 2. Growth Trend Chart
    if (document.getElementById('growthChart')) {
        new Chart(document.getElementById('growthChart'), {
            type: 'bar',
            data: {
                labels: regData.map(d => d.month_label),
                datasets: [{
                    label: 'New Users',
                    data: regData.map(d => d.count),
                    backgroundColor: '#3b82f6', // Blue
                    borderRadius: 4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    }
</script>