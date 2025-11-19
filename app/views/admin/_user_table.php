<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-neutral-200">
        <thead class="bg-neutral-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Role</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase">Status</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-neutral-200">
            <?php if (empty($users)): ?>
                <tr><td colspan="4" class="px-6 py-4 text-center text-neutral-500">No users found.</td></tr>
            <?php else: ?>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-neutral-900"><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></div>
                            <div class="text-sm text-neutral-500"><?php echo htmlspecialchars($u['email']); ?></div>
                            
                            <?php if($u['status'] == 'suspended' && !empty($u['suspension_reason'])): ?>
                                <div class="mt-1 text-xs text-red-600 bg-red-50 p-1 rounded border border-red-100">
                                    <strong>Note:</strong> <?php echo htmlspecialchars($u['suspension_reason']); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            <span class="capitalize"><?php echo htmlspecialchars($u['role']); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php if ($u['status'] == 'approved'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            <?php elseif ($u['status'] == 'suspended'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Suspended</span>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800"><?php echo ucfirst($u['status']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                            <a href="<?php echo site_url('/admin/user/edit/' . $u['user_id']); ?>" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <?php if ($u['role'] !== 'admin'): // Don't let admin suspend themselves ?>
                                
                                <?php if ($u['status'] == 'suspended'): ?>
                                    <a href="<?php echo site_url('/admin/user/reactivate/' . $u['user_id']); ?>" 
                                       class="text-green-600 hover:text-green-900" title="Reactivate User"
                                       onclick="return confirm('Are you sure you want to reactivate this user?');">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo site_url('/admin/user/suspend/' . $u['user_id']); ?>" 
                                       class="text-red-600 hover:text-red-900" title="Suspend User">
                                        <i class="fas fa-ban"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <form action="<?php echo site_url('/admin/user/delete/' . $u['user_id']); ?>" method="POST" class="inline-block" onsubmit="return confirm('Are you sure? This cannot be undone.');">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-neutral-400 hover:text-red-600 transition-colors" title="Delete">
                                        <i class="fas fa-trash"></i>
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