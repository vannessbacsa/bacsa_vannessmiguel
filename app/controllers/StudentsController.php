<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentsController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->model('StudentModel');
        $this->call->model('AuthModel');
    }

    // Display all students with pagination and search
    public function index() {
        // Require admin login
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Access denied! Admin privileges required.');
            redirect('auth/login');
        }

        // Load pagination library
        $this->call->library('pagination');

        // Get search query
        $search_query = isset($_GET['q']) ? trim($_GET['q']) : null;

        // Get current page from query parameter
        $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($current_page < 1) { $current_page = 1; }

        // Per-page limiter like in reference project
        $allowed_per_page = [10, 25, 50, 100];
        $rows_per_page = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
        if (!in_array($rows_per_page, $allowed_per_page, true)) { $rows_per_page = 10; }

        // Base URL for pagination links
        $base_url = 'students';

        // Configure pagination options for query parameters
        if ($search_query) {
            $this->pagination->set_options(['page_delimiter' => '/?q='.urlencode($search_query).'&page=']);
        } else {
            $this->pagination->set_options(['page_delimiter' => '/?page=']);
        }
        
        $this->pagination->set_theme('custom');
        $this->pagination->set_custom_classes([
            'nav'    => 'pagination-nav',
            'ul'     => 'pagination-list',
            'li'     => 'pagination-item',
            'a'      => 'pagination-link',
            'active' => 'active'
        ]);

        // Get total rows based on search
        if ($search_query) {
            $total_rows = $this->StudentModel->count_search_students($search_query);
        } else {
            $total_rows = $this->StudentModel->count_active_students();
        }

        // Initialize pagination
        $page_data = $this->pagination->initialize(
            $total_rows,
            $rows_per_page,
            $current_page,
            $base_url,
            5 // number of visible page links
        );

        // Get paginated students based on search
        $limit_clause = $page_data['limit'];
        if ($search_query) {
            $data['students'] = $this->StudentModel->search_students($search_query, $limit_clause);
        } else {
            $data['students'] = $this->StudentModel->get_paginated_students($limit_clause);
        }
        
        $data['total_students'] = $total_rows;
        $data['per_page'] = $rows_per_page;
        $data['allowed_per_page'] = $allowed_per_page;
        $data['search_query'] = $search_query;

        // Generate pagination links with per_page and search parameters
        $links = $this->pagination->paginate();
        if (strpos($links, 'href=') !== false) {
            $append = [];
            if ($rows_per_page !== 10) { $append['per_page'] = $rows_per_page; }
            if ($search_query) { $append['q'] = $search_query; }
            if (!empty($append)) {
                $links = preg_replace_callback('/href=\"([^\"]+)\"/i', function($m) use ($append) {
                    $url = $m[1];
                    $sep = (strpos($url, '?') !== false) ? '&' : '?';
                    foreach ($append as $k => $v) {
                        if (strpos($url, $k.'=') === false) {
                            $url .= $sep.rawurlencode($k).'='.rawurlencode((string)$v);
                            $sep = '&';
                        }
                    }
                    return 'href="'.$url.'"';
                }, $links);
            }
        }
        $data['pagination'] = $links;

        $this->call->view('students/index', $data);
    }

    // Show create form
    public function create() {
        // Require admin login
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Access denied! Admin privileges required.');
            redirect('auth/login');
        }
        
        $this->call->view('students/create');
    }

    // Store new student
    public function store() {
        // Require admin login
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Access denied! Admin privileges required.');
            redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Get input data
                $first_name = trim($this->io->post('first_name'));
                $last_name = trim($this->io->post('last_name'));
                $email = trim($this->io->post('email'));
                $username = trim($this->io->post('username'));
                $password = trim($this->io->post('password'));
                $role = trim($this->io->post('role'));
                
                // Basic validation
                if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password) || empty($role)) {
                    $this->session->set_flashdata('error', 'All fields are required!');
                    $this->call->view('students/create');
                    return;
                }
                
                // Validate role
                if (!in_array($role, ['admin', 'student'])) {
                    $this->session->set_flashdata('error', 'Invalid role selected!');
                    $this->call->view('students/create');
                    return;
                }
                
                // Check if email already exists
                if ($this->StudentModel->email_exists($email)) {
                    $this->session->set_flashdata('error', 'Email address already exists!');
                    $this->call->view('students/create');
                    return;
                }
                
                // Check if username already exists
                if ($this->StudentModel->username_exists($username)) {
                    $this->session->set_flashdata('error', 'Username already exists!');
                    $this->call->view('students/create');
                    return;
                }
                
                // Prepare student data
                $student_data = [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email
                ];
                
                // Prepare auth data
                $auth_data = [
                    'username' => $username,
                    'password' => $password,
                    'role' => $role
                ];
                
                // Create student with authentication record
                $result = $this->StudentModel->create_student_with_auth($student_data, $auth_data);
                
                if ($result) {
                    $this->session->set_flashdata('success', 'Student created successfully with login credentials!');
                    redirect('students');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create student!');
                    $this->call->view('students/create');
                }
            } catch (Exception $e) {
                $this->session->set_flashdata('error', 'Error creating student: ' . $e->getMessage());
                $this->call->view('students/create');
            }
        } else {
            $this->call->view('students/create');
        }
    }

    // Show edit form
    public function edit($id) {
        try {
            $data['student'] = $this->StudentModel->get_student_with_auth($id);
            if (!$data['student']) {
                $this->session->set_flashdata('error', 'Student not found!');
                redirect('students');
            }
            $this->call->view('students/edit', $data);
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error loading student: ' . $e->getMessage());
            redirect('students');
        }
    }

    // Update student
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Get input data
                $first_name = trim($this->io->post('first_name'));
                $last_name = trim($this->io->post('last_name'));
                $email = trim($this->io->post('email'));
                $username = trim($this->io->post('username'));
                $password = trim($this->io->post('password'));
                $role = trim($this->io->post('role'));
                
                // Basic validation
                if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($role)) {
                    $this->session->set_flashdata('error', 'All fields are required!');
                    $data['student'] = $this->StudentModel->get_student_with_auth($id);
                    $this->call->view('students/edit', $data);
                    return;
                }
                
                // Validate role
                if (!in_array($role, ['admin', 'student'])) {
                    $this->session->set_flashdata('error', 'Invalid role selected!');
                    $data['student'] = $this->StudentModel->get_student_with_auth($id);
                    $this->call->view('students/edit', $data);
                    return;
                }
                
                // Check if email already exists for another student
                if ($this->StudentModel->email_exists($email, $id)) {
                    $this->session->set_flashdata('error', 'Email address already exists for another student!');
                    $data['student'] = $this->StudentModel->get_student_with_auth($id);
                    $this->call->view('students/edit', $data);
                    return;
                }
                
                // Check if username already exists for another student
                if ($this->StudentModel->username_exists($username, $id)) {
                    $this->session->set_flashdata('error', 'Username already exists for another student!');
                    $data['student'] = $this->StudentModel->get_student_with_auth($id);
                    $this->call->view('students/edit', $data);
                    return;
                }
                
                // Prepare student data
                $student_data = [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email
                ];
                
                // Prepare auth data
                $auth_data = [
                    'username' => $username,
                    'role' => $role
                ];
                
                // Add password only if provided
                if (!empty($password)) {
                    $auth_data['password'] = $password;
                }
                
                // Update student with authentication record
                $result = $this->StudentModel->update_student_with_auth($id, $student_data, $auth_data);
                
                if ($result) {
                    $this->session->set_flashdata('success', 'Student updated successfully!');
                    redirect('students');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update student!');
                    $data['student'] = $this->StudentModel->get_student_with_auth($id);
                    $this->call->view('students/edit', $data);
                }
            } catch (Exception $e) {
                $this->session->set_flashdata('error', 'Error updating student: ' . $e->getMessage());
                $data['student'] = $this->StudentModel->get_student_with_auth($id);
                $this->call->view('students/edit', $data);
            }
        } else {
            $this->call->view('students/edit', ['student' => $this->StudentModel->get_student_with_auth($id)]);
        }
    }

    // Soft delete student
    public function delete($id) {
        try {
            $result = $this->StudentModel->soft_delete_student($id);
            if ($result) {
                $this->session->set_flashdata('success', 'Student soft deleted successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to soft delete student!');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error soft deleting student: ' . $e->getMessage());
        }
        
        redirect('students');
    }

    // Show soft-deleted students
    public function deleted() {
        $data['students'] = $this->StudentModel->get_deleted_students();
        $data['total_deleted'] = $this->StudentModel->count_deleted_students();
        $this->call->view('students/deleted', $data);
    }

    // Restore soft-deleted student
    public function restore($id) {
        try {
            $result = $this->StudentModel->restore_student($id);
            if ($result) {
                $this->session->set_flashdata('success', 'Student restored successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to restore student!');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error restoring student: ' . $e->getMessage());
        }
        
        redirect('students/deleted');
    }

    // Hard delete student (permanent deletion)
    public function hard_delete($id) {
        try {
            $result = $this->StudentModel->hard_delete_student($id);
            if ($result) {
                $this->session->set_flashdata('success', 'Student permanently deleted!');
            } else {
                $this->session->set_flashdata('error', 'Failed to permanently delete student!');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error permanently deleting student: ' . $e->getMessage());
        }
        
        redirect('students/deleted');
    }
}
