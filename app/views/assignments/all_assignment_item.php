<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>

<div class="hunt-card group relative overflow-hidden group-hover:border-primary-300">
    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary-600"></div>

    <div class="hunt-icon-box bg-primary-50 text-primary-600 border border-primary-200">
        <i class="fas fa-tasks"></i>
    </div>

    <div class="flex-1 min-w-0">
        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-3">
            
            <div>
                <h3 class="text-lg font-bold text-neutral-900 leading-tight group-hover:text-primary-700 transition-colors mb-1.5">
                    <a href="<?php echo site_url('/assignments/' . $assignment['assignment_id'] . '/submissions'); ?>" class="hover:underline">
                        <?php echo htmlspecialchars($assignment['title']); ?>
                    </a>
                </h3>
                
                <div class="flex flex-wrap items-center gap-3 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-neutral-100 text-neutral-600 border border-neutral-200">
                        <i class="fas fa-book-open mr-1.5 text-neutral-400"></i>
                        <?php echo htmlspecialchars($assignment['course_title']); ?>
                    </span>
                    
                    <span class="flex items-center text-neutral-500 text-xs font-medium">
                        <i class="far fa-clock mr-1.5"></i> 
                        Due: <?php echo date('M d, Y @ g:i A', strtotime($assignment['due_date'])); ?>
                    </span>
                </div>
            </div>

            <div class="flex items-center self-start md:self-center mt-2 md:mt-0">
                <a href="<?php echo site_url('/assignments/' . $assignment['assignment_id'] . '/submissions'); ?>" class="btn btn-sm bg-white border border-neutral-200 text-neutral-600 font-semibold hover:text-primary-700 hover:border-primary-300 shadow-sm transition-all rounded-lg px-4 py-2">
                    View Submissions
                </a>
            </div>
        </div>
    </div>
</div>