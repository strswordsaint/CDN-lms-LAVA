<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* === Modern Variables & Reset === */
    :root {
        --bg-body: #f1f5f9; 
        --primary-soft: #eff6ff;
        --primary-border: #bfdbfe;
        --primary-text: #1d4ed8;
    }
    
    body {
        background-color: var(--bg-body);
        font-family: 'Inter', sans-serif;
    }

    /* === PAGE BANNER === */
    .page-banner {
        background: white;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #e2e8f0;
        padding: 2rem 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        margin-bottom: 2rem;
    }
    
    .banner-decoration {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.6;
        z-index: 0;
    }
    .decoration-1 { top: -60%; left: -10%; width: 500px; height: 500px; background: #fef3c7; }
    .decoration-2 { bottom: -60%; right: -5%; width: 400px; height: 400px; background: #fff7ed; }

    .banner-content {
        position: relative;
        z-index: 10;
    }

    /* === STICKY NAVIGATION === */
    .sticky-tabs-wrapper {
        position: sticky;
        top: 0;
        z-index: 40;
        background: rgba(241, 245, 249, 0.95);
        backdrop-filter: blur(8px);
        padding: 1rem 0;
        border-bottom: 1px solid transparent;
    }

    .nav-pills {
        display: flex;
        gap: 0.75rem;
        overflow-x: auto;
        padding: 0.25rem;
    }

    .tab-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #64748b;
        background: white;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .tab-pill:hover {
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }

    .tab-pill.active {
        background-color: #d97706; /* Amber for Activities */
        color: white;
        border-color: #d97706;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
    }

    .tab-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #ef4444;
        border-radius: 50%;
        margin-left: 8px;
        box-shadow: 0 0 0 2px white;
    }

    /* === HIGH VISIBILITY CARD STYLE === */
    .hunt-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #cbd5e1; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .hunt-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
        background-color: #f59e0b; /* Amber Accent */
    }

    .hunt-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.1); 
        border-color: #f59e0b;
    }

    .hunt-icon-box {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        background-color: #fffbeb; 
        color: #d97706;
        border: 1px solid #fcd34d;
    }

    .tab-panel { display: none; animation: fadeIn 0.3s ease-out; }
    .tab-panel.active { display: block; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="page-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-neutral-900 tracking-tight mb-1">
                    <?php echo $page_title ?? 'My Activities'; ?>
                </h1>
                <p class="text-neutral-500 font-medium">Manage your quizzes and class activities.</p>
            </div>
            <div class="bg-amber-50 text-amber-700 px-5 py-2.5 rounded-xl border border-amber-200 font-bold shadow-sm flex items-center">
                <i class="fas fa-gamepad mr-2.5 text-lg"></i> 
                <span>Activity Hub</span>
            </div>
        </div>
    </div>
</div>

<div class="sticky-tabs-wrapper">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="nav-pills">
            <button class="tab-pill active" data-tab="upcoming">
                Upcoming
                <?php if (count($activities_upcoming) > 0): ?>
                    <span class="tab-dot"></span>
                <?php endif; ?>
            </button>
            <button class="tab-pill" data-tab="past-due">
                Past Due
                <?php if (count($activities_past_due) > 0): ?>
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
            <?php if (empty($activities_upcoming)): ?>
                <div class="bg-white rounded-2xl border-2 border-dashed border-neutral-300 p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-neutral-50 text-neutral-400 mb-4">
                        <i class="fas fa-clipboard-check text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-neutral-900">All Caught Up</h3>
                    <p class="text-neutral-500">No upcoming activities scheduled.</p>
                </div>
            <?php else: ?>
                <?php foreach ($activities_upcoming as $assignment): ?>
                    <?php include 'app/views/student/all_activity_item.php'; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="tab-panel-past-due" class="tab-panel">
            <?php if (empty($activities_past_due)): ?>
                <div class="bg-white rounded-2xl border-2 border-dashed border-neutral-300 p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-50 text-green-500 mb-4">
                        <i class="fas fa-check text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-neutral-900">Great Job!</h3>
                    <p class="text-neutral-500">No overdue activities.</p>
                </div>
            <?php else: ?>
                <?php foreach ($activities_past_due as $assignment): ?>
                     <?php include 'app/views/student/all_activity_item.php'; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="tab-panel-completed" class="tab-panel">
            <?php if (empty($activities_completed)): ?>
                <div class="text-center py-12 text-neutral-400 font-medium">No completed activities yet.</div>
            <?php else: ?>
                <?php foreach ($activities_completed as $assignment): ?>
                     <?php include 'app/views/student/all_activity_item.php'; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    var storageKey = 'studentActivityTab';

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