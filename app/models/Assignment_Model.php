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
        'type', // 'announcement', 'activity', 'assignment', 'quiz'
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
                c.title as course_title,
                (SELECT COUNT(*) FROM post_replies pr WHERE pr.assignment_id = a.assignment_id) as reply_count,
                s.submission_id, 
                s.grade, 
                s.submitted_at
            FROM 
                {$this->table} a
            JOIN 
                courses c ON a.course_id = c.course_id
            LEFT JOIN 
                assignment_submissions s ON a.assignment_id = s.assignment_id AND s.student_id = ?
            WHERE 
                a.course_id = ?
            ORDER BY
                a.created_at DESC
        ";
        return $this->db->raw($sql, [$student_id, $course_id])->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function get_assignments_by_course($course_id) {
        return $this->filter([
                        'course_id' => $course_id,
                        'type' => 'assignment'
                    ])
                    ->order_by('due_date', 'ASC') 
                    ->get_all();
    }
    
    public function get_activities_by_course($course_id) {
        // UPDATED: Include Quizzes here too if needed, or keep strict
        return $this->filter([
                        'course_id' => $course_id,
                        'type' => 'activity' 
                    ])
                    ->order_by('due_date', 'ASC') 
                    ->get_all();
    }

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

    public function get_recent_assignments_for_teacher($teacher_id, $limit = 5) {
        $this->db->table($this->table . ' a')
                 ->select('a.assignment_id, a.title, a.due_date, c.title as course_title, c.course_id')
                 ->join('courses c', 'a.course_id = c.course_id')
                 ->where('c.teacher_id', $teacher_id)
                 ->where('a.type', 'assignment')
                 ->order_by('a.due_date', 'DESC') 
                 ->limit($limit);
        
        return $this->db->get_all();
    }

    /**
     * Count pending assignments, activities, AND QUIZZES for a student.
     */
    public function count_pending_for_student($student_id) {
        $sql = "
            SELECT COUNT(a.assignment_id) as pending_count
            FROM assignments a
            JOIN enrollments e ON a.course_id = e.course_id
            LEFT JOIN assignment_submissions s ON a.assignment_id = s.assignment_id AND s.student_id = e.student_id
            WHERE e.student_id = ?
            AND e.status = 'approved'
            AND a.type IN ('assignment', 'activity', 'quiz') -- UPDATED: Added quiz
            AND a.due_date > NOW()
            AND s.submission_id IS NULL
        ";
        $result = $this->db->raw($sql, [$student_id])->fetch(PDO::FETCH_ASSOC);
        return $result['pending_count'] ?? 0;
    }

    /**
     * Get upcoming items (including quizzes) for student dashboard.
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
                a.type IN ('assignment', 'activity', 'quiz') -- UPDATED: Added quiz
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
                a.assignment_id, a.title, a.due_date, a.points, a.type,
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
                a.type = 'assignment'
            ORDER BY 
                a.due_date DESC
        ";
        
        return $this->db->raw($sql, [$student_id])->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * UPDATED: Get all activities AND QUIZZES for a student.
     */
    public function get_all_activities_for_student($student_id) {
        $sql = "
            SELECT 
                a.assignment_id, a.title, a.due_date, a.points, a.type, -- Ensure type is selected
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
                a.type IN ('activity', 'quiz') -- UPDATED: Include quiz
            ORDER BY 
                a.due_date DESC
        ";
        
        return $this->db->raw($sql, [$student_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_all_for_teacher($teacher_id) {
        $sql = "
            SELECT 
                a.assignment_id, a.title, a.due_date, a.points, a.type,
                c.title as course_title, c.course_id
            FROM {$this->table} a
            JOIN courses c ON a.course_id = c.course_id
            WHERE 
                c.teacher_id = ?
            AND 
                a.type = 'assignment'
            ORDER BY 
                a.due_date DESC
        ";
        
        return $this->db->raw($sql, [$teacher_id])->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * UPDATED: Get all activities AND QUIZZES for a teacher.
     */
    public function get_all_activities_for_teacher($teacher_id) {
        $sql = "
            SELECT 
                a.assignment_id, a.title, a.due_date, a.points, a.type, -- Ensure type is selected
                c.title as course_title, c.course_id
            FROM {$this->table} a
            JOIN courses c ON a.course_id = c.course_id
            WHERE 
                c.teacher_id = ?
            AND 
                a.type IN ('activity', 'quiz') -- UPDATED: Include quiz
            ORDER BY 
                a.due_date DESC
        ";
        return $this->db->raw($sql, [$teacher_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_calendar_events_for_user($user_id, $role) {
        if ($role === 'teacher') {
            $sql = "
                SELECT a.assignment_id, a.title, a.due_date, a.type, a.course_id
                FROM assignments a
                JOIN courses c ON a.course_id = c.course_id
                WHERE c.teacher_id = ?
                AND a.type IN ('assignment', 'activity', 'quiz') -- Added quiz
                AND a.due_date IS NOT NULL
            ";
            return $this->db->raw($sql, [$user_id])->fetchAll(PDO::FETCH_ASSOC);
            
        } else if ($role === 'student') {
            $sql = "
                SELECT a.assignment_id, a.title, a.due_date, a.type, a.course_id
                FROM assignments a
                JOIN enrollments e ON a.course_id = e.course_id
                WHERE e.student_id = ?
                AND e.status = 'approved'
                AND a.type IN ('assignment', 'activity', 'quiz') -- Added quiz
                AND a.due_date IS NOT NULL
            ";
            return $this->db->raw($sql, [$user_id])->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return [];
    }

    public function count_all_assignments() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE type = 'assignment'";
        $result = $this->db->raw($sql)->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function get_student_stats_for_week($student_id) {
        $sql = "
            SELECT 
                COUNT(CASE 
                    WHEN a.due_date BETWEEN NOW() AND NOW() + INTERVAL 7 DAY 
                         AND s.submission_id IS NULL 
                    THEN 1 
                END) as upcoming,
                
                COUNT(CASE 
                    WHEN a.due_date BETWEEN NOW() - INTERVAL 7 DAY AND NOW() 
                         AND s.submission_id IS NOT NULL 
                    THEN 1 
                END) as completed,
                
                COUNT(CASE 
                    WHEN a.due_date BETWEEN NOW() - INTERVAL 7 DAY AND NOW() 
                         AND s.submission_id IS NULL 
                    THEN 1 
                END) as past_due
            
            FROM assignments a
            JOIN enrollments e ON a.course_id = e.course_id
            LEFT JOIN assignment_submissions s ON a.assignment_id = s.assignment_id AND e.student_id = s.student_id
            
            WHERE 
                e.student_id = ?
            AND 
                e.status = 'approved'
            AND 
                a.type IN ('assignment', 'activity', 'quiz') -- Added quiz
            AND
                a.due_date BETWEEN (NOW() - INTERVAL 7 DAY) AND (NOW() + INTERVAL 7 DAY)
        ";
        
        return $this->db->raw($sql, [$student_id])->fetch(PDO::FETCH_ASSOC);
    }
}
?>