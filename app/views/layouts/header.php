<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS - Colegio de Naujan</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- NEW: Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script>
      // Define Colegio de Naujan Blue Theme
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'cdn-blue': '#0056A0',         // Colegio de Naujan deep blue
              'cdn-light-blue': '#1E88E5',   // Accent blue
              'cdn-bg': '#F5F9FF',           // Subtle light blue background
              'cdn-gray': '#D1D5DB',         // Neutral gray
              'cdn-dark': '#1E293B',         // Dark text
              'success': '#16A34A',
              'error': '#DC2626',
              'error-bg': '#FEE2E2',
              'success-bg': '#DCFCE7',
            }
          }
        }
      }
    </script>
    <style>
        /* Flash message styling */
        .notice {
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            border-width: 1px;
            border-style: solid;
            display: none;
        }
        .notice-success {
            background-color: #DCFCE7;
            color: #15803D;
            border-color: #15803D;
        }
        .notice-error {
            background-color: #FEE2E2;
            color: #B91C1C;
            border-color: #B91C1C;
        }
        body {
            background-color: #F5F9FF; /* Light blue background */
            color: #1E293B;
        }
        /* Style for form inputs */
        .input-field {
            border: 1px solid #cbd5e1; /* cdn-border */
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-field:focus {
            border-color: #3b82f6; /* cdn-blue-light */
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
            outline: none;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <?php
        $LAVA = lava_instance();
        if (!isset($LAVA->session)) {
            $LAVA->call->library('session');
        }
        if (!function_exists('site_url')) {
            $LAVA->call->helper('url');
        }
    ?>

    <!-- Colegio de Naujan Header -->
    <nav class="bg-cdn-blue shadow-md">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center">
            <a href="<?php echo site_url('/'); ?>" class="flex items-center space-x-2 text-white hover:text-cdn-light-blue transition duration-200">
                <img src="/public/images/Logo2.jpg" alt="Colegio de Naujan Logo" class="h-8 w-8 rounded-full border border-white shadow-sm">
                <span class="text-lg font-semibold tracking-wide">Colegio de Naujan LMS</span>
            </a>

            <div class="flex items-center space-x-3">
                <?php if ($LAVA->session->has_userdata('user_id')): ?>
                    <span class="text-sm text-white/90">Welcome, <span class="font-semibold"><?php echo htmlspecialchars($LAVA->session->userdata('first_name')); ?></span></span>
                    <a href="<?php echo site_url('auth/logout'); ?>" class="bg-red-600 text-white text-sm font-medium py-2 px-3 rounded-md hover:bg-red-700 transition duration-200">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="<?php echo site_url('auth/login'); ?>" class="text-sm text-white hover:text-cdn-light-blue transition duration-200">Login</a>
                    <a href="<?php echo site_url('auth/register'); ?>" class="bg-white text-cdn-blue text-sm font-medium py-2 px-3 rounded-md hover:bg-blue-100 transition duration-200">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-4 sm:p-6 lg:p-8">
        <!-- Flash Messages -->
        <?php $success_message = $LAVA->session->flashdata('success'); ?>
        <div class="notice notice-success mb-4" role="alert" <?php echo empty($success_message) ? '' : 'style="display:block;"'; ?>>
            <?php echo htmlspecialchars($success_message ?? ''); ?>
        </div>

        <?php $error_message = $LAVA->session->flashdata('error'); ?>
        <div class="notice notice-error mb-4" role="alert" <?php echo empty($error_message) ? '' : 'style="display:block;"'; ?>>
            <?php echo htmlspecialchars($error_message ?? ''); ?>
        </div>

        <?php $validation_errors = $LAVA->session->flashdata('validation_errors'); ?>
        <?php if (!empty($validation_errors)): ?>
            <div class="notice notice-error mb-4" role="alert" style="display:block;">
                <p class="font-bold mb-1">Please fix the following errors:</p>
                <ul class="list-disc list-inside">
                    <?php foreach ($validation_errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>