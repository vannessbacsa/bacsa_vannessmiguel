<?php
$title = 'Soft Deleted Students';
$content = '
<div class="mb-8">
    <div class="bg-gradient-to-r from-red-600 to-red-500 rounded-xl p-8 text-center card">
        <h3 class="text-5xl font-bold text-white mb-2">' . $total_deleted . '</h3>
        <p class="text-red-100 text-lg font-medium">Soft Deleted Students</p>
    </div>
</div>

<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-light text-text-primary mb-2">Soft Deleted Students</h2>
        <p class="text-text-muted">Students that have been temporarily removed</p>
    </div>
    <a href="' . site_url('students') . '" class="btn-primary px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Back to Active Students</span>
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
                <th class="text-left py-4 px-6 font-semibold text-accent-light tracking-wider uppercase text-sm">Deleted At</th>
                <th class="text-center py-4 px-6 font-semibold text-accent-light tracking-wider uppercase text-sm">Actions</th>
            </tr>
        </thead>
        <tbody>';

if (!empty($students)) {
    foreach ($students as $student) {
        $deleted_at = isset($student['deleted_at']) ? date('M j, Y g:i A', strtotime($student['deleted_at'])) : 'N/A';
        
        $content .= '
            <tr class="table-row">
                <td class="py-4 px-6 font-mono text-accent-light font-medium">' . htmlspecialchars($student['id']) . '</td>
                <td class="py-4 px-6 font-medium text-text-primary">' . htmlspecialchars($student['first_name']) . '</td>
                <td class="py-4 px-6 font-medium text-text-primary">' . htmlspecialchars($student['last_name']) . '</td>
                <td class="py-4 px-6 text-text-secondary">' . htmlspecialchars($student['email']) . '</td>
                <td class="py-4 px-6 text-text-muted text-sm">' . $deleted_at . '</td>
                <td class="py-4 px-6">
                    <div class="flex justify-center space-x-3">
                        <a href="' . site_url('students/restore/' . $student['id']) . '" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 inline-flex items-center space-x-1" onclick="return confirm(\'Are you sure you want to restore this student?\')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>Restore</span>
                        </a>
                        <a href="' . site_url('students/hard_delete/' . $student['id']) . '" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 inline-flex items-center space-x-1" onclick="return confirm(\'Are you sure you want to permanently delete this student? This action cannot be undone!\')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <span>Hard Delete</span>
                        </a>
                    </div>
                </td>
            </tr>';
    }
} else {
    $content .= '
        <tr>
            <td colspan="6" class="text-center py-16">
                <div class="card rounded-lg p-8 inline-block">
                    <svg class="w-16 h-16 text-text-muted mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-xl font-medium text-text-secondary mb-2">No Soft Deleted Students</h3>
                    <p class="text-text-muted">All students are currently active.</p>
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