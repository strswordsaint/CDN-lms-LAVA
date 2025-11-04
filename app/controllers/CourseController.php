<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: CourseController
 * * Automatically generated via CLI.
 */
class CourseController extends Controller {
    public function __construct()
    {
        parent::__construct();
         $this->call->database(); // Ensure DB is available

        // Load the models
        $this->call->model('Course_Model');
        $this->call->model('Enrollment_Model');
        $this->call->model('Assignment_Model');
        $this->call->model('Resource_Model');
        $this->call->model('Assignment_Attachment_Model');

        // Middleware: Check if user is logged in for all course actions
        $this->check_auth();
    }

     // Middleware to check authentication and role
    protected function check_auth($role_required = ['teacher', 'admin']) {
        $session = lava_instance()->session; // Use instance for clarity
        if (!$session->has_userdata('user_id')) {
            $session->set_flashdata('error', 'Please login to access this section.');
            redirect('/auth/login');
            exit; // Important: Stop execution after redirect
        }
        $user_role = $session->userdata('role');
        if (!in_array($user_role, $role_required)) {
             $session->set_flashdata('error', 'You do not have permission to access this section.');
             redirect('/dashboard'); // Redirect to their own dashboard
             exit; // Important: Stop execution after redirect
        }
    }

    /**
     * Display a list of courses created by the logged-in teacher. (READ)
     */
    public function index() {
        $teacher_id = lava_instance()->session->userdata('user_id');
        $data['courses'] = $this->Course_Model->get_courses_with_stats_by_teacher($teacher_id);
        $data['page_title'] = 'My Courses';
        $data['success_message'] = lava_instance()->session->flashdata('success');
        $data['error_message'] = lava_instance()->session->flashdata('error');

        // Use the correct view file name
        $this->call->view('/courses/CourseList', $data);
    }

     /**
     * Show details and content management area for a specific course. (READ specific)
     */
    public function show($course_id) {
        $teacher_id = lava_instance()->session->userdata('user_id');
        $course = $this->Course_Model->find_course($course_id, $teacher_id); 

        if (!$course) {
            lava_instance()->session->set_flashdata('error', 'Course not found or permission denied.');
            redirect('/courses');
            return; 
        }

        $data['course'] = $course;
        
        // --- NEW LOGIC FOR ATTACHMENTS ---
        // 1. Get all assignments
        $assignments = $this->Assignment_Model->get_assignments_by_course($course_id);
        
        // 2. Loop through and get attachments for each one
        foreach ($assignments as $key => $assignment) {
            $assignments[$key]['attachments'] = $this->Assignment_Attachment_Model->get_for_assignment($assignment['assignment_id']);
        }
        $data['assignments'] = $assignments;
        // --- END NEW LOGIC ---
        
        $data['materials'] = $this->Resource_Model->get_for_course($course_id);
        $data['page_title'] = 'Manage Course: ' . htmlspecialchars($course['title']);
        $this->call->view('/courses/ShowCourse', $data);
    }

    /**
     * Show the form to create a new course. (CREATE form)
     */
    public function create() {
        $data['page_title'] = 'Create New Course';
        $data['validation_errors'] = lava_instance()->session->flashdata('validation_errors');
        // Use the correct view file name
        $this->call->view('/courses/CreateCourse', $data);
    }

    /**
     * Process the form submission for creating a new course. (CREATE action)
     */
    public function store() {
        $form_validation = lava_instance()->form_validation; // Use instance for clarity
        $session = lava_instance()->session;
        $io = lava_instance()->io;

        // Run validation
        $form_validation
            ->name('title')
                ->required('Course title is required.')
                ->min_length(3, 'Title must be at least 3 characters.')
                ->max_length(255, 'Title cannot exceed 255 characters.')
            ->name('description')
                ->max_length(5000, 'Description is too long.');

        if ($form_validation->run() == FALSE) {
            $session->set_flashdata('validation_errors', $form_validation->get_errors());
            redirect('/courses/create'); // Redirect back to create form
        } else {
            // Validation passed, prepare data
            $teacher_id = $session->userdata('user_id');

            // Build the data array (This is the original, correct way)
            $data = [
                'title'       => $io->post('title'),
                'description' => $io->post('description'),
                'teacher_id'  => $teacher_id,
                'enrollment_code' => $this->_generate_enrollment_code()
            ];

            // Insert using the model
            $course_id = $this->Course_Model->insert($data);

            if ($course_id) {
                $session->set_flashdata('success', 'Course created successfully!');
                redirect('/courses'); // Redirect to the course list
            } else {
                $session->set_flashdata('error', 'Failed to create course. Please try again.');
                redirect('/courses/create');
            }
        }
    }

    /**
     * Show the form to edit an existing course. (UPDATE form)
     */
    public function edit($course_id) {
        $teacher_id = lava_instance()->session->userdata('user_id');
        // Find the course AND ensure the current teacher owns it
        $course = $this->Course_Model->find_course($course_id, $teacher_id);

        if (!$course) {
             lava_instance()->session->set_flashdata('error', 'Course not found or you do not have permission to edit it.');
             redirect('/courses');
             return; // Stop execution
        }

        $data['course'] = $course;
        $data['page_title'] = 'Edit Course: ' . htmlspecialchars($course['title']);
        $data['validation_errors'] = lava_instance()->session->flashdata('validation_errors'); // For potential redirects
        $this->call->view('/courses/EditCourse', $data); // Load the edit view
    }

    /**
     * Process the form submission for updating an existing course. (UPDATE action)
     */
    public function update($course_id) {
        $teacher_id = lava_instance()->session->userdata('user_id');
        // Ensure the teacher owns the course before attempting update
        $existing_course = $this->Course_Model->find_course($course_id, $teacher_id);

        if (!$existing_course) {
            lava_instance()->session->set_flashdata('error', 'Course not found or you do not have permission to update it.');
            redirect('/courses');
            return; // Stop execution
        }

        $form_validation = lava_instance()->form_validation;
        $session = lava_instance()->session;
        $io = lava_instance()->io;

         // Run validation (same rules as create)
        $form_validation
            ->name('title')
                ->required('Course title is required.')
                ->min_length(3, 'Title must be at least 3 characters.')
                ->max_length(255, 'Title cannot exceed 255 characters.')
            ->name('description')
                ->max_length(5000, 'Description is too long.');

        if ($form_validation->run() == FALSE) {
            $session->set_flashdata('validation_errors', $form_validation->get_errors());
            // Redirect back to edit form, passing the course ID
            redirect('/courses/edit/' . $course_id);
        } else {
            // Validation passed, prepare data for update
            $data = [
                'title'       => $io->post('title'),
                'description' => $io->post('description'),
                // teacher_id doesn't change
            ];

            // Update using the model (update method is inherited)
            $updated = $this->Course_Model->update_course($course_id, $data);

            if ($updated) {
                $session->set_flashdata('success', 'Course updated successfully!');
                redirect('/courses'); // Redirect to the course list
            } else {
                $session->set_flashdata('success', 'Course details saved (no changes detected).');
                 redirect('/courses');
            }
        }
    }

     /**
     * Process the deletion of a course. (DELETE action)
     */
    public function delete($course_id) {
        $teacher_id = lava_instance()->session->userdata('user_id');
        // Ensure the teacher owns the course before attempting delete
        $course = $this->Course_Model->find_course($course_id, $teacher_id);

        if (!$course) {
            lava_instance()->session->set_flashdata('error', 'Course not found or you do not have permission to delete it.');
            redirect('/courses');
            return; // Stop execution
        }

        // Perform deletion using the model's delete (or soft_delete) method
        // Check config if soft delete is enabled
        $soft_delete_enabled = config_item('soft_delete');
        $deleted = $this->Course_Model->delete_course($course_id, $soft_delete_enabled);


        if ($deleted) {
             lava_instance()->session->set_flashdata('success', 'Course deleted successfully!');
        } else {
            lava_instance()->session->set_flashdata('error', 'Failed to delete course. Please try again.');
        }
        redirect('/courses'); // Redirect back to the course list
    }

    public function manage_enrollments($course_id) {
        $teacher_id = $this->session->userdata('user_id');
        $course = $this->Course_Model->find_course($course_id, $teacher_id); // Check ownership

        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found or access denied.');
            redirect('/courses');
            return;
        }

        // $this->call->model('Enrollment_Model'); // Already loaded

        $data['course'] = $course;
        $data['page_title'] = 'Manage Enrollments for: ' . htmlspecialchars($course['title']);
        $data['pending_enrollments'] = $this->Enrollment_Model->get_enrollments_for_course($course_id, 'pending');
        $data['approved_enrollments'] = $this->Enrollment_Model->get_enrollments_for_course($course_id, 'approved');
        $data['success_message'] = $this->session->flashdata('success');
        $data['error_message'] = $this->session->flashdata('error');

        $this->call->view('/courses/manage_enrollments', $data);
    }

    /**
     * Approve a pending enrollment request.
     */
    public function approve_enrollment($enrollment_id) {
        $teacher_id = $this->session->userdata('user_id');
        // $this->call->model('Enrollment_Model'); // Already loaded
        
        $enrollment = $this->Enrollment_Model->find($enrollment_id);
        if (!$enrollment) {
             $this->session->set_flashdata('error', 'Enrollment request not found.');
             redirect($_SERVER['HTTP_REFERER'] ?? '/courses');
             return;
        }

        $course = $this->Course_Model->find_course($enrollment['course_id'], $teacher_id);
        if (!$course) {
             $this->session->set_flashdata('error', 'You do not have permission to manage this course.');
             redirect('/courses');
             return;
        }
        
        if ($this->Enrollment_Model->update_enrollment_status($enrollment_id, 'approved')) {
            $this->session->set_flashdata('success', 'Enrollment approved successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to approve enrollment.');
        }
        
        redirect('/courses/' . $enrollment['course_id'] . '/enrollments');
    }

    /**
     * Reject a pending enrollment request.
     */
    public function reject_enrollment($enrollment_id) {
        $teacher_id = $this->session->userdata('user_id');
        // $this->call->model('Enrollment_Model'); // Already loaded

        $enrollment = $this->Enrollment_Model->find($enrollment_id);
        if (!$enrollment) {
             $this->session->set_flashdata('error', 'Enrollment request not found.');
             redirect($_SERVER['HTTP_REFERER'] ?? '/courses');
             return;
        }

        $course = $this->Course_Model->find_course($enrollment['course_id'], $teacher_id);
        if (!$course) {
             $this->session->set_flashdata('error', 'You do not have permission to manage this course.');
             redirect('/courses');
             return;
        }

        if ($this->Enrollment_Model->update_enrollment_status($enrollment_id, 'rejected')) {
             $this->session->set_flashdata('success', 'Enrollment rejected.');
        } else {
             $this->session->set_flashdata('error', 'Failed to reject enrollment.');
        }
        
        redirect('/courses/' . $enrollment['course_id'] . '/enrollments');
    }
    /**
     * Remove an approved student from the course.
     */
    public function remove_enrollment($enrollment_id) {
        $teacher_id = $this->session->userdata('user_id');
        
        $enrollment = $this->Enrollment_Model->find($enrollment_id);
        if (!$enrollment) {
             $this->session->set_flashdata('error', 'Enrollment record not found.');
             redirect($_SERVER['HTTP_REFERER'] ?? '/courses');
             return;
        }

        // Security check: Make sure the teacher owns this course
        $course = $this->Course_Model->find_course($enrollment['course_id'], $teacher_id);
        if (!$course) {
             $this->session->set_flashdata('error', 'You do not have permission to manage this course.');
             redirect('/courses');
             return;
        }

        // Use the safe delete function we created in Enrollment_Model
        if ($this->Enrollment_Model->delete_enrollment($enrollment_id)) {
             $this->session->set_flashdata('success', 'Student removed from the course.');
        } else {
             $this->session->set_flashdata('error', 'Failed to remove the student.');
        }
        
        redirect('/courses/' . $enrollment['course_id'] . '/enrollments');
    }
    /**
    * Private helper function to generate a unique enrollment code.
    */
    private function _generate_enrollment_code($length = 6) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
            $exists = $this->Course_Model->filter(['enrollment_code' => $code])->get();
        } while ($exists);
        return $code;
    }

    
}
?>