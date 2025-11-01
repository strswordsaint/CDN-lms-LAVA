<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS - Colegio de Naujan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
              // MS Teams inspired colors
              'teams-bg': '#F5F5F5',      // Main background
              'teams-sidebar': '#FFFFFF',  // Sidebar background
              'teams-text': '#333333',
              'teams-hover': '#F0F0F0',     // Sidebar hover
              'teams-active': '#EAEAEA',    // Sidebar active
              'teams-primary': '#6264A7',   // Teams purple
            }
          }
        }
      }
    </script>
    <style>
        /* Flash message styling */
        .notice {
            padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 1rem;
            font-size: 0.875rem; border-width: 1px; border-style: solid; display: none;
        }
        .notice-success { background-color: #DCFCE7; color: #15803D; border-color: #15803D; }
        .notice-error { background-color: #FEE2E2; color: #B91C1C; border-color: #B91C1C; }
        
        /* New Layout Styles */
        html, body { height: 100%; overflow: hidden; /* Prevent full page scroll */ }
        body { background-color: #F5F5F5; color: #1E293B; }
        .main-layout { height: calc(100vh - 52px); /* Full height minus top-nav */ }
        
        .sidebar-nav-link {
            display: flex; align-items: center; width: 100%;
            padding: 0.75rem 1rem; /* 12px vertical, 16px horizontal */
            color: #333333; font-weight: 500; font-size: 0.9rem;
            border-radius: 6px; transition: background-color 0.2s, color 0.2s;
            white-space: nowrap; /* Prevent text wrap during animation */
            overflow: hidden; /* Hide text as it slides out */
        }
        .sidebar-nav-link:hover { background-color: #F0F0F0; text-decoration: none; }
        .sidebar-nav-link.active {
            background-color: #EAEAEA;
            color: #6264A7; /* Teams Purple */
            font-weight: 600;
        }
        .sidebar-nav-link i { 
            width: 1.25rem; /* 20px */
            margin-right: 0.75rem; /* 12px space between icon and text */
            text-align: center;
            flex-shrink: 0; /* Prevent icon from shrinking */
            font-size: 1.125rem;
            transition: margin 0.3s ease-in-out;
        }
        .sidebar-text {
            transition: opacity 0.2s ease-in-out;
        }

        /* == Collapsed State Styles == */
        #app-sidebar.collapsed {
            width: 5rem; /* 80px */
        }
        #app-sidebar.collapsed .sidebar-text {
            opacity: 0;
            display: none; /* Hide text */
        }
        #app-sidebar.collapsed .sidebar-nav-link {
            justify-content: center; /* Center the icon */
        }
        #app-sidebar.collapsed .sidebar-nav-link i {
            margin-right: 0; /* Remove margin when text is hidden */
            font-size: 1.25rem; /* Slightly larger icon */
        }
    </style>
</head>
<body class="font-sans antialiased">
    <?php
        // Load session and helpers for use in the layout
        $LAVA = lava_instance();
        if (!isset($LAVA->session)) $LAVA->call->library('session');
        if (!function_exists('site_url')) $LAVA->call->helper('url');
        if (!function_exists('segment')) $LAVA->call->helper('url');
        
        $is_logged_in = $LAVA->session->has_userdata('user_id');
        $user_role = $LAVA->session->userdata('role');
        $user_name = $LAVA->session->userdata('first_name');

        // === UPDATED: Active link logic ===
        $current_segment = segment(2);
        // This will highlight all course-related buttons if you are in any of these sections
        $is_teacher_section = in_array($current_segment, ['courses', 'assignments', 'submissions', 'activities']);
        // NEW: Active logic for students
        $is_student_course_section = (segment(3) == 'my' || $current_segment == 'my-courses' || $current_segment == 'assignment');
    ?>

    <nav class="bg-cdn-blue shadow-md z-20 relative" style="height: 52px;">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 h-full flex justify-between items-center">
            <div class="flex items-center space-x-2">
                
                <?php if ($is_logged_in): ?>
                <button id="sidebar-toggle" class="text-white p-2 rounded-md hover:bg-cdn-light-blue focus:outline-none">
                    <i class="fas fa-bars"></i>
                </button>
                <?php endif; ?>
                
                <a href="<?php echo site_url('/'); ?>" class="flex items-center space-x-2 text-white hover:text-cdn-light-blue transition duration-200">
                    <img src="<?php echo base_url(); ?>public/images/Logo2.jpg" alt="Colegio de Naujan Logo" class="h-8 w-8 rounded-full border border-white shadow-sm">
                    <span class="text-lg font-semibold tracking-wide">Colegio de Naujan LMS</span>
                </a>
            </div>
            
            <div class="flex items-center space-x-3">
                <?php if ($is_logged_in): ?>
                    <span class="text-sm text-white/90">Welcome, <span class="font-semibold"><?php echo htmlspecialchars($user_name); ?></span></span>
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

    <div class="flex main-layout">
        
        <?php if ($is_logged_in): // ONLY show sidebar if logged in ?>
        <aside id="app-sidebar" class="w-60 bg-teams-sidebar border-r border-gray-200 p-3 flex-shrink-0 overflow-y-auto transition-all duration-300 ease-in-out z-10">
            <nav>
                <ul class="space-y-1">
                    <li>
                        <a href="<?php echo site_url('/dashboard'); ?>" 
                           class="sidebar-nav-link <?php echo ($current_segment == 'dashboard') ? 'active' : ''; ?>">
                           <i class="fas fa-home"></i><span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    
                    <?php if ($user_role == 'teacher' || $user_role == 'admin'): ?>
                    <li>
                        <a href="<?php echo site_url('/courses'); ?>" 
                           class="sidebar-nav-link <?php echo $is_teacher_section ? 'active' : ''; ?>">
                           <i class="fas fa-book-open"></i><span class="sidebar-text">Manage Courses</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('/courses'); ?>" 
                           title="View Courses to Manage Assignments"
                           class="sidebar-nav-link <?php echo $is_teacher_section ? 'active' : ''; ?>">
                           <i class="fas fa-tasks"></i><span class="sidebar-text">Assignments</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('/courses'); ?>" 
                           title="Manage Activities (Coming Soon)"
                           class="sidebar-nav-link <?php echo $is_teacher_section ? 'active' : ''; ?>">
                           <i class="fas fa-rocket"></i><span class="sidebar-text">Activities</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if ($user_role == 'student'): ?>
                    <?php
                        // Updated active logic
                        $is_my_courses_active = (segment(3) == 'my' || $current_segment == 'my-courses');
                        $is_assignments_active = ($current_segment == 'my-assignments' || $current_segment == 'assignment');
                    ?>
                    <li>
                        <a href="<?php echo site_url('/courses/my'); ?>" 
                           class="sidebar-nav-link <?php echo $is_my_courses_active ? 'active' : ''; ?>">
                           <i class="fas fa-chalkboard"></i><span class="sidebar-text">My Courses</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('/my-assignments'); ?>" 
                           title="View All Assignments"
                           class="sidebar-nav-link <?php echo $is_assignments_active ? 'active' : ''; ?>">
                           <i class="fas fa-tasks"></i><span class="sidebar-text">Assignments</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('/courses/my'); ?>" 
                           title="View Activities in My Courses"
                           class="sidebar-nav-link"> <i class="fas fa-rocket"></i><span class="sidebar-text">Activities</span>
                        </a>
                    </li>
                    <?php endif; ?>

                </ul>
            </nav>
        </aside>
        <?php endif; ?>

        <main class="flex-1 overflow-y-auto">
            
            <div class="absolute top-4 right-4 z-50 max-w-sm w-full" style="top: 1rem; right: 1rem;">
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
            </div>
            <div class="<?php echo $is_logged_in ? 'p-4 sm:p-6 lg:p-8' : 'h-full'; ?>">