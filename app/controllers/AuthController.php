<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: AuthController
 * * Automatically generated via CLI.
 */
class AuthController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->database();        
        $this->call->model('User_Model');
        $this->call->library('session');
        $this->call->library('form_validation');
        $this->call->helper('url');
        $this->call->helper('security');
    }

  public function login() {
        // Access session via lava_instance() if needed, or rely on autoloaded property $this->session
         if (lava_instance()->session->has_userdata('user_id')) {
            redirect('/dashboard');
        }
        // Use lava_instance() for session flashdata
        $data['validation_errors'] = lava_instance()->session->flashdata('validation_errors');
        $data['error_message'] = lava_instance()->session->flashdata('error');
        $this->call->view('/auth/login', $data);
    }

    public function register() {
         // Access session via lava_instance() if needed, or rely on autoloaded property $this->session
         if (lava_instance()->session->has_userdata('user_id')) {
            redirect('/dashboard');
        }
        // Use lava_instance() for session flashdata
        $data['validation_errors'] = lava_instance()->session->flashdata('validation_errors');
        $data['error_message'] = lava_instance()->session->flashdata('error');
        $this->call->view('/auth/register', $data);
    }

    public function process_login() {
         // Access session via lava_instance() if needed, or rely on autoloaded property $this->session
        if (lava_instance()->session->has_userdata('user_id')) {
            redirect('/dashboard');
        }

        // Use lava_instance()->form_validation
        lava_instance()->form_validation
            ->name('email')
                ->required('Email is required.')
                ->valid_email('Please enter a valid email address.')
            ->name('password')
                ->required('Password is required.');

        // CSRF Check (Handled automatically by the framework if enabled)

        // Use lava_instance()->form_validation
        if (lava_instance()->form_validation->run() == FALSE) {
            // Use lava_instance() for session flashdata and form_validation errors
            lava_instance()->session->set_flashdata('validation_errors', lava_instance()->form_validation->get_errors());
            redirect('/auth/login');
        } else {
            $email = $this->io->post('email'); // Use $this->io for input
            $password = $this->io->post('password');

            // Use $this->User_Model (assuming it's autoloaded correctly)
            // The model will now be able to access lava_instance()->db
            $user = $this->User_Model->filter(['email' => $email])->get();

            if ($user && password_verify($password, $user['password'])) {
                $session_data = [
                    'user_id'    => $user['user_id'],
                    'first_name' => $user['first_name'],
                    'email'      => $user['email'],
                    'role'       => $user['role'],
                ];
                // Use lava_instance() for session operations
                lava_instance()->session->set_userdata($session_data);
                lava_instance()->session->set_flashdata('success', 'Login successful!');
                
                // --- THIS IS THE FIX ---
                // ALL users now go to /dashboard.
                // The DashboardController will handle showing the correct view (teacher, student, admin).
                redirect('/dashboard');
                // --- END FIX ---

            } else {
                // Use lava_instance() for session flashdata
                lava_instance()->session->set_flashdata('error', 'Invalid email or password.');
                redirect('/auth/login');
            }
        }
    }

    public function process_register() {
         // Access session via lava_instance() if needed, or rely on autoloaded property $this->session
         if (lava_instance()->session->has_userdata('user_id')) {
            redirect('/dashboard');
        }

        // CSRF Check (Handled automatically by the framework if enabled)

        // Use lava_instance()->form_validation
        lava_instance()->form_validation
            ->name('first_name')
                ->required('First name is required.')
                ->alpha_space('First name can only contain letters and spaces.')
                ->min_length(2, 'First name must be at least 2 characters.')
                ->max_length(50, 'First name cannot exceed 50 characters.')
            ->name('last_name')
                ->required('Last name is required.')
                ->alpha_space('Last name can only contain letters and spaces.')
                ->min_length(2, 'Last name must be at least 2 characters.')
                ->max_length(50, 'Last name cannot exceed 50 characters.')
            ->name('email')
                ->required('Email is required.')
                ->valid_email('Please enter a valid email address.')
            ->name('password')
                ->required('Password is required.')
                ->min_length(8, 'Password must be at least 8 characters long.')
                ->max_length(50, 'Password cannot exceed 50 characters.')
                // **Corrected Regex Pattern Here** - Added '.*' at the end
                ->custom_pattern('(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).*', 'Password must include uppercase, lowercase, number, and special character.')
            ->name('role')
                ->required('Role selection is required.')
                ->in_list('student,teacher,admin', 'Invalid role selected.');

        $email = $this->io->post('email'); // Use $this->io for input
        // Use $this->User_Model (assuming it's autoloaded correctly)
        // The model will now be able to access lava_instance()->db
        $user_exists = $this->User_Model->filter(['email' => $email])->get();
        // Use lava_instance()->form_validation
        $validation_ran = lava_instance()->form_validation->run();

        // Check if email already exists BEFORE running full validation rules
        if ($user_exists) {
           // Use lava_instance() for session and form_validation
           $errors = lava_instance()->session->flashdata('validation_errors') ?: (lava_instance()->form_validation->get_errors() ?: []);
           if (!in_array('This email address is already registered.', $errors)) {
                $errors[] = 'This email address is already registered.';
           }
           lava_instance()->session->set_flashdata('validation_errors', array_unique($errors));
           redirect('/auth/register');
           return; // Stop execution
        }


        if ($validation_ran == FALSE) {
             // Use lava_instance() for session and form_validation
             $errors = lava_instance()->session->flashdata('validation_errors') ?: [];
             $form_errors = lava_instance()->form_validation->get_errors() ?: [];
             $combined_errors = array_unique(array_merge($errors, $form_errors));
             lava_instance()->session->set_flashdata('validation_errors', $combined_errors);
             redirect('/auth/register');
        } else {
            // Validation passed and email is unique, proceed with registration

            $hashed_password = password_hash($this->io->post('password'), PASSWORD_DEFAULT); // Use $this->io

            $data = [
                'first_name' => $this->io->post('first_name'), // Use $this->io
                'last_name'  => $this->io->post('last_name'),  // Use $this->io
                'email'      => $email,
                'password'   => $hashed_password,
                'role'       => $this->io->post('role'),       // Use $this->io
            ];

            // Use $this->User_Model (assuming it's autoloaded correctly)
            // The model will now be able to access lava_instance()->db
            $user_id = $this->User_Model->insert($data);

            if ($user_id) {
                 // Use lava_instance() for session flashdata
                 lava_instance()->session->set_flashdata('success', 'Registration successful! Please login.');
                 redirect('/auth/login');
            } else {
                 // Use lava_instance() for session flashdata
                 lava_instance()->session->set_flashdata('error', 'Registration failed. Please try again.');
                 redirect('/auth/register');
            }
        }
    }

    public function logout() {
        // Use lava_instance() for session operations
        lava_instance()->session->sess_destroy();
        redirect('/auth/login');
    }
}