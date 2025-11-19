<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: PageController
 * * Handles static pages like 'About Us'.
 */
class PageController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->library('session');
        $this->call->helper('url');
        $this->call->database();

        if ($this->session->has_userdata('user_id')) {
            $role = $this->session->userdata('role');
            if ($role == 'student') {
                $this->call->model('Assignment_Model');
            } else if ($role == 'teacher') {
                $this->call->model('Assignment_Submission_Model');
                $this->call->model('Enrollment_Model');
            }
        }

        // Secure this controller
        $this->check_auth();
    }

    /**
     * Middleware to check if user is logged in.
     */
    protected function check_auth() {
        if (!$this->session->has_userdata('user_id')) {
             $this->session->set_flashdata('error', 'Please login to access this section.');
            redirect('/auth/login');
            exit;
        }
    }

    /**
     * Show the "About Us" page.
     */
    public function about() {
        $data['page_title'] = 'About Us';
        $this->call->view('/layouts/about', $data); // We will create this view next
    }
}
?>