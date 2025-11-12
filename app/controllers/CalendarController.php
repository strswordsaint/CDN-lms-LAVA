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
        $this->call->model('Assignment_Model'); // We need this
        $this->call->library('session');
        $this->call->helper('url');
        
        // Secure this entire controller
        $this->check_auth();
    }

    /**
     * Middleware to check if user is logged in
     */
    protected function check_auth() {
        if (!$this->session->has_userdata('user_id')) {
             $this->session->set_flashdata('error', 'Please login to access this section.');
            redirect('/auth/login');
            exit;
        }
    }

    /**
     * Show the calendar view page
     */
    public function index() {
        $data['page_title'] = 'My Calendar';
        $this->call->view('/calendar/index', $data);
    }

    /**
     * Provides the JSON data feed for FullCalendar
     */
    public function get_events() {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');
        
        // Get events from the model
        $events = $this->Assignment_Model->get_calendar_events_for_user($user_id, $role);
        
        $calendar_events = [];
        foreach($events as $event) {
            
            // Determine the URL
            $url = '';
            if($role == 'teacher') {
                $url = site_url('/courses/show/' . $event['course_id'] . '?tab=' . $event['type'] . 's');
            } else {
                $url = site_url('/assignment/' . $event['assignment_id']);
            }
            
            // Determine color based on type
            $color = '#1d4ed8'; // Default blue for assignment
            if($event['type'] == 'activity') {
                $color = '#f59e0b'; // Yellow for activity
            }
            
            $calendar_events[] = [
                'title' => $event['title'],
                'start' => $event['due_date'], // FullCalendar understands this
                'url'   => $url,
                'color' => $color
            ];
        }

        // Send the JSON response
        header('Content-Type: application/json');
        echo json_encode($calendar_events);
        exit;
    }
}
?>