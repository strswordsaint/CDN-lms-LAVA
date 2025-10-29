<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* Custom styles for approval/rejection buttons */
    .btn-approve {
        background-color: #16A34A; /* green-600 */
        color: white;
        padding: 0.3rem 0.75rem;
        border-radius: 0.375rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: background-color 0.2s;
    }
    .btn-approve:hover { background-color: #15803D; }

    .btn-reject {
        background-color: #DC2626; /* red-600 */
        color: white;
        padding: 0.3rem 0.75rem;
        border-radius: 0.375rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: background-color 0.2s;
    }
    .btn-reject:hover { background-color: #B91C1C; }

    /* Make forms inline */
    .action-form {
        display: inline-block;
        margin-left: 0.5rem;
    }
    .btnBack{
        
    }
</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?php echo site_url('/courses'); ?>" class="text-sm text-blue-600 hover:underline mb-4 inline-block">
        <i class="fas fa-arrow-left mr-1"></i> Back to Course List
    </a>

    <h1 class="text-2xl font-bold text-gray-800 mb-6"><?php echo $page_title ?? 'Manage Enrollments'; ?></h1>
    
    <!-- Display Flash Messages -->
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


    <!-- Pending Requests Section -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 mb-8">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h2 class="text-xl font-semibold text-gray-700">Pending Requests</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested On</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($pending_enrollments)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                No pending enrollment requests.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pending_enrollments as $enrollment): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($enrollment['first_name'] . ' ' . $enrollment['last_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?php echo htmlspecialchars($enrollment['email']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('M d, Y', strtotime($enrollment['enrolled_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <!-- Approve Form -->
                                    <form action="<?php echo site_url('/enrollments/approve/' . $enrollment['enrollment_id']); ?>" method="POST" class="action-form">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn-approve">
                                            <i class="fas fa-check mr-1"></i> Approve
                                        </button>
                                    </form>
                                    <!-- Reject Form -->
                                    <form action="<?php echo site_url('/enrollments/reject/' . $enrollment['enrollment_id']); ?>" method="POST" class="action-form" onsubmit="return confirm('Are you sure you want to reject this enrollment?');">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn-reject">
                                            <i class="fas fa-times mr-1"></i> Reject
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Approved Students Section -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h2 class="text-xl font-semibold text-gray-700">Approved Students</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled On</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($approved_enrollments)): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                No students have been approved for this course yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($approved_enrollments as $enrollment): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($enrollment['first_name'] . ' ' . $enrollment['last_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?php echo htmlspecialchars($enrollment['email']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('M d, Y', strtotime($enrollment['enrolled_at'])); ?>
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