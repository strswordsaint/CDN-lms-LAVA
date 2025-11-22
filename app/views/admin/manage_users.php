<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* === Modern Variables === */
    :root { --bg-body: #eef2f6; --primary-soft: #eff6ff; --primary-border: #bfdbfe; --primary-text: #1d4ed8; }
    body { background-color: var(--bg-body); font-family: 'Inter', sans-serif; }

    /* === DESIGNED BANNER === */
    .page-banner {
        background: white; position: relative; overflow: hidden; border-bottom: 1px solid #e2e8f0; padding: 2.5rem 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02); margin-bottom: 2rem;
    }
    .banner-decoration { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.6; z-index: 0; }
    .decoration-1 { top: -60%; left: -10%; width: 500px; height: 500px; background: #fee2e2; }
    .decoration-2 { bottom: -60%; right: -5%; width: 400px; height: 400px; background: #fecaca; }
    .banner-content { position: relative; z-index: 10; }

    /* === STICKY NAVIGATION === */
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
        border-radius: 9999px; padding: 0 0.4rem; height: 1.25rem; margin-left: 0.5rem;
        border: 2px solid white;
    }

    /* === TABLE STYLES === */
    .data-table-container {
        background: white; border-radius: 1rem; border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden;
    }
    
    .tab-panel { display: none; animation: slideUp 0.3s ease-out; }
    .tab-panel.active { display: block; }
    
    @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="page-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <a href="<?php echo site_url('/dashboard'); ?>" class="inline-flex items-center text-sm font-semibold text-neutral-500 hover:text-neutral-800 mb-3 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                </a>
                <h1 class="text-3xl font-extrabold text-neutral-900 tracking-tight mb-1">Manage Users</h1>
                <p class="text-neutral-500 text-sm">Administer student, teacher, and admin accounts.</p>
            </div>
            <div>
                <a href="<?php echo site_url('/admin/user/create'); ?>" class="btn btn-primary rounded-xl shadow-md hover:shadow-lg px-6 py-3 font-bold flex items-center transition-all">
                    <i class="fas fa-user-plus mr-2"></i> Create New User
                </a>
            </div>
        </div>
    </div>
</div>

<div class="sticky-tabs-wrapper">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="nav-pills">
            <button class="tab-pill" data-tab="pending-teachers">
                Pending Teachers
                <?php if(count($pending_teachers) > 0): ?>
                    <span class="notification-badge"><?php echo count($pending_teachers); ?></span>
                <?php endif; ?>
            </button>
            <button class="tab-pill active" data-tab="students">Students (<?php echo count($students); ?>)</button>
            <button class="tab-pill" data-tab="teachers">Teachers (<?php echo count($teachers); ?>)</button>
            <button class="tab-pill" data-tab="admins">Admins (<?php echo count($admins); ?>)</button>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    
    <?php if (!empty($success_message)): ?>
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-3 text-lg"></i> <span class="font-medium"><?php echo htmlspecialchars($success_message); ?></span>
        </div>
    <?php endif; ?>
    <?php if (!empty($error_message)): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center shadow-sm">
            <i class="fas fa-exclamation-circle mr-3 text-lg"></i> <span class="font-medium"><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    <?php endif; ?>

    <div class="data-table-container">
        <div id="tab-panel-pending-teachers" class="tab-panel">
            <?php include 'app/views/admin/_pending_teacher_table.php'; ?>
        </div>
        <div id="tab-panel-students" class="tab-panel active">
            <?php $users = $students; include 'app/views/admin/_user_table.php'; ?>
        </div>
        <div id="tab-panel-teachers" class="tab-panel">
            <?php $users = $teachers; include 'app/views/admin/_user_table.php'; ?>
        </div>
        <div id="tab-panel-admins" class="tab-panel">
            <?php $users = $admins; include 'app/views/admin/_user_table.php'; ?>
        </div>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    var storageKey = 'adminUsersActiveTab';
    var savedTab = sessionStorage.getItem(storageKey);
    var hasPending = <?php echo count($pending_teachers) > 0 ? 'true' : 'false'; ?>;

    if (!savedTab && hasPending) { savedTab = 'pending-teachers'; }
    else if (!savedTab) { savedTab = 'students'; }

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
});
</script>