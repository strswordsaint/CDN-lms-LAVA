<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-3xl">
    <a href="<?php echo site_url('/admin/users'); ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Manage Users
    </a>

    <h1 class="text-2xl font-bold text-neutral-900 mb-6"><?php echo $page_title ?? 'Edit User'; ?></h1>

    <div class="card p-6">
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

        <?php if (empty($user)): ?>
            <div class="notice notice-error" role="alert" style="display:block;">
                User data could not be found.
            </div>
        <?php else: ?>
            <form action="<?php echo site_url('/admin/user/update/' . $user['user_id']); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                      <label for="first_name" class="block text-sm font-medium text-neutral-700 mb-1">First Name <span class="text-error-500">*</span></label>
                      <input type="text" id="first_name" name="first_name" required class="form-input"
                             value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                  </div>
                  <div>
                      <label for="last_name" class="block text-sm font-medium text-neutral-700 mb-1">Last Name <span class="text-error-500">*</span></label>
                      <input type="text" id="last_name" name="last_name" required class="form-input"
                             value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                  </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-neutral-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" readonly disabled class="form-input bg-neutral-100"
                           value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                    <small class="text-xs text-neutral-500">Email cannot be changed.</small>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-neutral-700 mb-1">Role <span class="text-error-500">*</span></label>
                    <select id="role" name="role" required class="form-select" <?php echo $is_self ? 'disabled' : ''; ?>>
                        <option value="student" <?php echo ($user['role'] == 'student') ? 'selected' : ''; ?>>Student</option>
                        <option value="teacher" <?php echo ($user['role'] == 'teacher') ? 'selected' : ''; ?>>Teacher</option>
                        <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                    <?php if ($is_self): ?>
                         <small class="text-xs text-neutral-500">You cannot change your own role.</small>
                    <?php endif; ?>
                </div>
                
                <div class="pt-4 text-right space-x-2">
                    <a href="<?php echo site_url('/admin/users'); ?>" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>