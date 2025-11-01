<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AssignmentController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->database();
        $this->call->model('Assignment_Model');
        $this->call->model('Course_Model'); // For checking course ownership
        // --- ADDED: Load Submission Model ---
        $this->call->model('Assignment_Submission_Model'); 
        // --- END ADD ---
        $this->call->library('session');
        $this->call->library('form_validation');
        $this->call->library('Upload'); // Load Upload library
        $this->call->helper('url');

        // Protect all teacher-facing assignment methods
        $this->check_auth();
    }

    /**
     * Middleware to check if user is a teacher/admin
     */
    protected function check_auth($role_required = ['teacher', 'admin']) {
        if (!$this->session->has_userdata('user_id')) {
            $this->session->set_flashdata('error', 'Please login to access this section.');
            redirect('/auth/login');
            exit;
        }
        $user_role = $this->session->userdata('role');
        if (!in_array($user_role, $role_required)) {
             $this->session->set_flashdata('error', 'You do not have permission to access this section.');
             redirect('/dashboard');
             exit;
        }
    }

    /**
     * Show the form to create a new assignment for a course.
     */
    public function create($course_id) {
        $teacher_id = $this->session->userdata('user_id');
        $course = $this->Course_Model->find_course($course_id, $teacher_id);

        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found or you do not have permission.');
            redirect('/courses');
            return;
        }

        $data['course'] = $course;
        $data['page_title'] = 'Create New Assignment';
        $data['validation_errors'] = $this->session->flashdata('validation_errors');
        $data['error_message'] = $this->session->flashdata('error'); // For file upload errors

        $this->call->view('assignments/create', $data);
    }

    /**
     * Store the new assignment (with file upload) in the database.
     */
    public function store($course_id) {
        $teacher_id = $this->session->userdata('user_id');
        $course = $this->Course_Model->find_course($course_id, $teacher_id);

        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found or permission denied.');
            redirect('/courses');
            return;
        }

        $this->form_validation
            ->name('title')->required('Title is required.')
            ->name('points')->required('Points are required.')->numeric('Points must be a number.')
            ->name('due_date')->required('Due date is required.');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('validation_errors', $this->form_validation->get_errors());
            redirect('/courses/' . $course_id . '/assignments/create');
            return;
        }

        $data = [
            'course_id'   => $course_id,
            'title'       => $this->io->post('title'),
            'description' => $this->io->post('description'),
            'points'      => $this->io->post('points'),
            'due_date'    => $this->io->post('due_date'),
            'attachment_path' => null // Default to null
        ];

        // Handle File Upload
        $file_uploaded = false;
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
            
            $this->Upload->file = $_FILES['attachment'];
            $upload_dir = 'uploads/assignments/materials';
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }

            $this->Upload->set_dir($upload_dir);
            $this->Upload->allowed_extensions(array('pdf', 'docx', 'doc', 'pptx', 'txt', 'jpg', 'png', 'zip', 'mp4', 'mov', 'wmv'));
            $this->Upload->allowed_mimes(array('application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'text/plain', 'image/jpeg', 'image/png', 'application/zip', 'video/mp4', 'video/quicktime', 'video/x-ms-wmv'));
            $this->Upload->encrypt_name(); 

            if ($this->Upload->do_upload()) {
                $filename = $this->Upload->get_filename();
                $data['attachment_path'] = $upload_dir . '/' . $filename;
                $file_uploaded = true;
            } else {
                $this->session->set_flashdata('error', $this->Upload->get_errors()[0]);
                redirect('/courses/' . $course_id . '/assignments/create');
                return;
            }
        }

        // Insert into database
        if ($this->Assignment_Model->insert($data)) {
            $this->session->set_flashdata('success', 'Assignment created successfully.');
            redirect('/courses/show/' . $course_id);
        } else {
            if($file_uploaded && isset($data['attachment_path'])) { @unlink($data['attachment_path']); }
            $this->session->set_flashdata('error', 'Failed to create assignment in database.');
            redirect('/courses/' . $course_id . '/assignments/create');
        }
    }
    
    // --- NEW METHOD: View Submissions ---
    /**
     * Display the list of submissions for a specific assignment.
     * Corresponds to route: GET /assignments/{assign_id}/submissions
     */
    public function view_submissions($assignment_id) {
        $teacher_id = $this->session->userdata('user_id');

        // 1. Get assignment details AND verify teacher ownership via course
        // Note: Using the find_with_course_check method we added to Assignment_Model
        $assignment = $this->Assignment_Model->find_with_course_check($assignment_id, $teacher_id); 
        if (!$assignment) {
            $this->session->set_flashdata('error', 'Assignment not found or you do not have permission.');
            redirect('/courses'); // Redirect back to course list
            return;
        }

        // 2. Get all submissions for this assignment 
        // (Assignment_Submission_Model was loaded in constructor)
        $data['submissions'] = $this->Assignment_Submission_Model->get_submissions_for_assignment($assignment_id);

        $data['assignment'] = $assignment;
        $data['page_title'] = 'Submissions for: ' . htmlspecialchars($assignment['title']);
        $data['course_id'] = $assignment['course_id']; // Pass course ID for back button

        // Load the view file (which already exists)
        $this->call->view('/assignments/submissions', $data);
    }
    // --- END NEW METHOD ---


    // --- NEW METHOD: Show Grade Form ---
     /**
     * Show the form/page for grading a specific submission.
     * Corresponds to route: GET /submissions/{sub_id}/grade
     */
    public function show_grade_form($submission_id) {
        $teacher_id = $this->session->userdata('user_id');

        // 1. Get submission details (includes student, assignment, course info)
        $submission = $this->Assignment_Submission_Model->get_submission_details($submission_id);

        if (!$submission) {
            $this->session->set_flashdata('error', 'Submission not found.');
            redirect('/courses'); 
            return;
        }

        // 2. Security Check: Does the current teacher own the course?
        $course = $this->Course_Model->find_course($submission['course_id'], $teacher_id);
        if (!$course) {
             $this->session->set_flashdata('error', 'You do not have permission to grade this submission.');
             redirect('/courses');
             return;
        }

        $data['submission'] = $submission;
        $data['page_title'] = 'Grade Submission: ' . htmlspecialchars($submission['assignment_title']);
        $data['validation_errors'] = $this->session->flashdata('validation_errors');

        // Load the view (which already exists)
        $this->call->view('/assignments/grade_submission', $data);
    }
    // --- END NEW METHOD ---


    // --- NEW METHOD: Process Grade ---
    /**
     * Process the submitted grade and feedback.
     * Corresponds to route: POST /submissions/{sub_id}/grade
     */
    public function process_grade($submission_id) {
        $teacher_id = $this->session->userdata('user_id');

        // 1. Get submission details (for security check and context)
        $submission = $this->Assignment_Submission_Model->get_submission_details($submission_id);
        if (!$submission) {
            $this->session->set_flashdata('error', 'Submission not found.');
            redirect('/courses');
            return;
        }

        // 2. Security Check: Teacher ownership
        $course = $this->Course_Model->find_course($submission['course_id'], $teacher_id);
        if (!$course) {
             $this->session->set_flashdata('error', 'Permission denied.');
             redirect('/courses');
             return;
        }

        // 3. Validation
        $this->form_validation
            ->name('grade')
                ->required('Grade is required.')
                ->numeric('Grade must be a number.')
                ->less_than_equal($submission['assignment_points'], 'Grade cannot exceed max points (' . $submission['assignment_points'] . ').')
                ->greater_than_equal(0, 'Grade cannot be negative.'); 

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('validation_errors', $this->form_validation->get_errors());
            redirect('/submissions/' . $submission_id . '/grade'); 
            return;
        }

        // 4. Update the database
        $grade = $this->io->post('grade');
        $feedback = $this->io->post('feedback');

        if ($this->Assignment_Submission_Model->update_grade($submission_id, $grade, $feedback)) {
             $this->session->set_flashdata('success', 'Grade and feedback saved successfully.');
        } else {
             $this->session->set_flashdata('error', 'Failed to save grade. Please try again.');
        }

        // Redirect back to the list of submissions for that assignment
        redirect('/assignments/' . $submission['assignment_id'] . '/submissions');
    }
    // --- END NEW METHOD ---


    // --- PLACEHOLDER for Edit (Add this later) ---
    public function edit($assignment_id) {
        // TODO: Implement logic to show edit form
        $this->session->set_flashdata('error', 'Edit assignment feature not yet implemented.');
        // Find assignment details first
        $assignment = $this->Assignment_Model->find($assignment_id); 
        if ($assignment) {
             redirect('/courses/show/' . $assignment['course_id']); // Redirect back to course page
        } else {
             redirect('/courses'); // Redirect to course list if assignment not found
        }
    }
    // --- END PLACEHOLDER ---

}
?>