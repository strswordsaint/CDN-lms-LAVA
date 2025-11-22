<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<style>
    /* === Modern Variables === */
    :root { --bg-body: #eef2f6; --primary-soft: #eff6ff; --primary-border: #bfdbfe; --primary-text: #1d4ed8; }
    body { background-color: var(--bg-body); font-family: 'Inter', sans-serif; }

    /* === DESIGNED BANNER === */
    .page-banner {
        background: white; position: relative; overflow: hidden; border-bottom: 1px solid #e2e8f0; padding: 2rem 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02); margin-bottom: 2rem;
    }
    .banner-decoration { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.6; z-index: 0; }
    .decoration-1 { top: -60%; left: -10%; width: 500px; height: 500px; background: #fee2e2; }
    .decoration-2 { bottom: -60%; right: -5%; width: 400px; height: 400px; background: #fecaca; }
    .banner-content { position: relative; z-index: 10; }

    /* === FORM CARD === */
    .form-card {
        @apply bg-white rounded-2xl shadow-sm border border-neutral-200 p-8 max-w-2xl mx-auto;
    }
    
    /* === INPUT STYLES === */
    .form-label { @apply block text-sm font-bold text-neutral-700 mb-1.5; }
    .form-input { 
        @apply block w-full px-4 py-2.5 border border-neutral-300 rounded-lg text-neutral-900 placeholder-neutral-400 transition-shadow;
        @apply focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500;
    }
    .form-select {
        @apply block w-full px-4 py-2.5 border border-neutral-300 rounded-lg bg-white text-neutral-900 transition-shadow;
        @apply focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500;
    }
</style>

<div class="page-banner">
    <div class="banner-decoration decoration-1"></div>
    <div class="banner-decoration decoration-2"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 banner-content">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <a href="<?php echo site_url('/admin/users'); ?>" class="inline-flex items-center text-sm font-semibold text-neutral-500 hover:text-neutral-800 mb-3 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Users
                </a>
                <h1 class="text-3xl font-extrabold text-neutral-900 tracking-tight mb-1">Create User</h1>
                <p class="text-neutral-500 text-sm">Add a new student, teacher, or admin to the system.</p>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    
    <div class="form-card">
        <?php $validation_errors = lava_instance()->session->flashdata('validation_errors'); ?>
        <?php if (!empty($validation_errors)): ?>
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                <div class="flex items-center mb-2 font-bold">
                    <i class="fas fa-exclamation-circle mr-2"></i> Please fix the following errors:
                </div>
                <ul class="list-disc list-inside ml-1 space-y-1">
                    <?php foreach ($validation_errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php $error_message = lava_instance()->session->flashdata('error'); ?>
        <?php if (!empty($error_message)): ?>
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center text-sm font-medium">
                <i class="fas fa-exclamation-triangle mr-2"></i> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo site_url('/admin/user/store'); ?>" method="POST" id="create-user-form" class="space-y-6">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="form-label">First Name <span class="text-red-500">*</span></label>
                    <input type="text" id="first_name" name="first_name" required class="form-input" placeholder="e.g. John">
                </div>
                <div>
                    <label for="last_name" class="form-label">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" id="last_name" name="last_name" required class="form-input" placeholder="e.g. Doe">
                </div>
            </div>

            <div>
                <label for="email" class="form-label">Email Address <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"></div>
                    <input type="email" id="email" name="email" required class="form-input pl-10" placeholder="user@gmail.com">
                </div>
            </div>

            <div>
                <label for="role" class="form-label">Assign Role <span class="text-red-500">*</span></label>
                <select id="role" name="role" required class="form-select">
                    <option value="" disabled selected>Select a role...</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="admin">Administrator</option>
                </select>
                <div id="teacher-note" class="hidden mt-2 text-xs text-amber-600 bg-amber-50 p-2 rounded border border-amber-100 flex items-start">
                    <i class="fas fa-info-circle mr-1.5 mt-0.5"></i> 
                    Note: Teacher accounts require approval. They will be listed as 'Pending' until you approve them.
                </div>
            </div>

            <div>
                <label for="password" class="form-label">Temporary Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"></div>
                    <input type="password" id="password" name="password" required minlength="8" class="form-input pl-10" placeholder="********">
                </div>
                <p class="text-xs text-neutral-500 mt-1.5">
                    Must be at least 8 characters with uppercase, lowercase, number, and symbol.
                </p>
            </div>
            
            <div class="pt-6 border-t border-neutral-100 flex items-center justify-end gap-3">
                <a href="<?php echo site_url('/admin/users'); ?>" class="btn btn-secondary px-6 py-2.5 rounded-lg">Cancel</a>
                <button type="submit" class="btn btn-primary px-8 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all">
                    <i class="fas fa-check mr-2"></i> Create User
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>

<script>
    // Simple script to show note when Teacher is selected
    document.getElementById('role').addEventListener('change', function() {
        var note = document.getElementById('teacher-note');
        if (this.value === 'teacher') {
            note.classList.remove('hidden');
        } else {
            note.classList.add('hidden');
        }
    });
</script>