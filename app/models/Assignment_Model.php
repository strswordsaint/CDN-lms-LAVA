<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: Assignment_Model
 *
 * Manages the 'assignments' table.
 */
class Assignment_Model extends Model {

    protected $table = 'assignments';
    protected $primary_key = 'assignment_id';

    // Fields allowed for mass assignment
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'points', // Added points
        'due_date',
        'attachment_path' // Added attachment path
    ];


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all assignments for a specific course.
     *
     * @param int $course_id The ID of the course.
     * @return array List of assignments for that course.
     */
    public function get_assignments_by_course($course_id) {
        // Use the filter method inherited from the base Model
        // Filter where the 'course_id' column matches the provided $course_id
        // This method correctly uses $this->db internally.
        return $this->filter(['course_id' => $course_id])
                    ->order_by('due_date', 'ASC') // Order by due date (optional)
                    ->get_all();
    }

    /**
     * Find a specific assignment AND check if the teacher owns the course it belongs to.
     *
     * @param int $assignment_id The ID of the assignment to find.
     * @param int $teacher_id The ID of the teacher who should own the course.
     * @return object|null The assignment details (including course_id and teacher_id) if found and owned, otherwise null.
     */
     public function find_with_course_check($assignment_id, $teacher_id) {
         // --- THIS IS THE FIX ---
         // Build the query using $this->db->... methods
         $this->db->table($this->table . ' assignments') // Alias the table
                   ->select('assignments.*, courses.teacher_id')
                   ->join('courses', 'courses.course_id = assignments.course_id')
                   ->where('assignments.assignment_id', $assignment_id);
         
         $assignment = $this->db->get(); // Execute the query
         // --- END FIX ---

         if ($assignment && $assignment['teacher_id'] == $teacher_id) {
             return $assignment; // Return assignment if found and teacher matches
         }
         return null; // Otherwise return null
    }

    public function get_recent_assignments_for_teacher($teacher_id, $limit = 5) {
        // Join with courses to filter by teacher_id
        $this->db->table($this->table . ' a')
                 ->select('a.assignment_id, a.title, a.due_date, c.title as course_title, c.course_id')
                 ->join('courses c', 'a.course_id = c.course_id')
                 ->where('c.teacher_id', $teacher_id)
                 ->order_by('a.due_date', 'DESC') // Show most recent first
                 ->limit($limit);
        
        return $this->db->get_all();
    }

    public function count_pending_for_student($student_id) {
        $sql = "
            SELECT COUNT(a.assignment_id) as pending_count
            FROM assignments a
            JOIN enrollments e ON a.course_id = e.course_id
            LEFT JOIN assignment_submissions s ON a.assignment_id = s.assignment_id AND s.student_id = e.student_id
            WHERE e.student_id = ?
            AND e.status = 'approved'
            AND a.due_date > NOW()
            AND s.submission_id IS NULL
        ";
        $result = $this->db->raw($sql, [$student_id])->fetch(PDO::FETCH_ASSOC);
        return $result['pending_count'] ?? 0;
    }

    /**
     * Get upcoming assignment deadlines for a student.
     *
     * @param int $student_id
     * @param int $limit
     * @return array
     */
    public function get_upcoming_for_student($student_id, $limit = 5) {
        $sql = "
            SELECT 
                a.assignment_id, a.title, a.due_date, c.title as course_title
            FROM 
                assignments a
            JOIN 
                enrollments e ON a.course_id = e.course_id
            
            -- THIS IS THE FIX: ADDED THE JOIN TO THE 'courses' TABLE --
            JOIN 
                courses c ON a.course_id = c.course_id
            
            LEFT JOIN 
                assignment_submissions s ON a.assignment_id = s.assignment_id AND s.student_id = e.student_id
            WHERE 
                e.student_id = ?
            AND 
                e.status = 'approved'
            AND 
                a.due_date > NOW()
            AND 
                s.submission_id IS NULL
            ORDER BY 
                a.due_date ASC
            LIMIT ?
        ";
        return $this->db->raw($sql, [$student_id, $limit])->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function get_all_assignments_for_student($student_id) {
        $sql = "
            SELECT 
                a.assignment_id, 
                a.title, 
                a.due_date, 
                a.points,
                c.title as course_title,
                c.course_id,
                s.submission_id, 
                s.grade,
                s.file_path,
                s.submitted_at
            FROM 
                assignments a
            JOIN 
                courses c ON a.course_id = c.course_id
            JOIN 
                enrollments e ON a.course_id = e.course_id
            LEFT JOIN 
                assignment_submissions s ON a.assignment_id = s.assignment_id 
                                        AND e.student_id = s.student_id
            WHERE 
                e.student_id = ?
            AND 
                e.status = 'approved'
            ORDER BY 
                a.due_date DESC
        ";
        
        return $this->db->raw($sql, [$student_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_all_for_teacher($teacher_id) {
        $sql = "
            SELECT 
                a.assignment_id, 
                a.title, 
                a.due_date, 
                a.points,
                c.title as course_title,
                c.course_id
            FROM 
                {$this->table} a
            JOIN 
                courses c ON a.course_id = c.course_id
            WHERE 
                c.teacher_id = ?
            ORDER BY 
                a.due_date DESC
        ";
        
        return $this->db->raw($sql, [$teacher_id])->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>