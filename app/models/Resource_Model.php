<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: Resource_Model
 *
 * Manages the 'course_materials' table.
 */
class Resource_Model extends Model {
    
    protected $table = 'course_materials';
    protected $primary_key = 'material_id';
    
    // Fields allowed for mass assignment
    protected $fillable = [
        'course_id',
        'file_name',
        'file_path'
    ];

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all materials for a specific course.
     *
     * @param int $course_id The ID of the course.
     * @return array List of materials for that course.
     */
    public function get_for_course($course_id) {
        return $this->filter(['course_id' => $course_id])
                    ->order_by('uploaded_at', 'DESC')
                    ->get_all();
    }
}
?>