<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthModel extends Model {
    protected $table = 'auth';
    protected $primary_key = 'id';
    protected $fillable = ['student_id', 'username', 'password', 'role', 'profile_image'];

    public function __construct()
    {
        parent::__construct();
    }

    // Create new authentication record
    public function create_auth($data) {
        return $this->insert($data);
    }

    // Get auth record by username
    public function get_auth_by_username($username) {
        return $this->db->table($this->table)
                        ->where('username', $username)
                        ->get();
    }

    // Get auth record by student ID
    public function get_auth_by_student_id($student_id) {
        return $this->db->table($this->table)
                        ->where('student_id', $student_id)
                        ->get();
    }

    // Update auth record
    public function update_auth($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->update($id, $data);
    }

    // Update auth by student ID
    public function update_auth_by_student_id($student_id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->table($this->table)
                        ->where('student_id', $student_id)
                        ->update($data);
    }

    // Check if username exists
    public function username_exists($username, $exclude_id = null) {
        $query = $this->db->table($this->table)
                          ->where('username', $username);
        
        if ($exclude_id) {
            $query->where('id', '!=', $exclude_id);
        }
        
        $result = $query->get();
        return $result ? true : false;
    }

    // Get auth with student details
    public function get_auth_with_student($username) {
        return $this->db->table($this->table . ' a')
                        ->join('students s', 'a.student_id = s.id')
                        ->where('a.username', $username)
                        ->where_null('s.deleted_at')
                        ->select('a.*, s.first_name, s.last_name, s.email, s.profile_image as student_profile_image')
                        ->get();
    }

    // Get auth with student details by student ID
    public function get_auth_with_student_by_id($student_id) {
        return $this->db->table($this->table . ' a')
                        ->join('students s', 'a.student_id = s.id')
                        ->where('a.student_id', $student_id)
                        ->where_null('s.deleted_at')
                        ->select('a.*, s.first_name, s.last_name, s.email, s.profile_image as student_profile_image')
                        ->get();
    }

    // Delete auth record
    public function delete_auth($id) {
        return $this->delete($id);
    }

    // Delete auth by student ID
    public function delete_auth_by_student_id($student_id) {
        return $this->db->table($this->table)
                        ->where('student_id', $student_id)
                        ->delete();
    }

    // Get all admins
    public function get_admins() {
        return $this->db->table($this->table . ' a')
                        ->join('students s', 'a.student_id = s.id')
                        ->where('a.role', 'admin')
                        ->where_null('s.deleted_at')
                        ->select('a.*, s.first_name, s.last_name, s.email')
                        ->get_all();
    }

    // Get all students (non-admin users)
    public function get_students() {
        return $this->db->table($this->table . ' a')
                        ->join('students s', 'a.student_id = s.id')
                        ->where('a.role', 'student')
                        ->where_null('s.deleted_at')
                        ->select('a.*, s.first_name, s.last_name, s.email')
                        ->get_all();
    }

    // Update profile image
    public function update_profile_image($student_id, $image_path) {
        return $this->db->table($this->table)
                        ->where('student_id', $student_id)
                        ->update([
                            'profile_image' => $image_path,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
    }

    // Verify password
    public function verify_password($password, $hash) {
        return password_verify($password, $hash);
    }

    // Hash password
    public function hash_password($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
