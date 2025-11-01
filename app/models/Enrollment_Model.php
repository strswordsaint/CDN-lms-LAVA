<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Enrollment_Model extends Model {
    
    protected $table = 'enrollments';
    protected $primary_key = 'enrollment_id';
    
    protected $fillable = [
        'student_id',
        'course_id',
        'status' // Added status
    ];

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Check if a student has a pending or approved enrollment for a course.
     * @param int $student_id
     * @param int $course_id
     * @return bool
     */
    public function has_pending_or_approved_enrollment($student_id, $course_id) {
        // Use the raw query method to check for specific statuses
        $sql = "SELECT 1 FROM {$this->table} 
                WHERE student_id = :student_id 
                AND course_id = :course_id 
                AND status IN ('pending', 'approved') 
                LIMIT 1";
        
        $result = $this->db->raw($sql, [
            ':student_id' => $student_id,
            ':course_id' => $course_id
        ])->fetch();

        return $result ? true : false;
    }

    /**
     * Get enrollments for a specific course, optionally filtered by status.
     * Joins with the users table to get student names.
     *
     * @param int $course_id
     * @param string|null $status 'pending', 'approved', 'rejected', or null for all
     * @return array
     */
    public function get_enrollments_for_course($course_id, $status = null) {
        $this->db->table($this->table . ' e') // Alias the table
                 ->select('e.enrollment_id, e.student_id, e.status, e.enrolled_at, u.first_name, u.last_name, u.email')
                 ->join('users u', 'e.student_id = u.user_id') // Join users table
                 ->where('e.course_id', $course_id);

        if ($status !== null && in_array($status, ['pending', 'approved', 'rejected'])) {
            $this->db->where('e.status', $status);
        }

        return $this->db->get_all(); // Fetch all matching enrollments
    }

    /**
     * Get courses a student is enrolled in (or pending), joined with course details.
     *
     * @param int $student_id
     * @param string|null $status 'pending', 'approved', or null for both
     * @return array
     */
    public function get_student_courses($student_id, $status = null) {
         $this->db->table($this->table . ' e')
                 ->select('e.enrollment_id, c.course_id, c.title, c.description, c.enrollment_code, e.status, e.enrolled_at')
                 ->join('courses c', 'e.course_id = c.course_id') // Join courses table
                 ->where('e.student_id', $student_id);

        if ($status !== null && in_array($status, ['pending', 'approved'])) {
             $this->db->where('e.status', $status);
        } else {
             // Default to showing pending and approved, excluding rejected
             $this->db->in('e.status', ['pending', 'approved']); // <-- NEW (Correct)
        }
         $this->db->order_by('c.title', 'ASC'); // Order by course title
         
        return $this->db->get_all();
    }

    /**
     * Update the status of an enrollment request.
     *
     * @param int $enrollment_id
     * @param string $new_status ('approved' or 'rejected')
     * @return bool TRUE on success, FALSE on failure
     */
    public function update_enrollment_status($enrollment_id, $new_status) {
        if (!in_array($new_status, ['approved', 'rejected'])) {
            return false; // Invalid status
        }
        
        // --- THIS IS THE FIX ---
        // We use try/catch because a void function throws an Exception on failure.
        try {
            // This function returns void, so we don't 'return' it
            $this->update($enrollment_id, ['status' => $new_status]);
            
            // If the line above didn't throw an exception, it succeeded.
            return true;
        } catch (Exception $e) {
            // Log the error if you have a logger
            // log_message('error', $e->getMessage());
            return false;
        }
        // --- END FIX ---
    }

    /**
     * Safely delete an enrollment. (Used by StudentController)
     *
     * @param int $enrollment_id
     * @return bool TRUE on success, FALSE on failure
     */
    public function delete_enrollment($enrollment_id) {
        // --- THIS IS THE 2ND FIX (for the same problem) ---
        // This stops the same 'void' error when a student leaves a course.
        try {
            $this->delete($enrollment_id);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>