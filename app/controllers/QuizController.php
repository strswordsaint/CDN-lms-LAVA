<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class QuizController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->database();
        $this->call->model('Assignment_Model');
        $this->call->model('Assignment_Submission_Model');
        $this->call->model('Course_Model');
        $this->call->model('Enrollment_Model');
        $this->call->library('session');
        $this->call->helper('url');
        $this->check_auth(); 
    }

    protected function check_auth() {
        if (!$this->session->has_userdata('user_id')) {
            redirect('/auth/login');
            exit;
        }
    }

    /**
     * TEACHER: Show the Drag-and-Drop Quiz Builder
     */
    public function create($course_id) {
        if ($this->session->userdata('role') == 'student') {
            redirect('/dashboard');
        }

        $data['course_id'] = $course_id;
        $data['page_title'] = 'Create Quiz';
        $this->call->view('/quizzes/create', $data);
    }

    /**
     * TEACHER: Save the Quiz to the Database
     */
    public function store() {
        $course_id = $this->io->post('course_id');
        
        $data = [
            'course_id'   => $course_id,
            'type'        => 'quiz',
            'title'       => $this->io->post('title'),
            'description' => 'Complete this quiz by the due date.',
            'due_date'    => $this->io->post('due_date'),
            'points'      => $this->io->post('points'),
            'quiz_data'   => $this->io->post('quiz_data')
        ];

        if ($this->Assignment_Model->insert($data)) {
            $this->session->set_flashdata('success', 'Quiz created successfully!');
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save quiz.']);
        }
        exit;
    }

    /**
     * TEACHER: Show the Quiz Builder with existing data
     */
    public function edit($assignment_id) {
        if ($this->session->userdata('role') == 'student') redirect('/dashboard');

        $quiz = $this->Assignment_Model->find($assignment_id);
        
        if (!$quiz || $quiz['type'] != 'quiz') {
            redirect('/courses');
        }

        $data['quiz'] = $quiz;
        $data['course_id'] = $quiz['course_id'];
        $data['page_title'] = 'Edit Quiz: ' . $quiz['title'];
        
        $this->call->view('/quizzes/edit', $data);
    }

    /**
     * TEACHER: Update the Quiz
     */
    public function update($assignment_id) {
        $data = [
            'title'       => $this->io->post('title'),
            'due_date'    => $this->io->post('due_date'),
            'points'      => $this->io->post('points'),
            'quiz_data'   => $this->io->post('quiz_data')
        ];

        if ($this->Assignment_Model->update($assignment_id, $data)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update quiz.']);
        }
        exit;
    }

    /**
     * STUDENT: Take the Quiz
     */
    public function take($assignment_id) {
        $user_id = $this->session->userdata('user_id');
        
        $quiz = $this->Assignment_Model->find($assignment_id);
        
        if (!$quiz || $quiz['type'] != 'quiz') {
            $this->session->set_flashdata('error', 'Quiz not found.');
            redirect('/dashboard');
        }

        $submission = $this->Assignment_Submission_Model->check_existing_submission($user_id, $assignment_id);
        if ($submission) {
            $this->session->set_flashdata('error', 'You have already taken this quiz.');
            redirect('/my-courses/' . $quiz['course_id']);
            return;
        }

        $data['quiz'] = $quiz;
        $data['page_title'] = 'Take Quiz: ' . $quiz['title'];
        $this->call->view('/quizzes/take', $data);
    }

    /**
     * STUDENT: Submit Quiz Results
     */
    public function submit_results($assignment_id) {
        $user_id = $this->session->userdata('user_id');
        
        $json_result = $this->io->post('results');
        $score = $this->io->post('score');
        $max_points = $this->io->post('max_points');

        $data = [
            'assignment_id' => $assignment_id,
            'student_id'    => $user_id,
            'submitted_at'  => date('Y-m-d H:i:s'),
            'file_path'     => '', 
            'grade'         => $score,
            'quiz_result_json' => $json_result
        ];

        $this->Assignment_Submission_Model->insert($data);

        echo json_encode(['status' => 'success']);
        exit;
    }
}
?>