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
        $this->call->model('Course_Model');
        
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
     * Handles the POST request from the enrollment form.
     * Now inserts with 'pending' status.
     */
    public function enroll() {
        $enrollment_code = $this->io->post('enrollment_code');
        $student_id = $this->session->userdata('user_id');

        if (empty($enrollment_code)) {
            $this->session->set_flashdata('error', 'Please enter an enrollment code.');
            redirect('/dashboard');
            return;
        }

        $course = $this->Course_Model->filter(['enrollment_code' => $enrollment_code])->get();

        if (!$course) {
            $this->session->set_flashdata('error', 'Invalid enrollment code.');
            redirect('/dashboard');
            return;
        }
        
        // Use the new check: Is there already a pending or approved enrollment?
        $already_requested_or_enrolled = $this->Enrollment_Model->has_pending_or_approved_enrollment($student_id, $course['course_id']);

        if ($already_requested_or_enrolled) {
            $this->session->set_flashdata('error', 'You have already requested or are enrolled in this course.');
            redirect('/dashboard');
            return;
        }
        
        // Create the enrollment request with 'pending' status
        $data = [
            'student_id' => $student_id,
            'course_id'  => $course['course_id'],
            'status'     => 'pending' // Set status to pending
        ];
        
        if ($this->Enrollment_Model->insert($data)) {
            $this->session->set_flashdata('success', 'Enrollment requested for: ' . $course['title'] . '. Waiting for teacher approval.');
        } else {
            $this->session->set_flashdata('error', 'An error occurred submitting your request. Please try again.');
        }
        
        redirect('/dashboard');
    }

    /**
     * Display the student's enrolled (and pending) courses.
     * Corresponds to route: $router->get('/courses/my', 'StudentController::my_courses');
     */
     public function my_courses() {
         $student_id = $this->session->userdata('user_id');
         
         // Use the Enrollment_Model to get courses with status
         $data['courses'] = $this->Enrollment_Model->get_student_courses($student_id); 
         $data['page_title'] = 'My Courses';
         $data['success_message'] = $this->session->flashdata('success');
         $data['error_message'] = $this->session->flashdata('error');

         // Load the view from the new 'student' folder
         $this->call->view('student/my_courses', $data); 
     }

     /**
      * Display details of a single course IF enrollment is approved.
      * Corresponds to route: $router->get('/my-courses/{id}', 'StudentController::view_course');
      */
     public function view_course($course_id) {
         $student_id = $this->session->userdata('user_id');

         // Check if student has APPROVED enrollment for this course
         $enrollment = $this->Enrollment_Model->filter([
             'student_id' => $student_id,
             'course_id' => $course_id,
             'status' => 'approved'
         ])->get();

         if (!$enrollment) {
             $this->session->set_flashdata('error', 'You do not have approved access to this course.');
             redirect('/courses/my'); // Redirect to their course list
             return;
         }

         // If approved, get course details
         $course = $this->Course_Model->find($course_id);

         if (!$course) {
             $this->session->set_flashdata('error', 'Course not found.');
             redirect('/courses/my');
             return;
         }

         $data['course'] = $course;
         $data['page_title'] = 'Course: ' . htmlspecialchars($course['title']);
         
         // Placeholders for future features
         $data['assignments'] = []; 
         $data['quizzes'] = []; 
         $data['discussions'] = []; 
         $data['resources'] = []; 

         // Load the view from the new 'student' folder
         $this->call->view('/student/view_course', $data);
     }

    // --- Placeholder methods for future features ---
    public function browse_courses() { $this->call->view('errors/error_general', ['heading' => 'Not Implemented', 'message'=>'Browsing all courses is not yet implemented.']); }
    public function view_assignments($course_id) { $this->call->view('errors/error_general', ['heading' => 'Not Implemented', 'message'=>'Viewing assignments is not yet implemented.']);}
    public function view_assignment($assign_id) { $this->call->view('errors/error_general', ['heading' => 'Not Implemented', 'message'=>'Viewing single assignment is not yet implemented.']);}
    public function submit_assignment($assign_id) { $this->call->view('errors/error_general', ['heading' => 'Not Implemented', 'message'=>'Submitting assignment is not yet implemented.']);}
    public function view_quizzes($course_id) { $this->call->view('errors/error_general', ['heading' => 'Not Implemented', 'message'=>'Viewing quizzes is not yet implemented.']);}
    public function take_quiz($quiz_id) { $this->call->view('errors/error_general', ['heading' => 'Not Implemented', 'message'=>'Taking quiz is not yet implemented.']);}
    public function submit_quiz($quiz_id) { $this->call->view('errors/error_general', ['heading' => 'Not Implemented', 'message'=>'Submitting quiz is not yet implemented.']);}
    public function view_quiz_results($quiz_id) { $this->call->view('errors/error_general', ['heading' => 'Not Implemented', 'message'=>'Viewing quiz results is not yet implemented.']);}
    public function view_discussions($course_id) { $this->call->view('errors/error_general', ['heading' => 'Not Implemented', 'message'=>'Viewing discussions is not yet implemented.']);}
    public function view_discussion($disc_id) { $this->call->view('errors/error_general', ['heading' => 'Not Implemented', 'message'=>'Viewing single discussion is not yet implemented.']);}
    public function post_reply($disc_id) { $this->call->view('errors/error_general', ['heading' => 'Not Implemented', 'message'=>'Posting reply is not yet implemented.']);}
    public function view_grades() { $this->call->view('errors/error_general', ['heading' => 'Not Implemented', 'message'=>'Viewing grades is not yet implemented.']);}

}
?>
