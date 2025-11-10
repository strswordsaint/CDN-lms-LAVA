<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: Post_Reply_Model
 *
 * Manages the 'post_replies' table.
 */
class Post_Reply_Model extends Model {
    
    protected $table = 'post_replies';
    protected $primary_key = 'reply_id';
    
    // Fields allowed for mass assignment
    protected $fillable = [
        'assignment_id', // This is the foreign key to the 'assignments' table
        'user_id',
        'content'
    ];

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all replies for a specific post, joined with user info.
     *
     * @param int $post_id (which is an assignment_id)
     * @return array List of replies.
     */
    public function get_replies_for_post($post_id) {
        $this->db->table($this->table . ' r')
                 ->select('r.reply_id, r.content, r.created_at, u.first_name, u.last_name, u.role')
                 ->join('users u', 'r.user_id = u.user_id')
                 ->where('r.assignment_id', $post_id)
                 ->order_by('r.created_at', 'ASC'); // Show oldest first, like a chat

        return $this->db->get_all();
    }
}
?>