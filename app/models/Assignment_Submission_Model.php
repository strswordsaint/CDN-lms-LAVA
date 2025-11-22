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
        'quiz_result_json'
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

    // === NEW GRADEBOOK FUNCTIONS ===

    /**
     * Get the Gradebook Overview for a course.
     * Calculates totals for every student AND SORTS BY RANK.
     */
    public function get_course_gradebook($course_id) {
        // 1. Get all approved students
        $this->db->table('enrollments e')
                 ->select('u.user_id, u.first_name, u.last_name, u.email')
                 ->join('users u', 'e.student_id = u.user_id')
                 ->where('e.course_id', $course_id)
                 ->where('e.status', 'approved');
                 // Removed order_by name, we will sort by grade later
        
        $students = $this->db->get_all();
        
        // 2. Get all GRADED items
        $sql_assignments = "SELECT assignment_id, points FROM assignments WHERE course_id = ? AND type != ? AND points > ?";
        $assignments = $this->db->raw($sql_assignments, [$course_id, 'announcement', 0])->fetchAll(PDO::FETCH_ASSOC);
        
        $course_total_points = 0;
        foreach ($assignments as $a) {
            $course_total_points += $a['points'];
        }

        // 3. Calculate stats for each student
        $gradebook = [];
        foreach ($students as $student) {
            $sql = "SELECT SUM(s.grade) as total_earned
                    FROM assignment_submissions s
                    JOIN assignments a ON s.assignment_id = a.assignment_id
                    WHERE s.student_id = ? AND a.course_id = ? AND s.grade IS NOT NULL";
            
            $result = $this->db->raw($sql, [$student['user_id'], $course_id])->fetch(PDO::FETCH_ASSOC);
            $earned = $result['total_earned'] ?? 0;
            
            $sql_ungraded = "SELECT COUNT(*) as count
                             FROM assignment_submissions s
                             JOIN assignments a ON s.assignment_id = a.assignment_id
                             WHERE s.student_id = ? AND a.course_id = ? AND s.grade IS NULL";
            $ungraded = $this->db->raw($sql_ungraded, [$student['user_id'], $course_id])->fetch(PDO::FETCH_ASSOC);

            $percentage = ($course_total_points > 0) ? round(($earned / $course_total_points) * 100, 1) : 0;

            $gradebook[] = [
                'student_id' => $student['user_id'],
                'name'       => $student['first_name'] . ' ' . $student['last_name'],
                'email'      => $student['email'],
                'earned'     => $earned,
                'total'      => $course_total_points,
                'percentage' => $percentage,
                'ungraded'   => $ungraded['count'] ?? 0
            ];
        }
        
        // 4. SORT BY PERCENTAGE (DESCENDING)
        usort($gradebook, function ($a, $b) {
            if ($a['percentage'] == $b['percentage']) {
                return 0;
            }
            return ($a['percentage'] > $b['percentage']) ? -1 : 1;
        });
        
        return $gradebook;
    }

    /**
     * Get detailed assignment status for a specific student in a course.
     * Used for the AJAX Grade Modal.
     */
    public function get_student_detailed_grades($course_id, $student_id) {
        $sql = "
            SELECT 
                a.assignment_id, 
                a.title, 
                a.points, 
                a.due_date, 
                a.type,
                s.submission_id, 
                s.grade, 
                s.submitted_at, 
                s.file_path
            FROM assignments a
            LEFT JOIN assignment_submissions s 
                ON a.assignment_id = s.assignment_id AND s.student_id = ?
            WHERE a.course_id = ? 
            AND a.type IN ('assignment', 'activity', 'quiz')
            ORDER BY a.due_date DESC
        ";
                
        return $this->db->raw($sql, [$student_id, $course_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get overall grade percentage for all courses a student is enrolled in.
     */
    public function get_student_overall_grades($student_id) {
        // 1. Get Enrolled Courses
        $sql_courses = "SELECT c.course_id, c.title 
                        FROM enrollments e 
                        JOIN courses c ON e.course_id = c.course_id 
                        WHERE e.student_id = ? AND e.status = 'approved'";
        $courses = $this->db->raw($sql_courses, [$student_id])->fetchAll(PDO::FETCH_ASSOC);

        $results = [];

        foreach($courses as $course) {
            // 2. Calculate Total Possible Points
            $sql_total = "SELECT SUM(points) as total 
                          FROM assignments 
                          WHERE course_id = ? AND type != 'announcement' AND points > 0";
            $total = $this->db->raw($sql_total, [$course['course_id']])->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // 3. Calculate Total Earned Points
            $sql_earned = "SELECT SUM(s.grade) as earned 
                           FROM assignment_submissions s 
                           JOIN assignments a ON s.assignment_id = a.assignment_id 
                           WHERE s.student_id = ? AND a.course_id = ? AND s.grade IS NOT NULL";
            $earned = $this->db->raw($sql_earned, [$student_id, $course['course_id']])->fetch(PDO::FETCH_ASSOC)['earned'] ?? 0;

            // 4. Calculate Percentage
            $percentage = ($total > 0) ? round(($earned / $total) * 100, 1) : 0;

            $results[] = [
                'course' => $course['title'],
                'grade' => $percentage
            ];
        }
        return $results;
    }
    /**
     * ADMIN REPORT: Get top 5 students based on overall grade percentage.
     */
    public function get_top_students_system_wide($limit = 5) {
        $sql = "
            SELECT 
                u.first_name, 
                u.last_name, 
                u.email,
                (SUM(s.grade) / SUM(a.points)) * 100 as percentage
            FROM users u
            JOIN assignment_submissions s ON u.user_id = s.student_id
            JOIN assignments a ON s.assignment_id = a.assignment_id
            WHERE s.grade IS NOT NULL AND a.points > 0
            GROUP BY u.user_id
            ORDER BY percentage DESC
            LIMIT ?
        ";
        return $this->db->raw($sql, [$limit])->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ADMIN REPORT: Get top 5 courses based on average student performance.
     */
    public function get_course_performance_ranking($limit = 5) {
        $sql = "
            SELECT 
                c.title, 
                u.first_name, 
                u.last_name,
                AVG((s.grade / a.points) * 100) as average_percentage,
                COUNT(DISTINCT s.student_id) as student_count
            FROM courses c
            JOIN users u ON c.teacher_id = u.user_id
            JOIN assignments a ON c.course_id = a.course_id
            JOIN assignment_submissions s ON a.assignment_id = s.assignment_id
            WHERE s.grade IS NOT NULL AND a.points > 0
            GROUP BY c.course_id
            ORDER BY average_percentage DESC
            LIMIT ?
        ";
        return $this->db->raw($sql, [$limit])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count_submissions_by_date($range) {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table;
        
        if ($range == 'weekly') {
            $sql .= " WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
        } elseif ($range == 'monthly') {
            $sql .= " WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        } elseif ($range == 'yearly') {
            $sql .= " WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
        }
        
        $result = $this->db->raw($sql)->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // --- NEW: Get data for the Chart ---
    public function get_submission_trends() {
        // Get submissions for the last 6 months
        $sql = "SELECT DATE_FORMAT(submitted_at, '%Y-%m') as month, COUNT(*) as count
                FROM assignment_submissions
                WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY month
                ORDER BY month ASC";
        return $this->db->raw($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the system-wide average grade for ALL students who have graded work.
     * Returns an associative array: [user_id => percentage]
     */
    public function get_all_student_averages() {
        $sql = "
            SELECT 
                u.user_id,
                (SUM(s.grade) / SUM(a.points)) * 100 as average_grade
            FROM users u
            JOIN assignment_submissions s ON u.user_id = s.student_id
            JOIN assignments a ON s.assignment_id = a.assignment_id
            WHERE s.grade IS NOT NULL AND a.points > 0
            GROUP BY u.user_id
        ";
        
        $results = $this->db->raw($sql)->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert to a simple key-value pair array [user_id => grade] for easy lookup
        $grades = [];
        foreach ($results as $row) {
            $grades[$row['user_id']] = $row['average_grade'];
        }
        return $grades;
    }
    
}
?>