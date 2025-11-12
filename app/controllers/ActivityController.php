<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: ActivityController
 * * Handles the "All Activities" page for teachers.
 */
class ActivityController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->database();
        $this->call->model('Course_Model');
        $this->call->model('Assignment_Model'); // We use this model
        $this->call->library('session');
        $this->call->helper('url');
        
        $this->check_auth_teacher();
    }

    /**
     * Middleware to check if user is a teacher/admin
     */
    protected function check_auth_teacher() {
        if (!$this->session->has_userdata('user_id')) {
             $this->session->set_flashdata('error', 'Please login to access this section.');
            redirect('/auth/login');
            exit;
        }
        $user_role = $this->session->userdata('role');
        if ($user_role !== 'teacher' && $user_role !== 'admin') {
             $this->session->set_flashdata('error', 'You do not have permission.');
             redirect('/dashboard');
             exit;
        }
    }

    /**
     * Show the "All Activities" page with tabs
     */
    public function view_all() {
        $teacher_id = $this->session->userdata('user_id');
        
        // Use the new model function we will create
        $all_activities = $this->Assignment_Model->get_all_activities_for_teacher($teacher_id); 
        
        $upcoming = [];
        $past_due = [];
        
        foreach ($all_activities as $activity) {
            if (strtotime($activity['due_date']) > time()) {
                $upcoming[] = $activity;
            } else {
                $past_due[] = $activity;
            }
        }

        $data['activities_upcoming'] = $upcoming;
        $data['activities_past_due'] = $past_due;
        
        // This tab will just show the "Past Due" list
        $data['activities_completed'] = $past_due; 
        
        $data['page_title'] = 'All Activities';
        
        // We will create this new view file next
        $this->call->view('/activities/all_activities', $data);
    }
}
?>