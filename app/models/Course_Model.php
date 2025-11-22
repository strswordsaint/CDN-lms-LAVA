<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: Course_Model
 */
class Course_Model extends Model {
    protected $table = 'courses';
    protected $primary_key = 'course_id';
    protected $fillable = ['title', 'description', 'teacher_id', 'enrollment_code'];

    public function __construct() {
        parent::__construct();
    }

    public function get_courses_by_teacher($teacher_id) {
        return $this->filter(['teacher_id' => $teacher_id])->get_all();
    }

    public function find_course($course_id, $teacher_id = null) {
        $conditions = [$this->primary_key => $course_id];
        if ($teacher_id !== null) { $conditions['teacher_id'] = $teacher_id; }
        return $this->filter($conditions)->get();
    }

    public function get_courses_with_stats_by_teacher($teacher_id) {
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.course_id AND e.status = 'approved') as student_count,
                (SELECT COUNT(*) FROM assignments a WHERE a.course_id = c.course_id AND a.type = 'assignment') as assignment_count
                FROM {$this->table} c WHERE c.teacher_id = ? ORDER BY c.created_at DESC";
        return $this->db->raw($sql, [$teacher_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update_course($course_id, $data) {
        try { $this->update($course_id, $data); return true; } catch (Exception $e) { return false; }
    }

    public function delete_course($course_id, $soft_delete_enabled) {
        try { 
            if ($soft_delete_enabled) { $this->soft_delete($course_id); } else { $this->delete($course_id); } 
            return true; 
        } catch (Exception $e) { return false; }
    }

    public function count_all_courses() {
        $this->db->table($this->table);
        return $this->db->count();
    }

    public function get_all_courses_with_teacher() {
        $sql = "SELECT c.*, u.first_name, u.last_name,
                (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.course_id AND e.status = 'approved') as student_count,
                (SELECT COUNT(*) FROM assignments a WHERE a.course_id = c.course_id AND a.type = 'assignment') as assignment_count
                FROM {$this->table} c LEFT JOIN users u ON c.teacher_id = u.user_id ORDER BY c.created_at DESC";
        return $this->db->raw($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * NEW: Get top courses by student enrollment count.
     */
    public function get_popular_courses($limit = 5) {
        $sql = "SELECT c.title, COUNT(e.enrollment_id) as student_count
                FROM courses c
                LEFT JOIN enrollments e ON c.course_id = e.course_id AND e.status = 'approved'
                GROUP BY c.course_id
                ORDER BY student_count DESC
                LIMIT ?";
        return $this->db->raw($sql, [$limit])->fetchAll(PDO::FETCH_ASSOC);
    }
}