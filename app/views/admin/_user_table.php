<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-neutral-200 text-left">
        <thead class="bg-neutral-50">
            <tr>
                <th class="px-6 py-4 text-xs font-bold text-neutral-500 uppercase tracking-wider">User Profile</th>
                <th class="px-6 py-4 text-xs font-bold text-neutral-500 uppercase tracking-wider">Role</th>
                <th class="px-6 py-4 text-center text-xs font-bold text-neutral-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-right text-xs font-bold text-neutral-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-neutral-100">
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-neutral-400">
                        <div class="flex flex-col items-center justify-center">
                            <i class="far fa-user text-3xl mb-2 opacity-50"></i>
                            <p class="text-sm">No users found in this category.</p>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $u): ?>
                    <tr class="hover:bg-neutral-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center font-bold text-sm border border-primary-100">
                                    <?php echo strtoupper(substr($u['first_name'], 0, 1) . substr($u['last_name'], 0, 1)); ?>
                                </div>
                                <div>
                                    <div class="font-bold text-neutral-900"><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></div>
                                    <div class="text-xs text-neutral-500"><?php echo htmlspecialchars($u['email']); ?></div>
                                    
                                    <?php if($u['status'] == 'suspended' && !empty($u['suspension_reason'])): ?>
                                        <div class="mt-1.5 inline-flex items-center px-2 py-0.5 rounded bg-red-50 border border-red-100 text-red-600 text-[10px]">
                                            <i class="fas fa-info-circle mr-1"></i> <?php echo htmlspecialchars($u['suspension_reason']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-neutral-100 text-neutral-600 capitalize border border-neutral-200">
                                <?php echo htmlspecialchars($u['role']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php if ($u['status'] == 'approved'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span> Active
                                </span>
                            <?php elseif ($u['status'] == 'suspended'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span> Suspended
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5"></span> <?php echo ucfirst($u['status']); ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                <a href="<?php echo site_url('/admin/user/edit/' . $u['user_id']); ?>" class="btn btn-sm bg-white border border-neutral-200 text-neutral-500 hover:text-indigo-600 hover:border-indigo-200 p-2 rounded-lg shadow-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <?php if ($u['role'] !== 'admin'): ?>
                                    <?php if ($u['status'] == 'suspended'): ?>
                                        <a href="<?php echo site_url('/admin/user/reactivate/' . $u['user_id']); ?>" 
                                           class="btn btn-sm bg-white border border-neutral-200 text-neutral-500 hover:text-green-600 hover:border-green-200 p-2 rounded-lg shadow-sm" 
                                           title="Reactivate" onclick="return confirm('Reactivate user?');">
                                            <i class="fas fa-undo"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo site_url('/admin/user/suspend/' . $u['user_id']); ?>" 
                                           class="btn btn-sm bg-white border border-neutral-200 text-neutral-500 hover:text-amber-600 hover:border-amber-200 p-2 rounded-lg shadow-sm" 
                                           title="Suspend">
                                            <i class="fas fa-ban"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <form action="<?php echo site_url('/admin/user/delete/' . $u['user_id']); ?>" method="POST" class="inline-block m-0" onsubmit="return confirm('Delete user permanently?');">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm bg-white border border-neutral-200 text-neutral-500 hover:text-red-600 hover:border-red-200 p-2 rounded-lg shadow-sm" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>