<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: Assignment_Attachment_Model
 *
 * Manages the 'assignment_attachments' table.
 */
class Assignment_Attachment_Model extends Model {
    
    protected $table = 'assignment_attachments';
    protected $primary_key = 'attachment_id';
    
    // Fields allowed for mass assignment
    protected $fillable = [
        'assignment_id',
        'file_name',
        'file_path'
    ];

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all attachments for a specific assignment.
     *
     * @param int $assignment_id
     * @return array
     */
    public function get_for_assignment($assignment_id) {
        return $this->filter(['assignment_id' => $assignment_id])
                    ->order_by('file_name', 'ASC')
                    ->get_all();
    }
}
?>