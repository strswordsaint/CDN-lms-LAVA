<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8 max-w-xl">
    <div class="card p-6 border-l-4 border-red-500">
        <h1 class="text-xl font-bold text-red-600 mb-2">
            <i class="fas fa-ban mr-2"></i>Suspend User
        </h1>
        <p class="text-neutral-600 mb-6">
            You are about to suspend <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong>.
            They will no longer be able to log in.
        </p>

        <form action="<?php echo site_url('/admin/user/process_suspension/' . $user['user_id']); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="mb-6">
                <label class="block text-sm font-medium text-neutral-700 mb-1">Reason for Suspension <span class="text-red-500">*</span></label>
                <textarea name="reason" rows="4" required 
                          class="w-full border border-neutral-300 rounded-md p-3 focus:ring-red-500 focus:border-red-500"
                          placeholder="e.g., Violation of school policy..."></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="<?php echo site_url('/admin/users'); ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-danger">Confirm Suspension</button>
            </div>
        </form>
    </div>
</div>
<?php include 'app/views/layouts/footer.php'; ?>