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
     * Store the new assignment in the database.
     * Corresponds to route: POST /courses/{id}/assignments/store
     */
    public function store($course_id) {
        $teacher_id = $this->session->userdata('user_id');

        // 1. Check ownership
        $course = $this->Course_Model->find_course($course_id, $teacher_id);
        if (!$course) {
            $this->session->set_flashdata('error', 'Permission denied.');
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
            redirect('/courses/' . $course_id . '/assignments/create');
            return;
        }

        // 3. Prepare Assignment Data
        $data = [
            'course_id' => $course_id,
            'title' => $this->io->post('title'),
            'description' => $this->io->post('description'),
            'due_date' => $this->io->post('due_date'),
            'points' => $this->io->post('points')
        ];

        // 4. Save the main assignment to get its ID
        $assignment_id = $this->Assignment_Model->insert($data);
        if (!$assignment_id) {
            $this->session->set_flashdata('error', 'Failed to create assignment.');
            redirect('/courses/' . $course_id . '/assignments/create');
            return;
        }

        // 5. Handle Multiple File Uploads (NEW SULOTION)
        $files = $_FILES['attachments'] ?? null;
        $files_uploaded_success = true;
        $uploaded_file_db_data = []; // To store data for DB
        $uploaded_file_paths = [];   // To store paths for rollback

        // Check if files were actually uploaded (name[0] is not empty)
        if ($files && !empty($files['name'][0])) {
            $file_count = count($files['name']);
            
            $upload_dir = 'uploads/assignments/materials/' . $course_id . '/' . $assignment_id;
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }

            // Set the *constant* settings for the library (which was loaded in constructor)
            $this->Upload->set_dir($upload_dir);
            $this->Upload->allowed_extensions(array('pdf', 'docx', 'doc', 'pptx', 'ppt', 'txt', 'jpg', 'png', 'zip', 'mp4', 'mov', 'xls', 'xlsx'));
            $this->Upload->allowed_mimes(array(
                'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.ms-powerpoint',
                'text/plain', 'image/jpeg', 'image/png', 'application/zip', 'video/mp4', 'video/quicktime',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ));
            // $this->Upload->encrypt_name(); // Optional: if you want unique filenames

            for ($i = 0; $i < $file_count; $i++) {
                // Skip empty file inputs (error code 4 is UPLOAD_ERR_NO_FILE)
                if (empty($files['name'][$i]) || $files['error'][$i] == 4) {
                    continue;
                }

                // Create the single-file array structure
                $file_to_upload = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i],
                ];

                // Manually set the library's 'file' property
                // This is the key to the solution
                $this->Upload->file = $file_to_upload;

                // Use the SINGLE upload method
                if ($this->Upload->do_upload()) {
                    $new_filename = $this->Upload->get_filename();
                    $original_name = $file_to_upload['name'];
                    $filepath = $upload_dir . '/' . $new_filename;

                    // Store data to be saved, but don't save yet
                    $uploaded_file_db_data[] = [
                        'assignment_id' => $assignment_id,
                        'file_name' => $original_name, 
                        'file_path' => $filepath
                    ];
                    $uploaded_file_paths[] = $filepath; // Keep track for potential rollback
                } else {
                    // If *any* file fails, stop, set error, and break the loop
                    $this->session->set_flashdata('error', 'File upload failed: ' . $this->Upload->get_errors()[0] . '. Please check the file type and try again.');
                    $files_uploaded_success = false;
                    break; 
                }
            }
        }

        // Now, check if all files succeeded
        if ($files_uploaded_success) {
            // All files uploaded (or none were selected), now save to database
            foreach ($uploaded_file_db_data as $file_data) {
                $this->Assignment_Attachment_Model->insert($file_data);
            }
            
            $this->session->set_flashdata('success', 'Assignment created successfully.');
            redirect('/courses/show/' . $course_id);
        } else {
            // An error occurred
            // Rollback: Delete the assignment
            $this->Assignment_Model->delete($assignment_id);
            // Rollback: Delete any files that *did* upload
            foreach ($uploaded_file_paths as $path) {
                // *** PERFORMANCE FIX HERE ***
                $abs_path = ROOT_DIR . '/' . $path;
                if(file_exists($abs_path)) {
                    @unlink($abs_path);
                }
            }
            // Redirect back with the error message
            redirect('/courses/' . $course_id . '/assignments/create');
            return;
        }
    }

    /**
     * Show the page to view all submissions for an assignment.
     */
    public function view_submissions($assignment_id) {
        $teacher_id = $this->session->userdata('user_id');
        
        // This method name is from your previously uploaded file
        $assignment = $this->Assignment_Model->find_with_course_check($assignment_id, $teacher_id); 

        if (!$assignment) {
            $this->session->set_flashdata('error', 'Assignment not found or permission denied.');
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

        $this->form_validation
            ->name('grade')
                ->required('Grade is required.')
                ->numeric('Grade must be a number.')
                ->less_than_equal_to($submission['assignment_points'], 'Grade cannot exceed max points (' . $submission['assignment_points'] . ').')
                ->greater_than_equal_to(0, 'Grade cannot be negative.');
                
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('validation_errors', $this->form_validation->get_errors());
            redirect('/submissions/' . $submission_id . '/grade');
            return;
        }

        // This method name is from your previously uploaded file
        if ($this->Assignment_Submission_Model->update_grade($submission_id, $grade, $feedback)) { 
             $this->session->set_flashdata('success', 'Grade and feedback saved successfully.');
        } else {
             $this->session->set_flashdata('error', 'Failed to save grade. Please try again.');
        }

        redirect('/assignments/' . $submission['assignment_id'] . '/submissions');
    }

    public function delete($assignment_id) {
        $teacher_id = $this->session->userdata('user_id');

        // 1. Security Check: Find assignment and verify teacher ownership
        // This method also joins the 'courses' table to check the teacher_id
        $assignment = $this->Assignment_Model->find_with_course_check($assignment_id, $teacher_id);

        if (!$assignment) {
            $this->session->set_flashdata('error', 'Assignment not found or permission denied.');
            redirect('/courses');
            return;
        }

        // 2. Get all associated files
        $attachments = $this->Assignment_Attachment_Model->get_for_assignment($assignment_id);
        $submissions = $this->Assignment_Submission_Model->get_submissions_for_assignment($assignment_id);

        // 3. Delete files from the server
        try {
            // Delete attachment files
            foreach ($attachments as $file) {
                // *** PERFORMANCE FIX HERE ***
                $abs_path = ROOT_DIR . '/' . $file['file_path'];
                if ($file['file_path'] && file_exists($abs_path)) {
                    @unlink($abs_path);
                }
            }
            // Delete submission files
            foreach ($submissions as $sub) {
                // *** PERFORMANCE FIX HERE ***
                // Handle both JSON and single-string paths
                $files = json_decode($sub['file_path'], true);
                if (is_array($files)) {
                    foreach($files as $file) {
                         $abs_path = ROOT_DIR . '/' . $file['file_path'];
                         if (isset($file['file_path']) && file_exists($abs_path)) {
                            @unlink($abs_path);
                        }
                    }
                } else if (!empty($sub['file_path'])) {
                    $abs_path = ROOT_DIR . '/' . $sub['file_path'];
                    if(file_exists($abs_path)) {
                        @unlink($abs_path);
                    }
                }
            }
        } catch (Exception $e) {
            // Log error if needed, but don't stop the database delete
        }

        // 4. Delete records from the database
        // We delete from child tables first to avoid foreign key errors
        
        // Delete attachment records
        $this->db->table('assignment_attachments')->where('assignment_id', $assignment_id)->delete();
        
        // Delete submission records
        $this->db->table('assignment_submissions')->where('assignment_id', $assignment_id)->delete();
        
        // Finally, delete the main assignment
        $deleted = $this->Assignment_Model->delete($assignment_id);

        if ($deleted) {
            $this->session->set_flashdata('success', 'Assignment and all its submissions were deleted.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete the assignment from the database.');
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
        if (!$assignment) {
            $this->session->set_flashdata('error', 'Permission denied.');
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
        redirect('/courses/show/' . $assignment['course_id']);
    }

    public function view_all() {
        $teacher_id = $this->session->userdata('user_id');
        
        $all_assignments = $this->Assignment_Model->get_all_for_teacher($teacher_id);
        
        $upcoming = [];
        $past_due = [];
        
        foreach ($all_assignments as $assignment) {
            if (strtotime($assignment['due_date']) > time()) {
                $upcoming[] = $assignment;
            } else {
                $past_due[] = $assignment;
            }
        }

        $data['assignments_upcoming'] = $upcoming;
        $data['assignments_past_due'] = $past_due;
        
        // As requested, the "Completed" tab will show the same list as "Past Due."
        // This "Past Due" list is the primary area for grading.
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
}
?>