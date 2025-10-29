<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Assignment_Model extends Model {
    
    protected $table = 'assignments';
    protected $primary_key = 'assignment_id';
    
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'points',
        'due_date',
        'attachment_path' // <-- ADD THIS LINE
    ];

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all assignments for a specific course.
     */
    public function get_assignments_for_course($course_id) {
        return $this->filter(['course_id' => $course_id])
                    ->order_by('due_date', 'ASC')
                    ->get_all();
    }
}
?>

