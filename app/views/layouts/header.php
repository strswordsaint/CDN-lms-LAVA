<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS - Colegio de Naujan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
      // NEW: Updated & Cohesive Color Theme
      tailwind.config = {
        theme: {
          extend: {
            // NEW: Set 'Inter' as the default sans-serif font
            fontFamily: {
              sans: ['Inter', 'sans-serif'],
            },
            colors: {
              // Your primary brand color palette
              'primary': {
                '50': '#ecf5ff',
                '100': '#dbeafe',
                '200': '#bfdbfe',
                '300': '#93c5fd',
                '400': '#60a5fa',
                '500': '#3b82f6', // A slightly brighter blue for links
                '600': '#2563eb',
                '700': '#1d4ed8',
                '800': '#0056A0', // Your original 'cdn-blue'
                '900': '#1e3a8a',
                '950': '#172554',
              },
              // Neutral grays for text, borders, and backgrounds
              'neutral': {
                '50': '#f8fafc',
                '100': '#f1f5f9', // Lightest page background
                '200': '#e2e8f0', // Borders
                '300': '#cbd5e1',
                '400': '#94a3b8', // Muted text
                '500': '#64748b',
                '600': '#475569',
                '700': '#334155', // Body text
                '800': '#1e293b',
                '900': '#0f172a', // Headings
              },
              // Accent colors for success, error, warning
              'success': {
                '50': '#f0fdf4',
                '100': '#dcfce7',
                '500': '#22c55e',
                '600': '#16a34a', // Added for buttons
                '700': '#15803d',
              },
              'error': {
                '50': '#fef2f2',
                '100': '#fee2e2',
                '500': '#ef4444',
                '600': '#dc2626', // Added for buttons
                '700': '#b91c1c',
              },
              'warning': {
                '50': '#fffbeb',
                '100': '#fef3c7',
                '500': '#f59e0b',
                '600': '#d97706', // Added for buttons
                '700': '#b45309',
              },
            }
          }
        }
      }
    </script>
    <style>
        /* Flash message styling (Updated to new colors) */
        .notice {
            padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 1rem;
            font-size: 0.875rem; border-width: 1px; border-style: solid; display: none;
        }
        .notice-success { background-color: #f0fdf4; color: #15803d; border-color: #a3e6b6; }
        .notice-error { background-color: #fef2f2; color: #b91c1c; border-color: #fca5a5; }
        
        /* === CSS STICKY FOOTER FIX (START) === */
        html, body { height: 100%; overflow: hidden; }
        body { 
            background-color: #f1f5f9; /* neutral-100 */
            color: #334155; /* neutral-700 */
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* This is the critical line */
        }
        
        .main-layout { 
            flex: 1; /* This makes the content area grow */
            min-height: 0; /* Fix for flexbox overflow */
        }
        /* === CSS STICKY FOOTER FIX (END) === */
        
        /* Sidebar Styles (Updated) */
        .sidebar-nav-link {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            width: 100%; height: 4.5rem; /* 72px */
            padding: 0.5rem 0.25rem;
            color: #334155; /* neutral-700 */
            font-size: 0.75rem; /* 12px */
            font-weight: 500;
            border-radius: 6px;
            transition: background-color 0.2s, color 0.2s;
            white-space: nowrap; text-align: center;
            position: relative; 
        }
        .sidebar-nav-link:hover { 
            background-color: #e2e8f0; /* neutral-200 */
            text-decoration: none; 
        }
        .sidebar-nav-link.active {
            background-color: #dbeafe; /* primary-100 */
            color: #1d4ed8; /* primary-700 */
            font-weight: 600;
        }
        .sidebar-nav-link i { 
            margin-bottom: 0.375rem; /* 6px */
            font-size: 1.5rem; /* 24px */
        }
        
        .notification-dot {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            width: 0.5rem;
            height: 0.5rem;
            background-color: #ef4444; /* error-500 */
            border-radius: 9999px;
            border: 2px solid #ffffff; /* Matches sidebar bg */
        }
        .sidebar-nav-link.active .notification-dot {
            border-color: #dbeafe; /* primary-100 */
        }

        /* === NEW: Form Styles === */
        .form-input, .form-textarea, .form-select {
            @apply block w-full px-3 py-2 border border-neutral-300 rounded-md shadow-sm;
            @apply placeholder-neutral-400 text-neutral-900;
            @apply focus:outline-none focus:ring-primary-500 focus:border-primary-500;
        }
        .form-input-file {
            @apply block w-full text-sm text-neutral-500;
            @apply file:mr-4 file:py-2 file:px-4;
            @apply file:rounded-md file:border-0 file:text-sm file:font-semibold;
            @apply file:bg-primary-50 file:text-primary-700;
            @apply file:hover:bg-primary-100 file:cursor-pointer;
        }

        /* === NEW: Button Styles === */
        .btn {
            @apply inline-flex items-center justify-center px-4 py-2 border border-transparent;
            @apply text-sm font-medium rounded-md shadow-sm;
            @apply focus:outline-none focus:ring-2 focus:ring-offset-2;
            @apply transition-colors duration-200;
        }
        .btn-primary {
            @apply text-white bg-primary-700 hover:bg-primary-800 focus:ring-primary-500;
        }
        .btn-secondary {
            @apply text-neutral-700 bg-white border-neutral-300 hover:bg-neutral-50 focus:ring-primary-500;
        }
        .btn-success {
            @apply text-white bg-success-600 hover:bg-success-700 focus:ring-success-500;
        }
        .btn-danger {
            @apply text-white bg-error-600 hover:bg-error-700 focus:ring-error-500;
        }
        .btn-danger-sm {
            @apply btn bg-error-50 text-error-600 hover:bg-error-100 focus:ring-error-500;
            @apply px-2 py-1 text-xs;
        }

        /* === NEW: Card Style === */
        .card {
            @apply bg-white shadow-md rounded-lg border border-neutral-200;
        }
        
        /* === NEW PROFILE DROPDOWN STYLES === */
        .profile-dropdown {
            @apply absolute z-40 right-0 top-12 mt-2 w-80;
            @apply bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5;
        }
        .profile-tab {
            @apply px-3 py-2 text-sm font-medium text-neutral-500;
            @apply border-b-2 border-transparent;
            @apply hover:text-neutral-700 hover:border-neutral-300;
        }
        .profile-tab.active {
            @apply text-primary-600 border-primary-600;
        }
    </style>
</head>
<body class="antialiased">
    <?php
        // Load session and helpers
        $LAVA = lava_instance();
        if (!isset($LAVA->session)) $LAVA->call->library('session');
        if (!function_exists('site_url')) $LAVA->call->helper('url');
        if (!function_exists('segment')) $LAVA->call->helper('url');
        
        $is_logged_in = $LAVA->session->has_userdata('user_id');
        $user_role = $LAVA->session->userdata('role');
        $user_name = $LAVA->session->userdata('first_name');

        // Active link logic
        $current_segment = segment(2);
        
        // --- Teacher Logic ---
        $is_courses_section = in_array($current_segment, ['courses', 'submissions']);
        $is_assignments_section = ($current_segment == 'assignments');
        $is_activities_section = ($current_segment == 'activities'); // <-- NEW
        
        // --- Student Logic ---
        $is_my_courses_active = (segment(3) == 'my' || $current_segment == 'my-courses');
        $is_assignments_active = ($current_segment == 'my-assignments' || $current_segment == 'assignment');
        $is_activities_active = ($current_segment == 'my-activities'); // <-- NEW
        
        // === NEW: Notification Data Fetching ===
        $pending_assignment_count = 0;
        $ungraded_count = 0;
        $pending_enrollment_count = 0;
        $admin_user_management_active = false; // Initialize
        $pending_activity_count = 0; // <-- NEW

        if ($is_logged_in) {
            $user_id = $LAVA->session->userdata('user_id');
            
            if ($user_role == 'student') {
                $LAVA->call->model('Assignment_Model');
                // count_pending_for_student will be updated to include both
                $pending_assignment_count = $LAVA->Assignment_Model->count_pending_for_student($user_id);
                // We'll separate this later if needed, for now one dot is fine.
            } 
            else if ($user_role == 'teacher') {
                $LAVA->call->model('Assignment_Submission_Model');
                $LAVA->call->model('Enrollment_Model');
                // count_ungraded_for_teacher will be updated to include both
                $ungraded_count = $LAVA->Assignment_Submission_Model->count_ungraded_for_teacher($user_id);
                $pending_enrollment_count = $LAVA->Enrollment_Model->count_pending_for_teacher($user_id);
            }
            else if ($user_role == 'admin') {
                $admin_user_management_active = (segment(2) == 'admin' && segment(3) == 'users');
            }
        }
        // === END: Notification Data Fetching ===
    ?>

    <nav class="bg-primary-800 shadow-md z-20 relative" style="height: 52px;">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 h-full flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <a href="<?php echo site_url('/'); ?>" class="flex items-center space-x-2 text-white hover:text-primary-200 transition duration-200">
                    <img src="<?php echo base_url(); ?>public/images/Logo2.png" alt="Colegio de Naujan Logo" class="h-8 w-8 rounded-full border border-white shadow-sm">
                    <span class="text-lg font-semibold tracking-wide">Colegio de Naujan LMS</span>
                </a>
            </div>
            
            <div class="flex items-center space-x-4">
                <?php if ($is_logged_in): ?>
                    
                    <a href="<?php echo site_url('/profile'); ?>" 
                       class="flex items-center justify-center w-8 h-8 bg-primary-700 rounded-full text-white font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-primary-800 focus:ring-white"
                       title="My Profile">
                        <span class="sr-only">Open user menu</span>
                        <?php
                            // Use data from session
                            $first_initial = lava_instance()->session->userdata('first_name')[0] ?? 'U';
                            $last_initial = lava_instance()->session->userdata('last_name')[0] ?? 'S';
                            echo htmlspecialchars(strtoupper($first_initial . $last_initial));
                        ?>
                    </a>
                    <?php else: ?>
                    <a href="<?php echo site_url('auth/login'); ?>" class="text-sm text-white hover:text-primary-200 transition duration-200">Login</a>
                    <a href="<?php echo site_url('auth/register'); ?>" class="bg-white text-primary-800 text-sm font-medium py-2 px-3 rounded-md hover:bg-primary-100 transition duration-200">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="flex main-layout">
        
        <?php if ($is_logged_in): // ONLY show sidebar if logged in ?>
        <aside id="app-sidebar" class="w-20 bg-white border-r border-neutral-200 p-3 flex-shrink-0 overflow-y-auto z-10">
            <nav>
                <ul class="space-y-1">
                    
                    <?php if ($user_role == 'admin'): ?>
                        <?php
                            // === NEW ADMIN-SPECIFIC ACTIVE LOGIC ===
                            $is_admin_dashboard_active = ($current_segment == 'dashboard');
                            $is_admin_courses_active = ($current_segment == 'admin' && segment(3) == 'courses');
                            $is_admin_users_active = ($current_segment == 'admin' && segment(3) == 'users');
                        ?>
                        <li>
                            <a href="<?php echo site_url('/dashboard'); ?>" 
                               title="Dashboard"
                               class="sidebar-nav-link <?php echo $is_admin_dashboard_active ? 'active' : ''; ?>">
                               <i class="fas fa-home"></i>
                               <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('/admin/courses'); ?>" 
                               title="Manage Courses"
                               class="sidebar-nav-link <?php echo $is_admin_courses_active ? 'active' : ''; ?>">
                               <i class="fas fa-book-open"></i>
                               <span>Courses</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('/admin/users'); ?>" 
                               title="Manage Users"
                               class="sidebar-nav-link <?php echo $is_admin_users_active ? 'active' : ''; ?>">
                               <i class="fas fa-user-cog"></i>
                               <span>Users</span>
                            </a>
                        </li>
                        
                    <?php elseif ($user_role == 'teacher'): ?>
                        <li>
                            <a href="<?php echo site_url('/dashboard'); ?>" 
                               title="Dashboard"
                               class="sidebar-nav-link <?php echo ($current_segment == 'dashboard') ? 'active' : ''; ?>">
                               <i class="fas fa-home"></i>
                               <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('/calendar'); ?>" 
                               title="Calendar"
                               class="sidebar-nav-link <?php echo ($current_segment == 'calendar') ? 'active' : ''; ?>">
                               <i class="fas fa-calendar-alt"></i>
                               <span>Calendar</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('/courses'); ?>" 
                               title="Manage Courses"
                               class="sidebar-nav-link <?php echo $is_courses_section ? 'active' : ''; ?>">
                               
                               <?php if ($pending_enrollment_count > 0): ?>
                                    <span class="notification-dot"></span>
                               <?php endif; ?>
                               
                               <i class="fas fa-book-open"></i>
                               <span>Courses</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('/activities/all'); ?>" 
                               title="Activities"
                               class="sidebar-nav-link <?php echo $is_activities_section ? 'active' : ''; ?>">
                               
                               <?php if ($ungraded_count > 0): // This dot will now represent ungraded activities AND assignments ?>
                                    <span class="notification-dot"></span>
                               <?php endif; ?>
                               
                               <i class="fas fa-gamepad"></i> <span>Activities</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('/assignments/all'); ?>" 
                               title="Assignments"
                               class="sidebar-nav-link <?php echo $is_assignments_section ? 'active' : ''; ?>">
                               
                               <?php if ($ungraded_count > 0): ?>
                                    <span class="notification-dot"></span>
                               <?php endif; ?>
                               
                               <i class="fas fa-tasks"></i>
                               <span>Assignments</span>
                            </a>
                        </li>

                    <?php elseif ($user_role == 'student'): ?>
                        <li>
                            <a href="<?php echo site_url('/dashboard'); ?>" 
                               title="Dashboard"
                               class="sidebar-nav-link <?php echo ($current_segment == 'dashboard') ? 'active' : ''; ?>">
                               <i class="fas fa-home"></i>
                               <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('/my-calendar'); ?>" 
                               title="Calendar"
                               class="sidebar-nav-link <?php echo ($current_segment == 'my-calendar') ? 'active' : ''; ?>">
                               <i class="fas fa-calendar-alt"></i>
                               <span>Calendar</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('/courses/my'); ?>" 
                               title="My Courses"
                               class="sidebar-nav-link <?php echo $is_my_courses_active ? 'active' : ''; ?>">
                               <i class="fas fa-chalkboard"></i>
                               <span>My Courses</span>
                            </a>
                        </li>
                         <li>
                            <a href="<?php echo site_url('/my-activities'); ?>" 
                               title="View All Activities"
                               class="sidebar-nav-link <?php echo $is_activities_active ? 'active' : ''; ?>">
                               
                               <?php if ($pending_assignment_count > 0): // This dot represents both ?>
                                    <span class="notification-dot"></span>
                               <?php endif; ?>
                               
                               <i class="fas fa-gamepad"></i> <span>Activities</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('/my-assignments'); ?>" 
                               title="View All Assignments"
                               class="sidebar-nav-link <?php echo $is_assignments_active ? 'active' : ''; ?>">
                               
                               <?php if ($pending_assignment_count > 0): ?>
                                    <span class="notification-dot"></span>
                               <?php endif; ?>
                               
                               <i class="fas fa-tasks"></i>
                               <span>Assignments</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav> <?php // ?>
        </aside> <?php // ?>
        <?php endif; ?> <?php // ?>

        <?php // ?>
        <main class="flex-1 overflow-y-auto">
            <div class="<?php echo $is_logged_in ? 'p-4 sm:p-6 lg:p-8' : 'h-full'; ?>">