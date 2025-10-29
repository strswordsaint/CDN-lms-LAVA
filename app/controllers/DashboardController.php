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
        
        // --- ADD THIS LINE ---
        // Load the model so the view can access it
        $this->call->model('Enrollment_Model');
        // --- END ADD ---

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
                // Use direct call->view()
                $this->call->view('/dashboards/admin', $data);
                break;
            case 'teacher':
                 // Use direct call->view()
                $this->call->view('/dashboards/teacher', $data);
                break;
            case 'student':
            default:
                 // The Enrollment_Model is now loaded,
                 // so the view 'dashboards/student' will work.
                $this->call->view('/dashboards/student', $data);
                break;
        }
    }
}