<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: Site_Announcement_Model
 *
 * Manages the 'site_announcements' table.
 */
class Site_Announcement_Model extends Model {
    
    protected $table = 'site_announcements';
    protected $primary_key = 'announcement_id';
    
    // Fields allowed for mass assignment
    protected $fillable = [
        'admin_id',
        'title',
        'content'
    ];

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all announcements, joined with the admin's name.
     * Newest first.
     *
     * @return array List of announcements.
     */
    public function get_all_with_admin() {
        $this->db->table($this->table . ' sa')
                 ->select('sa.announcement_id, sa.title, sa.content, sa.created_at, u.first_name, u.last_name')
                 ->join('users u', 'sa.admin_id = u.user_id')
                 ->order_by('sa.created_at', 'DESC');

        return $this->db->get_all();
    }
}
?>