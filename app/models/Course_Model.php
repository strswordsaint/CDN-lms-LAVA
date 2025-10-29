<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: Course_Model
 * 
 * Automatically generated via CLI.
 */
class Course_Model extends Model {
    protected $table = 'courses';
    protected $primary_key = 'course_id';
    // Fields allowed for mass assignment
    protected $fillable = ['title', 'description', 'teacher_id', 'enrollment_code',];


    public function __construct()
    {
        parent::__construct();
    }

  /**
     * Get all courses created by a specific teacher.
     *
     * @param int $teacher_id The ID of the teacher.
     * @return array List of courses.
     */
    public function get_courses_by_teacher($teacher_id) {
        // Use the filter method inherited from the base Model
        // to find courses where teacher_id matches
        return $this->filter(['teacher_id' => $teacher_id])->get_all();
        // You could also add ordering, e.g.:
        // return $this->filter(['teacher_id' => $teacher_id])->order_by('created_at', 'DESC')->get_all();
    }

    /**
     * Create a new course.
     * Inherits the insert() method from the base Model.
     * The $fillable property controls which fields are inserted.
     */
     // public function create_course($data) {
     //    return $this->insert($data); // Base Model handles insert
     // }

    /**
     * Find a specific course by its ID (and optionally check teacher ownership).
     *
     * @param int $course_id
     * @param int|null $teacher_id (Optional) Check if this teacher owns the course.
     * @return object|null Course data or null if not found/not owned.
     */
    public function find_course($course_id, $teacher_id = null) {
        $conditions = [$this->primary_key => $course_id];
        if ($teacher_id !== null) {
            $conditions['teacher_id'] = $teacher_id;
        }
        return $this->filter($conditions)->get();
    }

    // You can add update_course and delete_course methods here later,
    // potentially using $this->update() and $this->delete() from the base Model.
}