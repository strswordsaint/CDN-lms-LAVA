<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: ProfileController
 * * Handles editing user details and changing password for all user roles.
 */
class ProfileController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->call->database();
        $this->call->library('session');
        $this->call->helper('url');
        $this->call->model('User_Model');
        $this->call->library('form_validation');
        
        // Secure this entire controller
        $this->check_auth();
    }

    /**
     * Middleware to check if user is logged in (any role).
     */
    protected function check_auth() {
        if (!$this->session->has_userdata('user_id')) {
             $this->session->set_flashdata('error', 'Please login to access this section.');
            redirect('/auth/login');
            exit;
        }
    }

    /**
     * MODIFIED: Show the new profile index page
     */
    public function index() {
        // Prepare data for the view
        $data['first_name'] = $this->session->userdata('first_name');
        $data['last_name'] = $this->session->userdata('last_name');
        $data['email'] = $this->session->userdata('email');
        
        // Pass flash messages to the view
        $data['success_message'] = $this->session->flashdata('success');
        $data['error_message'] = $this->session->flashdata('error');
        $data['validation_errors'] = $this->session->flashdata('validation_errors');

        // Load the new view
        $this->call->view('/profile/index', $data);
    }

    /**
     * Handle the form submission for updating user details.
     */
    public function update_details() {
        $user_id = $this->session->userdata('user_id');

        $this->form_validation
            ->name('first_name')
                ->required('First name is required.')
                ->alpha_space('First name can only contain letters and spaces.')
            ->name('last_name')
                ->required('Last name is required.')
                ->alpha_space('Last name can only contain letters and spaces.');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('validation_errors', $this->form_validation->get_errors());
            // Set a flag to re-open the correct tab
            $this->session->set_flashdata('open_tab', 'details');
        } else {
            $data = [
                'first_name' => $this->io->post('first_name'),
                'last_name' => $this->io->post('last_name')
            ];
            
            if ($this->User_Model->update($user_id, $data)) {
                // Update the session as well
                $this->session->set_userdata('first_name', $data['first_name']);
                $this->session->set_userdata('last_name', $data['last_name']);
                $this->session->set_flashdata('success', 'Profile details updated successfully.');
                $this->session->set_flashdata('open_tab', 'details'); // Re-open on success
            } else {
                $this->session->set_flashdata('error', 'Failed to update details. Please try again.');
                $this->session->set_flashdata('open_tab', 'details');
            }
        }
        
        // MODIFIED: Redirect to the new profile page
        redirect('/profile');
    }

    /**
     * Handle the form submission for changing the password.
     */
    public function update_password() {
        $user_id = $this->session->userdata('user_id');

        $this->form_validation
            ->name('current_password')
                ->required('Current password is required.')
            ->name('new_password')
                ->required('New password is required.')
                ->min_length(8, 'New password must be at least 8 characters long.')
                ->custom_pattern('(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).*', 'Password must include uppercase, lowercase, number, and special character.')
            ->name('confirm_password')
                ->required('Please confirm your new password.')
                ->matches('new_password', 'New passwords do not match.');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('validation_errors', $this->form_validation->get_errors());
            $this->session->set_flashdata('open_tab', 'password'); // Open 'password' tab
            redirect('/profile');
            return;
        }

        // Validation passed, now check the current password
        $user = $this->User_Model->find($user_id);
        $current_password = $this->io->post('current_password');

        if ($user && password_verify($current_password, $user['password'])) {
            // Current password is correct. Hash and update the new one.
            $new_password_hashed = password_hash($this->io->post('new_password'), PASSWORD_DEFAULT);
            
            if ($this->User_Model->update($user_id, ['password' => $new_password_hashed])) {
                $this->session->set_flashdata('success', 'Password changed successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update password. Please try again.');
            }
        } else {
            // Incorrect current password
            $this->session->set_flashdata('validation_errors', ['current_password' => 'Your current password is not correct.']);
        }
        
        $this->session->set_flashdata('open_tab', 'password'); // Re-open 'password' tab
        // MODIFIED: Redirect to the new profile page
        redirect('/profile');
    }
}
?>