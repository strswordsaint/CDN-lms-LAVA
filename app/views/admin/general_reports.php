<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    @media print {
        /* Hide all non-essential UI */
        body > nav, 
        body > footer, 
        #app-sidebar, 
        #print-button, 
        .back-link {
            display: none !important;
        }

        /* Ensure the main content and body fill the page */
        body, html {
            background: #fff !important;
            color: #000 !important;
        }
        main.flex-1 {
            padding: 0 !important;
            overflow: visible !important;
        }

        /* Reset the printable area to fill the page */
        #printable-area {
            visibility: visible !important;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 1rem;
            box-shadow: none !important;
            border: none !important;
        }

        /* Reset card styles for printing */
        .card, .card div {
            box-shadow: none !important;
            background: #fff !important;
            border: 1px solid #ccc !important;
            page-break-inside: avoid; /* Try to keep cards from splitting */
        }
        
        /* Ensure all text is black */
        h1, h2, div, p, th, td, span {
            color: #000 !important;
        }

        /* Add borders to tables */
        table, th, td {
            border: 1px solid #999;
            border-collapse: collapse;
        }
        tr {
            page-break-inside: avoid;
        }

        /* Set page margins */
        @page {
            margin: 0.75in;
        }
    }
</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="flex justify-between items-center mb-4">
        <a href="<?php echo site_url('/dashboard'); ?>" class="back-link text-sm text-primary-600 hover:underline inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
        </a>
        <button id="print-button" onclick="window.print();" class="btn btn-secondary">
            <i class="fas fa-print mr-2"></i> Print Report
        </button>
    </div>

    <div id="printable-area">
        <h1 class="text-2xl font-bold text-neutral-900 mb-6"><?php echo $page_title ?? 'General Reports'; ?></h1>

        <h2 class="text-xl font-semibold text-neutral-700 mb-4">Site-Wide Totals</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card p-6 flex items-center">
                <i class="fas fa-book-open text-3xl text-primary-500 mr-4"></i>
                <div>
                    <div class="text-sm text-neutral-500">Total Courses</div>
                    <div class="text-2xl font-bold text-neutral-800"><?php echo $total_courses ?? 0; ?></div>
                </div>
            </div>
            <div class="card p-6 flex items-center">
                <i class="fas fa-tasks text-3xl text-yellow-500 mr-4"></i>
                <div>
                    <div class="text-sm text-neutral-500">Total Assignments</div>
                    <div class="text-2xl font-bold text-neutral-800"><?php echo $total_assignments ?? 0; ?></div>
                </div>
            </div>
            <div class="card p-6 flex items-center">
                <i class="fas fa-user-graduate text-3xl text-success-500 mr-4"></i>
                <div>
                    <div class="text-sm text-neutral-500">Total Enrollments</div>
                    <div class="text-2xl font-bold text-neutral-800"><?php echo $total_enrollments ?? 0; ?></div>
                </div>
            </div>
            <div class="card p-6 flex items-center">
                <i class="fas fa-file-upload text-3xl text-neutral-500 mr-4"></i>
                <div>
                    <div class="text-sm text-neutral-500">Total Submissions</div>
                    <div class="text-2xl font-bold text-neutral-800"><?php echo $total_submissions ?? 0; ?></div>
                </div>
            </div>
        </div>

        <h2 class="text-xl font-semibold text-neutral-700 mb-4">Master Course Report</h2>
        <div class="card overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Teacher</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Students</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Assignments</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        <?php if(empty($master_course_list)): ?>
                            <tr><td colspan="4" class="p-4 text-center text-neutral-500">No courses found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($master_course_list as $course): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900"><?php echo htmlspecialchars($course['title']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600"><?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-neutral-500"><?php echo $course['student_count'] ?? 0; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-neutral-500"><?php echo $course['assignment_count'] ?? 0; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h2 class="text-xl font-semibold text-neutral-700 mb-4">Master User Report</h2>
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                         <?php if(empty($master_user_list)): ?>
                            <tr><td colspan="4" class="p-4 text-center text-neutral-500">No users found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($master_user_list as $user): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500"><?php echo ucfirst(htmlspecialchars($user['role'])); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <?php if ($user['status'] == 'approved'): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-success-100 text-success-800">Approved</span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-warning-100 text-warning-800">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> </div>

<?php include 'app/views/layouts/footer.php'; ?>