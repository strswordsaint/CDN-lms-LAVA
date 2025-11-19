<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: Assignment_Model
 *
 * Manages the 'assignments' table for Announcements, Activities, and Assignments.
 */
class Assignment_Model extends Model {

    protected $table = 'assignments';
    protected $primary_key = 'assignment_id';

    // Fields allowed for mass assignment
    protected $fillable = [
        'course_id',
        'type', // 'announcement', 'activity', 'assignment'
        'title',
        'description',
        'points', 
        'due_date',
    ];


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all posts for a course stream, INCLUDING submission status for a specific student.
     */
    public function get_posts_for_course_stream($course_id, $student_id = null) {
        $sql = "
            SELECT 
                a.*,
                (SELECT COUNT(*) FROM post_replies pr WHERE pr.assignment_id = a.assignment_id) as reply_count,
                s.submission_id, 
                s.grade, 
                s.submitted_at
            FROM 
                {$this->table} a
            LEFT JOIN 
                assignment_submissions s ON a.assignment_id = s.assignment_id AND s.student_id = ?
            WHERE 
                a.course_id = ?
            ORDER BY
                a.created_at DESC
        ";
        // Pass student_id first, then course_id (matching the ? positions)
        return $this->db->raw($sql, [$student_id, $course_id])->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get *only* 'assignment' type posts for a course.
     */
    public function get_assignments_by_course($course_id) {
        return $this->filter([
                        'course_id' => $course_id,
                        'type' => 'assignment'
                    ])
                    ->order_by('due_date', 'ASC') 
                    ->get_all();
    }
    
    /**
     * Get *only* 'activity' type posts for a course.
     */
    public function get_activities_by_course($course_id) {
        return $this->filter([
                        'course_id' => $course_id,
                        'type' => 'activity'
                    ])
                    ->order_by('due_date', 'ASC') 
                    ->get_all();
    }

    /**
     * Find a specific assignment AND check if the teacher owns the course.
     */
     public function find_with_course_check($assignment_id, $teacher_id) {
         $this->db->table($this->table . ' assignments') 
                   ->select('assignments.*, courses.teacher_id')
                   ->join('courses', 'courses.course_id = assignments.course_id')
                   ->where('assignments.assignment_id', $assignment_id);
         
         $assignment = $this->db->get(); 

         if ($assignment && $assignment['teacher_id'] == $teacher_id) {
             return $assignment; 
         }
         return null;
    }

    /**
     * Get recent *assignments* for teacher dashboard.
     */
    public function get_recent_assignments_for_teacher($teacher_id, $limit = 5) {
        $this->db->table($this->table . ' a')
                 ->select('a.assignment_id, a.title, a.due_date, c.title as course_title, c.course_id')
                 ->join('courses c', 'a.course_id = c.course_id')
                 ->where('c.teacher_id', $teacher_id)
                 ->where('a.type', 'assignment') // Only type 'assignment'
                 ->order_by('a.due_date', 'DESC') 
                 ->limit($limit);
        
        return $this->db->get_all();
    }

    /**
     * Count pending *assignments and activities* for a student.
     */
    public function count_pending_for_student($student_id) {
        $sql = "
            SELECT COUNT(a.assignment_id) as pending_count
            FROM assignments a
            JOIN enrollments e ON a.course_id = e.course_id
            LEFT JOIN assignment_submissions s ON a.assignment_id = s.assignment_id AND s.student_id = e.student_id
            WHERE e.student_id = ?
            AND e.status = 'approved'
            AND a.type IN ('assignment', 'activity') -- UPDATED
            AND a.due_date > NOW()
            AND s.submission_id IS NULL
        ";
        $result = $this->db->raw($sql, [$student_id])->fetch(PDO::FETCH_ASSOC);
        return $result['pending_count'] ?? 0;
    }

    /**
     * Get upcoming *assignments and activities* for a student dashboard.
     */
    public function get_upcoming_for_student($student_id, $limit = 5) {
        $sql = "
            SELECT 
                a.assignment_id, a.title, a.due_date, c.title as course_title, a.type
            FROM 
                assignments a
            JOIN 
                enrollments e ON a.course_id = e.course_id
            JOIN 
                courses c ON a.course_id = c.course_id
            LEFT JOIN 
                assignment_submissions s ON a.assignment_id = s.assignment_id AND s.student_id = e.student_id
            WHERE 
                e.student_id = ?
            AND 
                e.status = 'approved'
            AND 
                a.type IN ('assignment', 'activity') -- UPDATED
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
    
    /**
     * Get all *assignments* for a student's "All Assignments" page.
     */
    public function get_all_assignments_for_student($student_id) {
        $sql = "
            SELECT 
                a.assignment_id, a.title, a.due_date, a.points,
                c.title as course_title, c.course_id,
                s.submission_id, s.grade, s.file_path, s.submitted_at
            FROM assignments a
            JOIN courses c ON a.course_id = c.course_id
            JOIN enrollments e ON a.course_id = e.course_id
            LEFT JOIN assignment_submissions s ON a.assignment_id = s.assignment_id 
                                        AND e.student_id = s.student_id
            WHERE 
                e.student_id = ?
            AND 
                e.status = 'approved'
            AND 
                a.type = 'assignment' -- Only type 'assignment'
            ORDER BY 
                a.due_date DESC
        ";
        
        return $this->db->raw($sql, [$student_id])->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * NEW: Get all *activities* for a student's "My Activities" page.
     */
    public function get_all_activities_for_student($student_id) {
        $sql = "
            SELECT 
                a.assignment_id, a.title, a.due_date, a.points,
                c.title as course_title, c.course_id,
                s.submission_id, s.grade, s.file_path, s.submitted_at
            FROM assignments a
            JOIN courses c ON a.course_id = c.course_id
            JOIN enrollments e ON a.course_id = e.course_id
            LEFT JOIN assignment_submissions s ON a.assignment_id = s.assignment_id 
                                        AND e.student_id = s.student_id
            WHERE 
                e.student_id = ?
            AND 
                e.status = 'approved'
            AND 
                a.type = 'activity' -- Only type 'activity'
            ORDER BY 
                a.due_date DESC
        ";
        
        return $this->db->raw($sql, [$student_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all *assignments* for a teacher's "All Assignments" page.
     */
    public function get_all_for_teacher($teacher_id) {
        $sql = "
            SELECT 
                a.assignment_id, a.title, a.due_date, a.points,
                c.title as course_title, c.course_id
            FROM {$this->table} a
            JOIN courses c ON a.course_id = c.course_id
            WHERE 
                c.teacher_id = ?
            AND 
                a.type = 'assignment' -- Only type 'assignment'
            ORDER BY 
                a.due_date DESC
        ";
        
        return $this->db->raw($sql, [$teacher_id])->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * NEW: Get all *activities* for a teacher's "All Activities" page.
     */
    public function get_all_activities_for_teacher($teacher_id) {
        $sql = "
            SELECT 
                a.assignment_id, a.title, a.due_date, a.points,
                c.title as course_title, c.course_id
            FROM {$this->table} a
            JOIN courses c ON a.course_id = c.course_id
            WHERE 
                c.teacher_id = ?
            AND 
                a.type = 'activity' -- Only type 'activity'
            ORDER BY 
                a.due_date DESC
        ";
        return $this->db->raw($sql, [$teacher_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * NEW: Get all assignments and activities for a user's calendar
     */
    public function get_calendar_events_for_user($user_id, $role) {
        if ($role === 'teacher') {
            // Teacher: Get all activities/assignments from courses they OWN
            $sql = "
                SELECT a.assignment_id, a.title, a.due_date, a.type, a.course_id
                FROM assignments a
                JOIN courses c ON a.course_id = c.course_id
                WHERE c.teacher_id = ?
                AND a.type IN ('assignment', 'activity')
                AND a.due_date IS NOT NULL
            ";
            return $this->db->raw($sql, [$user_id])->fetchAll(PDO::FETCH_ASSOC);
            
        } else if ($role === 'student') {
            // Student: Get all activities/assignments from courses they are ENROLLED IN
            $sql = "
                SELECT a.assignment_id, a.title, a.due_date, a.type, a.course_id
                FROM assignments a
                JOIN enrollments e ON a.course_id = e.course_id
                WHERE e.student_id = ?
                AND e.status = 'approved'
                AND a.type IN ('assignment', 'activity')
                AND a.due_date IS NOT NULL
            ";
            return $this->db->raw($sql, [$user_id])->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return []; // Return empty for admin or other roles
    }

    /**
     * NEW: Count all assignments (not activities or announcements).
     */
    public function count_all_assignments() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE type = 'assignment'";
        $result = $this->db->raw($sql)->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * NEW: Get student's weekly stats for the dashboard chart.
     * Counts items (assignments + activities) due from 7 days ago to 7 days from now.
     */
    public function get_student_stats_for_week($student_id) {
        $sql = "
            SELECT 
                -- 1. Count items due in the next 7 days that are not submitted
                COUNT(CASE 
                    WHEN a.due_date BETWEEN NOW() AND NOW() + INTERVAL 7 DAY 
                         AND s.submission_id IS NULL 
                    THEN 1 
                END) as upcoming,
                
                -- 2. Count items due in the last 7 days that ARE submitted or graded
                COUNT(CASE 
                    WHEN a.due_date BETWEEN NOW() - INTERVAL 7 DAY AND NOW() 
                         AND s.submission_id IS NOT NULL 
                    THEN 1 
                END) as completed,
                
                -- 3. Count items due in the last 7 days that are NOT submitted
                COUNT(CASE 
                    WHEN a.due_date BETWEEN NOW() - INTERVAL 7 DAY AND NOW() 
                         AND s.submission_id IS NULL 
                    THEN 1 
                END) as past_due
            
            FROM assignments a
            
            -- Join to get only courses the student is in
            JOIN enrollments e 
                ON a.course_id = e.course_id
            
            -- Left join to check submission status
            LEFT JOIN assignment_submissions s 
                ON a.assignment_id = s.assignment_id AND e.student_id = s.student_id
            
            WHERE 
                e.student_id = ?
            AND 
                e.status = 'approved'
            AND 
                a.type IN ('assignment', 'activity')
            AND
                a.due_date BETWEEN (NOW() - INTERVAL 7 DAY) AND (NOW() + INTERVAL 7 DAY)
        ";
        
        // Use fetch() instead of fetchAll() since we only expect one row
        return $this->db->raw($sql, [$student_id])->fetch(PDO::FETCH_ASSOC);
    }
}
?>