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
</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Navigation -->
     <a href="<?php echo site_url('/dashboard'); ?>" class="text-sm text-primary-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
    </a>

    <h1 class="text-2xl font-bold text-neutral-900 mb-6"><?php echo $page_title ?? 'Manage Users'; ?></h1>

    <!-- Flash Messages -->
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
    <!-- End Flash Messages -->

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Registered On
                        </th>
                         <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    <?php if (empty($all_users)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-neutral-500">
                                No users found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($all_users as $user): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                    <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php echo $user['role'] == 'admin' ? 'bg-primary-100 text-primary-800' : 
                                             ($user['role'] == 'teacher' ? 'bg-success-100 text-success-800' : 
                                             'bg-neutral-100 text-neutral-800'); ?>">
                                        <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                    <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                    <a href="<?php echo site_url('/admin/user/edit/' . $user['user_id']); ?>" title="Edit User" class="btn btn-icon btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Prevent admin from deleting themselves -->
                                    <?php if ($user['user_id'] != lava_instance()->session->userdata('user_id')): ?>
                                        <form action="<?php echo site_url('/admin/user/delete/' . $user['user_id']); ?>" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                            <?php echo csrf_field(); ?> 
                                            <button type="submit" title="Delete User" class="btn btn-icon btn-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>