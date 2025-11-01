<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->database();
        $this->call->library('session');
        $this->call->helper('url');
        
        // Load the models we will need
        $this->call->model('Enrollment_Model');
        $this->call->model('Course_Model'); // For finding the course
        
        // --- NEW: Load the Assignment Model ---
        $this->call->model('Assignment_Model'); 
        
        // Protect this entire controller
        $this->check_auth();
    }

    /**
     * Auth middleware: Ensure user is logged in and is a 'student'
     */
    protected function check_auth() {
        if (!$this->session->has_userdata('user_id')) {
            $this->session->set_flashdata('error', 'Please login to access that page.');
            redirect('/auth/login');
            exit;
        }
        
        if ($this->session->userdata('role') !== 'student') {
            $this->session->set_flashdata('error', 'You do not have permission to access that page.');
            redirect('/dashboard'); // Redirect to their own (e.g., teacher) dashboard
            exit;
        }
    }
    
    /**
     * Display the student's list of enrolled/pending courses.
     * Corresponds to route: $router->get('/courses/my', 'StudentController::my_courses');
     */
    public function my_courses() {
        $student_id = $this->session->userdata('user_id');
        
        // Use the model to get all courses (pending and approved)
        $data['my_courses'] = $this->Enrollment_Model->get_student_courses($student_id);
        $data['page_title'] = 'My Courses';
        
        // Create this view file next if it doesn't exist
        $this->call->view('/student/my_courses', $data); 
    }

    /**
     * Handles the POST request from the enrollment form.
     * Corresponds to route: $router->post('/courses/enroll', 'StudentController::enroll');
     */
    public function enroll() {
        $enrollment_code = $this->io->post('enrollment_code');
        $student_id = $this->session->userdata('user_id');

        if (empty($enrollment_code)) {
            $this->session->set_flashdata('error', 'Please enter an enrollment code.');
            redirect('/dashboard');
            return;
        }

        // 1. Find the course by its enrollment code
        $course = $this->Course_Model->filter(['enrollment_code' => $enrollment_code])->get();

        if (!$course) {
            $this->session->set_flashdata('error', 'Invalid enrollment code.');
            redirect('/dashboard');
            return;
        }
        
        // 2. Check if student has already requested or is enrolled
        $is_enrolled = $this->Enrollment_Model->has_pending_or_approved_enrollment($student_id, $course['course_id']);

        if ($is_enrolled) {
            $this->session->set_flashdata('error', 'You have already requested or are enrolled in that course.');
            redirect('/dashboard');
            return;
        }
        
        // 3. Enroll the student (with 'pending' status)
        $data = [
            'student_id' => $student_id,
            'course_id'  => $course['course_id'],
            'status'     => 'pending' // Set status to pending
        ];
        
        if ($this->Enrollment_Model->insert($data)) {
            $this->session->set_flashdata('success', 'Enrollment request sent for: ' . $course['title']);
        } else {
            $this->session->set_flashdata('error', 'An error occurred during enrollment. Please try again.');
        }
        
        redirect('/dashboard');
    }

    /**
     * View a single course (assignments, quizzes, etc.)
     * Corresponds to route: $router->get('/my-courses/{id}', 'StudentController::view_course');
     */
    public function view_course($course_id) {
        $student_id = $this->session->userdata('user_id');
        
        // 1. Check if student is *approved* for this course
        $is_approved = $this->Enrollment_Model->filter([
            'student_id' => $student_id,
            'course_id'  => $course_id,
            'status'     => 'approved'
        ])->get();

        if (!$is_approved) {
            $this->session->set_flashdata('error', 'You do not have access to this course.');
            redirect('/dashboard');
            return;
        }

        // 2. Get course details
        $data['course'] = $this->Course_Model->find($course_id);
        
        // --- NEW: Get all assignments for this course ---
        $data['assignments'] = $this->Assignment_Model
                                    ->filter(['course_id' => $course_id])
                                    ->order_by('due_date', 'ASC')
                                    ->get_all();
        
        $data['page_title'] = $data['course']['title'];
        
        // --- NEW: We will check submission status later ---
        // For now, we just pass the assignments to the view
        
        $this->call->view('/student/view_course', $data);
    }
    
    public function view_assignment($assignment_id) {
        $student_id = $this->session->userdata('user_id');

        // 1. Get assignment details
        $assignment = $this->Assignment_Model->find($assignment_id); // Find using primary key
        if (!$assignment) {
            $this->session->set_flashdata('error', 'Assignment not found.');
            redirect('/dashboard'); // Or maybe back to the course page?
            return;
        }

        // 2. Security Check: Is the student enrolled and approved for this assignment's course?
        $is_approved = $this->Enrollment_Model->filter([
            'student_id' => $student_id,
            'course_id'  => $assignment['course_id'],
            'status'     => 'approved'
        ])->get();

        if (!$is_approved) {
            $this->session->set_flashdata('error', 'You do not have access to this assignment.');
            redirect('/dashboard');
            return;
        }
        
        // 3. Get course details (for navigation/context)
        $data['course'] = $this->Course_Model->find($assignment['course_id']);
        
        // 4. Check if the student has already submitted
        $this->call->model('Assignment_Submission_Model'); // Load the submission model
        $data['submission'] = $this->Assignment_Submission_Model->check_existing_submission($student_id, $assignment_id);

        $data['assignment'] = $assignment;
        $data['page_title'] = 'Submit: ' . htmlspecialchars($assignment['title']);
        $data['error_message'] = $this->session->flashdata('error'); // For upload errors

        // We will create this view file next
        $this->call->view('/student/submit_assignment', $data);
    }

    /**
     * Handle the file upload for an assignment submission.
     * Corresponds to route: POST /assignment/{assign_id}/submit
     */
   public function submit_assignment($assignment_id) {
        $student_id = $this->session->userdata('user_id');

        // 1. Get assignment details (needed for validation and saving)
        $assignment = $this->Assignment_Model->find($assignment_id);
        if (!$assignment) {
            $this->session->set_flashdata('error', 'Assignment not found.');
            redirect('/dashboard');
            return;
        }

        // 2. Security Check: Is student approved for this course?
        $is_approved = $this->Enrollment_Model->filter([
            'student_id' => $student_id,
            'course_id'  => $assignment['course_id'],
            'status'     => 'approved'
        ])->get();
        if (!$is_approved) {
            $this->session->set_flashdata('error', 'You do not have permission to submit to this assignment.');
            redirect('/dashboard');
            return;
        }
        
        // 3. Check if already submitted
        $this->call->model('Assignment_Submission_Model');
        $existing_submission = $this->Assignment_Submission_Model->check_existing_submission($student_id, $assignment_id);
        if ($existing_submission) {
             $this->session->set_flashdata('error', 'You have already submitted this assignment.');
             redirect('/assignment/' . $assignment_id); // Redirect back to the assignment page
             return;
        }

        // 4. Handle File Upload
        if (!isset($_FILES['submission_file']) || $_FILES['submission_file']['error'] != UPLOAD_ERR_OK) {
            $this->session->set_flashdata('error', 'File upload failed or no file selected. Please try again.');
            redirect('/assignment/' . $assignment_id);
            return;
        }

        // Use the Upload library (make sure it's loaded, e.g., in BaseController or here)
        $this->call->library('Upload', $_FILES['submission_file']); // Pass file info

        // Define upload directory (relative to index.php)
        $upload_dir = 'uploads/assignments/submissions/' . $assignment['course_id'] . '/' . $assignment_id;
        
        // Create the directory if it doesn't exist (course_id/assignment_id structure)
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $this->Upload->set_dir($upload_dir);
        // Allow common document/image/archive types
        $this->Upload->allowed_extensions(array('pdf', 'docx', 'doc', 'txt', 'jpg', 'png', 'zip', 'ppt', 'pptx')); 
        $this->Upload->allowed_mimes(array(
            'application/pdf', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // docx
            'application/msword', // doc
            'text/plain', 
            'image/jpeg', 
            'image/png', 
            'application/zip', 
            'application/vnd.ms-powerpoint', // ppt
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' //pptx
        )); 
        
        // --- THIS IS THE FIX ---
        // Let the library handle unique filenames
        $this->Upload->encrypt_name(); 
        
        // Remove the manual filename generation and the call to set_filename()
        // $original_name = pathinfo($_FILES['submission_file']['name'], PATHINFO_FILENAME);
        // $extension = pathinfo($_FILES['submission_file']['name'], PATHINFO_EXTENSION);
        // $new_filename = $student_id . '_' . time() . '_' . preg_replace("/[^a-zA-Z0-9_.]/", "_", $original_name) . '.' . $extension;
        // $this->Upload->set_filename($new_filename); // REMOVED THIS LINE
        // --- END FIX ---


        if ($this->Upload->do_upload()) {
            $uploaded_filename = $this->Upload->get_filename();
            $file_path = $upload_dir . '/' . $uploaded_filename;

            // 5. Save submission record to database
            $submission_data = [
                'assignment_id' => $assignment_id,
                'student_id'    => $student_id,
                'file_path'     => $file_path
            ];

            if ($this->Assignment_Submission_Model->insert($submission_data)) {
                $this->session->set_flashdata('success', 'Assignment submitted successfully!');
                // Redirect back to the main course page after successful submission
                redirect('/my-courses/' . $assignment['course_id']); 
            } else {
                // Database insert failed, delete uploaded file
                @unlink($file_path); 
                $this->session->set_flashdata('error', 'Database error: Could not save submission.');
                redirect('/assignment/' . $assignment_id);
            }
        } else {
            // Upload failed
            $this->session->set_flashdata('error', 'File upload failed: ' . $this->Upload->get_errors()[0]);
            redirect('/assignment/' . $assignment_id);
        }
    }
    public function view_all_assignments() {
        $student_id = $this->session->userdata('user_id');
        
        // 1. Get all assignments using the new model function
        $data['assignments'] = $this->Assignment_Model->get_all_assignments_for_student($student_id);
        
        $data['page_title'] = 'All Assignments';
        
        // 2. We will create this new view file next
        $this->call->view('/student/all_assignments', $data);
    }
    
}
?>