<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentModel extends Model {
    protected $table = 'students';
    protected $primary_key = 'id';
    protected $fillable = ['first_name', 'last_name', 'email'];
    protected $has_soft_delete = true;
    protected $soft_delete_column = 'deleted_at';

    public function __construct()
    {
        parent::__construct();
    }

    // Get all active students (excluding soft-deleted) - Using ORM with proper ordering
    public function get_all_students() {
        return $this->order_by('created_at', 'DESC', false)->get_all(); // Order by newest first
    }

    // Get student by ID (excluding soft-deleted) - Using ORM
    public function get_student_by_id($id) {
        return $this->find($id, false); // false = exclude soft-deleted
    }

    // Create new student - Using ORM
    public function create_student($data) {
        return $this->insert($data);
    }

    // Update student - Using ORM
    public function update_student($id, $data) {
        return $this->update($id, $data);
    }

    // Soft delete student - Using ORM
    public function soft_delete_student($id) {
        return $this->soft_delete($id);
    }

    // Restore soft-deleted student - Using ORM
    public function restore_student($id) {
        return $this->restore($id);
    }

    // Get soft-deleted students - Using QueryBuilder with proper ordering
    public function get_deleted_students() {
        return $this->db->table($this->table)
                        ->where_not_null('deleted_at')
                        ->order_by('deleted_at', 'DESC')
                        ->get_all();
    }

    // Count active students - Using ORM
    public function count_active_students() {
        return $this->count(false); // false = exclude soft-deleted
    }

    // Count deleted students - Using QueryBuilder
    public function count_deleted_students() {
        return $this->db->table($this->table)
                        ->where_not_null('deleted_at')
                        ->count();
    }

    // Check if email exists (excluding soft-deleted) - Using QueryBuilder
    public function email_exists($email, $exclude_id = null) {
        $query = $this->db->table($this->table)
                          ->where('email', $email)
                          ->where_null('deleted_at');
        
        if ($exclude_id) {
            $query->where('id', '!=', $exclude_id);
        }
        
        $result = $query->get();
        return $result ? true : false;
    }

    // Hard delete student (permanent deletion) - Using ORM
    public function hard_delete_student($id) {
        return $this->delete($id);
    }

    // Get students with their creation and update timestamps
    public function get_students_with_timestamps() {
        return $this->db->table($this->table)
                        ->select('id, first_name, last_name, email, created_at, updated_at')
                        ->where_null('deleted_at')
                        ->order_by('created_at', 'DESC')
                        ->get_all();
    }

    // Override update method to automatically set updated_at timestamp
    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return parent::update($id, $data);
    }

    // Override soft_delete method to automatically set updated_at timestamp
    public function soft_delete($id) {
        // Update the updated_at timestamp when soft deleting
        $this->db->table($this->table)
                 ->where('id', $id)
                 ->update(['updated_at' => date('Y-m-d H:i:s')]);
        return parent::soft_delete($id);
    }

    // Override restore method to automatically set updated_at timestamp
    public function restore($id) {
        // Update the updated_at timestamp when restoring
        $this->db->table($this->table)
                 ->where('id', $id)
                 ->update(['updated_at' => date('Y-m-d H:i:s')]);
        return parent::restore($id);
    }
}
