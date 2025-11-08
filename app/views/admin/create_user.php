<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* Adding these utility classes here to ensure the form inputs
      get the correct border and focus styles, just like your auth pages.
    */
    .form-input-themed {
        @apply block w-full px-3 py-2 border border-neutral-300 rounded-md shadow-sm;
        @apply placeholder-neutral-400 text-neutral-900;
        @apply focus:outline-none focus:ring-primary-500 focus:border-primary-500;
    }
    .form-select-themed {
        @apply block w-full px-3 py-2 border border-neutral-300 rounded-md shadow-sm bg-white;
        @apply text-neutral-900;
        @apply focus:outline-none focus:ring-primary-500 focus:border-primary-500;
    }
    .btn-primary-themed {
        @apply inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white;
        @apply bg-primary-700 hover:bg-primary-800;
        @apply focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500;
    }
    .btn-secondary-themed {
        @apply inline-flex justify-center py-2 px-4 border border-neutral-300 shadow-sm text-sm font-medium rounded-md text-neutral-700 bg-white;
        @apply hover:bg-neutral-50;
        @apply focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500;
    }
</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-3xl">
    <a href="<?php echo site_url('/admin/users'); ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Manage Users
    </a>

    <h1 class="text-2xl font-bold text-neutral-900 mb-6"><?php echo $page_title ?? 'Create New User'; ?></h1>

    <div class="bg-white rounded-lg shadow-md border border-neutral-200 p-6">
        <?php $validation_errors = lava_instance()->session->flashdata('validation_errors'); ?>
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

        <?php $error_message = lava_instance()->session->flashdata('error'); ?>
        <?php if (!empty($error_message)): ?>
            <div class="notice notice-error mb-4" role="alert" style="display:block;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <form action="<?php echo site_url('/admin/user/store'); ?>" method="POST" id="create-user-form" class="space-y-4">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                  <label for="first_name" class="block text-sm font-medium text-neutral-700 mb-1">First Name <span class="text-error-500">*</span></label>
                  <input type="text" id="first_name" name="first_name" required
                         class="form-input-themed">
              </div>
              <div>
                  <label for="last_name" class="block text-sm font-medium text-neutral-700 mb-1">Last Name <span class="text-error-500">*</span></label>
                  <input type="text" id="last_name" name="last_name" required
                         class="form-input-themed">
              </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-neutral-700 mb-1">Email <span class="text-error-500">*</span></label>
                <input type="email" id="email" name="email" required
                       class="form-input-themed">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-neutral-700 mb-1">Password <span class="text-error-500">*</span></label>
                <input type="password" id="password" name="password" required minlength="8"
                       class="form-input-themed">
                <small class="text-xs text-neutral-500">Min 8 characters, with uppercase, lowercase, number, and symbol.</small>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-neutral-700 mb-1">Role <span class="text-error-500">*</span></label>
                <select id="role" name="role" required
                        class="form-select-themed">
                    <option value="" disabled selected>-- Select a role --</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            
            <div class="pt-4 text-right space-x-2">
                <a href="<?php echo site_url('/admin/users'); ?>" class="btn-secondary-themed">
                    Cancel
                </a>
                <button type="submit" class="btn-primary-themed">
                    <i class="fas fa-plus mr-2"></i> Create User Account
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>