<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AssignmentController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->database();
        $this->call->model('Assignment_Model');
        $this->call->model('Course_Model'); // For checking course ownership
        $this->call->library('session');
        $this->call->library('form_validation');
        
        // --- THIS IS THE FIX ---
        // Load the Upload library and URL helper so they are always available
        $this->call->library('Upload');
        $this->call->helper('url');
        // --- END FIX ---
        
        // Protect all teacher-facing assignment methods
        $this->check_auth();
    }

    /**
     * Middleware to check if user is a teacher/admin
     */
    protected function check_auth($role_required = ['teacher', 'admin']) {
        if (!$this->session->has_userdata('user_id')) {
            $this->session->set_flashdata('error', 'Please login to access this section.');
            redirect('/auth/login');
            exit;
        }
        $user_role = $this->session->userdata('role');
        if (!in_array($user_role, $role_required)) {
             $this->session->set_flashdata('error', 'You do not have permission to access this section.');
             redirect('/dashboard');
             exit;
        }
    }

    /**
     * Show the form to create a new assignment for a course.
     */
    public function create($course_id) {
        $teacher_id = $this->session->userdata('user_id');
        $course = $this->Course_Model->find_course($course_id, $teacher_id);

        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found or you do not have permission.');
            redirect('/courses');
            return;
        }

        $data['course'] = $course;
        $data['page_title'] = 'Create New Assignment';
        $data['validation_errors'] = $this->session->flashdata('validation_errors');
        $data['error_message'] = $this->session->flashdata('error'); // For file upload errors

        $this->call->view('/assignments/create', $data);
    }

    /**
     * Store the new assignment (with file upload) in the database.
     */
    public function store($course_id) {
        $teacher_id = $this->session->userdata('user_id');
        $course = $this->Course_Model->find_course($course_id, $teacher_id);

        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found or permission denied.');
            redirect('/courses');
            return;
        }

        $this->form_validation
            ->name('title')->required('Title is required.')
            ->name('points')->required('Points are required.')->numeric('Points must be a number.')
            ->name('due_date')->required('Due date is required.');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('validation_errors', $this->form_validation->get_errors());
            redirect('/courses/' . $course_id . '/assignments/create');
            return;
        }

        $data = [
            'course_id'   => $course_id,
            'title'       => $this->io->post('title'),
            'description' => $this->io->post('description'),
            'points'      => $this->io->post('points'),
            'due_date'    => $this->io->post('due_date'),
            'attachment_path' => null // Default to null
        ];

        // --- Handle File Upload ---
        $file_uploaded = false;
        // Check if a file was actually uploaded without errors
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
            
            // We must pass the $_FILES array to the library's public property
            // --- FIX: Use $this->Upload (Uppercase U) ---
            $this->Upload->file = $_FILES['attachment'];
            
            $upload_dir = 'uploads/assignments/materials';
            
            // Create the directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // --- FIX: Use $this->Upload (Uppercase U) and add mime types ---
            $this->Upload->set_dir($upload_dir);
            $this->Upload->allowed_extensions(array('pdf', 'docx', 'doc', 'pptx', 'txt', 'jpg', 'png', 'zip', 'mp4', 'mov', 'wmv'));
            
            // Add corresponding MIME types to fix the "invalid mime type" error
            $this->Upload->allowed_mimes(array(
                'application/pdf', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                'application/msword', // .doc
                'application/vnd.openxmlformats-officedocument.presentationml.presentation', // .pptx
                'text/plain', // .txt
                'image/jpeg', // .jpg
                'image/png', // .png
                'application/zip', // .zip
                'video/mp4', // .mp4
                'video/quicktime', // .mov
                'video/x-ms-wmv' // .wmv
            ));
            
            $this->Upload->encrypt_name(); // Secure filename

            // --- FIX: Use $this->Upload (Uppercase U) ---
            if ($this->Upload->do_upload()) {
                // --- FIX: Use $this->Upload (Uppercase U) ---
                $filename = $this->Upload->get_filename();
                // Store the *relative* path for database
                $data['attachment_path'] = $upload_dir . '/' . $filename;
                $file_uploaded = true;
            } else {
                // If upload fails, show error and return
                // --- FIX: Use $this->Upload (Uppercase U) ---
                $this->session->set_flashdata('error', $this->Upload->get_errors()[0]);
                redirect('/courses/' . $course_id . '/assignments/create');
                return;
            }
        }
        // --- End File Upload ---

        // Insert into database
        if ($this->Assignment_Model->insert($data)) {
            $this->session->set_flashdata('success', 'Assignment created successfully.');
            redirect('/courses/show/' . $course_id);
        } else {
            // This might happen if the database insert fails for some reason
            if($file_uploaded && isset($data['attachment_path'])) {
                // Clean up the uploaded file if DB insert fails
                @unlink($data['attachment_path']);
            }
            $this->session->set_flashdata('error', 'Failed to create assignment in database.');
            redirect('/courses/' . $course_id . '/assignments/create');
        }
    }
    
    // We will add edit, update, delete, and view_submissions methods here later...
}
?>