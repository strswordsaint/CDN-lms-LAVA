<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: User_Model
 * 
 * Automatically generated via CLI.
 */
class User_Model extends Model {
    protected $table = 'users';
    protected $primary_key = 'id';

     protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password', // Password will be hashed in the controller before insert
        'role'
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
        $this->db->query("SELECT * FROM {$this->table} WHERE email = :email");
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();

        return ($this->db->rowCount() > 0) ? $row : false;
    }

    /**
     * Create a new user
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $this->db->query("INSERT INTO {$this->table} (username, email, password, role) 
                         VALUES (:username, :email, :password, :role)");
        
        // Bind values
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role']);

        // Execute
        return $this->db->execute();
    }
}