<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: Assignment_Submission_Model
 *
 * Manages the 'assignment_submissions' table.
 */
class Assignment_Submission_Model extends Model {
    
    protected $table = 'assignment_submissions';
    protected $primary_key = 'submission_id';
    
    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_path',
        'submitted_at', 
        'grade',
        'feedback',
        'quiz_result_json' // Ensure this is fillable
    ];

    public function __construct() {
        parent::__construct();
    }

    public function check_existing_submission($student_id, $assignment_id) {
        return $this->filter([
            'student_id' => $student_id,
            'assignment_id' => $assignment_id
        ])->get();
    }

    public function get_submissions_for_assignment($assignment_id) {
        $this->db->table($this->table . ' s')
                 // === UPDATED SELECT to include quiz_result_json ===
                 ->select('s.submission_id, s.student_id, s.file_path, s.submitted_at, s.grade, s.feedback, s.quiz_result_json, u.first_name, u.last_name, u.email')
                 ->join('users u', 's.student_id = u.user_id')
                 ->where('s.assignment_id', $assignment_id)
                 ->order_by('s.submitted_at', 'DESC');

        return $this->db->get_all();
    }

    public function get_submission_details($submission_id) {
        $this->db->table($this->table . ' s')
                 ->select('s.*, u.first_name, u.last_name, u.email, a.title as assignment_title, a.points as assignment_points, a.course_id')
                 ->join('users u', 's.student_id = u.user_id')
                 ->join('assignments a', 's.assignment_id = a.assignment_id')
                 ->where('s.submission_id', $submission_id);

        return $this->db->get(); 
    }

    public function update_grade($submission_id, $grade, $feedback) {
        $data = [
            'grade' => $grade,
            'feedback' => $feedback
        ];
        return $this->update($submission_id, $data);
    }

    public function count_ungraded_for_teacher($teacher_id) {
        $sql = "
            SELECT COUNT(s.submission_id) as ungraded_count
            FROM {$this->table} s
            JOIN assignments a ON s.assignment_id = a.assignment_id
            JOIN courses c ON a.course_id = c.course_id
            WHERE c.teacher_id = ?
            AND s.grade IS NULL
        ";
        $result = $this->db->raw($sql, [$teacher_id])->fetch(PDO::FETCH_ASSOC);
        return $result['ungraded_count'] ?? 0;
    }

    public function get_all_ungraded_by_teacher($teacher_id) {
        $sql = "
            SELECT 
                s.submission_id,
                s.submitted_at,
                a.assignment_id,
                a.title AS assignment_title,
                c.course_id,
                c.title AS course_title,
                u.first_name,
                u.last_name
            FROM 
                assignment_submissions s
            JOIN 
                assignments a ON s.assignment_id = a.assignment_id
            JOIN 
                courses c ON a.course_id = c.course_id
            JOIN
                users u ON s.student_id = u.user_id
            WHERE 
                c.teacher_id = ?
            AND 
                s.grade IS NULL
            ORDER BY
                c.title ASC, 
                a.due_date ASC, 
                s.submitted_at ASC
        ";
        
        return $this->db->raw($sql, [$teacher_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count_all_submissions() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->raw($sql)->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * NEW: Get submission counts for the last 7 days to populate chart.
     */
    public function get_submission_activity_7days($teacher_id) {
        $sql = "
            SELECT DATE(s.submitted_at) as activity_date, COUNT(*) as submission_count
            FROM {$this->table} s
            JOIN assignments a ON s.assignment_id = a.assignment_id
            JOIN courses c ON a.course_id = c.course_id
            WHERE c.teacher_id = ?
            AND s.submitted_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
            GROUP BY DATE(s.submitted_at)
            ORDER BY activity_date ASC
        ";
        return $this->db->raw($sql, [$teacher_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * NEW: Get top assignments that need grading.
     */
    public function get_priority_grading_list($teacher_id, $limit = 3) {
        $sql = "
            SELECT a.title, c.title as course_title, COUNT(s.submission_id) as ungraded_count, a.assignment_id
            FROM assignment_submissions s
            JOIN assignments a ON s.assignment_id = a.assignment_id
            JOIN courses c ON a.course_id = c.course_id
            WHERE c.teacher_id = ?
            AND s.grade IS NULL
            GROUP BY a.assignment_id
            ORDER BY ungraded_count DESC
            LIMIT ?
        ";
        return $this->db->raw($sql, [$teacher_id, $limit])->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>