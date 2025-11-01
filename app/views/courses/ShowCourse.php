<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* Styles for the file lists */
    .list-item {
        border-bottom: 1px solid #e5e7eb;
    }
    .list-item:last-child { border-bottom: 0; }
    
    .btn-primary {
        background-color: #2563EB; color: white; padding: 0.5rem 1rem;
        border-radius: 0.375rem; font-weight: 600; font-size: 0.875rem;
        transition: background-color 0.2s;
    }
    .btn-primary:hover { background-color: #1D4ED8; }
    .btn-secondary {
        background-color: #f3f4f6; color: #374151; padding: 0.3rem 0.75rem;
        border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem;
        border: 1px solid #d1d5db; transition: background-color 0.2s;
    }
    .btn-secondary:hover { background-color: #e5e7eb; }
    .btn-danger-sm {
        background: none; border: none; color: #DC2626; font-size: 0.875rem;
        padding: 0.25rem; margin-left: 0.5rem; cursor: pointer;
    }
    .btn-danger-sm:hover { color: #B91C1C; }

    /* Style for file input (copied from assignments/create) */
    .file-input {
        display: block; width: 100%; padding: 0.75rem 1rem; font-size: 0.875rem;
        color: #374151; background-color: #f9fafb; border: 1px solid #d1d5db;
        border-radius: 0.375rem; cursor: pointer;
    }
    .file-input:hover { background-color: #f3f4f6; }
    .file-input::file-selector-button {
        padding: 0.5rem 1rem; margin-right: 0.75rem; font-weight: 500; color: #fff;
        background-color: #2563eb; border: none; border-radius: 0.25rem; cursor: pointer;
        transition: background-color 0.2s;
    }
    .file-input::file-selector-button:hover { background-color: #1d4ed8; }

    /* NEW: Style for delete button form */
    .delete-form {
        display: inline-block;
        margin-left: 0.5rem;
    }
    .btn-danger-link {
        background-color: #f3f4f6; color: #DC2626; padding: 0.3rem 0.75rem;
        border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem;
        border: 1px solid #d1d5db; transition: all 0.2s;
        cursor: pointer;
    }
    .btn-danger-link:hover { background-color: #fee2e2; border-color: #fca5a5; }

</style>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
        <div>
            <a href="<?php echo site_url('/courses'); ?>" class="text-sm text-blue-600 hover:underline">
                <i class="fas fa-arrow-left mr-1"></i> Back to Course List
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mt-2"><?php echo htmlspecialchars($course['title']); ?></h1>
        </div>
        <a href="<?php echo site_url('/courses/' . $course['course_id'] . '/enrollments'); ?>" class="btn-primary">
            <i class="fas fa-users mr-1"></i> Manage Enrollments
        </a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-3">Course Details</h2>
        <p class="text-gray-600 text-sm mb-4">Created: <?php echo date('M d, Y', strtotime($course['created_at'])); ?></p>
        <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-700">Course Materials</h2>
        </div>

        <form action="<?php echo site_url('/courses/' . $course['course_id'] . '/materials/upload'); ?>" method="POST" enctype="multipart/form-data" class="p-6 border-b">
            <?php echo csrf_field(); ?>
            <label for="material_file" class="block text-sm font-medium text-gray-700 mb-2">Upload a New File</label>
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="file" id="material_file" name="material_file" class="file-input flex-1" required>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm transition duration-200">
                    <i class="fas fa-upload mr-2"></i>Upload
                </button>
            </div>
            <small class="text-xs text-gray-500 mt-2 block">Allowed: PDF, DOCX, PPTX, MP4, ZIP, etc.</small>
        </form>

        <div class="divide-y divide-gray-200">
            <?php if (empty($materials)): ?>
                <p class="text-gray-500 p-6 text-center">No materials have been uploaded yet.</p>
            <?php else: ?>
                <?php foreach ($materials as $material): ?>
                    <div class="p-4 flex justify-between items-center hover:bg-gray-50 list-item">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-gray-500 mr-3"></i>
                            <a href="<?php echo base_url() . $material['file_path']; ?>" download class="text-sm font-medium text-blue-600 hover:underline">
                                <?php echo htmlspecialchars($material['file_name']); ?>
                            </a>
                        </div>
                        <form action="<?php echo site_url('/materials/delete/' . $material['material_id']); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?');">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn-danger-sm" title="Delete File">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-700">Assignments</h2>
            <a href="<?php echo site_url('/courses/' . $course['course_id'] . '/assignments/create'); ?>" class="btn-primary">
                <i class="fas fa-plus mr-1"></i> New Assignment
            </a>
        </div>
        <div class="divide-y divide-gray-200">
            <?php if (empty($assignments)): ?>
                <p class="text-gray-500 p-6 text-center">No assignments have been created yet.</p>
            <?php else: ?>
                <?php foreach ($assignments as $assignment): ?>
                    <div class="p-6 hover:bg-gray-50 list-item">
                        <div class="flex flex-wrap justify-between items-center gap-4">
                            <div class="flex-grow">
                                <h3 class="text-lg font-semibold text-blue-800"><?php echo htmlspecialchars($assignment['title']); ?></h3>
                                <div class="text-sm text-gray-500 mt-1">
                                    <span class="mr-4"><i class="fas fa-calendar-alt mr-1"></i> <strong>Due:</strong> <?php echo date('M d, Y @ g:i A', strtotime($assignment['due_date'])); ?></span>
                                    <span><i class="fas fa-star mr-1"></i> <strong>Points:</strong> <?php echo htmlspecialchars($assignment['points']); ?></span>
                                </div>
                                
                                <?php if (!empty($assignment['attachments'])): ?>
                                    <ul class="mt-3 space-y-1">
                                        <li class="text-sm font-medium text-gray-600">Attachments:</li>
                                        <?php foreach ($assignment['attachments'] as $file): ?>
                                            <li class="ml-4">
                                                <a href="<?php echo base_url() . $file['file_path']; ?>" download class="text-sm text-blue-600 hover:underline">
                                                    <i class="fas fa-paperclip mr-1"></i>
                                                    <?php echo htmlspecialchars($file['file_name']); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                                </div>
                            <div class="flex-shrink-0 flex items-center gap-3">
                                <a href="<?php echo site_url('/assignments/' . $assignment['assignment_id'] . '/submissions'); ?>" class="btn-secondary">
                                    View Submissions
                                </a>
                                <a href="<?php echo site_url('/assignments/edit/' . $assignment['assignment_id']); ?>" class="btn-secondary">
                                    Edit
                                </a>
                                <form action="<?php echo site_url('/assignments/delete/' . $assignment['assignment_id']); ?>" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this assignment? This will also delete all associated attachments and submissions.');">
                                    <?php echo csrf_field(); ?> 
                                    <button type="submit" title="Delete Assignment" class="btn-danger-link">
                                        Delete
                                    </button>
                                </form>
                                </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>