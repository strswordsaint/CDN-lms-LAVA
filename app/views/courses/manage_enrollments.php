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
    .decoration-1 { top: -60%; left: -10%; width: 500px; height: 500px; background: #dbeafe; }
    .decoration-2 { bottom: -60%; right: -5%; width: 400px; height: 400px; background: #e0e7ff; }
    .banner-content { position: relative; z-index: 10; }

    /* === STICKY TABS === */
    .sticky-tabs-wrapper {
        position: sticky; top: 0; z-index: 40;
        background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px);
        border-bottom: 1px solid #cbd5e1; padding: 0.75rem 0; margin-bottom: 2rem;
        transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .nav-pills { display: flex; gap: 0.5rem; overflow-x: auto; padding: 0.25rem; }
    .tab-pill {
        display: inline-flex; align-items: center; padding: 0.5rem 1.25rem; border-radius: 999px;
        font-size: 0.875rem; font-weight: 600; color: #64748b; background: transparent;
        border: 1px solid transparent; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;
    }
    .tab-pill:hover { background-color: #e2e8f0; color: #1e293b; }
    .tab-pill.active {
        background-color: #2563eb; color: white; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3); border-color: #2563eb;
    }
    .notification-badge {
        display: inline-flex; align-items: center; justify-content: center;
        background-color: #ef4444; color: white; font-size: 0.65rem; font-weight: bold;
        border-radius: 9999px; padding: 0 0.4rem; height: 1.25rem; margin-left: 0.5rem; border: 2px solid white;
    }

    /* === LEADERBOARD STYLES === */
    .rank-card {
        @apply bg-white rounded-xl border p-5 flex items-center gap-4 transition-transform hover:-translate-y-1 shadow-sm;
    }
    .rank-1 { border-color: #fbbf24; background: linear-gradient(to bottom right, #fff, #fffbeb); }
    .rank-2 { border-color: #94a3b8; background: linear-gradient(to bottom right, #fff, #f8fafc); }
    .rank-3 { border-color: #d97706; background: linear-gradient(to bottom right, #fff, #fff7ed); }
    
    .medal-icon {
        @apply w-12 h-12 rounded-full flex items-center justify-center text-xl shadow-sm border-2 border-white;
    }
    .medal-1 { background-color: #fcd34d; color: #92400e; }
    .medal-2 { background-color: #e2e8f0; color: #475569; }
    .medal-3 { background-color: #fdba74; color: #9a3412; }

    /* === TABLE & CARD STYLES === */
    .data-table-container {
        background: white; border-radius: 1rem; border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden;
    }
    .modern-table th {
        background: #f8fafc; color: #64748b; font-weight: 600; text-transform: uppercase;
        font-size: 0.75rem; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 1px solid #e2e8f0;
    }
    .modern-table td {
        padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 0.9rem; vertical-align: middle;
    }
    .modern-table tr:last-child td { border-bottom: none; }
    .modern-table tr:hover td { background-color: #f8fafc; }

    /* Avatar */
    .avatar-circle {
        width: 2.5rem; height: 2.5rem; border-radius: 50%; background: #eff6ff; color: #3b82f6;
        display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; border: 1px solid #bfdbfe;
    }

    /* === MODAL STYLES === */
    #gradeModal {
        background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);
    }
    .grade-modal-content { max-height: 85vh; }
    .grade-item-row {
        @apply p-4 rounded-lg border border-neutral-100 hover:bg-neutral-50 transition-colors flex justify-between items-center mb-2;
    }

    .tab-panel { display: none; animation: slideUp 0.3s ease-out; }
    .tab-panel.active { display: block; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* Filter Buttons Active State */
    .filter-btn.active {
        @apply bg-primary-50 border-primary-200 text-primary-700;
    }
</style>

<div class="page-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <a href="<?php echo site_url('/courses'); ?>" class="inline-flex items-center text-sm font-semibold text-neutral-500 hover:text-neutral-800 mb-3 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Courses
                </a>
                <h1 class="text-3xl font-extrabold text-neutral-900 tracking-tight mb-1"><?php echo htmlspecialchars($course['title']); ?></h1>
                <p class="text-neutral-500 text-sm">Enrollment Code: <span class="font-mono font-bold text-primary-600"><?php echo htmlspecialchars($course['enrollment_code']); ?></span></p>
            </div>
        </div>
    </div>
</div>

<div class="sticky-tabs-wrapper">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="nav-pills">
            <button class="tab-pill active" data-tab="enrolled">
                Enrolled Students
                <span class="ml-2 text-xs bg-neutral-200 text-neutral-600 py-0.5 px-2 rounded-full"><?php echo count($approved_enrollments); ?></span>
            </button>
            <button class="tab-pill" data-tab="pending">
                Pending Requests
                <?php if(count($pending_enrollments) > 0): ?>
                    <span class="notification-badge"><?php echo count($pending_enrollments); ?></span>
                <?php endif; ?>
            </button>
            <button class="tab-pill" data-tab="gradebook">
                Gradebook & Ranking
                <span class="ml-2 text-xs bg-amber-100 text-amber-700 py-0.5 px-2 rounded-full border border-amber-200"><i class="fas fa-trophy mr-1"></i>Ranked</span>
            </button>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    
    <?php if (!empty($success_message)): ?>
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-3 text-lg"></i> <span class="font-medium"><?php echo htmlspecialchars($success_message); ?></span>
        </div>
    <?php endif; ?>
    
    <div class="data-table-container">
        
        <div id="tab-panel-enrolled" class="tab-panel active">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left modern-table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Date Enrolled</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($approved_enrollments)): ?>
                            <tr><td colspan="3" class="text-center py-8 text-neutral-400">No enrolled students yet.</td></tr>
                        <?php else: ?>
                            <?php foreach($approved_enrollments as $student): ?>
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar-circle">
                                                <?php echo strtoupper(substr($student['first_name'],0,1).substr($student['last_name'],0,1)); ?>
                                            </div>
                                            <div>
                                                <div class="font-bold text-neutral-900"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></div>
                                                <div class="text-xs text-neutral-500"><?php echo htmlspecialchars($student['email']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-neutral-500 text-sm"><?php echo date('M d, Y', strtotime($student['enrolled_at'])); ?></td>
                                    <td class="text-right">
                                        <form action="<?php echo site_url('/courses/enrollment/remove/' . $student['enrollment_id']); ?>" method="POST" onsubmit="return confirm('Remove this student?');">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm bg-white border border-neutral-200 text-red-500 hover:bg-red-50 hover:border-red-200 rounded-lg px-3 py-1.5 transition-colors">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab-panel-pending" class="tab-panel">
             <div class="overflow-x-auto">
                <table class="min-w-full text-left modern-table">
                    <thead class="bg-amber-50">
                        <tr>
                            <th class="text-amber-900">Student Name</th>
                            <th class="text-amber-900">Request Date</th>
                            <th class="text-right text-amber-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($pending_enrollments)): ?>
                            <tr><td colspan="3" class="text-center py-8 text-neutral-400">No pending requests.</td></tr>
                        <?php else: ?>
                            <?php foreach($pending_enrollments as $student): ?>
                                <tr>
                                    <td class="font-medium text-neutral-900"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                    <td class="text-neutral-500 text-sm"><?php echo date('M d, Y', strtotime($student['enrolled_at'])); ?></td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <form action="<?php echo site_url('/courses/enrollment/approve/' . $student['enrollment_id']); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-success rounded-lg px-3 py-1.5">Approve</button>
                                            </form>
                                            <form action="<?php echo site_url('/courses/enrollment/reject/' . $student['enrollment_id']); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-danger rounded-lg px-3 py-1.5">Reject</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab-panel-gradebook" class="tab-panel">
            <div class="p-6 bg-neutral-50">
                
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h3 class="text-lg font-bold text-neutral-800">Class Performance</h3>
                    <div class="flex gap-2">
                        <button class="btn btn-sm bg-white border border-neutral-200 text-neutral-600 hover:bg-neutral-50 rounded-lg filter-btn active" id="sortDesc">
                            <i class="fas fa-sort-amount-down mr-1.5"></i> Highest First
                        </button>
                        <button class="btn btn-sm bg-white border border-neutral-200 text-neutral-600 hover:bg-neutral-50 rounded-lg filter-btn" id="sortAsc">
                            <i class="fas fa-sort-amount-up mr-1.5"></i> Lowest First
                        </button>
                        <button class="btn btn-sm bg-white border border-neutral-200 text-red-500 hover:bg-red-50 hover:border-red-200 rounded-lg filter-btn" id="filterRisk">
                            <i class="fas fa-exclamation-triangle mr-1.5"></i> At Risk (<75%)
                        </button>
                    </div>
                </div>

                <?php if(!empty($gradebook) && count($gradebook) > 0): ?>
                <div id="podium-section" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <?php if(isset($gradebook[1])): $s = $gradebook[1]; ?>
                    <div class="rank-card rank-2 md:mt-4">
                        <div class="medal-icon medal-2">2</div>
                        <div>
                            <h4 class="font-bold text-neutral-800 text-lg"><?php echo htmlspecialchars($s['name']); ?></h4>
                            <div class="text-2xl font-black text-slate-500"><?php echo $s['percentage']; ?>%</div>
                            <div class="text-xs text-neutral-500 uppercase tracking-wide">Silver</div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if(isset($gradebook[0])): $s = $gradebook[0]; ?>
                    <div class="rank-card rank-1 transform md:-translate-y-2 shadow-md">
                        <div class="medal-icon medal-1 text-2xl"><i class="fas fa-crown"></i></div>
                        <div>
                            <h4 class="font-bold text-neutral-900 text-xl"><?php echo htmlspecialchars($s['name']); ?></h4>
                            <div class="text-3xl font-black text-amber-500"><?php echo $s['percentage']; ?>%</div>
                            <div class="text-xs text-amber-600 font-bold uppercase tracking-wide">Top of Class</div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if(isset($gradebook[2])): $s = $gradebook[2]; ?>
                    <div class="rank-card rank-3 md:mt-6">
                        <div class="medal-icon medal-3">3</div>
                        <div>
                            <h4 class="font-bold text-neutral-800 text-lg"><?php echo htmlspecialchars($s['name']); ?></h4>
                            <div class="text-2xl font-black text-orange-700"><?php echo $s['percentage']; ?>%</div>
                            <div class="text-xs text-neutral-500 uppercase tracking-wide">Bronze</div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden shadow-sm">
                    <table class="min-w-full text-left modern-table" id="gradesTable">
                        <thead>
                            <tr>
                                <th class="w-16 text-center">Rank</th>
                                <th>Student</th>
                                <th class="text-center">Progress</th>
                                <th class="text-center cursor-pointer hover:bg-neutral-100 transition-colors" title="Sort by Average">Average <i class="fas fa-sort ml-1 text-neutral-300"></i></th>
                                <th class="text-center">Action Needed</th>
                                <th class="text-right">Details</th>
                            </tr>
                        </thead>
                        <tbody id="gradebookBody">
                            <?php if(empty($gradebook)): ?>
                                <tr><td colspan="6" class="text-center py-8 text-neutral-400">No data available.</td></tr>
                            <?php else: ?>
                                <?php 
                                    $rank = 1;
                                    foreach($gradebook as $entry): 
                                        // Icon for rank
                                        $rankDisplay = '<span class="text-neutral-400 font-mono text-sm">#' . $rank . '</span>';
                                        if ($rank == 1) $rankDisplay = '<i class="fas fa-trophy text-amber-400 text-lg"></i>';
                                        if ($rank == 2) $rankDisplay = '<i class="fas fa-medal text-slate-400 text-lg"></i>';
                                        if ($rank == 3) $rankDisplay = '<i class="fas fa-medal text-orange-400 text-lg"></i>';
                                ?>
                                    <tr class="hover:bg-blue-50/30 transition-colors grade-row" data-percentage="<?php echo $entry['percentage']; ?>">
                                        <td class="text-center rank-cell"><?php echo $rankDisplay; ?></td>
                                        <td>
                                            <div class="font-bold text-neutral-900"><?php echo htmlspecialchars($entry['name']); ?></div>
                                            <div class="text-xs text-neutral-500"><?php echo htmlspecialchars($entry['email']); ?></div>
                                        </td>
                                        <td class="text-center">
                                            <div class="text-xs font-bold text-neutral-600 mb-1">
                                                <?php echo $entry['earned']; ?> / <?php echo $entry['total']; ?> pts
                                            </div>
                                            <div class="w-24 h-1.5 bg-neutral-100 rounded-full mx-auto overflow-hidden">
                                                <div class="h-full bg-blue-500 rounded-full" style="width: <?php echo $entry['percentage']; ?>%"></div>
                                            </div>
                                        </td>
                                        <td class="text-center font-bold text-neutral-800 text-lg percentage-cell">
                                            <?php echo $entry['percentage']; ?>%
                                        </td>
                                        <td class="text-center">
                                            <?php if($entry['ungraded'] > 0): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                                    <?php echo $entry['ungraded']; ?> Ungraded
                                                </span>
                                            <?php else: ?>
                                                <span class="text-xs text-neutral-400"><i class="fas fa-check text-green-500 mr-1"></i>Done</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right">
                                            <button type="button" class="btn btn-sm bg-white border border-neutral-200 text-primary-600 hover:border-primary-200 hover:bg-primary-50 rounded-lg shadow-sm transition-colors view-grades-btn" 
                                                    data-student-id="<?php echo $entry['student_id']; ?>"
                                                    data-student-name="<?php echo htmlspecialchars($entry['name']); ?>">
                                                View Grades
                                            </button>
                                        </td>
                                    </tr>
                                <?php $rank++; endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div id="noRiskResults" class="hidden text-center py-8 text-neutral-400">No students found below 75%.</div>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="gradeModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl grade-modal-content overflow-hidden flex flex-col transform transition-all scale-95 opacity-0" id="modalContent">
        <div class="p-6 border-b border-neutral-100 flex justify-between items-center bg-neutral-50">
            <div>
                <h3 class="text-xl font-bold text-neutral-900" id="modalStudentName">Student Name</h3>
                <p class="text-xs text-neutral-500">Assignment Breakdown</p>
            </div>
            <button type="button" id="closeModal" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto" id="modalBody">
            <div class="flex justify-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-primary-200"></i></div>
        </div>
        
        <div class="p-4 border-t border-neutral-100 bg-neutral-50 text-right">
            <button type="button" class="btn btn-secondary rounded-lg" onclick="document.getElementById('gradeModal').classList.add('hidden')">Close</button>
        </div>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // Tab Switching
    var storageKey = 'enrollmentActiveTab_<?php echo $course['course_id']; ?>';
    var savedTab = sessionStorage.getItem(storageKey) || 'enrolled';
    
    function activateTab(tab) {
        $('.tab-pill').removeClass('active');
        $('.tab-panel').removeClass('active');
        $('.tab-pill[data-tab="' + tab + '"]').addClass('active');
        $('#tab-panel-' + tab).addClass('active');
        sessionStorage.setItem(storageKey, tab);
    }
    activateTab(savedTab);
    
    $('.tab-pill').on('click', function() {
        activateTab($(this).data('tab'));
    });

    // === SORT & FILTER LOGIC ===
    
    function setActiveButton(btnId) {
        $('.filter-btn').removeClass('active bg-primary-50 border-primary-200 text-primary-700').addClass('bg-white text-neutral-600');
        $('#' + btnId).removeClass('bg-white text-neutral-600').addClass('active bg-primary-50 border-primary-200 text-primary-700');
    }

    function sortRows(ascending) {
        var rows = $('.grade-row').get();
        rows.sort(function(a, b) {
            var valA = parseFloat($(a).data('percentage'));
            var valB = parseFloat($(b).data('percentage'));
            return ascending ? (valA - valB) : (valB - valA);
        });
        $.each(rows, function(index, row) {
            $('#gradebookBody').append(row);
        });
        
        // Toggle Podium visibility
        if(ascending) {
            $('#podium-section').slideUp();
        } else {
            $('#podium-section').slideDown();
        }
    }

    // Sort Descending (High to Low)
    $('#sortDesc').on('click', function() {
        setActiveButton('sortDesc');
        $('.grade-row').show(); // Show all
        $('#noRiskResults').addClass('hidden');
        sortRows(false);
    });

    // Sort Ascending (Low to High)
    $('#sortAsc').on('click', function() {
        setActiveButton('sortAsc');
        $('.grade-row').show(); // Show all
        $('#noRiskResults').addClass('hidden');
        sortRows(true);
    });

    // Filter At Risk (<75%)
    $('#filterRisk').on('click', function() {
        setActiveButton('filterRisk');
        $('#podium-section').slideUp(); // Hide podium when filtering
        
        let visibleCount = 0;
        $('.grade-row').each(function() {
            var val = parseFloat($(this).data('percentage'));
            if(val < 75) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
        });
        
        if(visibleCount === 0) {
            $('#noRiskResults').removeClass('hidden');
        } else {
            $('#noRiskResults').addClass('hidden');
        }
        // Also sort low to high implicitly
        sortRows(true); 
    });


    // Modal Logic (Existing)
    const modal = $('#gradeModal');
    const modalContent = $('#modalContent');
    
    $('.view-grades-btn').on('click', function() {
        const studentId = $(this).data('student-id');
        const studentName = $(this).data('student-name');
        const courseId = <?php echo $course['course_id']; ?>;
        
        $('#modalStudentName').text(studentName);
        $('#modalBody').html('<div class="flex justify-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-primary-200"></i></div>');
        
        modal.removeClass('hidden');
        setTimeout(() => {
            modalContent.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        }, 10);

        $.ajax({
            url: '<?php echo site_url("/courses/api/grades/"); ?>' + courseId + '/' + studentId,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                let html = '';
                if(data.length === 0) {
                    html = '<p class="text-center text-neutral-400">No assignments found for this course.</p>';
                } else {
                    data.forEach(item => {
                        let statusBadge = '';
                        let actionBtn = '';
                        
                        if (item.grade !== null) {
                            statusBadge = `<span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded border border-green-100">Graded: ${item.grade}/${item.points}</span>`;
                            actionBtn = `<a href="<?php echo site_url('/assignments/'); ?>${item.assignment_id}/submissions" class="text-xs text-primary-600 hover:underline">Review</a>`;
                        } else if (item.submission_id !== null) {
                            statusBadge = `<span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded border border-amber-100">Needs Grading</span>`;
                            actionBtn = `<a href="<?php echo site_url('/assignments/'); ?>${item.assignment_id}/submissions" class="btn btn-xs btn-primary px-3 py-1 rounded text-xs">Grade Now</a>`;
                        } else {
                             let isOverdue = new Date(item.due_date) < new Date();
                             if(isOverdue) {
                                 statusBadge = `<span class="text-xs font-bold text-red-500">Missing</span>`;
                             } else {
                                 statusBadge = `<span class="text-xs text-neutral-400">Not Submitted</span>`;
                             }
                        }

                        html += `
                            <div class="grade-item-row">
                                <div>
                                    <h4 class="text-sm font-bold text-neutral-800">${item.title}</h4>
                                    <div class="text-xs text-neutral-500 mt-0.5">Due: ${new Date(item.due_date).toLocaleDateString()}</div>
                                </div>
                                <div class="text-right flex flex-col items-end gap-1">
                                    ${statusBadge}
                                    ${actionBtn}
                                </div>
                            </div>
                        `;
                    });
                }
                $('#modalBody').html(html);
            },
            error: function() {
                $('#modalBody').html('<p class="text-center text-red-500">Failed to load grades. Please try again.</p>');
            }
        });
    });

    $('#closeModal').on('click', function() {
        modalContent.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => {
            modal.addClass('hidden');
        }, 200);
    });
});
</script>