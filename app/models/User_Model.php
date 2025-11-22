<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: User_Model
 */
class User_Model extends Model {
    protected $table = 'users';
    protected $primary_key = 'user_id';
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'role', 'status', 'suspension_reason'];

    public function __construct() {
        parent::__construct();
    }

    public function findUserByEmail($email) {
        return $this->filter(['email' => $email])->get();
    }

    public function create($data) {
        $this->insert($data);
    }

    public function get_all_users() {
        return $this->filter([])->order_by('created_at', 'DESC')->get_all();
    }

    public function count_all_users() {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table;
        $result = $this->db->raw($sql)->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function count_by_role($role) {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE role = ?";
        $result = $this->db->raw($sql, [$role])->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function get_users_by_role($role) {
        if ($role === 'student') {
            $this->db->table($this->table . ' u')
                     ->select('u.*, GROUP_CONCAT(c.title SEPARATOR ", ") as courses_list')
                     ->where('u.role', $role);
            $this->db->left_join('enrollments e', 'u.user_id = e.student_id AND e.status = "approved"');
            $this->db->left_join('courses c', 'e.course_id = c.course_id');
        } else if ($role === 'teacher') {
            $this->db->table($this->table . ' u')
                     ->select('u.*, GROUP_CONCAT(c.title SEPARATOR ", ") as courses_list')
                     ->where('u.role', $role);
            $this->db->left_join('courses c', 'u.user_id = c.teacher_id');
        } else {
            $this->db->table($this->table . ' u')->select('u.*')->where('u.role', $role);
        }
        $this->db->group_by('u.user_id')->order_by('u.created_at', 'DESC');
        return $this->db->get_all();
    }

    public function get_pending_teachers() {
        return $this->filter(['role' => 'teacher', 'status' => 'pending'])
                    ->order_by('created_at', 'ASC')
                    ->get_all();
    }

    /**
     * NEW: Get registration counts per month for the last 6 months.
     */
    public function get_registration_trends() {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%b') as month_label, COUNT(*) as count
                FROM users
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY month_label
                ORDER BY created_at ASC";
        return $this->db->raw($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count_users_by_date($range) {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table;
        
        if ($range == 'weekly') {
            $sql .= " WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
        } elseif ($range == 'monthly') {
            $sql .= " WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        } elseif ($range == 'yearly') {
            $sql .= " WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
        }
        // 'all' needs no WHERE clause
        
        $result = $this->db->raw($sql)->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
?>