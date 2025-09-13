<?php
$title = 'All Students';
$content = '
<div class="mb-8">
    <div class="bg-gradient-to-r from-accent to-accent-light rounded-xl p-8 text-center card">
        <h3 class="text-5xl font-bold text-white mb-2">' . $total_students . '</h3>
        <p class="text-blue-100 text-lg font-medium">Total Active Students</p>
    </div>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-light text-text-primary">Student Records</h2>
    <a href="' . site_url('students/create') . '" class="btn-primary px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Add New Student</span>
    </a>
</div>

<div class="overflow-x-auto rounded-lg">
    <table class="w-full">
        <thead>
            <tr class="table-header">
                <th class="text-left py-4 px-6 font-semibold text-accent-light tracking-wider uppercase text-sm">ID</th>
                <th class="text-left py-4 px-6 font-semibold text-accent-light tracking-wider uppercase text-sm">First Name</th>
                <th class="text-left py-4 px-6 font-semibold text-accent-light tracking-wider uppercase text-sm">Last Name</th>
                <th class="text-left py-4 px-6 font-semibold text-accent-light tracking-wider uppercase text-sm">Email</th>
                <th class="text-left py-4 px-6 font-semibold text-accent-light tracking-wider uppercase text-sm">Created</th>
                <th class="text-left py-4 px-6 font-semibold text-accent-light tracking-wider uppercase text-sm">Updated</th>
                <th class="text-center py-4 px-6 font-semibold text-accent-light tracking-wider uppercase text-sm">Actions</th>
            </tr>
        </thead>
        <tbody>';

if (!empty($students)) {
    foreach ($students as $student) {
        $created_at = isset($student['created_at']) ? date('M j, Y g:i A', strtotime($student['created_at'])) : 'N/A';
        $updated_at = isset($student['updated_at']) && $student['updated_at'] ? date('M j, Y g:i A', strtotime($student['updated_at'])) : 'Never';
        
        $content .= '
            <tr class="table-row">
                <td class="py-4 px-6 font-mono text-accent-light font-medium">' . htmlspecialchars($student['id']) . '</td>
                <td class="py-4 px-6 font-medium text-text-primary">' . htmlspecialchars($student['first_name']) . '</td>
                <td class="py-4 px-6 font-medium text-text-primary">' . htmlspecialchars($student['last_name']) . '</td>
                <td class="py-4 px-6 text-text-secondary">' . htmlspecialchars($student['email']) . '</td>
                <td class="py-4 px-6 text-text-muted text-sm">' . $created_at . '</td>
                <td class="py-4 px-6 text-text-muted text-sm">' . $updated_at . '</td>
                <td class="py-4 px-6">
                    <div class="flex justify-center space-x-3">
                        <a href="' . site_url('students/edit/' . $student['id']) . '" class="btn-primary px-4 py-2 rounded-md text-sm font-medium inline-flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>Edit</span>
                        </a>
                        <a href="' . site_url('students/softdelete/' . $student['id']) . '" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 inline-flex items-center space-x-1" onclick="return confirm(\'Are you sure you want to soft delete this student?\')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <span>Delete</span>
                        </a>
                    </div>
                </td>
            </tr>';
    }
} else {
    $content .= '
        <tr>
            <td colspan="7" class="text-center py-16">
                <div class="card rounded-lg p-8 inline-block">
                    <svg class="w-16 h-16 text-text-muted mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    <h3 class="text-xl font-medium text-text-secondary mb-2">No Students Found</h3>
                    <p class="text-text-muted mb-6">Start by adding your first student to the system.</p>
                    <a href="' . site_url('students/create') . '" class="btn-primary px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Add First Student</span>
                    </a>
                </div>
            </td>
        </tr>';
}

$content .= '
        </tbody>
    </table>
</div>';

include 'layout.php';
?>