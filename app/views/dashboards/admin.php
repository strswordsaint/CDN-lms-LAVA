<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>

<?php include 'app/views/layouts/header.php'; ?>

<div class="bg-white p-6 rounded-lg shadow-md border border-teams-gray">
    <h1 class="text-2xl font-bold text-teams-dark-gray mb-4">Admin Dashboard</h1>
    <!-- Updated to use first_name -->
    <p class="text-teams-text">Welcome, <?php echo htmlspecialchars($first_name); ?>! This is your admin dashboard.</p>
    <!-- Add admin-specific content here -->
</div>

<?php include 'app/views/layouts/footer.php'; ?>

