<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: ReplyController
 * * Handles fetching and creating replies for posts (assignments/announcements).
 */
class ReplyController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->database();
        $this->call->library('session');
        $this->call->library('form_validation');
        $this->call->helper('url');
        
        // Load all models needed for security checks
        $this->call->model('Post_Reply_Model');
        $this->call->model('Enrollment_Model');
        $this->call->model('Assignment_Model'); 
        $this->call->model('Course_Model');
    }

    /**
     * Private helper to check if a user can access a post's replies.
     * This is the security check.
     */
    private function can_access_post($user_id, $role, $post_id) {
        $assignment = $this->Assignment_Model->find($post_id);
        if (!$assignment) {
            return false; // Post doesn't exist
        }
        
        $course_id = $assignment['course_id'];
        
        // Admin can access anything
        if ($role === 'admin') {
            return true; 
        }
        
        // Check if user is the teacher of this course
        if ($role === 'teacher') {
            $course = $this->Course_Model->find_course($course_id, $user_id);
            return $course ? true : false;
        }
        
        // Check if user is an approved student
        if ($role === 'student') {
            $is_approved = $this->Enrollment_Model->filter([
                'student_id' => $user_id,
                'course_id'  => $course_id,
                'status'     => 'approved'
            ])->get();
            return $is_approved ? true : false;
        }
        
        return false; // Default deny
    }

    /**
     * Get all replies for a specific post (AJAX).
     */
    public function get($post_id) {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');
        
        if (!$user_id) {
            // === FIX: Use standard PHP functions ===
            http_response_code(401); // Unauthorized
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        if (!$this->can_access_post($user_id, $role, $post_id)) {
            // === FIX: Use standard PHP functions ===
            http_response_code(403); // Forbidden
            echo json_encode(['error' => 'You do not have permission to view these replies.']);
            exit;
        }
        
        $replies = $this->Post_Reply_Model->get_replies_for_post($post_id);
        
        $data = [
            'replies' => $replies,
            'current_user_id' => $user_id
        ];

        // === FIX: Use standard PHP functions ===
        http_response_code(200); // OK
        echo json_encode($data);
        exit;
    }

    /**
     * Store a new reply for a post (AJAX).
     */
    public function store($post_id) {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        if (!$user_id) {
            // === FIX: Use standard PHP functions ===
            http_response_code(401); // Unauthorized
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        
        if (!$this->can_access_post($user_id, $role, $post_id)) {
            // === FIX: Use standard PHP functions ===
            http_response_code(403); // Forbidden
            echo json_encode(['error' => 'You do not have permission to reply to this post.']);
            exit;
        }

        $this->form_validation
            ->name('reply_content')->required('Reply content cannot be empty.');
            
        if ($this->form_validation->run() == FALSE) {
            // === FIX: Use standard PHP functions ===
            http_response_code(400); // Bad Request
            echo json_encode(['error' => $this->form_validation->get_errors()[0]]);
            exit;
        }

        $data = [
            'assignment_id' => $post_id,
            'user_id' => $user_id,
            'content' => $this->io->post('reply_content')
        ];

        if ($this->Post_Reply_Model->insert($data)) {
            // === FIX: Use standard PHP functions ===
            http_response_code(201); // Created
            echo json_encode(['success' => 'Reply posted.']);
        } else {
            // === FIX: Use standard PHP functions ===
            http_response_code(500); // Server Error
            echo json_encode(['error' => 'Failed to post reply.']);
        }
        exit;
    }
}
?>