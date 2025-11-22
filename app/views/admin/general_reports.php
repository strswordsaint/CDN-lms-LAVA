<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* === Modern Variables === */
    :root { --bg-body: #eef2f6; --primary-soft: #eff6ff; --primary-border: #bfdbfe; --primary-text: #1d4ed8; }
    body { background-color: var(--bg-body); font-family: 'Inter', sans-serif; }

    /* === DESIGNED BANNER === */
    .page-banner {
        background: white; position: relative; overflow: hidden; border-bottom: 1px solid #e2e8f0; padding: 2rem 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02); margin-bottom: 2rem;
    }
    .banner-decoration { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.6; z-index: 0; }
    .decoration-1 { top: -60%; left: -10%; width: 500px; height: 500px; background: #dcfce7; }
    .decoration-2 { bottom: -60%; right: -5%; width: 400px; height: 400px; background: #e0e7ff; }
    .banner-content { position: relative; z-index: 10; }

    /* === REPORT CARD STYLE === */
    .report-card {
        @apply bg-white rounded-xl border border-neutral-200 shadow-sm p-6 flex flex-col justify-between h-full transition-all duration-200;
    }
    .report-card:hover { @apply shadow-md border-primary-300 transform -translate-y-1; }
    
    .stat-value { @apply text-3xl font-extrabold text-neutral-900 mt-2 mb-1; }
    .stat-label { @apply text-xs font-bold text-neutral-400 uppercase tracking-wide; }
    
    /* Print Styles */
    @media print {
        body > nav, body > footer, #app-sidebar, #print-controls, .back-link { display: none !important; }
        body, html { background: #fff !important; color: #000 !important; height: auto !important; overflow: visible !important; }
        .main-layout { display: block !important; }
        .container { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
        .page-banner { box-shadow: none !important; border-bottom: 2px solid #000 !important; padding: 1rem 0 !important; margin-bottom: 1rem !important; }
        .report-card, .bg-white { box-shadow: none !important; border: 1px solid #ccc !important; break-inside: avoid; }
        table { width: 100% !important; border-collapse: collapse !important; }
        th, td { border: 1px solid #999 !important; padding: 8px !important; }
        .text-primary-600, .text-green-600, .text-red-600 { color: #000 !important; }
        .bg-green-100, .bg-red-100 { background: transparent !important; border: 1px solid #000 !important; }
    }
</style>

<div class="page-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-neutral-900 tracking-tight mb-1">General Reports</h1>
                <p class="text-neutral-500 text-sm">System-wide analytics and master records.</p>
            </div>
            
            <div id="print-controls" class="flex items-center gap-3">
                <form method="GET" class="flex items-center gap-2">
                    <select name="range" class="form-select text-sm border-neutral-300 rounded-lg focus:ring-primary-500" onchange="this.form.submit()">
                        <option value="all" <?php echo (!isset($_GET['range']) || $_GET['range'] == 'all') ? 'selected' : ''; ?>>All Time</option>
                        <option value="weekly" <?php echo (isset($_GET['range']) && $_GET['range'] == 'weekly') ? 'selected' : ''; ?>>This Week</option>
                        <option value="monthly" <?php echo (isset($_GET['range']) && $_GET['range'] == 'monthly') ? 'selected' : ''; ?>>This Month</option>
                        <option value="yearly" <?php echo (isset($_GET['range']) && $_GET['range'] == 'yearly') ? 'selected' : ''; ?>>This Year</option>
                    </select>
                </form>

                <button onclick="window.print();" class="btn btn-secondary flex items-center gap-2 shadow-sm">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    
    <a href="<?php echo site_url('/dashboard'); ?>" class="back-link inline-flex items-center text-sm font-semibold text-neutral-500 hover:text-neutral-800 mb-6 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
    </a>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="report-card border-l-4 border-l-indigo-500">
            <div class="flex justify-between items-start">
                <div>
                    <div class="stat-label">Total Courses</div>
                    <div class="stat-value"><?php echo $total_courses ?? 0; ?></div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                    <i class="fas fa-book"></i>
                </div>
            </div>
            <div class="text-xs text-neutral-500 mt-2">Active classes in system</div>
        </div>

        <div class="report-card border-l-4 border-l-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <div class="stat-label">Total Enrollments</div>
                    <div class="stat-value"><?php echo $total_enrollments ?? 0; ?></div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center text-lg">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            <div class="text-xs text-neutral-500 mt-2">Approved student seats</div>
        </div>

        <div class="report-card border-l-4 border-l-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <div class="stat-label">Assignments</div>
                    <div class="stat-value"><?php echo $total_assignments ?? 0; ?></div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
            <div class="text-xs text-neutral-500 mt-2">Created by teachers</div>
        </div>

        <div class="report-card border-l-4 border-l-amber-500">
            <div class="flex justify-between items-start">
                <div>
                    <div class="stat-label">Submissions</div>
                    <div class="stat-value"><?php echo $total_submissions ?? 0; ?></div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                    <i class="fas fa-file-upload"></i>
                </div>
            </div>
            <div class="text-xs text-neutral-500 mt-2">Files uploaded by students</div>
        </div>
    </div>

    <div class="space-y-8">
        
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="p-6 border-b border-neutral-100 flex justify-between items-center bg-neutral-50">
                <h2 class="text-lg font-bold text-neutral-800">Master Course List</h2>
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