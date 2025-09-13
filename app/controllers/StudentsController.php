<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentsController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->model('StudentModel');
    }

    // Display all students
    public function index() {
        $data['students'] = $this->StudentModel->get_students_with_timestamps();
        $data['total_students'] = $this->StudentModel->count_active_students();
        $this->call->view('students/index', $data);
    }

    // Show create form
    public function create() {
        $this->call->view('students/create');
    }

    // Store new student
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Get input data
                $first_name = trim($this->io->post('first_name'));
                $last_name = trim($this->io->post('last_name'));
                $email = trim($this->io->post('email'));
                
                // Basic validation
                if (empty($first_name) || empty($last_name) || empty($email)) {
                    $this->session->set_flashdata('error', 'All fields are required!');
                    $this->call->view('students/create');
                    return;
                }
                
                // Check if email already exists
                if ($this->StudentModel->email_exists($email)) {
                    $this->session->set_flashdata('error', 'Email address already exists!');
                    $this->call->view('students/create');
                    return;
                }
                
                // Create student
                $student_data = [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email
                ];
                
                $result = $this->StudentModel->create_student($student_data);
                
                if ($result) {
                    $this->session->set_flashdata('success', 'Student created successfully!');
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
            $data['student'] = $this->StudentModel->get_student_by_id($id);
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
                
                // Basic validation
                if (empty($first_name) || empty($last_name) || empty($email)) {
                    $this->session->set_flashdata('error', 'All fields are required!');
                    $data['student'] = $this->StudentModel->get_student_by_id($id);
                    $this->call->view('students/edit', $data);
                    return;
                }
                
                // Check if email already exists for another student
                if ($this->StudentModel->email_exists($email, $id)) {
                    $this->session->set_flashdata('error', 'Email address already exists for another student!');
                    $data['student'] = $this->StudentModel->get_student_by_id($id);
                    $this->call->view('students/edit', $data);
                    return;
                }
                
                // Update student
                $student_data = [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email
                ];
                
                $result = $this->StudentModel->update_student($id, $student_data);
                
                if ($result) {
                    $this->session->set_flashdata('success', 'Student updated successfully!');
                    redirect('students');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update student!');
                    $data['student'] = $this->StudentModel->get_student_by_id($id);
                    $this->call->view('students/edit', $data);
                }
            } catch (Exception $e) {
                $this->session->set_flashdata('error', 'Error updating student: ' . $e->getMessage());
                $data['student'] = $this->StudentModel->get_student_by_id($id);
                $this->call->view('students/edit', $data);
            }
        } else {
            $this->call->view('students/edit', ['student' => $this->StudentModel->get_student_by_id($id)]);
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
