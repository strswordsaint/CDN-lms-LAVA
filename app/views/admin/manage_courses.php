<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* === Modern Variables === */
    :root {
        --bg-body: #eef2f6; 
        --primary-soft: #eff6ff;
        --primary-border: #bfdbfe;
        --primary-text: #1d4ed8;
    }
    
    body { background-color: var(--bg-body); font-family: 'Inter', sans-serif; }

    /* === DESIGNED BANNER === */
    .page-banner {
        background: white;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #e2e8f0;
        padding: 2.5rem 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        margin-bottom: 2rem;
    }
    .banner-decoration { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.6; z-index: 0; }
    .decoration-1 { top: -60%; left: -10%; width: 500px; height: 500px; background: #fee2e2; }
    .decoration-2 { bottom: -60%; right: -5%; width: 400px; height: 400px; background: #fecaca; }
    .banner-content { position: relative; z-index: 10; }

    /* === TABLE & SEARCH STYLES === */
    .search-container {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    .search-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.75rem;
        font-size: 0.95rem;
        transition: all 0.2s;
        background: white;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .data-table-container {
        background: white;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .modern-table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .modern-table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .modern-table tr:last-child td { border-bottom: none; }
    .modern-table tr:hover td { background-color: #f8fafc; }

    /* Avatar Circle */
    .avatar-circle {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background: #eff6ff;
        color: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        border: 1px solid #bfdbfe;
    }
    
    /* Status Badge */
    .badge-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-blue { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .badge-gray { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
</style>

<div class="page-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <a href="<?php echo site_url('/dashboard'); ?>" class="inline-flex items-center text-sm font-semibold text-neutral-500 hover:text-neutral-800 mb-3 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                </a>
                <h1 class="text-3xl font-extrabold text-neutral-900 tracking-tight">Manage Courses</h1>
                <p class="text-neutral-500 text-sm mt-1">Overview of all courses and instructors in the system.</p>
            </div>
            <div>
                <a href="<?php echo site_url('/admin/courses/create'); ?>" class="btn btn-primary rounded-xl shadow-md hover:shadow-lg px-6 py-3 font-bold flex items-center transition-all">
                    <i class="fas fa-plus-circle mr-2"></i> Create Course
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">

    <?php if (!empty($success_message)): ?>
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-3 text-lg"></i> 
            <span class="font-medium"><?php echo htmlspecialchars($success_message); ?></span>
        </div>
    <?php endif; ?>
    <?php if (!empty($error_message)): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center shadow-sm">
            <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
            <span class="font-medium"><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    <?php endif; ?>

    <div class="search-container max-w-md">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="courseSearch" class="search-input" placeholder="Search courses, teachers, or codes...">
    </div>

    <div class="data-table-container">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full text-left border-collapse modern-table">
                <thead>
                    <tr>
                        <th class="w-1/3">Course Details</th>
                        <th class="w-1/4">Instructor</th>
                        <th class="text-center w-1/6">Enrolled</th>
                        <th class="text-center w-1/6">Status</th>
                        <th class="text-right w-1/6">Actions</th>
                    </tr>
                </thead>
                <tbody id="coursesTableBody">
                    <?php if (empty($all_courses)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-neutral-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-neutral-50 rounded-full flex items-center justify-center mb-3">
                                        <i class="fas fa-inbox text-2xl text-neutral-300"></i>
                                    </div>
                                    <p class="font-medium">No courses found.</p>
                                    <p class="text-xs mt-1">Click "Create Course" to get started.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($all_courses as $course): ?>
                            <tr class="course-row transition-colors">
                                <td>
                                    <div class="font-bold text-neutral-900 text-base mb-0.5"><?php echo htmlspecialchars($course['title']); ?></div>
                                    <div class="text-xs text-neutral-500 font-mono bg-neutral-50 inline-block px-1.5 py-0.5 rounded border border-neutral-100">
                                        <?php echo htmlspecialchars($course['enrollment_code']); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <?php 
                                            // Initials for Avatar
                                            $fname = $course['first_name'][0] ?? 'U'; 
                                            $lname = $course['last_name'][0] ?? 'U'; 
                                        ?>
                                        <div class="avatar-circle text-xs">
                                            <?php echo strtoupper($fname . $lname); ?>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-sm text-neutral-800">
                                                <?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?>
                                            </div>
                                            <div class="text-xs text-neutral-400">Teacher</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge-pill <?php echo ($course['student_count'] ?? 0) > 0 ? 'badge-blue' : 'badge-gray'; ?>">
                                        <i class="fas fa-users mr-1.5"></i>
                                        <?php echo $course['student_count'] ?? 0; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                        Active
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?php echo site_url('/admin/courses/view/' . $course['course_id']); ?>" class="btn bg-white border border-neutral-200 text-neutral-500 hover:text-blue-600 hover:border-blue-200 p-2 rounded-lg shadow-sm transition-colors" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo site_url('/admin/courses/edit/' . $course['course_id']); ?>" class="btn bg-white border border-neutral-200 text-neutral-500 hover:text-amber-600 hover:border-amber-200 p-2 rounded-lg shadow-sm transition-colors" title="Edit Settings">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                        </div>
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

<script>
    // Simple Search Filter
    document.getElementById('courseSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.course-row');
        
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>