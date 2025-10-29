<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>

<?php include 'app/views/layouts/header.php'; ?>

<div class="bg-white p-6 rounded-lg shadow-md border border-teams-gray">
    <h1 class="text-2xl font-bold text-teams-dark-gray mb-4">Teacher Dashboard</h1>
    <p class="text-teams-text mb-6">Welcome, <?php echo htmlspecialchars($first_name); ?>!</p>

    <!-- Add a link/button to manage courses -->
    <div class="mt-4">
        <a href="<?php echo site_url('/courses'); ?>" class="inline-block bg-blue-800 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-200">
            Manage My Courses
        </a>
    </div>

    <!-- Add other teacher-specific content or links here -->

</div>

<?php include 'app/views/layouts/footer.php'; ?>