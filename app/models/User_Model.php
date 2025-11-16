<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: User_Model
 * * Automatically generated via CLI.
 */
class User_Model extends Model {
    protected $table = 'users';
    protected $primary_key = 'user_id'; // Change this to 'user_id' if that is your primary key

     protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password', // Password will be hashed in the controller before insert
        'role',
        'status' // <-- ADDED
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Find a user by their email address
     * @param string $email
     * @return object|false
     */
    public function findUserByEmail($email) {
        // Use the filter() method which is safer
        return $this->filter(['email' => $email])->get();
    }

    /**
     * Create a new user (Note: this method returns void)
     * @param array $data
     */
    public function create($data) {
        // This uses the base Model's insert method
        $this->insert($data);
    }

    /**
     * Get all users from the database.
     * @return array
     */
    public function get_all_users() {
        // Use filter() with an empty array to get all
        return $this->filter([])
                    ->order_by('created_at', 'DESC')
                    ->get_all();
    }

    /**
     * Count all users in the table.
     * @return int
     */
    public function count_all_users() {
        // Use a raw query to bypass the model's 'dirty' query builder state
        $sql = "SELECT COUNT(*) as total FROM " . $this->table;
        $result = $this->db->raw($sql)->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    /**
     * Count users by a specific role.
     * @param string $role (e.g., 'student', 'teacher')
     * @return int
     */
    public function count_by_role($role) {
        // Use a raw query to bypass the model's 'dirty' query builder state
        $sql = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE role = ?";
        $result = $this->db->raw($sql, [$role])->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // --- THIS IS THE CORRECTED FUNCTION ---
    public function get_users_by_role($role) {
        
        if ($role === 'student') {
            // Join to get a list of enrolled courses
            $this->db->table($this->table . ' u')
                     ->select('u.*, GROUP_CONCAT(c.title SEPARATOR ", ") as courses_list')
                     ->where('u.role', $role);
            $this->db->left_join('enrollments e', 'u.user_id = e.student_id AND e.status = "approved"');
            $this->db->left_join('courses c', 'e.course_id = c.course_id');
            
        } else if ($role === 'teacher') {
            // Join to get a list of handled courses
            $this->db->table($this->table . ' u')
                     ->select('u.*, GROUP_CONCAT(c.title SEPARATOR ", ") as courses_list')
                     ->where('u.role', $role);
            $this->db->left_join('courses c', 'u.user_id = c.teacher_id');
        } else {
            // For 'admin' or any other role, just select from the user table
            $this->db->table($this->table . ' u')
                     ->select('u.*')
                     ->where('u.role', $role);
        }
        
        // Group by user to make GROUP_CONCAT work
        $this->db->group_by('u.user_id');
        $this->db->order_by('u.created_at', 'DESC');
        
        return $this->db->get_all();
    }

    /**
     * Get all teachers awaiting approval.
     * @return array
     */
    public function get_pending_teachers() {
        return $this->filter([
                        'role' => 'teacher',
                        'status' => 'pending'
                    ])
                    ->order_by('created_at', 'ASC')
                    ->get_all();
    }
}
?>