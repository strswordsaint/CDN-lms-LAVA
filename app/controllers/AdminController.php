<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: AdminController
 * * Handles all site-wide administrative tasks.
 */
class AdminController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->call->database();
        $this->call->library('session');
        $this->call->helper('url');
        
        // Load models
        $this->call->model('User_Model');
        $this->call->model('Course_Model');
        $this->call->model('Enrollment_Model');
        
        // === ADD THESE MODELS ===
        $this->call->model('Assignment_Model');
        $this->call->model('Assignment_Attachment_Model');
        $this->call->model('Assignment_Submission_Model');
        $this->call->model('Resource_Model');
        $this->call->model('Site_Announcement_Model');
        
        // --- ADD THIS LIBRARY ---
        $this->call->library('form_validation'); 
        
        // Secure this entire controller
        $this->check_auth_admin();
    }

    /**
     * Middleware to check if user is an admin.
     */
    protected function check_auth_admin() {
        if (!$this->session->has_userdata('user_id')) {
             $this->session->set_flashdata('error', 'Please login to access this section.');
            redirect('/auth/login');
            exit;
        }
        $user_role = $this->session->userdata('role');
        if ($user_role !== 'admin') {
             $this->session->set_flashdata('error', 'You do not have permission to access this page.');
             redirect('/dashboard');
             exit;
        }
    }

    /**
     * Show the "Manage Users" page.
     */
    public function manage_users() {
        // Fetch users separated by role
        $data['admins'] = $this->User_Model->get_users_by_role('admin');
        $data['teachers'] = $this->User_Model->get_users_by_role('teacher');
        $data['students'] = $this->User_Model->get_users_by_role('student');
        
        // --- ADDED ---
        $data['pending_teachers'] = $this->User_Model->get_pending_teachers();
        
        $data['page_title'] = 'Manage Users';
        $data['success_message'] = $this->session->flashdata('success');
        $data['error_message'] = $this->session->flashdata('error');
        
        $this->call->view('/admin/manage_users', $data);
    }

    // --- NEW: shows the create user form ---
    public function create_user() {
        $data['page_title'] = 'Create New User';
        $data['validation_errors'] = $this->session->flashdata('validation_errors');
        $data['error_message'] = $this->session->flashdata('error');
        $this->call->view('/admin/create_user', $data);
    }

    // --- NEW: handles the create user form post ---
    public function store_user() {
        // Use the same validation rules as the public registration
        $this->form_validation
            ->name('first_name')
                ->required('First name is required.')
                ->alpha_space('First name can only contain letters and spaces.')
            ->name('last_name')
                ->required('Last name is required.')
                ->alpha_space('Last name can only contain letters and spaces.')
            ->name('email')
                ->required('Email is required.')
                ->valid_email('Please enter a valid email address.')
            ->name('password')
                ->required('Password is required.')
                ->min_length(8, 'Password must be at least 8 characters long.')
                ->custom_pattern('(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).*', 'Password must include uppercase, lowercase, number, and special character.')
            ->name('role')
                ->required('Role selection is required.')
                ->in_list('student,teacher,admin', 'Invalid role selected.');

        $email = $this->io->post('email');
        $user_exists = $this->User_Model->filter(['email' => $email])->get();
        $validation_ran = $this->form_validation->run();

        // 1. Check for existing email (same as AuthController)
        if ($user_exists) {
           $errors = $this->session->flashdata('validation_errors') ?: ($this->form_validation->get_errors() ?: []);
           if (!in_array('This email address is already registered.', $errors)) {
                $errors[] = 'This email address is already registered.';
           }
           $this->session->set_flashdata('validation_errors', array_unique($errors));
           redirect('/admin/user/create');
           return; 
        }

        // 2. Check for validation errors
        if ($validation_ran == FALSE) {
             $errors = $this->session->flashdata('validation_errors') ?: [];
             $form_errors = $this->form_validation->get_errors() ?: [];
             $combined_errors = array_unique(array_merge($errors, $form_errors));
             $this->session->set_flashdata('validation_errors', $combined_errors);
             redirect('/admin/user/create');
             
        } else {
            // 3. Validation passed, create the user
            $role = $this->io->post('role');
            $hashed_password = password_hash($this->io->post('password'), PASSWORD_DEFAULT); 

            // **IMPORTANT**: Set status based on role
            // Admins and Students are approved instantly.
            // Teachers go to the pending queue.
            $status = ($role === 'teacher') ? 'pending' : 'approved';

            $data = [
                'first_name' => $this->io->post('first_name'), 
                'last_name'  => $this->io->post('last_name'),  
                'email'      => $email,
                'password'   => $hashed_password,
                'role'       => $role,       
                'status'     => $status
            ];

            $user_id = $this->User_Model->insert($data);

            if ($user_id) {
                 $this->session->set_flashdata('success', 'User account created successfully.');
                 redirect('/admin/users');
            } else {
                 $this->session->set_flashdata('error', 'Registration failed. Please try again.');
                 redirect('/admin/user/create');
            }
        }
    }

    /**
     * Approve a pending teacher account.
     */
    public function approve_teacher($user_id) {
        // Simple update
        $updated = $this->User_Model->update($user_id, ['status' => 'approved']);
        
        if ($updated) {
            $this->session->set_flashdata('success', 'Teacher account approved successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to approve teacher.');
        }
        redirect('/admin/users');
    }

    /**
     * Delete a user.
     */
    public function delete_user($user_id) {
        if ($user_id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
            redirect('/admin/users');
            return;
        }
        
        if ($this->User_Model->delete($user_id)) {
            $this->session->set_flashdata('success', 'User successfully deleted.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user.');
        }
        redirect('/admin/users');
    }
    
    // --- MODIFIED: Show the "Edit User" page ---
    public function edit_user($user_id) {
        $user = $this->User_Model->find($user_id);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('/admin/users');
            return;
        }

        // Prevent admin from editing their own role (safety)
        if ($user_id == $this->session->userdata('user_id')) {
            $data['is_self'] = true;
        } else {
            $data['is_self'] = false;
        }
        
        $data['user'] = $user;
        $data['page_title'] = 'Edit User: ' . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
        $data['validation_errors'] = $this->session->flashdata('validation_errors');
        
        // We will create this new view file
        $this->call->view('/admin/edit_user', $data);
    }

    // --- NEW: Process the "Edit User" form submission ---
    public function update_user($user_id) {
        // Find user first
        $user = $this->User_Model->find($user_id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('/admin/users');
            return;
        }

        // Validation
        $this->form_validation
            ->name('first_name')
                ->required('First name is required.')
            ->name('last_name')
                ->required('Last name is required.')
            ->name('role')
                ->required('Role is required.')
                ->in_list('student,teacher,admin', 'Invalid role selected.');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('validation_errors', $this->form_validation->get_errors());
            redirect('/admin/user/edit/' . $user_id);
            return;
        }
        
        $data = [
            'first_name' => $this->io->post('first_name'),
            'last_name' => $this->io->post('last_name'),
            'role' => $this->io->post('role'),
        ];

        // Safety check: Prevent admin from changing their own role
        if ($user_id == $this->session->userdata('user_id')) {
            unset($data['role']); // Remove role from the update array
        }
        
        // Update the user
        if ($this->User_Model->update($user_id, $data)) {
            $this->session->set_flashdata('success', 'User updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'No changes were made or an error occurred.');
        }

        redirect('/admin/users');
    }

    // === NEW FUNCTIONS FOR ADMIN COURSE MANAGEMENT ===

    /**
     * Show the list of all courses in the system.
     */
    public function manage_courses() {
        $data['all_courses'] = $this->Course_Model->get_all_courses_with_teacher();
        $data['page_title'] = 'Manage All Courses';
        $data['success_message'] = $this->session->flashdata('success');
        $data['error_message'] = $this->session->flashdata('error');
        
        $this->call->view('/admin/manage_courses', $data); // New view
    }

    /**
     * Show details for a single course, including student list.
     */
    public function view_course($course_id) {
        $course = $this->Course_Model->find($course_id);
        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found.');
            redirect('/admin/courses');
            return;
        }
        
        $data['course'] = $course;
        $data['teacher'] = $this->User_Model->find($course['teacher_id']);
        $data['students'] = $this->Enrollment_Model->get_enrollments_for_course($course_id, 'approved');
        
        $data['page_title'] = 'View Course: ' . htmlspecialchars($course['title']);
        $data['success_message'] = $this->session->flashdata('success');
        $data['error_message'] = $this->session->flashdata('error');

        $this->call->view('/admin/view_course', $data); // New view
    }
    
    /**
     * Show the edit form for a course (bypassing ownership check).
     */
    public function edit_course($course_id) {
        $course = $this->Course_Model->find($course_id);
        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found.');
            redirect('/admin/courses');
            return;
        }

        $data['course'] = $course;
        $data['page_title'] = 'Admin Edit: ' . htmlspecialchars($course['title']);
        $data['validation_errors'] = $this->session->flashdata('validation_errors');
        
        // We can reuse the teacher's edit view!
        $this->call->view('/courses/EditCourse', $data);
    }

    /**
     * Process the update for a course (bypassing ownership check).
     */
    public function update_course($course_id) {
        // Load form validation
        $this->call->library('form_validation');

        // Run validation
        $this->form_validation
            ->name('title')
                ->required('Course title is required.')
            ->name('description')
                ->max_length(5000, 'Description is too long.');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('validation_errors', $this->form_validation->get_errors());
            redirect('/admin/courses/edit/' . $course_id);
        } else {
            $data = [
                'title'       => $this->io->post('title'),
                'description' => $this->io->post('description'),
            ];

            // Use the Course_Model's update function
            if ($this->Course_Model->update($course_id, $data)) {
                $this->session->set_flashdata('success', 'Course updated successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to update course.');
            }
            redirect('/admin/courses');
        }
    }

    /**
     * Admin remove a student from a course.
     */
    public function remove_student_from_course($enrollment_id) {
        $enrollment = $this->Enrollment_Model->find($enrollment_id);
        if (!$enrollment) {
             $this->session->set_flashdata('error', 'Enrollment record not found.');
             redirect($_SERVER['HTTP_REFERER'] ?? '/admin/courses');
             return;
        }

        // Use the safe delete function
        if ($this->Enrollment_Model->delete_enrollment($enrollment_id)) {
             $this->session->set_flashdata('success', 'Student removed from the course.');
        } else {
             $this->session->set_flashdata('error', 'Failed to remove the student.');
        }
        
        // Redirect back to the course view page
        redirect('/admin/courses/view/' . $enrollment['course_id']);
    }

    public function delete_course($course_id) {
        // 1. Find the course
        $course = $this->Course_Model->find($course_id);
        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found.');
            redirect('/admin/courses');
            return;
        }

        // 2. Get all related data
        $assignments = $this->Assignment_Model->get_assignments_by_course($course_id);
        $materials = $this->Resource_Model->get_for_course($course_id);

        // 3. Delete all files from server
        try {
            // Delete assignment files
            foreach ($assignments as $assignment) {
                $attachments = $this->Assignment_Attachment_Model->get_for_assignment($assignment['assignment_id']);
                foreach ($attachments as $file) {
                    if ($file['file_path'] && file_exists(ROOT_DIR . '/' . $file['file_path'])) {
                        @unlink(ROOT_DIR . '/' . $file['file_path']);
                    }
                }
                
                $submissions = $this->Assignment_Submission_Model->get_submissions_for_assignment($assignment['assignment_id']);
                foreach ($submissions as $sub) {
                    $sub_files = json_decode($sub['file_path'], true);
                    if (is_array($sub_files)) {
                        foreach($sub_files as $file) {
                             if (isset($file['file_path']) && file_exists(ROOT_DIR . '/' . $file['file_path'])) {
                                @unlink(ROOT_DIR . '/' . $file['file_path']);
                            }
                        }
                    }
                }
            }
            
            // Delete material files
            foreach ($materials as $material) {
                if ($material['file_path'] && file_exists(ROOT_DIR . '/' . $material['file_path'])) {
                    @unlink(ROOT_DIR . '/' . $material['file_path']);
                }
            }
        } catch (Exception $e) {
            // Log error if file deletion fails, but continue to delete from DB
        }

        // 4. Delete all records from database (child tables first)
        
        // === THIS IS THE FIX: Changed where_in() to in() ===
        $assignment_ids = array_column($assignments, 'assignment_id');
        if (!empty($assignment_ids)) {
             $this->db->table('assignment_attachments')->in('assignment_id', $assignment_ids)->delete();
             $this->db->table('assignment_submissions')->in('assignment_id', $assignment_ids)->delete();
        }
        // === END FIX ===

        $this->db->table('assignments')->where('course_id', $course_id)->delete();
        $this->db->table('course_materials')->where('course_id', $course_id)->delete();
        $this->db->table('enrollments')->where('course_id', $course_id)->delete();
        
        // 5. Finally, delete the course
        $this->Course_Model->delete($course_id);

        $this->session->set_flashdata('success', 'Course and all related data deleted successfully.');
        redirect('/admin/courses');
    }

    /**
     * Show the "Manage Site Announcements" page.
    */
    public function manage_site_announcements() {
        $data['announcements'] = $this->Site_Announcement_Model->get_all_with_admin();
        $data['page_title'] = 'Manage Site Announcements';
        $data['success_message'] = $this->session->flashdata('success');
        $data['error_message'] = $this->session->flashdata('error');

        $this->call->view('/admin/manage_announcements', $data);
    }

    /**
     * Store a new site-wide announcement.
     */
    public function store_site_announcement() {
        $admin_id = $this->session->userdata('user_id');

        $this->form_validation
            ->name('title')->required('Title is required.')
            ->name('content')->required('Content is required.');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Both title and content are required.');
        } else {
            $data = [
                'admin_id' => $admin_id,
                'title' => $this->io->post('title'),
                'content' => $this->io->post('content')
            ];

            if ($this->Site_Announcement_Model->insert($data)) {
                $this->session->set_flashdata('success', 'Site announcement posted successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to post announcement.');
            }
        }

        redirect('/admin/announcements');
    }

    /**
     * Delete a site-wide announcement.
     */
    public function delete_site_announcement($announcement_id) {
        // The model is simple, no extra security needed since only admins can be here.
        if ($this->Site_Announcement_Model->delete($announcement_id)) {
            $this->session->set_flashdata('success', 'Announcement deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete announcement.');
        }
        redirect('/admin/announcements');
    }

    /**
     * Show the new General Reports page.
     */
    public function general_reports() {
        $data['page_title'] = 'General Reports';

        // 1. Get site-wide totals
        $data['total_assignments'] = $this->Assignment_Model->count_all_assignments();
        $data['total_submissions'] = $this->Assignment_Submission_Model->count_all_submissions();
        $data['total_enrollments'] = $this->Enrollment_Model->count_all_enrollments('approved');
        $data['total_courses'] = $this->Course_Model->count_all_courses();

        // 2. Get master lists
        $data['master_course_list'] = $this->Course_Model->get_all_courses_with_teacher();
        $data['master_user_list'] = $this->User_Model->get_all_users(); // Re-use existing method

        $this->call->view('/admin/general_reports', $data);
    }

    //  ADMIN CREATE COURSE (With Teacher Pick)

    public function create_course() {
        // Get list of approved teachers for the dropdown
        $data['teachers'] = $this->User_Model->get_users_by_role('teacher');
        
        $data['page_title'] = 'Create Course & Appoint Teacher';
        $data['validation_errors'] = $this->session->flashdata('validation_errors');
        $data['error_message'] = $this->session->flashdata('error');
        
        $this->call->view('/admin/create_course', $data);
    }

    public function store_course() {
        $this->call->library('form_validation');
        
        $this->form_validation
            ->name('title')->required('Title is required.')
            ->name('teacher_id')->required('You must appoint a teacher.');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('validation_errors', $this->form_validation->get_errors());
            redirect('/admin/courses/create');
        } else {
            $teacher_id = $this->io->post('teacher_id');
            
            // Generate a unique enrollment code
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
            
            $data = [
                'title' => $this->io->post('title'),
                'description' => $this->io->post('description'),
                'teacher_id' => $teacher_id, // Assign the selected teacher
                'enrollment_code' => $code
            ];

            if ($this->Course_Model->insert($data)) {
                $this->session->set_flashdata('success', 'Course created and teacher appointed successfully.');
                redirect('/admin/courses');
            } else {
                $this->session->set_flashdata('error', 'Failed to create course.');
                redirect('/admin/courses/create');
            }
        }
    }

    //  ADMIN SUSPEND / REACTIVATE USER

    // Show the suspension form
    public function suspend_user_form($user_id) {
        $user = $this->User_Model->find($user_id);
        if (!$user || $user['role'] == 'admin') {
            $this->session->set_flashdata('error', 'Cannot suspend this user.');
            redirect('/admin/users');
        }

        $data['user'] = $user;
        $data['page_title'] = 'Suspend User: ' . $user['first_name'];
        $this->call->view('/admin/suspend_user', $data);
    }

    // Process the suspension
    public function process_suspension($user_id) {
        $reason = $this->io->post('reason');
        
        if (empty(trim($reason))) {
            $this->session->set_flashdata('error', 'A reason for suspension is required.');
            redirect('/admin/user/suspend/' . $user_id);
            return;
        }

        $update_data = [
            'status' => 'suspended',
            'suspension_reason' => $reason
        ];

        if ($this->User_Model->update($user_id, $update_data)) {
            $this->session->set_flashdata('success', 'User suspended successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to suspend user.');
        }
        redirect('/admin/users');
    }

    // Reactivate a suspended user
    public function reactivate_user($user_id) {
        $update_data = [
            'status' => 'approved',
            'suspension_reason' => NULL // Clear the reason
        ];

        if ($this->User_Model->update($user_id, $update_data)) {
            $this->session->set_flashdata('success', 'User account reactivated.');
        } else {
            $this->session->set_flashdata('error', 'Failed to reactivate user.');
        }
        redirect('/admin/users');
    }
    
}
?>