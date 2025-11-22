<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* === Modern Variables & Reset === */
    :root {
        --bg-body: #eef2f6; 
        --primary-soft: #eff6ff;
        --primary-border: #bfdbfe;
        --primary-text: #1d4ed8;
    }
    
    body {
        background-color: var(--bg-body);
        font-family: 'Inter', sans-serif;
    }

    /* === PAGE BANNER === */
    .page-banner {
        background: white;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #e2e8f0;
        padding: 2.5rem 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        margin-bottom: 2rem;
    }
    
    .banner-decoration {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.6;
        z-index: 0;
    }
    .decoration-1 { top: -60%; left: -10%; width: 500px; height: 500px; background: #dbeafe; }
    .decoration-2 { bottom: -60%; right: -5%; width: 400px; height: 400px; background: #e0e7ff; }

    .banner-content {
        position: relative;
        z-index: 10;
    }

    /* === HUNTJOBS CARD STYLE === */
    .hunt-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid #cbd5e1; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .hunt-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
        background-color: #3b82f6; /* Primary Blue Accent */
    }

    .hunt-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.15); 
        border-color: #3b82f6;
    }

    .hunt-icon-box {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        background-color: #eff6ff; 
        color: #2563eb;
        border: 1px solid #bfdbfe;
        margin-bottom: 0.5rem;
    }

    /* === COPY BUTTON === */
    .btn-copy {
        background-color: #f1f5f9; 
        color: #64748b; 
        border: 1px solid #e2e8f0;
        padding: 0.25rem 0.5rem; 
        border-radius: 0.375rem; 
        font-size: 0.75rem;
        cursor: pointer; 
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .btn-copy:hover { background-color: #e2e8f0; color: #1e293b; }
    .btn-copy.copied {
        background-color: #dcfce7; color: #15803d; border-color: #a3e6b6;
    }

    /* === GRID LAYOUT === */
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
    }
    @media (min-width: 768px) {
        .courses-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (min-width: 1024px) {
        .courses-grid { grid-template-columns: repeat(3, 1fr); }
    }
</style>

<div class="page-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-neutral-900 tracking-tight mb-2">
                    <?php echo $page_title ?? 'My Courses'; ?>
                </h1>
                <p class="text-neutral-500 text-sm">Manage your classes, content, and students.</p>
            </div>
            <div>
                <a href="<?php echo site_url('/courses/create'); ?>" class="btn btn-primary rounded-xl shadow-md hover:shadow-lg px-6 py-3 font-bold flex items-center transition-all">
                    <i class="fas fa-plus-circle mr-2"></i> Create New Course
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    
    <?php if (!empty(lava_instance()->session->flashdata('success'))): ?>
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> <?php echo htmlspecialchars(lava_instance()->session->flashdata('success')); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty(lava_instance()->session->flashdata('error'))): ?>
        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 flex items-center shadow-sm">
            <i class="fas fa-exclamation-circle mr-2"></i> <?php echo htmlspecialchars(lava_instance()->session->flashdata('error')); ?>
        </div>
    <?php endif; ?>

    <div class="courses-grid">
        <?php if (empty($courses)): ?>
            <div class="col-span-full text-center py-16 bg-white rounded-2xl border-2 border-dashed border-neutral-300">
                <div class="w-16 h-16 bg-neutral-50 rounded-full flex items-center justify-center mx-auto mb-4 text-neutral-400">
                    <i class="fas fa-folder-open text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-neutral-900">No Courses Yet</h3>
                <p class="text-neutral-500 mt-1 mb-4">Get started by creating your first class.</p>
                <a href="<?php echo site_url('/courses/create'); ?>" class="text-primary-600 font-semibold hover:underline">Create Course &rarr;</a>
            </div>
        <?php else: ?>
            <?php foreach ($courses as $course): ?>
                <div class="hunt-card group">
                    <div class="flex justify-between items-start">
                        <div class="hunt-icon-box">
                            <i class="fas fa-book text-2xl"></i>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <form action="<?php echo site_url('/courses/delete/' . $course['course_id']); ?>" method="POST" onsubmit="return confirm('Delete this course? This cannot be undone.');" class="inline-block m-0">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-neutral-400 hover:text-red-600 transition-colors p-1" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-neutral-900 group-hover:text-primary-700 transition-colors mb-2">
                            <a href="<?php echo site_url('/courses/show/' . $course['course_id']); ?>">
                                <?php echo htmlspecialchars($course['title']); ?>
                            </a>
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-neutral-500 uppercase tracking-wide">Code:</span>
                            <span class="font-mono text-sm font-medium text-neutral-800 bg-neutral-100 px-2 py-0.5 rounded border border-neutral-200" id="code-<?php echo $course['course_id']; ?>">
                                <?php echo htmlspecialchars($course['enrollment_code']); ?>
                            </span>
                            <button class="btn-copy copy-btn" data-clipboard-target="#code-<?php echo $course['course_id']; ?>" title="Copy Code">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mt-auto pt-4 border-t border-neutral-100 flex justify-between items-center text-sm text-neutral-500">
                        <div class="flex items-center gap-4">
                            <span title="Enrolled Students" class="flex items-center">
                                <i class="fas fa-users mr-1.5 text-neutral-400"></i> <?php echo $course['student_count'] ?? 0; ?>
                            </span>
                            <span title="Assignments" class="flex items-center">
                                <i class="fas fa-tasks mr-1.5 text-neutral-400"></i> <?php echo $course['assignment_count'] ?? 0; ?>
                            </span>
                        </div>
                        <span class="text-xs text-neutral-400"><?php echo date('M d, Y', strtotime($course['created_at'])); ?></span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2">
                        <a href="<?php echo site_url('/courses/' . $course['course_id'] . '/enrollments'); ?>" class="btn bg-white border border-neutral-200 text-neutral-600 hover:text-primary-700 hover:border-primary-300 shadow-sm rounded-lg py-2 text-center text-sm font-medium transition-all">
                            Students
                        </a>
                        <a href="<?php echo site_url('/courses/show/' . $course['course_id']); ?>" class="btn btn-primary rounded-lg py-2 text-center text-sm font-medium shadow-sm hover:shadow-md transition-all">
                            Manage
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // Copy Button Logic
    $('.copy-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $this = $(this);
        var targetSelector = $this.data('clipboard-target');
        var $target = $(targetSelector);
        
        if ($target.length === 0) return;
        
        var textToCopy = $target.text().trim();
        
        function showSuccess() {
            $this.html('<i class="fas fa-check text-green-600"></i>');
            $this.addClass('border-green-300 bg-green-50');
            setTimeout(function() {
                $this.html('<i class="fas fa-copy"></i>');
                $this.removeClass('border-green-300 bg-green-50');
            }, 2000);
        }

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(textToCopy).then(showSuccess).catch(function(err) {
                fallbackCopyTextToClipboard(textToCopy);
            });
        } else {
            fallbackCopyTextToClipboard(textToCopy);
        }

        function fallbackCopyTextToClipboard(text) {
            var textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                var successful = document.execCommand('copy');
                if (successful) showSuccess();
            } catch (err) {
                console.error('Fallback: Oops, unable to copy', err);
            }
            document.body.removeChild(textArea);
        }
    });
});
</script>