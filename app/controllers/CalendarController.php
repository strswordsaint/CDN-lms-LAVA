<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: CalendarController
 * * Handles the new calendar page for all users
 */
class CalendarController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->database();
        $this->call->model('Assignment_Model');
        $this->call->library('session');
        $this->call->helper('url');
        
        $this->check_auth();
    }

    protected function check_auth() {
        if (!$this->session->has_userdata('user_id')) {
             $this->session->set_flashdata('error', 'Please login to access this section.');
            redirect('/auth/login');
            exit;
        }
    }

    public function index() {
        $data['page_title'] = 'My Calendar';
        $this->call->view('/calendar/index', $data);
    }

    public function get_events() {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');
        
        $events = $this->Assignment_Model->get_calendar_events_for_user($user_id, $role);
        
        $calendar_events = [];
        foreach($events as $event) {
            
            // Determine URL based on role
            $url = '';
            if($role == 'teacher') {
                // Teacher goes to grading/edit view or main list
                 $url = site_url('/assignments/' . $event['assignment_id'] . '/submissions');
            } else {
                // Student goes to submit/view view
                $isQuiz = ($event['type'] == 'quiz');
                $url = site_url(($isQuiz ? '/quiz/' : '/assignment/') . $event['assignment_id']);
            }
            
            // === NEW: Color Coding to match Dashboard ===
            $color = '#3b82f6'; // Default Blue (Assignment)
            $borderColor = '#2563eb';
            
            if($event['type'] == 'activity') {
                $color = '#f59e0b'; // Amber (Activity)
                $borderColor = '#d97706';
            } elseif($event['type'] == 'quiz') {
                $color = '#9333ea'; // Purple (Quiz)
                $borderColor = '#7e22ce';
            }
            
            $calendar_events[] = [
                'title' => $event['title'],
                'start' => $event['due_date'],
                'url'   => $url,
                'backgroundColor' => $color,
                'borderColor' => $borderColor,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'type' => ucfirst($event['type'])
                ]
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($calendar_events);
        exit;
    }
}
?>