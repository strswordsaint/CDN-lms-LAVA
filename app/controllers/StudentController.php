<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->database();
        $this->call->library('session');
        $this->call->helper('url');
        
        // Load the models we will need
        $this->call->model('Enrollment_Model');
        $this->call->model('Course_Model');
        $this->call->model('Assignment_Model'); 
        $this->call->model('Resource_Model');
        $this->call->model('Assignment_Attachment_Model'); 
        
        $this->call->library('Upload');
        
        // Protect this entire controller
        $this->check_auth();
    }

    /**
     * Auth middleware: Ensure user is logged in and is a 'student'
     */
    protected function check_auth() {
        if (!$this->session->has_userdata('user_id')) {
            $this->session->set_flashdata('error', 'Please login to access that page.');
            redirect('/auth/login');
            exit;
        }
        
        if ($this->session->userdata('role') !== 'student') {
            $this->session->set_flashdata('error', 'You do not have permission to access that page.');
            redirect('/dashboard'); // Redirect to their own (e.g., teacher) dashboard
            exit;
        }
    }
    
    /**
     * Display the student's list of enrolled/pending courses.
     */
    public function my_courses() {
        $student_id = $this->session->userdata('user_id');
        
        // Use the model to get all courses (pending and approved)
        $data['my_courses'] = $this->Enrollment_Model->get_student_courses($student_id);
        $data['page_title'] = 'My Courses';
        
        $this->call->view('/student/my_courses', $data); 
    }

    /**
     * Handles the POST request from the enrollment form.
     */
    public function enroll() {
        // We trim the input to remove any spaces from copy-pasting.
        $enrollment_code = trim($this->io->post('enrollment_code'));
        $student_id = $this->session->userdata('user_id');

        if (empty($enrollment_code)) {
            $this->session->set_flashdata('error', 'Please enter an enrollment code.');
            redirect('/courses/my'); // Updated redirect
            return;
        }

        // 1. Find the course by its enrollment code
        $course = $this->Course_Model->filter(['enrollment_code' => $enrollment_code])->get();

        if (!$course) {
            $this->session->set_flashdata('error', 'Invalid enrollment code.');
            redirect('/courses/my'); // Updated redirect
            return;
        }
        
        // 2. Check if student has already requested or is enrolled
        $is_enrolled = $this->Enrollment_Model->has_pending_or_approved_enrollment($student_id, $course['course_id']);

        if ($is_enrolled) {
            $this->session->set_flashdata('error', 'You have already requested or are enrolled in that course.');
            redirect('/courses/my'); // Updated redirect
            return;
        }
        
        // 3. Enroll the student (with 'pending' status)
        $data = [
            'student_id' => $student_id,
            'course_id'  => $course['course_id'],
            'status'     => 'pending' // Set status to pending
        ];
        
        if ($this->Enrollment_Model->insert($data)) {
            $this->session->set_flashdata('success', 'Enrollment request sent for: ' . $course['title']);
        } else {
            $this->session->set_flashdata('error', 'An error occurred during enrollment. Please try again.');
        }
        
        redirect('/courses/my'); // Updated redirect
    }

    /**
     * View a single course (assignments, quizzes, etc.)
     */
    public function view_course($course_id) {
        $student_id = $this->session->userdata('user_id');
        
        // 1. Check if student is *approved* for this course
        $is_approved = $this->Enrollment_Model->filter([
            'student_id' => $student_id,
            'course_id'  => $course_id,
            'status'     => 'approved'
        ])->get();

        if (!$is_approved) {
            $this->session->set_flashdata('error', 'You do not have access to this course.');
            redirect('/dashboard');
            return;
        }

        // 2. Get course details
        $data['course'] = $this->Course_Model->find($course_id);
        
        // --- NEW 4-TAB LOGIC ---
        // 1. Get all posts (announcements, activities, assignments)
        $all_posts = $this->Assignment_Model->get_posts_for_course_stream($course_id, $student_id);
        
        $announcements_list = [];
        $activities_list = [];
        $assignments_list = [];
        
        // 2. Loop through, get attachments, and sort
        foreach ($all_posts as $post) {
            $post['attachments'] = $this->Assignment_Attachment_Model->get_for_assignment($post['assignment_id']);
            
            // Add to the main "Announcements" stream
            $announcements_list[] = $post;
            
            // Add to the "Activities" tab if it's an activity
            if ($post['type'] === 'activity') {
                $activities_list[] = $post;
            }
            // Add to the "Assignments" tab if it's an assignment
            else if ($post['type'] === 'assignment') {
                $assignments_list[] = $post;
            }
        }
        
        // 3. Pass all three arrays to the view
        $data['announcements'] = $announcements_list; // All posts
        $data['activities'] = $activities_list;       // Only activities
        $data['assignments'] = $assignments_list;     // Only assignments
        
        // This is still needed for the "Materials" tab
        $data['materials'] = $this->Resource_Model->get_for_course($course_id);
        
        $data['page_title'] = $data['course']['title'];
        
        $this->call->view('/student/view_course', $data);
    }
    
    public function view_assignment($assignment_id) {
        $student_id = $this->session->userdata('user_id');
        // $this->call->model('Assignment_Attachment_Model'); // Already loaded

        // 1. Get assignment details
        $assignment = $this->Assignment_Model->find($assignment_id); 
        if (!$assignment) {
            $this->session->set_flashdata('error', 'Assignment not found.');
            redirect('/dashboard');
            return;
        }
        
        // --- THIS IS THE FIX ---
        // Check if it's an assignment OR activity
        if (!in_array($assignment['type'], ['assignment', 'activity'])) {
        // --- END FIX ---
            $this->session->set_flashdata('error', 'This post is not a submittable item.');
            // Redirect back to the course stream
            redirect('/my-courses/' . $assignment['course_id']);
            return;
        }

        // 2. Security Check: Is the student enrolled and approved for this assignment's course?
        $is_approved = $this->Enrollment_Model->filter([
            'student_id' => $student_id,
            'course_id'  => $assignment['course_id'],
            'status'     => 'approved'
        ])->get();

        if (!$is_approved) {
            $this->session->set_flashdata('error', 'You do not have access to this assignment.');
            redirect('/dashboard');
            return;
        }
        
        // 3. Get course details (for navigation/context)
        $data['course'] = $this->Course_Model->find($assignment['course_id']);
        
        // 4. Check if the student has already submitted
        $this->call->model('Assignment_Submission_Model'); 
        $data['submission'] = $this->Assignment_Submission_Model->check_existing_submission($student_id, $assignment_id);

        // 5. Get assignment attachments
        $data['assignment']['attachments'] = $this->Assignment_Attachment_Model->get_for_assignment($assignment_id);

        $data['assignment'] = $assignment;
        $data['page_title'] = 'Submit: ' . htmlspecialchars($assignment['title']);
        $data['error_message'] = $this->session->flashdata('error');

        $this->call->view('/student/submit_assignment', $data);
    }

    /**
     * Handle the file upload for an assignment submission.
     */
   public function submit_assignment($assignment_id) {
        $student_id = $this->session->userdata('user_id');

        // 1. Get assignment details
        $assignment = $this->Assignment_Model->find($assignment_id);
        // --- THIS IS THE FIX ---
        if (!$assignment || !in_array($assignment['type'], ['assignment', 'activity'])) {
        // --- END FIX ---
            $this->session->set_flashdata('error', 'Assignment not found.');
            redirect('/dashboard');
            return;
        }

        // 2. Security Check: Is student approved for this course?
        $is_approved = $this->Enrollment_Model->filter([
            'student_id' => $student_id,
            'course_id'  => $assignment['course_id'],
            'status'     => 'approved'
        ])->get();
        if (!$is_approved) {
            $this->session->set_flashdata('error', 'You do not have permission to submit to this assignment.');
            redirect('/dashboard');
            return;
        }
        
        // 3. Check if already submitted
        $this->call->model('Assignment_Submission_Model');
        $existing_submission = $this->Assignment_Submission_Model->check_existing_submission($student_id, $assignment_id);
        if ($existing_submission) {
             $this->session->set_flashdata('error', 'You have already submitted this assignment.');
             redirect('/assignment/' . $assignment_id);
             return;
        }

        // 4. Handle Multiple File Uploads
        $files = $_FILES['submission_files'] ?? null;
        
        if (!$files || empty($files['name'][0])) {
            $this->session->set_flashdata('error', 'File upload failed or no file selected. Please try again.');
            redirect('/assignment/' . $assignment_id);
            return;
        }

        // --- NEW MULTI-UPLOAD LOGIC (COPIED FROM AssignmentController) ---
        $upload_dir = 'uploads/assignments/submissions/' . $assignment['course_id'] . '/' . $assignment_id;
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }

        // Set the *constant* settings for the library
        $this->Upload->set_dir($upload_dir);
        
        // --- COPIED FROM AssignmentController to allow all file types ---
        $this->Upload->allowed_extensions(array('pdf', 'docx', 'doc', 'pptx', 'ppt', 'txt', 'jpg', 'png', 'zip', 'mp4', 'mov', 'xls', 'xlsx'));
        $this->Upload->allowed_mimes(array(
            'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.ms-powerpoint',
            'text/plain', 'image/jpeg', 'image/png', 'application/zip', 'video/mp4', 'video/quicktime',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ));
        // --- END COPY ---
        
        $uploaded_file_data = []; // To store paths for the JSON
        $file_count = count($files['name']);
        $at_least_one_file_success = false;

        for ($i = 0; $i < $file_count; $i++) {
            if (empty($files['name'][$i]) || $files['error'][$i] !== UPLOAD_ERR_OK) {
                continue; // Skip empty/failed uploads
            }

            $file_to_upload = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i],
            ];

            // Manually set the library's 'file' property
            $this->Upload->file = $file_to_upload;
            $this->Upload->encrypt_name(); // Make filename unique

            if ($this->Upload->do_upload()) {
                $at_least_one_file_success = true;
                // Store the new filename and original name for the DB
                $uploaded_file_data[] = [
                    'file_path' => $upload_dir . '/' . $this->Upload->get_filename(),
                    'file_name' => $file_to_upload['name']
                ];
            } else {
                // If *any* file fails, stop, delete already uploaded files, and show error
                foreach ($uploaded_file_data as $file) {
                    $abs_path = ROOT_DIR . '/' . $file['file_path'];
                    if(file_exists($abs_path)) {
                        @unlink($abs_path);
                    }
                }
                $this->session->set_flashdata('error', 'File upload failed: ' . $this->Upload->get_errors()[0]);
                redirect('/assignment/' . $assignment_id);
                return;
            }
        }

        if (!$at_least_one_file_success) {
             $this->session->set_flashdata('error', 'No valid files were uploaded.');
             redirect('/assignment/' . $assignment_id);
             return;
        }

        // 5. Save the JSON list of files to the database
        $submission_data = [
            'assignment_id' => $assignment_id,
            'student_id'    => $student_id,
            'file_path'     => json_encode($uploaded_file_data) // Save the list as a JSON string
        ];

        if ($this->Assignment_Submission_Model->insert($submission_data)) {
            $this->session->set_flashdata('success', 'Assignment (with ' . count($uploaded_file_data) . ' files) submitted successfully!');
            redirect('/my-courses/' . $assignment['course_id']); 
        } else {
            // Database insert failed, delete all uploaded files
            foreach ($uploaded_file_data as $file) {
                $abs_path = ROOT_DIR . '/' . $file['file_path'];
                if(file_exists($abs_path)) {
                    @unlink($abs_path);
                }
            }
            $this->session->set_flashdata('error', 'Database error: Could not save submission.');
            redirect('/assignment/' . $assignment_id);
        }
    }
    
    public function view_all_assignments() {
        $student_id = $this->session->userdata('user_id');
        
        // 1. Get all assignments
        $all_assignments = $this->Assignment_Model->get_all_assignments_for_student($student_id);
        
        // 2. Categorize them
        $upcoming = [];
        $past_due = [];
        $completed = [];
        
        foreach ($all_assignments as $assignment) {
            $is_submitted = $assignment['submission_id'] !== null;
            $is_graded = $assignment['grade'] !== null;
            $is_overdue = strtotime($assignment['due_date']) < time();

            if ($is_graded) {
                $completed[] = $assignment;
            } else if ($is_submitted && $is_overdue) {
                $completed[] = $assignment; // Submitted and past due counts as "completed"
            } else if (!$is_submitted && $is_overdue) {
                $past_due[] = $assignment;
            } else {
                // This covers:
                // - Not submitted, not due
                // - Submitted, not due
                $upcoming[] = $assignment;
            }
        }

        // 3. Pass to the view
        $data['assignments_upcoming'] = $upcoming;
        $data['assignments_past_due'] = $past_due;
        $data['assignments_completed'] = $completed;
        
        $data['page_title'] = 'All Assignments';
        
        $this->call->view('/student/all_assignments', $data);
    }
    
    public function leave_course($enrollment_id) {
        $student_id = $this->session->userdata('user_id');

        // 1. Find the enrollment record
        $enrollment = $this->Enrollment_Model->find($enrollment_id);

        // 2. Security Check: Ensure the record exists and belongs to the logged-in student
        if (!$enrollment || $enrollment['student_id'] != $student_id) {
            $this->session->set_flashdata('error', 'Unable to perform this action.');
            redirect('/courses/my');
            return;
        }

        // 3. Delete the enrollment record
        if ($this->Enrollment_Model->delete_enrollment($enrollment_id)) {
            if ($enrollment['status'] == 'pending') {
                $this->session->set_flashdata('success', 'Enrollment request successfully cancelled.');
            } else {
                $this->session->set_flashdata('success', 'You have successfully left the course.');
            }
        } else {
            $this->session->set_flashdata('error', 'An error occurred. Please try again.');
        }

        redirect('/courses/my');
    }

    /**
     * Unsubmit an assignment, if it has not been graded.
     */
    public function unsubmit_assignment($submission_id) {
        $student_id = $this->session->userdata('user_id');
        $this->call->model('Assignment_Submission_Model');

        // 1. Find the submission
        $submission = $this->Assignment_Submission_Model->find($submission_id);

        // 2. Security Checks
        if (!$submission) {
            $this->session->set_flashdata('error', 'Submission not found.');
            redirect($_SERVER['HTTP_REFERER'] ?? '/my-assignments');
            return;
        }

        if ($submission['student_id'] != $student_id) {
            $this->session->set_flashdata('error', 'You do not have permission to modify this submission.');
            redirect('/my-assignments');
            return;
        }

        if ($submission['grade'] !== null) {
            $this->session->set_flashdata('error', 'Cannot unsubmit a graded assignment.');
            redirect('/assignment/' . $submission['assignment_id']);
            return;
        }

        // 3. Delete Files from Server
        $files = json_decode($submission['file_path'], true);
        if (is_array($files)) {
            foreach ($files as $file) {
                $file_abs_path = ROOT_DIR . '/' . $file['file_path'];
                if (isset($file['file_path']) && file_exists($file_abs_path)) {
                    @unlink($file_abs_path);
                }
            }
        } else if (!empty($submission['file_path'])) {
             $file_abs_path = ROOT_DIR . '/' . $submission['file_path'];
             if (file_exists($file_abs_path)) {
                @unlink($file_abs_path);
             }
        }


        // 4. Delete from Database
        if ($this->Assignment_Submission_Model->delete($submission_id)) {
            $this->session->set_flashdata('success', 'Assignment successfully unsubmited. You may now resubmit.');
        } else {
            $this->session->set_flashdata('error', 'Failed to remove submission from database.');
        }

        // 5. Redirect back to the assignment page
        redirect('/assignment/' . $submission['assignment_id']);
    }
    /**
     * Show the "My Activities" page with tabs
     */
    public function my_activities() {
        $student_id = $this->session->userdata('user_id');
        
        // Use the new model function we will create
        $all_activities = $this->Assignment_Model->get_all_activities_for_student($student_id); 
        
        $upcoming = [];
        $past_due = [];
        $completed = [];
        
        foreach ($all_activities as $activity) {
            $is_submitted = $activity['submission_id'] !== null;
            $is_graded = $activity['grade'] !== null;
            $is_overdue = strtotime($activity['due_date']) < time();

            if ($is_graded) {
                $completed[] = $activity;
            } else if ($is_submitted && $is_overdue) {
                $completed[] = $activity;
            } else if (!$is_submitted && $is_overdue) {
                $past_due[] = $activity;
            } else {
                $upcoming[] = $activity;
            }
        }

        $data['activities_upcoming'] = $upcoming;
        $data['activities_past_due'] = $past_due;
        $data['activities_completed'] = $completed;
        
        $data['page_title'] = 'My Activities';
        
        // We will create this new view file next
        $this->call->view('/student/all_activities', $data);
    }

    /**
     * View detailed results of a taken quiz.
     */
    public function view_quiz_results($quiz_id) {
        $student_id = $this->session->userdata('user_id');
        $this->call->model('Assignment_Submission_Model');

        $quiz = $this->Assignment_Model->find($quiz_id);
        if (!$quiz || $quiz['type'] != 'quiz') {
            $this->session->set_flashdata('error', 'Quiz not found.');
            redirect('/dashboard');
        }

        $submission = $this->Assignment_Submission_Model->check_existing_submission($student_id, $quiz_id);
        if (!$submission) {
            $this->session->set_flashdata('error', 'You have not taken this quiz yet.');
            redirect('/quiz/' . $quiz_id);
            return;
        }

        $data['quiz'] = $quiz;
        $data['submission'] = $submission;
        $data['page_title'] = 'Results: ' . $quiz['title'];
        
        $this->call->view('/student/quiz_results', $data);
    }

}
?>