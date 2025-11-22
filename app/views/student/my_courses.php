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
    .decoration-1 { top: -60%; left: -10%; width: 500px; height: 500px; background: #dcfce7; }
    .decoration-2 { bottom: -60%; right: -5%; width: 400px; height: 400px; background: #e0e7ff; }

    .banner-content {
        position: relative;
        z-index: 10;
    }

    /* === ENROLLMENT CARD === */
    .enroll-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid #cbd5e1;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s;
    }
    .enroll-card:focus-within {
        border-color: #3b82f6;
        ring: 2px solid #bfdbfe;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    /* === HUNTJOBS CARD STYLE === */
    .hunt-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid #cbd5e1; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .hunt-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.15); 
    }

    .accent-bar {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
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
        box-shadow: inset 0 2px 4px rgba(255,255,255,0.3);
    }

    /* === FOCUS RINGS === */
    input:focus, button:focus, .btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.4);
        border-color: #3b82f6;
        transition: all 0.2s;
    }
</style>

<div class="page-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-neutral-900 tracking-tight mb-2">
                    <?php echo $page_title ?? 'My Courses'; ?>
                </h1>
                <p class="text-neutral-500 text-sm">Access your enrolled classes and join new ones.</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/80 backdrop-blur text-neutral-700 px-4 py-2 rounded-xl border border-neutral-200 text-sm font-bold shadow-sm flex items-center">
                    <i class="fas fa-graduation-cap mr-2 text-primary-600"></i> 
                    Student Portal
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="max-w-5xl mx-auto">

        <div class="enroll-card">
            <h2 class="text-lg font-bold text-neutral-800 mb-4 flex items-center">
                <i class="fas fa-plus-circle text-primary-600 mr-2"></i> Join a New Course
            </h2>
            <form action="<?php echo site_url('/courses/enroll'); ?>" method="POST" class="flex flex-col sm:flex-row gap-4">
                <?php echo csrf_field(); ?>
                <div class="flex-grow relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-key text-neutral-400"></i>
                    </div>
                    <input type="text" id="enrollment_code" name="enrollment_code"
                           class="block w-full pl-10 pr-3 py-3 border border-neutral-300 rounded-lg leading-5 bg-neutral-50 placeholder-neutral-400 focus:outline-none focus:bg-white focus:ring-0 focus:border-primary-500 sm:text-sm transition-colors"
                           placeholder="Enter 6-digit class code..." required>
                </div>
                <button type="submit" class="btn btn-primary rounded-lg px-6 py-3 font-semibold shadow-md hover:shadow-lg transition-all flex-shrink-0">
                    Enroll Now
                </button>
            </form>
        </div>

        <div class="space-y-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-neutral-800">Your Classes</h2>
                <span class="bg-neutral-100 text-neutral-600 px-3 py-1 rounded-full text-xs font-bold border border-neutral-200">
                    <?php echo count($my_courses); ?> Enrolled
                </span>
            </div>

            <?php if (empty($my_courses)): ?>
                <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-neutral-300">
                    <div class="w-16 h-16 bg-neutral-50 rounded-full flex items-center justify-center mx-auto mb-4 text-neutral-400">
                        <i class="fas fa-book-open text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-neutral-900">No courses yet</h3>
                    <p class="text-neutral-500 mt-1">Enter a code above to join your first class.</p>
                </div>
            <?php else: ?>
                <?php foreach ($my_courses as $course): ?>
                    <?php 
                        $isApproved = ($course['status'] == 'approved');
                        $accentColor = $isApproved ? 'bg-green-500' : 'bg-amber-500';
                        $hoverBorder = $isApproved ? 'hover:border-green-400' : 'hover:border-amber-400';
                        $iconBg = $isApproved ? 'bg-green-50 text-green-600 border-green-200' : 'bg-amber-50 text-amber-600 border-amber-200';
                        $iconClass = $isApproved ? 'fa-book-reader' : 'fa-hourglass-half';
                        
                        $statusBadge = $isApproved 
                            ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-green-100 text-green-700 border border-green-200"><i class="fas fa-check-circle mr-1.5"></i> Active</span>'
                            : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200"><i class="fas fa-clock mr-1.5"></i> Pending</span>';
                    ?>

                    <div class="hunt-card group <?php echo $hoverBorder; ?>">
                        <div class="accent-bar <?php echo $accentColor; ?>"></div>

                        <div class="hunt-icon-box <?php echo $iconBg; ?> border">
                            <i class="fas <?php echo $iconClass; ?>"></i>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                                <div>
                                    <h3 class="text-xl font-bold text-neutral-900 group-hover:text-primary-700 transition-colors mb-1">
                                        <?php if ($isApproved): ?>
                                            <a href="<?php echo site_url('/my-courses/' . $course['course_id']); ?>" class="hover:underline">
                                                <?php echo htmlspecialchars($course['title']); ?>
                                            </a>
                                        <?php else: ?>
                                            <span><?php echo htmlspecialchars($course['title']); ?></span>
                                        <?php endif; ?>
                                    </h3>
                                    
                                    <div class="flex items-center gap-3 text-sm text-neutral-500 mt-2">
                                        <span class="bg-neutral-100 border border-neutral-200 px-2 py-0.5 rounded text-xs font-mono font-medium text-neutral-600">
                                            Code: <?php echo htmlspecialchars($course['enrollment_code']); ?>
                                        </span>
                                        <?php echo $statusBadge; ?>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 self-start md:self-center">
                                    <?php if ($isApproved): ?>
                                        <a href="<?php echo site_url('/my-courses/' . $course['course_id']); ?>" class="btn btn-primary rounded-lg px-5 py-2 shadow-sm hover:shadow-md transition-all font-medium">
                                            Enter Class
                                        </a>
                                        <form action="<?php echo site_url('/courses/leave/' . $course['enrollment_id']); ?>" method="POST" onsubmit="return confirm('Are you sure you want to leave this course?');">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn bg-white border border-neutral-200 text-neutral-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50 rounded-lg px-3 py-2 transition-all" title="Leave Course">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?php echo site_url('/courses/leave/' . $course['enrollment_id']); ?>" method="POST" onsubmit="return confirm('Cancel enrollment request?');">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn bg-white border border-neutral-200 text-neutral-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50 rounded-lg px-4 py-2 transition-all font-medium">
                                                Cancel Request
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>