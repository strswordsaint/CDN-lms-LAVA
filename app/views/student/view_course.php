<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* === Modern Variables & Reset === */
    :root {
        --bg-body: #eef2f6; 
        --primary-soft: #eff6ff;
        --primary-border: #bfdbfe;
        --primary-text: #1d4ed8;
    }
    
    body {
        background-color: var(--bg-body);
        font-family: 'Inter', sans-serif;
    }

    /* === DESIGNED BANNER === */
    .course-banner {
        background: white;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #e2e8f0;
        padding: 2.5rem 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        margin-bottom: 2rem;
    }
    
    /* Decorative Background Elements */
    .banner-decoration {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.6;
        z-index: 0;
    }
    .decoration-1 { top: -60%; left: -10%; width: 500px; height: 500px; background: #dbeafe; }
    .decoration-2 { bottom: -60%; right: -5%; width: 400px; height: 400px; background: #e0e7ff; }

    .banner-content {
        position: relative;
        z-index: 10;
    }

    /* === STICKY NAVIGATION === */
    .sticky-tabs-wrapper {
        position: sticky;
        top: 0;
        z-index: 40;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid #cbd5e1;
        padding: 0.75rem 0;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .nav-pills {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding: 0.25rem;
    }

    .tab-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1.25rem;
        border-radius: 999px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        background: transparent;
        border: 1px solid transparent;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .tab-pill:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }

    .tab-pill.active {
        background-color: #2563eb; /* Primary Blue */
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        border-color: #2563eb;
    }

    /* === HUNTJOBS CARD STYLE === */
    .hunt-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid #e2e8f0; 
        box-shadow: 0 1px 3px rgba(0,0,0,0.04); 
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        display: flex;
        gap: 1.25rem;
        align-items: flex-start;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .hunt-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.15); 
    }

    /* Accent Bar Logic handled via PHP inline style/class */
    .accent-bar {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
    }

    .hunt-icon-box {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        box-shadow: inset 0 2px 4px rgba(255,255,255,0.3);
    }

    .meta-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    /* === RICH CONTENT AREA === */
    .post-content-area {
        margin-top: 1rem;
        color: #334155;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    .post-content-area iframe, 
    .post-content-area video {
        max-width: 100%;
        border-radius: 0.75rem;
        margin-top: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .post-content-area img {
        max-width: 100%;
        height: auto;
        border-radius: 0.75rem;
        margin-top: 0.5rem;
        border: 1px solid #e2e8f0;
    }

    /* === ATTACHMENT FILE CARDS === */
    .attachment-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px dashed #e2e8f0;
    }
    
    .file-card {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s;
        text-decoration: none;
        max-width: 100%;
    }
    
    .file-card:hover {
        background: white;
        border-color: #93c5fd;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        transform: translateY(-1px);
    }
    
    .file-icon {
        width: 2rem;
        height: 2rem;
        background: #e0f2fe;
        color: #0284c7;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 0.875rem;
    }
    
    .file-info { display: flex; flex-direction: column; }
    .file-name { font-size: 0.8rem; font-weight: 600; color: #334155; }
    .file-action { font-size: 0.7rem; color: #64748b; }

    /* === FOCUS RINGS === */
    input:focus, textarea:focus, button:focus, .btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.4);
        border-color: #3b82f6;
        transition: all 0.2s;
    }

    /* === MODAL STYLES === */
    #progressModal {
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }

    .tab-panel { display: none; animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .tab-panel.active { display: block; }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="course-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            
            <div class="flex-1 min-w-0">
                <a href="<?php echo site_url('/courses/my'); ?>" class="inline-flex items-center text-sm font-semibold text-neutral-500 hover:text-primary-600 mb-3 transition-colors">
                    <div class="w-6 h-6 rounded-full bg-white border border-neutral-200 flex items-center justify-center mr-2 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Back to My Courses
                </a>
                <h1 class="text-3xl md:text-4xl font-extrabold text-neutral-900 tracking-tight">
                    <?php echo htmlspecialchars($course['title']); ?>
                </h1>
                <div class="mt-2 text-neutral-500 max-w-2xl text-sm line-clamp-2">
                    <?php echo nl2br(htmlspecialchars($course['description'])); ?>
                </div>
            </div>

            <div class="flex flex-col items-end gap-3">
                <div class="bg-white/80 backdrop-blur border border-neutral-200 shadow-sm rounded-xl px-5 py-3 text-right">
                    <div class="text-xs font-bold text-neutral-400 uppercase tracking-wider mb-0.5">Enrollment Code</div>
                    <div class="text-xl font-mono font-bold text-primary-700 select-all">
                        <?php echo htmlspecialchars($course['enrollment_code'] ?? $course['course_id']); ?>
                    </div>
                </div>
                
                <button type="button" id="btnViewProgress" class="btn bg-white border border-neutral-200 text-neutral-600 hover:text-primary-600 hover:border-primary-300 shadow-sm rounded-lg px-4 py-2 text-sm font-bold transition-all flex items-center">
                    <i class="fas fa-chart-pie mr-2 text-primary-500"></i> My Grades
                </button>
            </div>

        </div>
    </div>
</div>

<div class="sticky-tabs-wrapper">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="nav-pills">
            <button class="tab-pill active" data-tab="announcements">
                <i class="fas fa-bullhorn mr-2"></i> Stream
            </button>
            <button class="tab-pill" data-tab="activities">
                <i class="fas fa-gamepad mr-2"></i> Activities
            </button>
            <button class="tab-pill" data-tab="assignments">
                <i class="fas fa-tasks mr-2"></i> Assignments
            </button>
            <button class="tab-pill" data-tab="materials">
                <i class="fas fa-folder-open mr-2"></i> Materials
            </button>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="max-w-5xl mx-auto">
        
        <div id="tab-panel-announcements" class="tab-panel active">
            <div class="space-y-4">
                <?php if (empty($announcements)): ?>
                     <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-neutral-200">
                        <div class="w-16 h-16 bg-neutral-50 rounded-full flex items-center justify-center mx-auto mb-3 text-neutral-400">
                            <i class="far fa-comment-dots text-3xl"></i>
                        </div>
                        <h3 class="text-neutral-900 font-semibold">It's quiet here</h3>
                        <p class="text-neutral-500 text-sm">No announcements posted yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($announcements as $post): ?>
                        <?php 
                            // Determine Type & Colors
                            $isAssignment = $post['type'] == 'assignment';
                            $isActivity = $post['type'] == 'activity';
                            $isQuiz = $post['type'] == 'quiz';
                            
                            $bgClass = 'bg-neutral-100 text-neutral-500';
                            $icon = 'fa-bullhorn';
                            $accentColor = 'bg-neutral-400';
                            $hoverBorder = 'border-neutral-300';
                            
                            if ($isAssignment) { 
                                $bgClass = 'bg-blue-50 text-blue-600 border-blue-200'; 
                                $icon = 'fa-clipboard-list'; 
                                $accentColor = 'bg-blue-500';
                                $hoverBorder = 'border-blue-300';
                            }
                            elseif ($isActivity) { 
                                $bgClass = 'bg-amber-50 text-amber-600 border-amber-200'; 
                                $icon = 'fa-gamepad'; 
                                $accentColor = 'bg-amber-500';
                                $hoverBorder = 'border-amber-300';
                            }
                            elseif ($isQuiz) { 
                                $bgClass = 'bg-purple-50 text-purple-600 border-purple-200'; 
                                $icon = 'fa-puzzle-piece'; 
                                $accentColor = 'bg-purple-500';
                                $hoverBorder = 'border-purple-300';
                            }
                        ?>
                        
                        <div class="hunt-card group hover:<?php echo $hoverBorder; ?>">
                            <div class="accent-bar <?php echo $accentColor; ?>"></div>

                            <div class="hunt-icon-box <?php echo $bgClass; ?> border">
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col md:flex-row justify-between md:items-start gap-4">
                                    <div>
                                        <?php if ($isAssignment || $isActivity || $isQuiz): ?>
                                            <a href="<?php echo site_url(($isQuiz ? '/quiz/' : '/assignment/') . $post['assignment_id']); ?>" class="text-xl font-bold text-neutral-900 group-hover:text-primary-700 transition-colors block mb-1">
                                                <?php echo htmlspecialchars($post['title']); ?>
                                            </a>
                                        <?php else: ?>
                                            <h3 class="text-xl font-bold text-neutral-900 mb-1"><?php echo htmlspecialchars($post['title']); ?></h3>
                                        <?php endif; ?>

                                        <div class="flex flex-wrap items-center gap-3 text-sm">
                                            <span class="meta-badge bg-neutral-100 text-neutral-600 border border-neutral-200">
                                                <?php echo ucfirst($post['type']); ?>
                                            </span>
                                            
                                            <?php if ($isAssignment || $isActivity || $isQuiz): ?>
                                                <span class="text-neutral-300">&bull;</span>
                                                <span class="flex items-center text-neutral-500 font-medium">
                                                    <i class="far fa-clock mr-1.5"></i> Due: <?php echo date('M d, g:i A', strtotime($post['due_date'])); ?>
                                                </span>
                                                <span class="flex items-center font-semibold text-neutral-700 bg-neutral-50 px-2 py-0.5 rounded border border-neutral-100">
                                                    <?php echo htmlspecialchars($post['points']); ?> pts
                                                </span>
                                            <?php else: ?>
                                                <span class="text-neutral-400">Posted on <?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if ($isAssignment || $isActivity || $isQuiz): ?>
                                        <div class="flex-shrink-0 mt-2 md:mt-0">
                                            <?php if (!empty($post['submission_id'])): ?>
                                                <a href="<?php echo site_url(($isQuiz ? '/quiz/' : '/assignment/') . $post['assignment_id'] . ($isQuiz ? '/results' : '')); ?>" class="btn btn-primary rounded-lg px-4 py-2 shadow-sm text-sm">
                                                    <?php echo $isQuiz ? 'View Results' : 'View Submission'; ?>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo site_url(($isQuiz ? '/quiz/' : '/assignment/') . $post['assignment_id']); ?>" class="btn btn-success rounded-lg px-4 py-2 shadow-sm text-sm">
                                                    <?php echo $isQuiz ? 'Take Quiz' : 'Submit Work'; ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($post['description'])): ?>
                                    <div class="post-content-area">
                                        <?php echo $post['description']; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($post['attachments'])): ?>
                                    <div class="attachment-grid">
                                        <?php foreach ($post['attachments'] as $file): ?>
                                            <a href="<?php echo base_url() . $file['file_path']; ?>" download class="file-card">
                                                <div class="file-icon">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                                <div class="file-info">
                                                    <span class="file-name"><?php echo htmlspecialchars($file['file_name']); ?></span>
                                                    <span class="file-action">Download</span>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mt-4 pt-3 border-t border-neutral-100 flex justify-end">
                                    <button type="button" class="btn-replies text-neutral-500 hover:text-primary-600 text-sm font-medium flex items-center transition-colors" data-post-id="<?php echo $post['assignment_id']; ?>" data-post-title="<?php echo htmlspecialchars($post['title']); ?>">
                                        <i class="far fa-comment-dots mr-2 text-lg"></i> 
                                        <?php echo $post['reply_count'] > 0 ? $post['reply_count'] . ' Comments' : 'Comment'; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div id="tab-panel-activities" class="tab-panel">
            <div class="space-y-4">
                <?php if (empty($activities)): ?>
                    <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-neutral-200 text-neutral-400">No activities posted yet.</div>
                <?php else: ?>
                    <?php foreach ($activities as $post): ?>
                        <?php 
                            $assignment = $post; // Map variable for include
                            include 'app/views/student/all_activity_item.php'; 
                        ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div id="tab-panel-assignments" class="tab-panel">
            <div class="space-y-4">
                <?php if (empty($assignments)): ?>
                    <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-neutral-200 text-neutral-400">No assignments posted yet.</div>
                <?php else: ?>
                    <?php foreach ($assignments as $post): ?>
                        <?php 
                            $assignment = $post; // Map variable for include
                            include 'app/views/student/all_assignment_item.php'; 
                        ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div id="tab-panel-materials" class="tab-panel">
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
                <div class="p-6 border-b border-neutral-100 bg-neutral-50 flex justify-between items-center">
                    <h3 class="font-bold text-neutral-800 text-lg">Course Resources</h3>
                </div>
                <div class="divide-y divide-neutral-100">
                    <?php if (empty($materials)): ?>
                        <div class="p-12 text-center text-neutral-400 italic">No materials uploaded yet.</div>
                    <?php else: ?>
                        <?php foreach ($materials as $material): ?>
                            <div class="p-5 flex justify-between items-center hover:bg-neutral-50 transition-colors group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-neutral-100 rounded-xl flex items-center justify-center text-neutral-500 group-hover:bg-white group-hover:shadow-md transition-all">
                                        <i class="far fa-file-alt text-xl"></i>
                                    </div>
                                    <div>
                                        <a href="<?php echo base_url() . $material['file_path']; ?>" download class="text-lg font-bold text-neutral-800 hover:text-primary-600 hover:underline block transition-colors">
                                            <?php echo htmlspecialchars($material['file_name']); ?>
                                        </a>
                                        <span class="text-xs text-neutral-400 font-medium uppercase tracking-wide">
                                            Uploaded <?php echo date('M d, Y', strtotime($material['uploaded_at'])); ?>
                                        </span>
                                    </div>
                                </div>
                                <a href="<?php echo base_url() . $material['file_path']; ?>" download class="btn btn-success rounded-lg px-4 py-2 shadow-sm text-sm hover:shadow-md">
                                    <i class="fas fa-download mr-2"></i> Download
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="progressModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden flex flex-col transform transition-all scale-95 opacity-0" id="progressModalContent" style="max-height: 90vh;">
        
        <div class="p-6 border-b border-neutral-100 flex justify-between items-center bg-neutral-50">
            <div>
                <h3 class="text-xl font-extrabold text-neutral-900">My Progress</h3>
                <p class="text-xs text-neutral-500">Performance Summary</p>
            </div>
            <button type="button" id="closeProgressModal" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto">
            
            <div id="progressLoader" class="flex justify-center py-12">
                <i class="fas fa-spinner fa-spin text-4xl text-primary-200"></i>
            </div>

            <div id="progressData" class="hidden">
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-blue-50 rounded-xl p-4 text-center border border-blue-100">
                        <div class="text-2xl font-black text-blue-600" id="statPercent">0%</div>
                        <div class="text-[10px] font-bold text-blue-400 uppercase tracking-wide mt-1">Current Grade</div>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 text-center border border-green-100">
                        <div class="text-2xl font-black text-green-700" id="statPoints">0/0</div>
                        <div class="text-[10px] font-bold text-green-500 uppercase tracking-wide mt-1">Total Points</div>
                    </div>
                    <div class="bg-amber-50 rounded-xl p-4 text-center border border-amber-100">
                        <div class="text-2xl font-black text-amber-600" id="statPending">0</div>
                        <div class="text-[10px] font-bold text-amber-500 uppercase tracking-wide mt-1">Pending</div>
                    </div>
                    <div class="bg-red-50 rounded-xl p-4 text-center border border-red-100">
                        <div class="text-2xl font-black text-red-600" id="statMissing">0</div>
                        <div class="text-[10px] font-bold text-red-400 uppercase tracking-wide mt-1">Missing</div>
                    </div>
                </div>

                <h4 class="text-sm font-bold text-neutral-800 mb-4 border-b border-neutral-100 pb-2">Detailed Breakdown</h4>
                
                <div id="gradesList" class="space-y-2">
                    </div>

            </div>
        </div>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // --- Main Tab Switching Logic ---
    var storageKey = 'studentCourseActiveTab_<?php echo $course['course_id']; ?>';
    var savedTab = sessionStorage.getItem(storageKey);
    const urlParams = new URLSearchParams(window.location.search);
    const urlTab = urlParams.get('tab');
    
    if (urlTab) {
        savedTab = urlTab;
        sessionStorage.setItem(storageKey, urlTab);
    }

    if (!savedTab) {
        savedTab = 'announcements';
    }

    function activateTab(tab) {
        $('.tab-pill').removeClass('active');
        $('.tab-panel').removeClass('active');
        
        $('.tab-pill[data-tab="' + tab + '"]').addClass('active');
        $('#tab-panel-' + tab).addClass('active');
        
        sessionStorage.setItem(storageKey, tab);
    }

    activateTab(savedTab);

    $('.tab-pill').on('click', function(e) {
        e.preventDefault();
        activateTab($(this).data('tab'));
        $('html, body').animate({ scrollTop: 0 }, 300);
    });

    // === PROGRESS MODAL LOGIC ===
    const modal = $('#progressModal');
    const content = $('#progressModalContent');
    const loader = $('#progressLoader');
    const dataArea = $('#progressData');

    $('#btnViewProgress').on('click', function() {
        modal.removeClass('hidden');
        setTimeout(() => {
            content.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        }, 10);

        // Reset state
        loader.removeClass('hidden');
        dataArea.addClass('hidden');

        // Fetch Data via AJAX
        $.ajax({
            url: '<?php echo site_url("/student/api/progress/" . $course['course_id']); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // Update Stats
                $('#statPercent').text(response.stats.percentage + '%');
                $('#statPoints').text(response.stats.earned + ' / ' + response.stats.total);
                $('#statPending').text(response.stats.pending);
                $('#statMissing').text(response.stats.missing);

                // Build List
                let html = '';
                if(response.grades.length === 0) {
                    html = '<p class="text-center text-neutral-400 py-4">No assignments found.</p>';
                } else {
                    response.grades.forEach(item => {
                        html += `
                            <a href="${item.link}" class="flex items-center justify-between p-3 rounded-lg border border-neutral-100 hover:border-primary-200 hover:bg-primary-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-white border border-neutral-200 flex items-center justify-center text-neutral-400 text-xs shadow-sm">
                                        <i class="fas fa-${item.type === 'Quiz' ? 'puzzle-piece' : 'tasks'}"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-sm text-neutral-800 group-hover:text-primary-700">${item.title}</div>
                                        <div class="text-[10px] text-neutral-400">Due: ${item.due_date}</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border ${item.badge_class}">
                                    ${item.status_label}
                                </span>
                            </a>
                        `;
                    });
                }
                $('#gradesList').html(html);

                // Show Content
                loader.addClass('hidden');
                dataArea.removeClass('hidden');
            },
            error: function() {
                loader.addClass('hidden');
                $('#gradesList').html('<p class="text-center text-red-500 py-4">Failed to load progress data.</p>').removeClass('hidden');
            }
        });
    });

    // Close Modal Logic
    $('#closeProgressModal').on('click', function() {
        content.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => {
            modal.addClass('hidden');
        }, 200);
    });
    
    // Close on click outside
    $(window).on('click', function(e) {
        if (e.target == modal[0]) {
            $('#closeProgressModal').click();
        }
    });
});
</script>