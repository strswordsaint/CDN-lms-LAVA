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
        'submitted_at', // Usually set by default in DB
        'grade',
        'feedback'
    ];

    public function __construct() {
        parent::__construct();
    }

    /**
     * Check if a student has already submitted for a specific assignment.
     *
     * @param int $student_id
     * @param int $assignment_id
     * @return object|null Returns the submission record if found, otherwise null.
     */
    public function check_existing_submission($student_id, $assignment_id) {
        return $this->filter([
            'student_id' => $student_id,
            'assignment_id' => $assignment_id
        ])->get();
    }

    public function get_submissions_for_assignment($assignment_id) {
        $this->db->table($this->table . ' s') // Alias submissions table as 's'
                 ->select('s.submission_id, s.student_id, s.file_path, s.submitted_at, s.grade, s.feedback, u.first_name, u.last_name, u.email')
                 ->join('users u', 's.student_id = u.user_id') // Join users table aliased as 'u'
                 ->where('s.assignment_id', $assignment_id)
                 ->order_by('s.submitted_at', 'DESC'); // Show newest submissions first

        return $this->db->get_all();
    }

    public function get_submission_details($submission_id) {
        $this->db->table($this->table . ' s')
                 ->select('s.*, u.first_name, u.last_name, u.email, a.title as assignment_title, a.points as assignment_points, a.course_id')
                 ->join('users u', 's.student_id = u.user_id')
                 ->join('assignments a', 's.assignment_id = a.assignment_id')
                 ->where('s.submission_id', $submission_id);

        return $this->db->get(); // Get a single record
    }

    public function update_grade($submission_id, $grade, $feedback) {
        $data = [
            'grade' => $grade,
            'feedback' => $feedback
        ];
        return $this->update($submission_id, $data);
    }

    public function count_ungraded_for_teacher($teacher_id) {
        // We need to join submissions -> assignments -> courses
        // to find the ones owned by this teacher
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

    /**
     * NEW: Count all submissions in the system.
     */
    public function count_all_submissions() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->raw($sql)->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
?>