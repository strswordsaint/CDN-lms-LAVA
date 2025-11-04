<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: ResourceController
 * * Handles uploading and deleting of course materials.
 */
class ResourceController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->database();
        $this->call->model('Course_Model');
        $this->call->model('Resource_Model'); // The new model
        $this->call->library('session');
        $this->call->library('Upload');
        $this->call->helper('url');
        $this->check_auth(); // Secure the whole controller
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
             $this->session->set_flashdata('error', 'You do not have permission.');
             redirect('/dashboard');
             exit;
        }
    }

    /**
     * Process the file upload for course materials.
     */
    public function upload($course_id) {
        $teacher_id = $this->session->userdata('user_id');
        $course = $this->Course_Model->find_course($course_id, $teacher_id);

        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found or permission denied.');
            redirect('/courses');
            return;
        }

        if (!isset($_FILES['material_file']) || $_FILES['material_file']['error'] != UPLOAD_ERR_OK) {
            $this->session->set_flashdata('error', 'File upload failed or no file selected.');
            redirect('/courses/show/' . $course_id);
            return;
        }

        $this->Upload->file = $_FILES['material_file'];
        // We'll store materials in their own folder
        $upload_dir = 'uploads/courses/' . $course_id . '/materials';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }

        $this->Upload->set_dir($upload_dir);
        
        // Allow a wide range of common file types
        $this->Upload->allowed_extensions(array('pdf', 'docx', 'doc', 'pptx', 'ppt', 'txt', 'jpg', 'png', 'zip', 'mp4', 'mov', 'xls', 'xlsx'));
        $this->Upload->allowed_mimes(array(
            'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.ms-powerpoint',
            'text/plain', 'image/jpeg', 'image/png', 'application/zip', 'video/mp4', 'video/quicktime',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ));

        // Use the original file name, but let the uploader make it unique if it exists
        // Note: The Upload lib sanitizes the filename by default
        
        if ($this->Upload->do_upload(false)) { // Overwrite = false (default)
            $filename = $this->Upload->get_filename();
            $filepath = $upload_dir . '/' . $filename;

            $data = [
                'course_id' => $course_id,
                'file_name' => $filename, // The sanitized, potentially unique filename
                'file_path' => $filepath
            ];
            $this->Resource_Model->insert($data);
            
            $this->session->set_flashdata('success', 'File uploaded successfully.');
        } else {
            $this->session->set_flashdata('error', $this->Upload->get_errors()[0]);
        }
        redirect('/courses/show/' . $course_id);
    }

    /**
     * Delete a course material file.
     */
    public function delete($material_id) {
        $teacher_id = $this->session->userdata('user_id');
        $material = $this->Resource_Model->find($material_id);

        if (!$material) {
            $this->session->set_flashdata('error', 'File not found.');
            redirect('/courses');
            return;
        }

        // Security check: Does this teacher own the course this material belongs to?
        $course = $this->Course_Model->find_course($material['course_id'], $teacher_id);
        if (!$course) {
            $this->session->set_flashdata('error', 'You do not have permission to delete this file.');
            redirect('/courses');
            return;
        }

        // 1. Delete file from server
        // *** PERFORMANCE FIX HERE ***
        $abs_path = ROOT_DIR . '/' . $material['file_path'];
        if (file_exists($abs_path)) {
            @unlink($abs_path);
        }

        // 2. Delete from database
        $this->Resource_Model->delete($material_id);

        $this->session->set_flashdata('success', 'File deleted successfully.');
        redirect('/courses/show/' . $material['course_id']);
    }
}
?>