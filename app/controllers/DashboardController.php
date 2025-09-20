<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class DashboardController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->model('AuthModel');
        $this->call->model('StudentModel');
    }

    // Show dashboard
    public function index() {
        // Require login
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Please login to access this page!');
            redirect('auth/login');
        }

        $student_id = $this->session->userdata('student_id');
        $data['student'] = $this->StudentModel->get_student_by_id($student_id);
        $data['auth'] = $this->AuthModel->get_auth_by_student_id($student_id);
        
        $this->call->view('dashboard/index', $data);
    }

    // Update profile
    public function update_profile() {
        // Require login
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Please login to access this page!');
            redirect('auth/login');
        }

        $student_id = $this->session->userdata('student_id');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->process_profile_update($student_id);
        } else {
            $data['student'] = $this->StudentModel->get_student_by_id($student_id);
            $data['auth'] = $this->AuthModel->get_auth_by_student_id($student_id);
            $this->call->view('dashboard/update_profile', $data);
        }
    }

    // Process profile update
    private function process_profile_update($student_id) {
        $first_name = trim($this->io->post('first_name'));
        $last_name = trim($this->io->post('last_name'));
        $email = trim($this->io->post('email'));
        $username = trim($this->io->post('username'));
        $current_password = $this->io->post('current_password');
        $new_password = $this->io->post('new_password');
        $confirm_password = $this->io->post('confirm_password');

        // Basic validation
        if (empty($first_name) || empty($last_name) || empty($email) || empty($username)) {
            $this->session->set_flashdata('error', 'All fields are required!');
            $this->call->view('dashboard/update_profile', [
                'student' => $this->StudentModel->get_student_by_id($student_id),
                'auth' => $this->AuthModel->get_auth_by_student_id($student_id)
            ]);
            return;
        }

        // Check if email already exists for another student
        if ($this->StudentModel->email_exists($email, $student_id)) {
            $this->session->set_flashdata('error', 'Email address already exists for another student!');
            $this->call->view('dashboard/update_profile', [
                'student' => $this->StudentModel->get_student_by_id($student_id),
                'auth' => $this->AuthModel->get_auth_by_student_id($student_id)
            ]);
            return;
        }

        // Check if username already exists for another user
        $auth = $this->AuthModel->get_auth_by_student_id($student_id);
        if ($this->AuthModel->username_exists($username, $auth['id'])) {
            $this->session->set_flashdata('error', 'Username already exists for another user!');
            $this->call->view('dashboard/update_profile', [
                'student' => $this->StudentModel->get_student_by_id($student_id),
                'auth' => $this->AuthModel->get_auth_by_student_id($student_id)
            ]);
            return;
        }

        // Password change validation
        if (!empty($new_password)) {
            if (empty($current_password)) {
                $this->session->set_flashdata('error', 'Current password is required to change password!');
                $this->call->view('dashboard/update_profile', [
                    'student' => $this->StudentModel->get_student_by_id($student_id),
                    'auth' => $this->AuthModel->get_auth_by_student_id($student_id)
                ]);
                return;
            }

            if (!$this->AuthModel->verify_password($current_password, $auth['password'])) {
                $this->session->set_flashdata('error', 'Current password is incorrect!');
                $this->call->view('dashboard/update_profile', [
                    'student' => $this->StudentModel->get_student_by_id($student_id),
                    'auth' => $this->AuthModel->get_auth_by_student_id($student_id)
                ]);
                return;
            }

            if ($new_password !== $confirm_password) {
                $this->session->set_flashdata('error', 'New passwords do not match!');
                $this->call->view('dashboard/update_profile', [
                    'student' => $this->StudentModel->get_student_by_id($student_id),
                    'auth' => $this->AuthModel->get_auth_by_student_id($student_id)
                ]);
                return;
            }

            if (strlen($new_password) < 6) {
                $this->session->set_flashdata('error', 'New password must be at least 6 characters long!');
                $this->call->view('dashboard/update_profile', [
                    'student' => $this->StudentModel->get_student_by_id($student_id),
                    'auth' => $this->AuthModel->get_auth_by_student_id($student_id)
                ]);
                return;
            }
        }

        try {
            // Start transaction
            $this->db->transaction();

            // Update student record
            $student_data = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email
            ];

            $student_result = $this->StudentModel->update_student($student_id, $student_data);

            if (!$student_result) {
                throw new Exception('Failed to update student record');
            }

            // Update auth record
            $auth_data = [
                'username' => $username
            ];

            if (!empty($new_password)) {
                $auth_data['password'] = $this->AuthModel->hash_password($new_password);
            }

            $auth_result = $this->AuthModel->update_auth_by_student_id($student_id, $auth_data);

            if (!$auth_result) {
                throw new Exception('Failed to update authentication record');
            }

            // Commit transaction
            $this->db->commit();

            // Update session data
            $this->session->set_userdata([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'username' => $username
            ]);

            $this->session->set_flashdata('success', 'Profile updated successfully!');
            redirect('dashboard');

        } catch (Exception $e) {
            // Rollback transaction
            $this->db->rollback();
            $this->session->set_flashdata('error', 'Profile update failed: ' . $e->getMessage());
            $this->call->view('dashboard/update_profile', [
                'student' => $this->StudentModel->get_student_by_id($student_id),
                'auth' => $this->AuthModel->get_auth_by_student_id($student_id)
            ]);
        }
    }

    // Upload profile image
    public function upload_profile_image() {
        // Require login
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Please login to access this page!');
            redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
            $student_id = $this->session->userdata('student_id');
            
            // Initialize upload library
            $this->call->library('upload');
            
            // Set the file to upload
            $this->upload->file = $_FILES['profile_image'];
            
            // Configure upload using LavaLust Upload library methods
            $this->upload->allowed_extensions(['jpg', 'jpeg', 'png', 'gif'])
                        ->allowed_mimes(['image/jpeg', 'image/jpg', 'image/png', 'image/gif'])
                        ->set_dir('./public/uploads/')
                        ->max_size(2) // 2MB
                        ->is_image();

            if ($this->upload->do_upload()) {
                $filename = $this->upload->get_filename();
                $image_path = 'uploads/' . $filename;

                try {
                    // Start transaction
                    $this->db->transaction();

                    // Update auth table
                    $auth_result = $this->AuthModel->update_profile_image($student_id, $image_path);

                    if (!$auth_result) {
                        throw new Exception('Failed to update profile image in auth table');
                    }

                    // Update students table
                    $student_result = $this->StudentModel->update_student($student_id, [
                        'profile_image' => $image_path
                    ]);

                    if (!$student_result) {
                        throw new Exception('Failed to update profile image in students table');
                    }

                    // Commit transaction
                    $this->db->commit();

                    // Update session
                    $this->session->set_userdata('profile_image', $image_path);

                    $this->session->set_flashdata('success', 'Profile image uploaded successfully! Image path: ' . $image_path);
                    redirect('dashboard');

                } catch (Exception $e) {
                    // Rollback transaction
                    $this->db->rollback();
                    
                    // Delete uploaded file
                    if (file_exists('./public/' . $image_path)) {
                        unlink('./public/' . $image_path);
                    }
                    
                    $this->session->set_flashdata('error', 'Profile image upload failed: ' . $e->getMessage());
                    redirect('dashboard');
                }
            } else {
                $errors = $this->upload->get_errors();
                $this->session->set_flashdata('error', 'Upload failed: ' . implode(', ', $errors));
                redirect('dashboard');
            }
        } else {
            redirect('dashboard');
        }
    }
}
