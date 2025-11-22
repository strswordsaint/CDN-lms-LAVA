<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: AssignmentController
 * * Handles creation, editing, and grading of assignments.
 */
class AssignmentController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->database();
        $this->call->model('Course_Model');
        $this->call->model('Assignment_Model');
        $this->call->model('Assignment_Submission_Model');
        $this->call->model('Assignment_Attachment_Model');
        $this->call->library('session');
        $this->call->library('form_validation');
        $this->call->library('Upload');
        $this->call->helper('url');
        
        $this->check_auth_teacher(); // Secure the controller
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
     * Show the form to create a new assignment for a course.
     * Corresponds to route: GET /courses/{id}/assignments/create
     */
    public function create($course_id) {
        $teacher_id = $this->session->userdata('user_id');
        $course = $this->Course_Model->find_course($course_id, $teacher_id);
        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found or permission denied.');
            redirect('/courses');
            return;
        }
        $data['course'] = $course;
        $data['page_title'] = 'Create New Assignment';
        $data['validation_errors'] = $this->session->flashdata('validation_errors');
        $this->call->view('/assignments/create', $data);
    }
    /**
     * Store a new ASSIGNMENT from the create page.
     * Corresponds to route: POST /courses/{id}/assignments/store
     */
    public function store($course_id) {
        $teacher_id = $this->session->userdata('user_id');

        // 1. Check ownership
        $course = $this->Course_Model->find_course($course_id, $teacher_id);
        if (!$course && $this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Permission denied.');
            redirect('/courses');
            return;
        }

        // 2. Validation for ASSIGNMENT
        $this->form_validation
            ->name('title')->required('Title is required.')
            ->name('due_date')->required('Due date is required.')
            ->name('points')->required('Points are required.')->numeric('Points must be a number.');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('validation_errors', $this->form_validation->get_errors());
            // Go back to the create page
            redirect('/courses/' . $course_id . '/assignments/create');
            return;
        }

        $data = [
            'course_id' => $course_id,
            'type' => 'assignment', // Hard-coded as assignment
            'title' => $this->io->post('title'),
            'description' => $this->io->post('description') ?? null,
            'due_date' => $this->io->post('due_date'),
            'points' => $this->io->post('points')
        ];

        // 3. Save the main assignment post to get its ID
        $assignment_id = $this->Assignment_Model->insert($data);
        if (!$assignment_id) {
            $this->session->set_flashdata('error', 'Failed to create assignment.');
            redirect('/courses/' . $course_id . '/assignments/create');
            return;
        }

        // 4. Handle File Uploads
        $files = $_FILES['attachments'] ?? null;
        $files_uploaded_success = true;
        $uploaded_file_db_data = [];
        $uploaded_file_paths = [];

        if ($files && !empty($files['name'][0])) {
            $file_count = count($files['name']);
            $upload_dir = 'uploads/assignments/materials/' . $course_id . '/' . $assignment_id;
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }

            $this->Upload->set_dir($upload_dir);
            $this->Upload->allowed_extensions(array('pdf', 'docx', 'doc', 'pptx', 'ppt', 'txt', 'jpg', 'png', 'zip', 'mp4', 'mov', 'xls', 'xlsx'));
            $this->Upload->allowed_mimes(array(
                'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.ms-powerpoint',
                'text/plain', 'image/jpeg', 'image/png', 'application/zip', 'video/mp4', 'video/quicktime',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ));

            for ($i = 0; $i < $file_count; $i++) {
                if (empty($files['name'][$i]) || $files['error'][$i] == 4) {
                    continue;
                }
                
                // === THIS IS THE FIX ===
                // The code to build the file array was missing
                $file_to_upload = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i],
                ];
                // === END FIX ===
                
                // Manually set the library's 'file' property
                $this->Upload->file = $file_to_upload;

                if ($this->Upload->do_upload()) {
                    $new_filename = $this->Upload->get_filename();
                    $filepath = $upload_dir . '/' . $new_filename;
                    $uploaded_file_db_data[] = [
                        'assignment_id' => $assignment_id,
                        'file_name' => $file_to_upload['name'], 
                        'file_path' => $filepath
                    ];
                    $uploaded_file_paths[] = $filepath;
                } else {
                    $this->session->set_flashdata('error', 'File upload failed: ' . $this->Upload->get_errors()[0]);
                    $files_uploaded_success = false;
                    break; 
                }
            }
        }

        // 5. Finalize
        if ($files_uploaded_success) {
            foreach ($uploaded_file_db_data as $file_data) {
                $this->Assignment_Attachment_Model->insert($file_data);
            }
            $this->session->set_flashdata('success', 'Assignment created successfully.');
        } else {
            // Rollback
            $this->Assignment_Model->delete($assignment_id);
            foreach ($uploaded_file_paths as $path) {
                $abs_path = ROOT_DIR . '/' . $path;
                if(file_exists($abs_path)) { @unlink($abs_path); }
            }
        }
        
        // Redirect back to the new 'Assignments' tab
        redirect('/courses/show/' . $course_id . '?tab=assignments');
    }
    
    /**
     * Store a new ANNOUNCEMENT from the course page form.
     * Corresponds to route: POST /courses/{id}/announcement/store
     */
    public function store_announcement($course_id) {
        $teacher_id = $this->session->userdata('user_id');

        // 1. Check ownership
        $course = $this->Course_Model->find_course($course_id, $teacher_id);
        if (!$course && $this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Permission denied.');
            redirect('/courses');
            return;
        }
        
        // 2. Validation for ANNOUNCEMENT
        $this->form_validation->name('title')->required('Title is required.');
            
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('validation_errors', $this->form_validation->get_errors());
            redirect('/courses/show/' . $course_id . '?tab=announcements');
            return;
        }

        $data = [
            'course_id' => $course_id,
            'type' => 'announcement', // Hard-coded as announcement
            'title' => $this->io->post('title'),
            'description' => $this->io->post('description') ?? null,
            'due_date' => null,
            'points' => null
        ];

        // 3. Save the main post to get its ID
        $assignment_id = $this->Assignment_Model->insert($data);
        if (!$assignment_id) {
            $this->session->set_flashdata('error', 'Failed to create announcement.');
            redirect('/courses/show/' . $course_id . '?tab=announcements');
            return;
        }

        // 4. Handle File Uploads
        $files = $_FILES['attachments'] ?? null;
        $files_uploaded_success = true;
        $uploaded_file_db_data = [];
        $uploaded_file_paths = [];

        if ($files && !empty($files['name'][0])) {
            $file_count = count($files['name']);
            $upload_dir = 'uploads/assignments/materials/' . $course_id . '/' . $assignment_id;
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }

            $this->Upload->set_dir($upload_dir);
            $this->Upload->allowed_extensions(array('pdf', 'docx', 'doc', 'pptx', 'ppt', 'txt', 'jpg', 'png', 'zip', 'mp4', 'mov', 'xls', 'xlsx'));
            $this->Upload->allowed_mimes(array(
                'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.ms-powerpoint',
                'text/plain', 'image/jpeg', 'image/png', 'application/zip', 'video/mp4', 'video/quicktime',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ));

            for ($i = 0; $i < $file_count; $i++) {
                if (empty($files['name'][$i]) || $files['error'][$i] == 4) {
                    continue;
                }
                $file_to_upload = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i],
                ];
                $this->Upload->file = $file_to_upload;

                if ($this->Upload->do_upload()) {
                    $new_filename = $this->Upload->get_filename();
                    $filepath = $upload_dir . '/' . $new_filename;
                    $uploaded_file_db_data[] = [
                        'assignment_id' => $assignment_id,
                        'file_name' => $file_to_upload['name'], 
                        'file_path' => $filepath
                    ];
                    $uploaded_file_paths[] = $filepath;
                } else {
                    $this->session->set_flashdata('error', 'File upload failed: ' . $this->Upload->get_errors()[0]);
                    $files_uploaded_success = false;
                    break; 
                }
            }
        }

        // 5. Finalize
        if ($files_uploaded_success) {
            foreach ($uploaded_file_db_data as $file_data) {
                $this->Assignment_Attachment_Model->insert($file_data);
            }
            $this->session->set_flashdata('success', 'Announcement posted successfully.');
        } else {
            // Rollback
            $this->Assignment_Model->delete($assignment_id);
            foreach ($uploaded_file_paths as $path) {
                $abs_path = ROOT_DIR . '/' . $path;
                if(file_exists($abs_path)) { @unlink($abs_path); }
            }
        }
        
        redirect('/courses/show/' . $course_id . '?tab=announcements');
    }

    /**
     * Show the page to view all submissions for an assignment.
     */
    public function view_submissions($assignment_id) {
        $teacher_id = $this->session->userdata('user_id');
        
        $assignment = $this->Assignment_Model->find_with_course_check($assignment_id, $teacher_id); 

        // === THIS IS THE FIX ===
        // Allow if the type is 'assignment' OR 'activity'
        if (!$assignment || !in_array($assignment['type'], ['assignment', 'activity', 'quiz'])) {
        // === END FIX ===
            $this->session->set_flashdata('error', 'Item not found or permission denied.');
            redirect('/courses');
            return;
        }

        $data['assignment'] = $assignment;
        $data['submissions'] = $this->Assignment_Submission_Model->get_submissions_for_assignment($assignment_id);
        $data['page_title'] = 'Submissions for ' . htmlspecialchars($assignment['title']);
        $this->call->view('/assignments/submissions', $data);
    }
    
    /**
     * Show the form to grade a single submission.
     */
    public function show_grade_form($submission_id) {
        $teacher_id = $this->session->userdata('user_id');
        
        $submission = $this->Assignment_Submission_Model->get_submission_details($submission_id);
        
        if (!$submission) {
            $this->session->set_flashdata('error', 'Submission not found.');
            redirect('/courses');
            return;
        }
        
        $course = $this->Course_Model->find_course($submission['course_id'], $teacher_id);
        if (!$course) {
             $this->session->set_flashdata('error', 'You do not have permission to grade this submission.');
             redirect('/courses');
             return;
        }

        $data['submission'] = $submission;
        $data['page_title'] = 'Grade Submission';
        $data['validation_errors'] = $this->session->flashdata('validation_errors');
        $this->call->view('/assignments/grade_submission', $data);
    }
    
    /**
     * Save the grade for a submission.
     */
    public function process_grade($submission_id) {
        $teacher_id = $this->session->userdata('user_id');
        
        $submission = $this->Assignment_Submission_Model->get_submission_details($submission_id);
        if (!$submission) {
            $this->session->set_flashdata('error', 'Submission not found.');
            redirect('/courses');
            return;
        }

        $course = $this->Course_Model->find_course($submission['course_id'], $teacher_id);
        if (!$course) {
             $this->session->set_flashdata('error', 'Permission denied.');
             redirect('/courses');
             return;
        }

        $grade = $this->io->post('grade');
        $feedback = $this->io->post('feedback');

        // 1. Run Basic Validation (Numeric, Positive)
        $this->form_validation
            ->name('grade')
                ->required('Grade is required.')
                ->numeric('Grade must be a number.')
                // REMOVED: less_than_equal_to (We do this manually below)
                ->greater_than_equal_to(0, 'Grade cannot be negative.');
                
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('validation_errors', $this->form_validation->get_errors());
            redirect('/submissions/' . $submission_id . '/grade');
            return;
        }

        // 2. Manual Check for Max Points (Fixes the 100/100 bug)
        // We allow floats (e.g. 95.5) but ensure it doesn't exceed max
        if (floatval($grade) > floatval($submission['assignment_points'])) {
             $this->session->set_flashdata('validation_errors', ['Grade cannot exceed max points (' . $submission['assignment_points'] . ').']);
             redirect('/submissions/' . $submission_id . '/grade');
             return;
        }

        // 3. Save
        if ($this->Assignment_Submission_Model->update_grade($submission_id, $grade, $feedback)) { 
             $this->session->set_flashdata('success', 'Grade and feedback saved successfully.');
        } else {
             $this->session->set_flashdata('error', 'Failed to save grade. Please try again.');
        }

        redirect('/assignments/' . $submission['assignment_id'] . '/submissions');
    }

    public function delete($assignment_id) {
        $teacher_id = $this->session->userdata('user_id');

        // 1. Security Check: Find post and verify teacher ownership
        $assignment = $this->Assignment_Model->find_with_course_check($assignment_id, $teacher_id);

        if (!$assignment) {
            $this->session->set_flashdata('error', 'Post not found or permission denied.');
            redirect('/courses');
            return;
        }
        
        $post_type = $assignment['type']; // 'assignment' or 'announcement'

        // 2. Get all associated files
        $attachments = $this->Assignment_Attachment_Model->get_for_assignment($assignment_id);
        
        // 3. Delete files from the server
        try {
            // Delete attachment files
            foreach ($attachments as $file) {
                $abs_path = ROOT_DIR . '/' . $file['file_path'];
                if ($file['file_path'] && file_exists($abs_path)) {
                    @unlink($abs_path);
                }
            }
            
            // If it's an assignment, also delete all submission files
            if ($post_type === 'assignment') {
                $submissions = $this->Assignment_Submission_Model->get_submissions_for_assignment($assignment_id);
                foreach ($submissions as $sub) {
                    $files = json_decode($sub['file_path'], true);
                    if (is_array($files)) {
                        foreach($files as $file) {
                             $abs_path = ROOT_DIR . '/' . $file['file_path'];
                             if (isset($file['file_path']) && file_exists($abs_path)) {
                                @unlink($abs_path);
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // Log error if needed, but don't stop the database delete
        }

        // 4. Delete records from the database
        // The CASCADE DELETE on the DB will handle:
        // - assignment_attachments
        // - assignment_submissions (if any)
        // - post_replies (if any)
        
        $deleted = $this->Assignment_Model->delete($assignment_id);

        if ($deleted) {
            $message = ($post_type === 'assignment') ? 'Assignment and all its data were deleted.' : 'Announcement deleted successfully.';
            $this->session->set_flashdata('success', $message);
        } else {
            $this->session->set_flashdata('error', 'Failed to delete the post from the database.');
        }

        // Redirect back to the course page
        redirect('/courses/show/' . $assignment['course_id']);
    }

    public function edit($assignment_id) {
        $teacher_id = $this->session->userdata('user_id');

        // 1. Security Check: Find assignment and verify teacher ownership
        $assignment = $this->Assignment_Model->find_with_course_check($assignment_id, $teacher_id);

        if (!$assignment) {
            $this->session->set_flashdata('error', 'Assignment not found or permission denied.');
            redirect('/courses');
            return;
        }
        
        // --- NEW: Redirect announcements to the main page ---
        if ($assignment['type'] === 'announcement') {
             $this->session->set_flashdata('error', 'Announcements cannot be edited. Please delete and recreate it.');
             redirect('/courses/show/' . $assignment['course_id']);
             return;
        }

        // 2. Get current attachments
        $attachments = $this->Assignment_Attachment_Model->get_for_assignment($assignment_id);

        // 3. Prepare data for the view
        $data['assignment'] = $assignment;
        $data['attachments'] = $attachments;
        $data['page_title'] = 'Edit Assignment: ' . htmlspecialchars($assignment['title']);
        $data['validation_errors'] = $this->session->flashdata('validation_errors');

        // 4. Load the edit view
        $this->call->view('/assignments/edit', $data);
    }

    public function update($assignment_id) {
        $teacher_id = $this->session->userdata('user_id');

        // 1. Security Check: Verify teacher ownership
        $assignment = $this->Assignment_Model->find_with_course_check($assignment_id, $teacher_id);
        if (!$assignment || $assignment['type'] !== 'assignment') {
            $this->session->set_flashdata('error', 'Permission denied or post is not an assignment.');
            redirect('/courses');
            return;
        }

        // 2. Validation
        $this->form_validation
            ->name('title')->required('Title is required.')
            ->name('due_date')->required('Due date is required.')
            ->name('points')->required('Points are required.')->numeric('Points must be a number.');
            
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('validation_errors', $this->form_validation->get_errors());
            redirect('/assignments/edit/' . $assignment_id); // Redirect back to edit page
            return;
        }

        // 3. Prepare Assignment Data
        $data = [
            'title' => $this->io->post('title'),
            'description' => $this->io->post('description'),
            'due_date' => $this->io->post('due_date'),
            'points' => $this->io->post('points')
        ];

        // 4. Update the main assignment
        $this->Assignment_Model->update($assignment_id, $data);

        // 5. Handle *New* File Uploads (Uses the same logic as your store function)
        $files = $_FILES['attachments'] ?? null;
        
        if ($files && !empty($files['name'][0])) {
            $file_count = count($files['name']);
            $upload_dir = 'uploads/assignments/materials/' . $assignment['course_id'] . '/' . $assignment_id;
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }

            $this->Upload->set_dir($upload_dir);
            $this->Upload->allowed_extensions(array('pdf', 'docx', 'doc', 'pptx', 'ppt', 'txt', 'jpg', 'png', 'zip', 'mp4', 'mov', 'xls', 'xlsx'));
            $this->Upload->allowed_mimes(array(
                'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.ms-powerpoint',
                'text/plain', 'image/jpeg', 'image/png', 'application/zip', 'video/mp4', 'video/quicktime',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ));

            for ($i = 0; $i < $file_count; $i++) {
                if (empty($files['name'][$i]) || $files['error'][$i] == 4) {
                    continue;
                }
                $file_to_upload = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i],
                ];

                $this->Upload->file = $file_to_upload;

                if ($this->Upload->do_upload()) {
                    $new_filename = $this->Upload->get_filename();
                    $original_name = $file_to_upload['name'];
                    $filepath = $upload_dir . '/' . $new_filename;

                    $file_data = [
                        'assignment_id' => $assignment_id,
                        'file_name' => $original_name, 
                        'file_path' => $filepath
                    ];
                    $this->Assignment_Attachment_Model->insert($file_data);
                } else {
                    $this->session->set_flashdata('error', 'File upload failed: ' . $this->Upload->get_errors()[0]);
                    redirect('/assignments/edit/' . $assignment_id);
                    return;
                }
            }
        }
        
        $this->session->set_flashdata('success', 'Assignment updated successfully.');
        redirect('/courses/show/' . $assignment['course_id'] . '?tab=assignments');
    }

    public function view_all() {
        $teacher_id = $this->session->userdata('user_id');
        
        $all_assignments = $this->Assignment_Model->get_all_for_teacher($teacher_id);
        
        // 1. Get a list of all assignments that have at least one ungraded submission
        // We use the Assignment_Submission_Model to fetch this data
        $ungraded_submissions = $this->Assignment_Submission_Model->get_all_ungraded_by_teacher($teacher_id);
        // Extract just the unique assignment IDs
        $ungraded_assignment_ids = array_unique(array_column($ungraded_submissions, 'assignment_id'));

        $upcoming = [];
        $past_due = [];
        
        foreach ($all_assignments as $assignment) {
            // Check if this assignment has any pending grading
            $assignment['has_ungraded'] = in_array($assignment['assignment_id'], $ungraded_assignment_ids);

            if (strtotime($assignment['due_date']) > time()) {
                $upcoming[] = $assignment;
            } else {
                $past_due[] = $assignment;
            }
        }

        // 2. Sort the "Past Due" list: 
        // Prioritize assignments that have ungraded submissions (has_ungraded = true)
        usort($past_due, function($a, $b) {
            // Primary Sort: Has ungraded (True comes before False)
            if ($a['has_ungraded'] !== $b['has_ungraded']) {
                return $a['has_ungraded'] ? -1 : 1; 
            }
            // Secondary Sort: Due date (Newest due date first)
            return strtotime($b['due_date']) - strtotime($a['due_date']);
        });

        $data['assignments_upcoming'] = $upcoming;
        $data['assignments_past_due'] = $past_due;
        
        // The "Completed" tab shows the sorted Past Due list (Needs Grading > Fully Graded)
        $data['assignments_completed'] = $past_due; 
        
        $data['page_title'] = 'All Assignments';
        
        $this->call->view('/assignments/all_assignment', $data);
    }

    public function view_ungraded() {
        $teacher_id = $this->session->userdata('user_id');
        
        // 1. Get the flat list of submissions from the model
        $submissions = $this->Assignment_Submission_Model->get_all_ungraded_by_teacher($teacher_id);
        
        // 2. Group the flat list into an array by course title
        $grouped_submissions = [];
        foreach ($submissions as $sub) {
            $course_title = $sub['course_title'];
            
            // If this is the first time we see this course, create its entry
            if (!isset($grouped_submissions[$course_title])) {
                $grouped_submissions[$course_title] = [
                    'course_id' => $sub['course_id'],
                    'submissions' => [] // Create an empty array for its submissions
                ];
            }
            
            // Add the current submission to its course's array
            $grouped_submissions[$course_title]['submissions'][] = $sub;
        }

        // 3. Pass the grouped data to the new view
        $data['grouped_submissions'] = $grouped_submissions;
        $data['page_title'] = 'Ungraded Submissions';
        
        // 4. Call the new view file we are about to create
        $this->call->view('/assignments/ungraded_list', $data);
    }

   /**
     * Store a new ANNOUNCEMENT or ACTIVITY from the course page form.
     * Corresponds to route: POST /courses/{id}/post/store
     */
    public function store_announcement_or_activity($course_id) {
        $teacher_id = $this->session->userdata('user_id');

        // 1. Check ownership
        $course = $this->Course_Model->find_course($course_id, $teacher_id);
        if (!$course && $this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Permission denied.');
            redirect('/courses');
            return;
        }
        
        // --- NEW: Determine Post Type ---
        // We use standard PHP `isset()` to check if the checkbox was sent.
        // This prevents the $this->io->post() function from crashing.
        $is_activity = isset($_POST['is_activity']) ? $this->io->post('is_activity') : null;
        $post_type = ($is_activity === 'on') ? 'activity' : 'announcement';
        
        // Determine which tab to redirect back to
        $tab_redirect = ($post_type === 'activity') ? 'activities' : 'announcements';

        // 2. Validation
        $this->form_validation->name('title')->required('Title is required.');
        
        $data = [
            'course_id' => $course_id,
            'type' => $post_type,
            'title' => $this->io->post('title'),
            'description' => $this->io->post('description') ?? null,
            'due_date' => null,
            'points' => null
        ];

        // Only validate due_date and points if it's an "activity"
        if ($post_type === 'activity') {
            $this->form_validation
                ->name('due_date')->required('Due date is required.')
                ->name('points')->required('Points are required.')->numeric('Points must be a number.');
            
            $data['due_date'] = $this->io->post('due_date');
            $data['points'] = $this->io->post('points');
            
            // If it's an activity, redirect to the activities tab on failure
            $tab_redirect = 'activities';
        }
            
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('validation_errors', $this->form_validation->get_errors());
            redirect('/courses/show/' . $course_id . '?tab=' . $tab_redirect);
            return;
        }

        // 3. Save the main post to get its ID
        $assignment_id = $this->Assignment_Model->insert($data);
        if (!$assignment_id) {
            $this->session->set_flashdata('error', 'Failed to create post.');
            redirect('/courses/show/' . $course_id . '?tab=' . $tab_redirect);
            return;
        }

        // 4. Handle Multiple File Uploads
        $files = $_FILES['attachments'] ?? null;
        $files_uploaded_success = true;
        $uploaded_file_db_data = [];
        $uploaded_file_paths = [];

        if ($files && !empty($files['name'][0])) {
            $file_count = count($files['name']);
            
            $upload_dir = 'uploads/assignments/materials/' . $course_id . '/' . $assignment_id;
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }

            $this->Upload->set_dir($upload_dir);
            $this->Upload->allowed_extensions(array('pdf', 'docx', 'doc', 'pptx', 'ppt', 'txt', 'jpg', 'png', 'zip', 'mp4', 'mov', 'xls', 'xlsx'));
            $this->Upload->allowed_mimes(array(
                'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.ms-powerpoint',
                'text/plain', 'image/jpeg', 'image/png', 'application/zip', 'video/mp4', 'video/quicktime',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ));

            for ($i = 0; $i < $file_count; $i++) {
                if (empty($files['name'][$i]) || $files['error'][$i] == 4) {
                    continue;
                }

                $file_to_upload = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i],
                ];

                $this->Upload->file = $file_to_upload;

                if ($this->Upload->do_upload()) {
                    $new_filename = $this->Upload->get_filename();
                    $filepath = $upload_dir . '/' . $new_filename;

                    $uploaded_file_db_data[] = [
                        'assignment_id' => $assignment_id,
                        'file_name' => $file_to_upload['name'], 
                        'file_path' => $filepath
                    ];
                    $uploaded_file_paths[] = $filepath;
                } else {
                    $this->session->set_flashdata('error', 'File upload failed: ' . $this->Upload->get_errors()[0]);
                    $files_uploaded_success = false;
                    break; 
                }
            }
        }

        // 5. Finalize
        if ($files_uploaded_success) {
            foreach ($uploaded_file_db_data as $file_data) {
                $this->Assignment_Attachment_Model->insert($file_data);
            }
            $message = ($post_type === 'activity') ? 'Activity created successfully.' : 'Announcement posted successfully.';
            $this->session->set_flashdata('success', $message);
        } else {
            // Rollback
            $this->Assignment_Model->delete($assignment_id);
            foreach ($uploaded_file_paths as $path) {
                $abs_path = ROOT_DIR . '/' . $path;
                if(file_exists($abs_path)) {
                    @unlink($abs_path);
                }
            }
        }
        
        // On success, always redirect to the announcements tab
        redirect('/courses/show/' . $course_id . '?tab=announcements');
    }
}
?>