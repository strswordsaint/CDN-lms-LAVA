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
    .page-banner {
        background: white;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #e2e8f0;
        padding: 2.5rem 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    
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
        position: relative;
    }

    .tab-pill:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }

    .tab-pill.active {
        background-color: #2563eb;
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        border-color: #2563eb;
    }

    .tab-dot {
        display: inline-block;
        width: 6px;
        height: 6px;
        background-color: #ef4444;
        border-radius: 50%;
        margin-left: 8px;
        box-shadow: 0 0 0 2px white;
    }

    /* === HIGH VISIBILITY CARD STYLE === */
    .hunt-card {
        background: white;
        border-radius: 1rem;
        /* Darker Border */
        border: 1px solid #cbd5e1; 
        /* Visible Shadow */
        box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        display: flex;
        gap: 1.25rem;
        align-items: flex-start;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .hunt-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.15); 
        border-color: #3b82f6; /* Blue Highlight for Assignments */
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

    .tab-panel { display: none; animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .tab-panel.active { display: block; }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="page-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-neutral-900 tracking-tight mb-2">
                    <?php echo $page_title ?? 'All Assignments'; ?>
                </h1>
                <p class="text-neutral-500 text-sm">Track your assignments, submissions, and deadlines.</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-xl border border-blue-100 text-sm font-bold shadow-sm">
                    <i class="fas fa-tasks mr-2"></i> Task Manager
                </div>
            </div>
        </div>
    </div>
</div>

<div class="sticky-tabs-wrapper">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="nav-pills">
            <button class="tab-pill active" data-tab="upcoming">
                Upcoming
                <?php if (count($assignments_upcoming) > 0): ?>
                    <span class="tab-dot"></span>
                <?php endif; ?>
            </button>
            <button class="tab-pill" data-tab="past-due">
                Past Due
                <?php if (count($assignments_past_due) > 0): ?>
                    <span class="tab-dot"></span>
                <?php endif; ?>
            </button>
            <button class="tab-pill" data-tab="completed">
                Completed
            </button>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="max-w-5xl mx-auto">

        <div id="tab-panel-upcoming" class="tab-panel active">
            <?php if (empty($assignments_upcoming)): ?>
                <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-neutral-300">
                    <div class="w-16 h-16 bg-neutral-50 rounded-full flex items-center justify-center mx-auto mb-3 text-neutral-400">
                        <i class="fas fa-check-circle text-3xl"></i>
                    </div>
                    <h3 class="text-neutral-900 font-semibold">No Pending Work</h3>
                    <p class="text-neutral-500 text-sm">You have no upcoming assignments.</p>
                </div>
            <?php else: ?>
                <?php foreach ($assignments_upcoming as $assignment): ?>
                    <?php include 'app/views/assignments/all_assignment_item.php'; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="tab-panel-past-due" class="tab-panel">
            <?php if (empty($assignments_past_due)): ?>
                <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-neutral-300">
                    <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-3 text-green-500">
                        <i class="fas fa-thumbs-up text-3xl"></i>
                    </div>
                    <h3 class="text-neutral-900 font-semibold">All Clear!</h3>
                    <p class="text-neutral-500 text-sm">No overdue assignments.</p>
                </div>
            <?php else: ?>
                <?php foreach ($assignments_past_due as $assignment): ?>
                     <?php include 'app/views/assignments/all_assignment_item.php'; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="tab-panel-completed" class="tab-panel">
            <?php if (empty($assignments_completed)): ?>
                <div class="text-center py-12 text-neutral-400">No completed assignments yet.</div>
            <?php else: ?>
                <?php foreach ($assignments_completed as $assignment): ?>
                     <?php include 'app/views/assignments/all_assignment_item.php'; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    var storageKey = 'teacherAssignmentTab';

    var savedTab = sessionStorage.getItem(storageKey);
    if (savedTab) {
        $('.tab-pill').removeClass('active');
        $('.tab-panel').removeClass('active');
        $('.tab-pill[data-tab="' + savedTab + '"]').addClass('active');
        $('#tab-panel-' + savedTab).addClass('active');
    }

    $('.tab-pill').on('click', function(e) {
        e.preventDefault();
        var tab = $(this).data('tab');
        sessionStorage.setItem(storageKey, tab);
        
        $('.tab-pill').removeClass('active');
        $(this).addClass('active');
        
        $('.tab-panel').removeClass('active');
        $('#tab-panel-' + tab).addClass('active');
        
        $('html, body').animate({ scrollTop: 0 }, 300);
    });
});
</script>