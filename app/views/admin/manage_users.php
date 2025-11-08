<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* Style for small icon buttons in a table */
    .btn-icon {
        width: 2rem; /* 32px */
        height: 2rem; /* 32px */
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .delete-form {
        display: inline-block;
    }

    /* Styles for the active and inactive tabs */
    .tab-link {
        padding: 0.5rem 1rem;
        font-weight: 600;
        color: #64748b; /* neutral-500 */
        border-bottom: 2px solid transparent;
        cursor: pointer;
        /* --- ADDED --- */
        display: inline-flex;
        align-items: center;
    }
    .tab-link.active {
        color: #1d4ed8; /* primary-700 */
        border-bottom-color: #1d4ed8;
    }
    .tab-panel {
        display: none; /* Hide all panels by default */
    }
    .tab-panel.active {
        display: block; /* Show only the active panel */
    }
    /* --- ADDED --- */
    .tab-dot {
        display: inline-block;
        width: 0.5rem; /* 8px */
        height: 0.5rem; /* 8px */
        background-color: #ef4444; /* error-500 */
        border-radius: 9999px;
        margin-left: 0.375rem; /* 6px */
    }
</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?php echo site_url('/dashboard'); ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
    </a>

    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-neutral-900"><?php echo $page_title ?? 'Manage Users'; ?></h1>
        <a href="<?php echo site_url('/admin/user/create'); ?>" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Create New User
        </a>
    </div>
    <?php if (!empty($success_message)): ?>
        <div class="notice notice-success mb-4" role="alert" style="display:block;">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
     <?php if (!empty($error_message)): ?>
        <div class="notice notice-error mb-4" role="alert" style="display:block;">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>
    <div class="border-b border-neutral-300 mb-6">
        <nav class="flex -mb-px">
            <a class="tab-link" data-tab="pending-teachers">
                Pending Teachers (<?php echo count($pending_teachers); ?>)
                <?php if(count($pending_teachers) > 0): ?>
                    <span class="tab-dot" style="background-color: #f59e0b;"></span>
                <?php endif; ?>
            </a>
            <a class="tab-link active" data-tab="students">Students (<?php echo count($students); ?>)</a>
            <a class="tab-link" data-tab="teachers">Teachers (<?php echo count($teachers); ?>)</a>
            <a class="tab-link" data-tab="admins">Admins (<?php echo count($admins); ?>)</a>
        </nav>
    </div>

    <div class="card overflow-hidden">
        <div id="tab-panel-pending-teachers" class="tab-panel">
            <?php 
                // We will create this new file next
                include 'app/views/admin/_pending_teacher_table.php'; 
            ?>
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

<script>
$(document).ready(function() {
    var storageKey = 'adminUsersActiveTab';

    // 1. On page load, check for a saved tab
    var savedTab = sessionStorage.getItem(storageKey);
    
    // --- MODIFIED: Check if pending teachers exist ---
    var hasPending = <?php echo count($pending_teachers) > 0 ? 'true' : 'false'; ?>;
    
    // Default to pending tab if it has items and no tab is saved
    if (!savedTab && hasPending) {
        savedTab = 'pending-teachers';
    } else if (!savedTab) {
        savedTab = 'students'; // Default fallback
    }
    // --- END MODIFICATION ---

    if (savedTab) {
        // Remove default active state
        $('.tab-link').removeClass('active');
        $('.tab-panel').removeClass('active');
        
        // Apply the saved active state
        $('.tab-link[data-tab="' + savedTab + '"]').addClass('active');
        $('#tab-panel-' + savedTab).addClass('active');
    }

    // 2. On tab click, save the new tab
    $('.tab-link').on('click', function(e) {
        e.preventDefault();
        
        var tab = $(this).data('tab');
        
        // Save the clicked tab to session storage
        sessionStorage.setItem(storageKey, tab);
        
        // Update tab link active state
        $('.tab-link').removeClass('active');
        $(this).addClass('active');
        
        // Show/hide tab panels
        $('.tab-panel').removeClass('active');
        $('#tab-panel-' + tab).addClass('active');
    });
});
</script>

<?php include 'app/views/layouts/footer.php'; ?>