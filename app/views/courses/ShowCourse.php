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

    /* === DESIGNED BANNER === */
    .course-banner {
        background: white;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #e2e8f0;
        padding: 2.5rem 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    
    /* Decorative Background Elements */
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

    /* === STICKY NAVIGATION === */
    .sticky-tabs-wrapper {
        position: sticky;
        top: 0;
        z-index: 40;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid #cbd5e1;
        padding: 0.75rem 0;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .nav-pills {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding: 0.25rem;
    }

    .tab-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1.25rem;
        border-radius: 999px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        background: transparent;
        border: 1px solid transparent;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .tab-pill:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }

    .tab-pill.active {
        background-color: #2563eb;
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        border-color: #2563eb;
    }

    /* === HUNTJOBS CARD STYLE === */
    .hunt-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid #e2e8f0; 
        box-shadow: 0 1px 3px rgba(0,0,0,0.04); 
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        display: flex;
        gap: 1.25rem;
        align-items: flex-start;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .hunt-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.15); 
        border-color: #93c5fd;
    }

    .hunt-icon-box {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        box-shadow: inset 0 2px 4px rgba(255,255,255,0.3);
    }

    .meta-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    /* === RICH CONTENT AREA (Video/Image Support) === */
    .post-content-area {
        margin-top: 1rem;
        color: #334155;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    /* Responsive Video Container */
    .post-content-area iframe, 
    .post-content-area video {
        max-width: 100%;
        border-radius: 0.75rem;
        margin-top: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        display: block;
    }
    .post-content-area img {
        max-width: 100%;
        height: auto;
        border-radius: 0.75rem;
        margin-top: 0.5rem;
        border: 1px solid #e2e8f0;
    }

    /* === ATTACHMENT FILE CARDS === */
    .attachment-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px dashed #e2e8f0;
    }
    
    .file-card {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s;
        text-decoration: none;
        max-width: 100%;
    }
    
    .file-card:hover {
        background: white;
        border-color: #93c5fd;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        transform: translateY(-1px);
    }
    
    .file-icon {
        width: 2rem;
        height: 2rem;
        background: #e0f2fe;
        color: #0284c7;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 0.875rem;
    }
    
    .file-info { display: flex; flex-direction: column; }
    .file-name { font-size: 0.8rem; font-weight: 600; color: #334155; }
    .file-action { font-size: 0.7rem; color: #64748b; }

    /* === FOCUS RINGS === */
    input:focus, textarea:focus, button:focus, .btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.4);
        border-color: #3b82f6;
        transition: all 0.2s;
    }

    .tab-panel { display: none; animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .tab-panel.active { display: block; }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    #activity-fields { display: none; }
</style>

<div class="course-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            
            <div class="flex-1 min-w-0">
                <a href="<?php echo site_url('/courses'); ?>" class="inline-flex items-center text-sm font-semibold text-neutral-500 hover:text-primary-600 mb-3 transition-colors">
                    <div class="w-6 h-6 rounded-full bg-white border border-neutral-200 flex items-center justify-center mr-2 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Back to Courses
                </a>
                <h1 class="text-3xl md:text-4xl font-extrabold text-neutral-900 tracking-tight">
                    <?php echo htmlspecialchars($course['title']); ?>
                </h1>
                <p class="mt-2 text-neutral-500 max-w-2xl text-sm line-clamp-2">
                    <?php echo htmlspecialchars(strip_tags($course['description'])); ?>
                </p>
            </div>

            <div class="flex flex-row md:flex-col items-end gap-3">
                <div class="bg-white/80 backdrop-blur border border-neutral-200 shadow-sm rounded-xl px-5 py-3 text-right">
                    <div class="text-xs font-bold text-neutral-400 uppercase tracking-wider mb-0.5">Course Code</div>
                    <div class="text-xl font-mono font-bold text-primary-700 select-all">
                        <?php echo htmlspecialchars($course['enrollment_code'] ?? $course['course_id']); ?>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="<?php echo site_url('/courses/edit/' . $course['course_id']); ?>" class="btn bg-white border border-neutral-200 text-neutral-600 hover:text-primary-600 hover:border-primary-200 shadow-sm rounded-lg transition-all">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </a>
                    <div class="bg-green-50 border border-green-100 text-green-700 text-xs font-bold px-3 py-2 rounded-lg flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        Active
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="sticky-tabs-wrapper">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="nav-pills">
            <button class="tab-pill active" data-tab="announcements">
                <i class="fas fa-bullhorn mr-2"></i> Stream
            </button>
            <button class="tab-pill" data-tab="activities">
                <i class="fas fa-gamepad mr-2"></i> Activities
            </button>
            <button class="tab-pill" data-tab="assignments">
                <i class="fas fa-tasks mr-2"></i> Assignments
            </button>
            <button class="tab-pill" data-tab="materials">
                <i class="fas fa-folder-open mr-2"></i> Materials
            </button>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="max-w-5xl mx-auto">
        
        <div id="tab-panel-announcements" class="tab-panel active">
            
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 mb-8">
                <form action="<?php echo site_url('/courses/' . $course['course_id'] . '/post/store'); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
                    <div class="space-y-5">
                        <div>
                            <label for="title" class="block text-lg font-bold text-neutral-800 mb-2">Create a new post</label>
                            <input type="text" id="title" name="title" class="form-input w-full text-lg p-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-300" required placeholder="What's the title?">
                        </div>
                        
                        <div class="mt-4">
                            <label for="description" class="block text-sm font-bold text-neutral-700 mb-2">Description / Details</label>
                            <textarea id="description" name="description" rows="12" class="form-textarea w-full border border-neutral-300 rounded-lg" placeholder="Add details, instructions, etc..."></textarea>
                        </div>

                        <div class="mt-4">
                            <label class="flex items-center cursor-pointer select-none">
                                <input type="checkbox" id="is-activity-checkbox" name="is_activity" class="h-5 w-5 text-primary-600 border-neutral-300 rounded focus:ring-primary-500">
                                <span class="ml-3 block text-sm font-medium text-neutral-700">Make this an activity (add due date and points)</span>
                            </label>
                        </div>

                        <div id="activity-fields" class="space-y-4 mt-4 p-5 bg-neutral-50 rounded-xl border border-neutral-200">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="due_date" class="block text-sm font-bold text-neutral-700 mb-1">Due Date <span class="text-error-500">*</span></label>
                                    <input type="datetime-local" id="due_date" name="due_date" class="form-input w-full p-2.5 border border-neutral-300 rounded-lg">
                                </div>
                                <div>
                                    <label for="points" class="block text-sm font-bold text-neutral-700 mb-1">Points <span class="text-error-500">*</span></label>
                                    <input type="number" id="points" name="points" class="form-input w-full p-2.5 border border-neutral-300 rounded-lg" value="100" min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="attachments" class="block text-sm font-bold text-neutral-700 mb-2">Attach Files</label>
                            <input type="file" name="attachments[]" id="attachments" class="form-input-file w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" multiple>
                        </div>
                        
                        <div class="text-right mt-6 pt-4 border-t border-neutral-100">
                            <button type="submit" class="btn btn-primary px-8 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all text-base">
                                <i class="fas fa-paper-plane mr-2"></i> Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="space-y-4">
                <?php if (empty($announcements)): ?>
                    <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-neutral-200">
                        <div class="w-16 h-16 bg-neutral-50 rounded-full flex items-center justify-center mx-auto mb-3 text-neutral-400">
                            <i class="far fa-comment-dots text-3xl"></i>
                        </div>
                        <h3 class="text-neutral-900 font-semibold">No posts yet</h3>
                        <p class="text-neutral-500 text-sm">Start the conversation by creating a post above.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($announcements as $post): ?>
                        <?php 
                            $bgClass = 'bg-neutral-100 text-neutral-500';
                            $icon = 'fa-bullhorn';
                            
                            if ($post['type'] == 'assignment') { $bgClass = 'bg-blue-50 text-blue-600'; $icon = 'fa-clipboard-list'; }
                            elseif ($post['type'] == 'activity') { $bgClass = 'bg-amber-50 text-amber-600'; $icon = 'fa-gamepad'; }
                            elseif ($post['type'] == 'quiz') { $bgClass = 'bg-purple-50 text-purple-600'; $icon = 'fa-puzzle-piece'; }
                        ?>
                        
                        <div class="hunt-card group">
                            <div class="hunt-icon-box <?php echo $bgClass; ?>">
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row justify-between gap-2">
                                    <div>
                                        <h3 class="text-xl font-bold text-neutral-900 group-hover:text-primary-700 transition-colors">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-3 mt-1.5">
                                            <span class="meta-badge bg-neutral-100 text-neutral-600">
                                                <?php echo ucfirst($post['type']); ?>
                                            </span>
                                            <span class="text-xs text-neutral-400 font-medium">
                                                <?php echo date('M d, Y \a\t h:i A', strtotime($post['created_at'])); ?>
                                            </span>
                                            <?php if(in_array($post['type'], ['assignment', 'activity', 'quiz'])): ?>
                                                <span class="text-neutral-300">&bull;</span>
                                                <span class="text-xs font-bold text-error-600 flex items-center bg-error-50 px-2 py-0.5 rounded-md">
                                                    <i class="far fa-clock mr-1.5"></i> Due: <?php echo date('M d, g:i A', strtotime($post['due_date'])); ?>
                                                </span>
                                                <span class="text-xs font-bold text-neutral-600 flex items-center bg-neutral-100 px-2 py-0.5 rounded-md">
                                                    <i class="fas fa-star mr-1.5 text-amber-400"></i> <?php echo $post['points']; ?> pts
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 self-start">
                                        <?php if ($post['type'] != 'announcement'): ?>
                                            <a href="<?php echo site_url(($post['type']=='quiz'?'/quizzes/edit/':'/assignments/edit/') . $post['assignment_id']); ?>" class="btn btn-sm bg-white border border-neutral-200 text-neutral-500 hover:text-primary-600 hover:border-primary-300 shadow-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?php echo site_url('/assignments/' . $post['assignment_id'] . '/submissions'); ?>" class="btn btn-sm bg-white border border-neutral-200 text-neutral-500 hover:text-primary-600 hover:border-primary-300 shadow-sm">
                                                Submissions
                                            </a>
                                        <?php endif; ?>
                                        
                                        <form action="<?php echo site_url('/assignments/delete/' . $post['assignment_id']); ?>" method="POST" onsubmit="return confirm('Delete?');">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 text-neutral-400 hover:text-red-600 transition-colors" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <?php if (!empty($post['description'])): ?>
                                    <div class="post-content-area">
                                        <?php echo $post['description']; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($post['attachments'])): ?>
                                    <div class="attachment-grid">
                                        <?php foreach ($post['attachments'] as $file): ?>
                                            <a href="<?php echo base_url() . $file['file_path']; ?>" download class="file-card">
                                                <div class="file-icon">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                                <div class="file-info">
                                                    <span class="file-name"><?php echo htmlspecialchars($file['file_name']); ?></span>
                                                    <span class="file-action">Download</span>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mt-4 pt-3 border-t border-neutral-50 flex justify-between items-center">
                                    <button type="button" class="text-sm font-semibold text-neutral-500 hover:text-primary-600 transition-colors btn-replies flex items-center gap-2" data-post-id="<?php echo $post['assignment_id']; ?>" data-post-title="<?php echo htmlspecialchars($post['title']); ?>">
                                        <i class="far fa-comment-dots text-lg"></i> 
                                        <span><?php echo $post['reply_count'] > 0 ? $post['reply_count'] . ' Comments' : 'Write a comment...'; ?></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div id="tab-panel-activities" class="tab-panel">
            <div class="flex justify-end mb-6">
                <a href="<?php echo site_url('/courses/' . $course['course_id'] . '/quizzes/create'); ?>" class="btn btn-success rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-bold">
                    <i class="fas fa-plus mr-2"></i> Create Quiz
                </a>
            </div>
            <div class="space-y-4">
                <?php if (empty($activities)): ?>
                     <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-neutral-200 text-neutral-400">No activities yet.</div>
                <?php else: ?>
                    <?php foreach ($activities as $post): ?>
                        <?php 
                            $isQuiz = $post['type'] == 'quiz';
                            $bgClass = $isQuiz ? 'bg-purple-50 text-purple-600' : 'bg-amber-50 text-amber-600';
                            $icon = $isQuiz ? 'fa-puzzle-piece' : 'fa-gamepad';
                        ?>
                        <div class="hunt-card group">
                            <div class="hunt-icon-box <?php echo $bgClass; ?>">
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row justify-between gap-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-neutral-900 group-hover:text-primary-600 transition-colors">
                                            <a href="<?php echo site_url('/assignments/' . $post['assignment_id'] . '/submissions'); ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                                        </h3>
                                        <div class="flex items-center gap-3 mt-2 text-sm text-neutral-500">
                                             <span class="meta-badge bg-neutral-100 text-neutral-600"><?php echo ucfirst($post['type']); ?></span>
                                             <span class="flex items-center"><i class="far fa-clock mr-1.5"></i> Due <?php echo date('M d', strtotime($post['due_date'])); ?></span>
                                             <span class="flex items-center text-amber-600 font-semibold"><i class="fas fa-star mr-1.5"></i> <?php echo $post['points']; ?> pts</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 self-center">
                                        <a href="<?php echo site_url(($isQuiz?'/quizzes/edit/':'/assignments/edit/') . $post['assignment_id']); ?>" class="btn bg-white border border-neutral-200 text-neutral-600 hover:text-primary-600 shadow-sm rounded-lg text-sm">
                                            Edit
                                        </a>
                                        <a href="<?php echo site_url('/assignments/' . $post['assignment_id'] . '/submissions'); ?>" class="btn btn-primary rounded-lg px-4 shadow-sm hover:shadow-md transition-all text-sm">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div id="tab-panel-assignments" class="tab-panel">
             <div class="flex justify-end mb-6">
                <a href="<?php echo site_url('/courses/' . $course['course_id'] . '/assignments/create'); ?>" class="btn btn-primary rounded-lg shadow-md hover:shadow-lg font-bold">
                    <i class="fas fa-plus mr-2"></i> Create Assignment
                </a>
            </div>
            <div class="space-y-4">
                <?php if (empty($assignments)): ?>
                    <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-neutral-200 text-neutral-400">No assignments yet.</div>
                <?php else: ?>
                    <?php foreach ($assignments as $post): ?>
                        <div class="hunt-card group">
                            <div class="hunt-icon-box bg-blue-50 text-blue-600">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row justify-between gap-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-neutral-900 group-hover:text-primary-600 transition-colors">
                                            <a href="<?php echo site_url('/assignments/' . $post['assignment_id'] . '/submissions'); ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                                        </h3>
                                        <div class="flex items-center gap-3 mt-2 text-sm text-neutral-500">
                                             <span class="meta-badge bg-blue-50 text-blue-700 border-blue-100">Assignment</span>
                                             <span class="flex items-center"><i class="far fa-clock mr-1.5"></i> Due <?php echo date('M d', strtotime($post['due_date'])); ?></span>
                                             <span class="flex items-center text-amber-600 font-semibold"><i class="fas fa-star mr-1.5"></i> <?php echo $post['points']; ?> pts</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 self-center">
                                        <a href="<?php echo site_url('/assignments/edit/' . $post['assignment_id']); ?>" class="btn bg-white border border-neutral-200 text-neutral-600 hover:text-primary-600 shadow-sm rounded-lg text-sm">Edit</a>
                                        <a href="<?php echo site_url('/assignments/' . $post['assignment_id'] . '/submissions'); ?>" class="btn btn-primary rounded-lg px-4 shadow-sm text-sm">Submissions</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div id="tab-panel-materials" class="tab-panel">
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
                <div class="p-6 border-b border-neutral-100 bg-neutral-50 flex justify-between items-center">
                    <h3 class="font-bold text-neutral-800 text-lg">Course Resources</h3>
                </div>
                <div class="p-6 border-b border-neutral-100 bg-white">
                    <form action="<?php echo site_url('/courses/' . $course['course_id'] . '/materials/upload'); ?>" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-4 items-center">
                        <?php echo csrf_field(); ?>
                        <div class="flex-1 w-full">
                            <input type="file" name="material_file" class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer border border-neutral-200 rounded-lg" required>
                        </div>
                        <button type="submit" class="btn btn-success rounded-lg px-6 w-full sm:w-auto shadow-sm">
                            <i class="fas fa-cloud-upload-alt mr-2"></i> Upload
                        </button>
                    </form>
                </div>
                <div class="divide-y divide-neutral-100">
                    <?php if (empty($materials)): ?>
                        <div class="p-12 text-center text-neutral-400 italic">No materials uploaded yet.</div>
                    <?php else: ?>
                        <?php foreach ($materials as $material): ?>
                            <div class="p-5 flex justify-between items-center hover:bg-neutral-50 transition-colors group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-neutral-100 rounded-xl flex items-center justify-center text-neutral-500 group-hover:bg-white group-hover:shadow-md transition-all">
                                        <i class="far fa-file-alt text-xl"></i>
                                    </div>
                                    <div>
                                        <a href="<?php echo base_url() . $material['file_path']; ?>" download class="text-lg font-bold text-neutral-800 hover:text-primary-600 hover:underline block transition-colors">
                                            <?php echo htmlspecialchars($material['file_name']); ?>
                                        </a>
                                        <span class="text-xs text-neutral-400 font-medium uppercase tracking-wide">
                                            Uploaded <?php echo date('M d, Y', strtotime($material['uploaded_at'])); ?>
                                        </span>
                                    </div>
                                </div>
                                <form action="<?php echo site_url('/materials/delete/' . $material['material_id']); ?>" method="POST" onsubmit="return confirm('Remove file?');">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-neutral-300 hover:text-error-500 transition-colors p-2 rounded-lg hover:bg-error-50"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
    tinymce.init({
        selector: 'textarea#description',
        menubar: true, // Enabled menubar for better editing tools
        plugins: 'lists link image media table wordcount',
        toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link image media | alignleft aligncenter alignright | removeformat',
        height: 400, // MUCH BIGGER
        statusbar: true,
        resize: 'both', // Allow resizing
        license_key: 'gpl'
    });

    $(document).ready(function() {
        // Toggle Activity Fields
        $('#is-activity-checkbox').on('change', function() {
            if ($(this).is(':checked')) {
                $('#activity-fields').slideDown(200);
                $('#due_date, #points').prop('required', true);
            } else {
                $('#activity-fields').slideUp(200);
                $('#due_date, #points').prop('required', false);
            }
        });

        // Tab Switching Logic
        var storageKey = 'courseActiveTab_<?php echo $course['course_id']; ?>';
        var savedTab = sessionStorage.getItem(storageKey) || 'announcements';
        const urlTab = new URLSearchParams(window.location.search).get('tab');
        if (urlTab) savedTab = urlTab;

        function activateTab(tab) {
            $('.tab-pill').removeClass('active');
            $('.tab-panel').removeClass('active');
            
            $('.tab-pill[data-tab="' + tab + '"]').addClass('active');
            $('#tab-panel-' + tab).addClass('active');
            
            sessionStorage.setItem(storageKey, tab);
        }

        activateTab(savedTab);

        $('.tab-pill').on('click', function(e) {
            e.preventDefault();
            activateTab($(this).data('tab'));
            $('html, body').animate({ scrollTop: 0 }, 400);
        });
    });
</script>