<?php
$title = 'Add New Student';
$content = '
<div class="mb-8">
    <h2 class="text-3xl font-light text-text-primary mb-2">Add New Student</h2>
    <p class="text-text-muted">Create a new student record in the system</p>
</div>

<form action="' . site_url('students/store') . '" method="POST" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="first_name" class="block text-sm font-semibold text-text-secondary mb-3">First Name</label>
            <input type="text" id="first_name" name="first_name" 
                   class="input-field w-full px-4 py-3 rounded-lg text-text-primary placeholder-text-muted focus:outline-none" 
                   value="' . (isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '') . '" 
                   placeholder="Enter first name" required>
        </div>

        <div>
            <label for="last_name" class="block text-sm font-semibold text-text-secondary mb-3">Last Name</label>
            <input type="text" id="last_name" name="last_name" 
                   class="input-field w-full px-4 py-3 rounded-lg text-text-primary placeholder-text-muted focus:outline-none" 
                   value="' . (isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : '') . '" 
                   placeholder="Enter last name" required>
        </div>
    </div>

    <div>
        <label for="email" class="block text-sm font-semibold text-text-secondary mb-3">Email Address</label>
        <input type="email" id="email" name="email" 
               class="input-field w-full px-4 py-3 rounded-lg text-text-primary placeholder-text-muted focus:outline-none" 
               value="' . (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '') . '" 
               placeholder="Enter email address" required>
    </div>

    <div class="flex justify-end space-x-4 pt-8 border-t border-border-color">
        <a href="' . site_url('students') . '" class="btn-secondary px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span>Cancel</span>
        </a>
        <button type="submit" class="btn-primary px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Create Student</span>
        </button>
    </div>
</form>';

include 'layout.php';
?>