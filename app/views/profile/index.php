<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* Styles for the active and inactive tabs */
    .tab-link {
        padding: 0.5rem 1rem;
        font-weight: 600;
        color: #64748b; /* neutral-500 */
        border-bottom: 2px solid transparent;
        cursor: pointer;
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
</style>

<div class="max-w-6xl mx-auto">

    <h1 class="text-3xl font-bold text-neutral-900 mb-6">My Profile</h1>

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
    <?php if (!empty($validation_errors)): ?>
        <div class="notice notice-error mb-4" role="alert" style="display:block;">
            <strong class="font-bold">Please fix the following errors:</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                <?php foreach ($validation_errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="card p-6 text-center">
                <div class="flex-shrink-0 flex items-center justify-center w-24 h-24 bg-primary-700 rounded-full text-white font-bold text-4xl mx-auto">
                    <?php
                        $first_initial = !empty($first_name) ? $first_name[0] : 'U';
                        $last_initial = !empty($last_name) ? $last_name[0] : '';
                        echo htmlspecialchars(strtoupper($first_initial . $last_initial));
                    ?>
                </div>
                <h2 class="text-xl font-semibold text-neutral-900 mt-4"><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></h2>
                <p class="text-sm text-neutral-500"><?php echo htmlspecialchars($email); ?></p>
                
                <a href="<?php echo site_url('auth/logout'); ?>" 
                   id="sign-out-link"
                   class="btn btn-danger mt-6 w-full">
                   <i class="fas fa-sign-out-alt mr-2"></i>
                   Sign Out
                </a>
                </div>
        </div>

        <div class="lg:col-span-2">
            <div class="card">
                <div class="border-b border-neutral-200">
                    <nav class="flex -mb-px px-6">
                        <a class="tab-link active" data-tab="details">Edit Details</a>
                        <a class="tab-link" data-tab="password">Change Password</a>
                    </nav>
                </div>
                
                <div class="p-6">
                    
                    <div id="tab-panel-details" class="tab-panel active">
                        <h3 class="text-lg font-medium leading-6 text-neutral-900 mb-4">Account Information</h3>
                        
                        <form action="<?php echo site_url('/profile/update_details'); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            
                            <div class="bg-neutral-50 rounded-lg p-6 border border-neutral-200 space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label for="profile_first_name" class="block text-sm font-medium text-neutral-700 mb-1">First Name</label>
                                        <input type="text" id="profile_first_name" name="first_name" required class="form-input"
                                               value="<?php echo htmlspecialchars($first_name ?? ''); ?>">
                                    </div>
                                    <div>
                                        <label for="profile_last_name" class="block text-sm font-medium text-neutral-700 mb-1">Last Name</label>
                                        <input type="text" id="profile_last_name" name="last_name" required class="form-input"
                                               value="<?php echo htmlspecialchars($last_name ?? ''); ?>">
                                    </div>
                                </div>
                                <div>
                                    <label for="profile_email" class="block text-sm font-medium text-neutral-700 mb-1">Email Address</label>
                                    <input type="email" id="profile_email" name="email" readonly disabled
                                           class="form-input bg-neutral-200 border-neutral-300 cursor-not-allowed"
                                           value="<?php echo htmlspecialchars($email ?? ''); ?>">
                                    <p class="mt-1 text-xs text-neutral-500">Email cannot be changed.</p>
                                </div>
                            </div>
                            
                            <div class="text-right mt-6">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                    
                    <div id="tab-panel-password" class="tab-panel">
                        <h3 class="text-lg font-medium leading-6 text-neutral-900 mb-4">Security</h3>

                        <form action="<?php echo site_url('/profile/update_password'); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            
                            <div class="bg-neutral-50 rounded-lg p-6 border border-neutral-200 space-y-5">
                                <div>
                                    <label for="profile_current_password" class="block text-sm font-medium text-neutral-700 mb-1">Current Password</label>
                                    <input type="password" id="profile_current_password" name="current_password" required class="form-input">
                                </div>

                                <div class="border-t border-neutral-200"></div>

                                <div>
                                    <label for="profile_new_password" class="block text-sm font-medium text-neutral-700 mb-1">New Password</label>
                                    <input type="password" id="profile_new_password" name="new_password" required class="form-input">
                                    <p class="mt-1 text-xs text-neutral-500">Min 8 characters, with uppercase, lowercase, number, and symbol.</p>
                                </div>
                                <div>
                                    <label for="profile_confirm_password" class="block text-sm font-medium text-neutral-700 mb-1">Confirm New Password</label>
                                    <input type="password" id="profile_confirm_password" name="confirm_password" required class="form-input">
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="show-passwords" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded cursor-pointer">
                                    <label for="show-passwords" class="ml-2 block text-sm text-gray-900 cursor-pointer">
                                        Show Passwords
                                    </label>
                                </div>
                            </div>
                            
                            <div class="text-right mt-6">
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // 1. Tab switching logic
    $('.tab-link').on('click', function() {
        var tab = $(this).data('tab');
        
        // Set active tab
        $('.tab-link').removeClass('active');
        $(this).addClass('active');
        
        // Show/hide panels
        $('.tab-panel').removeClass('active');
        $('#tab-panel-' + tab).addClass('active');
    });

    // 2. Auto-open the correct tab if there was a validation error
    <?php
        $open_tab = lava_instance()->session->flashdata('open_tab') ?? 'details';
    ?>
    <?php if ($open_tab == 'password'): ?>
        // Click the password tab to activate it
        $('.tab-link[data-tab="password"]').click();
    <?php endif; ?>

    // 3. Trigger Custom Modal for Sign Out
    $('#sign-out-link').on('click', function(e) {
        e.preventDefault(); 
        var signOutUrl = $(this).attr('href');
        
        showConfirmationModal({
            title: 'Sign Out',
            body: '<p class="text-sm text-neutral-600">Are you sure you want to sign out of your account?</p>',
            confirmText: 'Sign Out',
            confirmClass: 'btn-danger',
            onConfirm: function() {
                window.location.href = signOutUrl;
            }
        });
    });

    // 4. Toggle Password Visibility
    $('#show-passwords').on('change', function() {
        const type = $(this).is(':checked') ? 'text' : 'password';
        $('#profile_current_password, #profile_new_password, #profile_confirm_password').attr('type', type);
    });
});
</script>

<?php include 'app/views/layouts/footer.php'; ?>    