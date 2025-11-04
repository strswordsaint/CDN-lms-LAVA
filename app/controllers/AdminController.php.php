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
        $data['all_users'] = $this->User_Model->get_all_users();
        $data['page_title'] = 'Manage Users';
        $data['success_message'] = $this->session->flashdata('success');
        $data['error_message'] = $this->session->flashdata('error');
        
        $this->call->view('/admin/manage_users', $data);
    }

    /**
     * Delete a user.
     */
    public function delete_user($user_id) {
        // Prevent admin from deleting themselves
        if ($user_id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
            redirect('/admin/users');
            return;
        }

        // TODO: Add logic to delete/re-assign user's courses or submissions
        // For now, we'll just delete the user record.
        
        if ($this->User_Model->delete($user_id)) {
            $this->session->set_flashdata('success', 'User successfully deleted.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user.');
        }
        redirect('/admin/users');
    }
    
    // You can add edit_user and update_user functions here later
    public function edit_user($user_id) {
        // Placeholder for "Edit User" page
        $this->session->set_flashdata('error', 'Edit functionality is not yet implemented.');
        redirect('/admin/users');
    }
}
?>