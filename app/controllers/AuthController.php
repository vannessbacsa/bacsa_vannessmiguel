<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->model('AuthModel');
        $this->call->model('StudentModel');
    }

    // Show login form
    public function login() {
        // If user is already logged in, redirect to dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->process_login();
        } else {
            $this->call->view('auth/login');
        }
    }

    // Process login
    private function process_login() {
        $username = trim($this->io->post('username'));
        $password = $this->io->post('password');

        // Basic validation
        if (empty($username) || empty($password)) {
            $this->session->set_flashdata('error', 'Username and password are required!');
            $this->call->view('auth/login');
            return;
        }

        // Get auth record with student details
        $auth = $this->AuthModel->get_auth_with_student($username);

        if (!$auth) {
            $this->session->set_flashdata('error', 'Invalid username or password!');
            $this->call->view('auth/login');
            return;
        }

        // Verify password
        if (!$this->AuthModel->verify_password($password, $auth['password'])) {
            $this->session->set_flashdata('error', 'Invalid username or password!');
            $this->call->view('auth/login');
            return;
        }

        // Set session data
        $this->session->set_userdata([
            'user_id' => $auth['id'],
            'student_id' => $auth['student_id'],
            'username' => $auth['username'],
            'first_name' => $auth['first_name'],
            'last_name' => $auth['last_name'],
            'email' => $auth['email'],
            'role' => $auth['role'],
            'profile_image' => $auth['profile_image'] ?: $auth['student_profile_image'],
            'logged_in' => true
        ]);

        // Redirect based on role
        if ($auth['role'] === 'admin') {
            redirect('students');
        } else {
            redirect('dashboard');
        }
    }

    // Show register form
    public function register() {
        // If user is already logged in, redirect to dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->process_register();
        } else {
            $this->call->view('auth/register');
        }
    }

    // Process registration
    private function process_register() {
        $first_name = trim($this->io->post('first_name'));
        $last_name = trim($this->io->post('last_name'));
        $email = trim($this->io->post('email'));
        $username = trim($this->io->post('username'));
        $password = $this->io->post('password');
        $confirm_password = $this->io->post('confirm_password');

        // Basic validation
        if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password)) {
            $this->session->set_flashdata('error', 'All fields are required!');
            $this->call->view('auth/register');
            return;
        }

        if ($password !== $confirm_password) {
            $this->session->set_flashdata('error', 'Passwords do not match!');
            $this->call->view('auth/register');
            return;
        }

        if (strlen($password) < 6) {
            $this->session->set_flashdata('error', 'Password must be at least 6 characters long!');
            $this->call->view('auth/register');
            return;
        }

        // Check if email already exists
        if ($this->StudentModel->email_exists($email)) {
            $this->session->set_flashdata('error', 'Email address already exists!');
            $this->call->view('auth/register');
            return;
        }

        // Check if username already exists
        if ($this->AuthModel->username_exists($username)) {
            $this->session->set_flashdata('error', 'Username already exists!');
            $this->call->view('auth/register');
            return;
        }

        try {
            // Start transaction
            $this->db->transaction();

            // Create student record
            $student_data = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email
            ];

            $student_id = $this->StudentModel->create_student($student_data);

            if (!$student_id) {
                throw new Exception('Failed to create student record');
            }

            // Create auth record
            $auth_data = [
                'student_id' => $student_id,
                'username' => $username,
                'password' => $this->AuthModel->hash_password($password),
                'role' => 'student'
            ];

            $auth_id = $this->AuthModel->create_auth($auth_data);

            if (!$auth_id) {
                throw new Exception('Failed to create authentication record');
            }

            // Commit transaction
            $this->db->commit();

            $this->session->set_flashdata('success', 'Registration successful! Please login.');
            redirect('auth/login');

        } catch (Exception $e) {
            // Rollback transaction
            $this->db->roll_back();
            $this->session->set_flashdata('error', 'Registration failed: ' . $e->getMessage());
            $this->call->view('auth/register');
        }
    }

    // Logout
    public function logout() {
        $this->session->unset_userdata(['user_id', 'student_id', 'username', 'first_name', 'last_name', 'email', 'role', 'profile_image', 'logged_in']);
        $this->session->set_flashdata('success', 'You have been logged out successfully!');
        redirect('auth/login');
    }

    // Check if user is logged in
    public function is_logged_in() {
        return $this->session->userdata('logged_in');
    }

    // Check if user has specific role
    public function has_role($role) {
        return $this->session->userdata('role') === $role;
    }

    // Require login
    public function require_login() {
        if (!$this->is_logged_in()) {
            $this->session->set_flashdata('error', 'Please login to access this page!');
            redirect('auth/login');
        }
    }

    // Require admin role
    public function require_admin() {
        $this->require_login();
        if (!$this->has_role('admin')) {
            $this->session->set_flashdata('error', 'Access denied! Admin privileges required.');
            redirect('dashboard');
        }
    }
}
