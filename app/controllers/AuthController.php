<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

// Make sure Composer's autoload is included
require_once ROOT_DIR . 'vendor/autoload.php';

/**
 * Controller: AuthController
 * * Automatically generated via CLI.
 */
class AuthController extends Controller {
    private $googleClient; // Add property to hold the client

    public function __construct()
    {
        parent::__construct();
        $this->call->database();        
        $this->call->model('User_Model');
        $this->call->library('session');
        $this->call->library('form_validation');
        $this->call->helper('url');
        $this->call->helper('security');

        // --- Google Client Initialization ---
        $this->googleClient = new Google_Client();
        $this->googleClient->setClientId(config_item('google_client_id')); // Use config_item
        $this->googleClient->setClientSecret(config_item('google_client_secret')); // Use config_item
        $this->googleClient->setRedirectUri(config_item('google_redirect_uri')); // Use config_item
        $this->googleClient->addScope("email");
        $this->googleClient->addScope("profile");
        // --- End Google Client Initialization ---
    }

    public function login() {
        if (lava_instance()->session->has_userdata('user_id')) {
           redirect('/dashboard');
        }
        $data['validation_errors'] = lava_instance()->session->flashdata('validation_errors');
        $data['error_message'] = lava_instance()->session->flashdata('error');
        
        // Add Google Login URL to data passed to the view
        $data['googleLoginUrl'] = $this->googleClient->createAuthUrl();
        
        $this->call->view('/auth/login', $data);
    }

    public function register() {
         if (lava_instance()->session->has_userdata('user_id')) {
            redirect('/dashboard');
        }
        $data['validation_errors'] = lava_instance()->session->flashdata('validation_errors');
        $data['error_message'] = lava_instance()->session->flashdata('error');
        $this->call->view('/auth/register', $data);
    }

    public function process_login() {
        if (lava_instance()->session->has_userdata('user_id')) {
            redirect('/dashboard');
        }

        lava_instance()->form_validation
            ->name('email')
                ->required('Email is required.')
                ->valid_email('Please enter a valid email address.')
            ->name('password')
                ->required('Password is required.');

        if (lava_instance()->form_validation->run() == FALSE) {
            lava_instance()->session->set_flashdata('validation_errors', lava_instance()->form_validation->get_errors());
            redirect('/auth/login');
        } else {
            $email = $this->io->post('email'); 
            $password = $this->io->post('password');

            $user = $this->User_Model->filter(['email' => $email])->get();

            if ($user && password_verify($password, $user['password'])) {
                $session_data = [
                    'user_id'    => $user['user_id'],
                    'first_name' => $user['first_name'],
                    'email'      => $user['email'],
                    'role'       => $user['role'],
                ];
                lava_instance()->session->set_userdata($session_data);
                lava_instance()->session->set_flashdata('success', 'Login successful!');
                
                redirect('/dashboard');

            } else {
                lava_instance()->session->set_flashdata('error', 'Invalid email or password.');
                redirect('/auth/login');
            }
        }
    }

    public function process_register() {
         if (lava_instance()->session->has_userdata('user_id')) {
            redirect('/dashboard');
        }

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
                ->custom_pattern('(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).*', 'Password must include uppercase, lowercase, number, and special character.')
            ->name('role')
                ->required('Role selection is required.')
                ->in_list('student,teacher,admin', 'Invalid role selected.');

        $email = $this->io->post('email'); 
        $user_exists = $this->User_Model->filter(['email' => $email])->get();
        $validation_ran = lava_instance()->form_validation->run();

        if ($user_exists) {
           $errors = lava_instance()->session->flashdata('validation_errors') ?: (lava_instance()->form_validation->get_errors() ?: []);
           if (!in_array('This email address is already registered.', $errors)) {
                $errors[] = 'This email address is already registered.';
           }
           lava_instance()->session->set_flashdata('validation_errors', array_unique($errors));
           redirect('/auth/register');
           return; 
        }


        if ($validation_ran == FALSE) {
             $errors = lava_instance()->session->flashdata('validation_errors') ?: [];
             $form_errors = lava_instance()->form_validation->get_errors() ?: [];
             $combined_errors = array_unique(array_merge($errors, $form_errors));
             lava_instance()->session->set_flashdata('validation_errors', $combined_errors);
             redirect('/auth/register');
        } else {
            $hashed_password = password_hash($this->io->post('password'), PASSWORD_DEFAULT); 

            $data = [
                'first_name' => $this->io->post('first_name'), 
                'last_name'  => $this->io->post('last_name'),  
                'email'      => $email,
                'password'   => $hashed_password,
                'role'       => $this->io->post('role'),       
            ];

            $user_id = $this->User_Model->insert($data);

            if ($user_id) {
                 lava_instance()->session->set_flashdata('success', 'Registration successful! Please login.');
                 redirect('/auth/login');
            } else {
                 lava_instance()->session->set_flashdata('error', 'Registration failed. Please try again.');
                 redirect('/auth/register');
            }
        }
    }

    public function logout() {
        lava_instance()->session->sess_destroy();
        redirect('/auth/login');
    }

    /**
     * Initiates the Google Login flow by redirecting the user.
     */
    public function google_login() {
        if ($this->session->has_userdata('user_id')) {
            redirect('/dashboard');
            exit;
        }
        $authUrl = $this->googleClient->createAuthUrl();
        header('Location: ' . $authUrl);
        exit;
    }

    /**
     * Handles the callback from Google after user authorization.
     */
    public function google_callback() {
        if ($this->session->has_userdata('user_id')) {
            redirect('/dashboard');
            exit;
        }

        if (isset($_GET['code'])) {
            $token = $this->googleClient->fetchAccessTokenWithAuthCode($_GET['code']);

            if(isset($token['error'])) {
                $this->session->set_flashdata('error', 'Google Sign-In failed: ' . $token['error_description']);
                redirect('/auth/login');
                exit;
            }

            $this->googleClient->setAccessToken($token['access_token']);

            $google_oauth = new Google_Service_Oauth2($this->googleClient);
            $google_account_info = $google_oauth->userinfo->get();
            $email =  $google_account_info->email;
            $google_id = $google_account_info->id;
            
            // --- User Handling Logic ---
            $user = $this->User_Model->filter(['email' => $email])->get();

            if ($user) {
                // User exists, log them in
                $session_data = [
                    'user_id'    => $user['user_id'], 
                    'first_name' => $user['first_name'],
                    'email'      => $user['email'],
                    'role'       => $user['role'],
                ];
                $this->session->set_userdata($session_data);
                $this->session->set_flashdata('success', 'Logged in successfully via Google!');
                redirect('/dashboard');
                exit;

            } else {
                // === THIS IS THE MODIFIED PART ===
                // User DOES NOT exist - Store Google info temporarily and ask for role
                $google_data = [
                     'first_name' => $google_account_info->givenName ?? '',
                     'last_name' => $google_account_info->familyName ?? '',
                     'email' => $email,
                     'google_id' => $google_id
                 ];
                 // Store this data in the session's flashdata
                 $this->session->set_flashdata('google_signup_data', $google_data);

                 // Redirect to a new page where they choose their role
                 redirect('/auth/choose_role');
                 exit;
                 // === END OF MODIFIED PART ===
            }
            // --- End User Handling Logic ---

        } else {
            $this->session->set_flashdata('error', 'Invalid Google Sign-In request.');
            redirect('/auth/login');
            exit;
        }
    }

    // === NEW METHOD 1 ===
    /**
     * Shows the role selection page for new Google sign-ups.
     */
    public function choose_role() {
        // Retrieve the temporary Google data
        $google_data = $this->session->flashdata('google_signup_data');

        // If no data (e.g., direct access), redirect to login
        if (!$google_data) {
            $this->session->set_flashdata('error', 'Invalid request. Please log in or register.');
            redirect('/auth/login');
            exit;
        }
        $this->session->keep_flashdata('google_signup_data');

        $data['google_data'] = $google_data; // Pass data to the view
        $data['error_message'] = $this->session->flashdata('error'); // Pass potential errors
        $this->call->view('/auth/choose_role', $data); // We will create this view next
    }

    // === NEW METHOD 2 ===
    /**
     * Processes the role selection and completes registration.
     */
    public function complete_google_register() {
        $google_data = $this->session->flashdata('google_signup_data');
        $selected_role = $this->io->post('role'); // Get role from the form

        // Basic validation
        if (!$google_data || !$selected_role || !in_array($selected_role, ['student', 'teacher'])) {
            // If data is missing or role invalid, try sending them back with error
            $this->session->set_flashdata('error', 'Invalid role selection or session expired. Please try signing in again.');
            if ($google_data) { // Try to preserve data if possible
               $this->session->set_flashdata('google_signup_data', $google_data);
            }
            redirect('/auth/choose_role'); // Redirect back to role choice
            exit;
        }

        // Check again if email exists
        $user_exists = $this->User_Model->filter(['email' => $google_data['email']])->get();
        if ($user_exists) {
           $this->session->set_flashdata('error', 'This email address was just registered. Please log in.');
           redirect('/auth/login');
           exit;
        }

        // Create the user with the selected role
        $newUser = [
            'first_name' => $google_data['first_name'],
            'last_name'  => $google_data['last_name'],
            'email'      => $google_data['email'],
            'password'   => password_hash(random_bytes(16), PASSWORD_DEFAULT), // Random secure password
            'role'       => $selected_role, // Use the selected role
            'google_id'  => $google_data['google_id']
        ];

        $userId = $this->User_Model->insert($newUser);

        if($userId) {
            // Log the new user in
            $session_data = [
                'user_id'    => $userId,
                'first_name' => $newUser['first_name'],
                'email'      => $newUser['email'],
                'role'       => $newUser['role'],
            ];
            $this->session->set_userdata($session_data);
            $this->session->set_flashdata('success', 'Account created successfully as a ' . $selected_role . '!');
            redirect('/dashboard');
            exit;
        } else {
            $this->session->set_flashdata('error', 'Failed to create account. Please try registering manually.');
            $this->session->set_flashdata('google_signup_data', $google_data);
            redirect('/auth/choose_role');
            exit;
        }
    }
}
?>