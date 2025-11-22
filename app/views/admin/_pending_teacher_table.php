<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-neutral-200 text-left">
        <thead class="bg-amber-50">
            <tr>
                <th class="px-6 py-4 text-xs font-bold text-amber-800 uppercase tracking-wider">Teacher Profile</th>
                <th class="px-6 py-4 text-xs font-bold text-amber-800 uppercase tracking-wider">Email</th>
                <th class="px-6 py-4 text-center text-xs font-bold text-amber-800 uppercase tracking-wider">Date Registered</th>
                <th class="px-6 py-4 text-right text-xs font-bold text-amber-800 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-neutral-100">
            <?php if (empty($pending_teachers)): ?>
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-neutral-400">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-12 h-12 bg-neutral-50 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-check-circle text-2xl text-green-400"></i>
                            </div>
                            <p class="text-sm">No pending teacher approvals.</p>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($pending_teachers as $t): ?>
                    <tr class="hover:bg-amber-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-sm border border-amber-200">
                                    <?php echo strtoupper(substr($t['first_name'], 0, 1) . substr($t['last_name'], 0, 1)); ?>
                                </div>
                                <div class="font-bold text-neutral-900">
                                    <?php echo htmlspecialchars($t['first_name'] . ' ' . $t['last_name']); ?>
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200 uppercase">
                                        Pending
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-600">
                            <?php echo htmlspecialchars($t['email']); ?>
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-neutral-500">
                            <?php echo date('M d, Y', strtotime($t['created_at'])); ?>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <form action="<?php echo site_url('/admin/user/approve/' . $t['user_id']); ?>" method="POST" class="inline-block m-0">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm bg-green-50 border border-green-200 text-green-700 hover:bg-green-600 hover:text-white hover:border-green-600 p-2 rounded-lg shadow-sm transition-all flex items-center gap-2" title="Approve Teacher">
                                        <i class="fas fa-check"></i>
                                        <span class="text-xs font-bold">Approve</span>
                                    </button>
                                </form>

                                <form action="<?php echo site_url('/admin/user/delete/' . $t['user_id']); ?>" method="POST" class="inline-block m-0" onsubmit="return confirm('Reject and delete this account?');">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm bg-white border border-neutral-200 text-neutral-400 hover:text-red-600 hover:border-red-200 p-2 rounded-lg shadow-sm transition-colors" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>