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

    // Get paginated students
    public function get_paginated_students($limit_clause) {
        return $this->db->raw("SELECT id, first_name, last_name, email, created_at, updated_at FROM {$this->table} WHERE deleted_at IS NULL ORDER BY created_at DESC {$limit_clause}")->fetchAll();
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

    // Create student with authentication record
    public function create_student_with_auth($student_data, $auth_data) {
        try {
            // Start transaction
            $this->db->transaction();
            
            // Create student record
            $student_id = $this->create_student($student_data);
            
            if (!$student_id) {
                throw new Exception('Failed to create student record');
            }
            
            // Add student_id to auth_data
            $auth_data['student_id'] = $student_id;
            
            // Hash password if provided
            if (isset($auth_data['password']) && !empty($auth_data['password'])) {
                $auth_data['password'] = password_hash($auth_data['password'], PASSWORD_DEFAULT);
            }
            
            // Create auth record
            $auth_result = $this->db->table('auth')->insert($auth_data);
            
            if (!$auth_result) {
                throw new Exception('Failed to create authentication record');
            }
            
            // Commit transaction
            $this->db->commit();
            return $student_id;
            
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->roll_back();
            throw $e;
        }
    }

    // Update student with authentication record
    public function update_student_with_auth($id, $student_data, $auth_data) {
        try {
            // Start transaction
            $this->db->transaction();
            
            // Update student record
            $student_result = $this->update_student($id, $student_data);
            
            if (!$student_result) {
                throw new Exception('Failed to update student record');
            }
            
            // Hash password if provided and not empty
            if (isset($auth_data['password']) && !empty($auth_data['password'])) {
                $auth_data['password'] = password_hash($auth_data['password'], PASSWORD_DEFAULT);
            } else {
                // Remove password from update if empty
                unset($auth_data['password']);
            }
            
            // Update auth record
            if (!empty($auth_data)) {
                $auth_result = $this->db->table('auth')
                                      ->where('student_id', $id)
                                      ->update($auth_data);
                
                if (!$auth_result) {
                    throw new Exception('Failed to update authentication record');
                }
            }
            
            // Commit transaction
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->roll_back();
            throw $e;
        }
    }

    // Get student with authentication details
    public function get_student_with_auth($id) {
        return $this->db->table($this->table . ' s')
                        ->join('auth a', 's.id = a.student_id')
                        ->where('s.id', $id)
                        ->where_null('s.deleted_at')
                        ->select('s.*, a.username, a.role, a.profile_image as auth_profile_image')
                        ->get();
    }

    // Check if username exists
    public function username_exists($username, $exclude_student_id = null) {
        $query = $this->db->table('auth')
                          ->where('username', $username);
        
        if ($exclude_student_id) {
            $query->where('student_id', '!=', $exclude_student_id);
        }
        
        $result = $query->get();
        return $result ? true : false;
    }

    // Search students by first_name, last_name, or email
    public function search_students($q, $limit_clause) {
        $like = "%{$q}%";
        $sql = "SELECT s.id, s.first_name, s.last_name, s.email, s.created_at, s.updated_at 
                FROM {$this->table} s 
                WHERE s.deleted_at IS NULL 
                AND (s.first_name LIKE ? OR s.last_name LIKE ? OR s.email LIKE ?)
                ORDER BY s.created_at DESC {$limit_clause}";
        $result = $this->db->raw($sql, [$like, $like, $like]);
        return $result ? $result->fetchAll() : [];
    }

    // Count search results
    public function count_search_students($q) {
        $like = "%{$q}%";
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} s 
                WHERE s.deleted_at IS NULL 
                AND (s.first_name LIKE ? OR s.last_name LIKE ? OR s.email LIKE ?)";
        $result = $this->db->raw($sql, [$like, $like, $like]);
        return $result ? (int)$result->fetch()['count'] : 0;
    }
}
