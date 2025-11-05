<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: DashboardController
 * * Automatically generated via CLI.
 */
class DashboardController extends Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->call->database();
        $this->call->library('session');
        $this->call->helper('url');
        
        // Load all models we'll need for the dashboards
        $this->call->model('Enrollment_Model');
        $this->call->model('Course_Model');
        $this->call->model('Assignment_Model');
        $this->call->model('Assignment_Submission_Model');

        $this->check_auth();
    }

    protected function check_auth() {
        if (!$this->session->has_userdata('user_id')) {
             $this->session->set_flashdata('error', 'Please login to access the dashboard.');
            redirect('/auth/login');
            exit;
        }
    }

    public function index() {
        $role = $this->session->userdata('role');
        $first_name = $this->session->userdata('first_name');

        $data = [
            'first_name' => $first_name,
            'success_message' => $this->session->flashdata('success'),
            'error_message' => $this->session->flashdata('error'),
        ];

        switch ($role) {
            case 'admin':
                // === THIS IS THE FIX ===
                // Load the models needed for stats
                $this->call->model('User_Model');
                $this->call->model('Course_Model');

                // Fetch the real stats from the models
                $data['stats'] = [
                    'total_users' => $this->User_Model->count_all_users(),
                    'total_students' => $this->User_Model->count_by_role('student'),
                    'total_teachers' => $this->User_Model->count_by_role('teacher'),
                    'total_courses' => $this->Course_Model->count_all_courses()
                ];
                // === END FIX ===

                $this->call->view('/dashboards/admin', $data);
                break;
            
            case 'teacher':
                // (Teacher dashboard logic remains the same)
                $teacher_id = $this->session->userdata('user_id');
                $courses_with_stats = $this->Course_Model->get_courses_with_stats_by_teacher($teacher_id);
                $ungraded_count = $this->Assignment_Submission_Model->count_ungraded_for_teacher($teacher_id);
                $recent_assignments = $this->Assignment_Model->get_recent_assignments_for_teacher($teacher_id, 5);
                $total_students = array_sum(array_column($courses_with_stats, 'student_count'));

                $data['courses_list'] = $courses_with_stats;
                $data['stats'] = [
                    'course_count' => count($courses_with_stats),
                    'student_count' => $total_students,
                    'ungraded_count' => $ungraded_count
                ];
                $data['recent_assignments'] = $recent_assignments;
                
                $this->call->view('/dashboards/teacher', $data);
                break;

            case 'student':
            default:
                // (Student dashboard logic remains the same)
                $student_id = $this->session->userdata('user_id');
                $courses = $this->Enrollment_Model->get_student_courses($student_id, 'approved');
                $pending_count = $this->Assignment_Model->count_pending_for_student($student_id);
                $upcoming = $this->Assignment_Model->get_upcoming_for_student($student_id, 5);

                $data['stats'] = [
                    'joined_courses' => count($courses),
                    'pending_assignments' => $pending_count,
                ];
                $data['upcoming_assignments'] = $upcoming;

                $this->call->view('/dashboards/student', $data);
                break;
        }
    }
}
?>