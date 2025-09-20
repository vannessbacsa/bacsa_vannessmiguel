<?php
$title = 'Dashboard';
$content = '
<div class="mb-8">
    <div class="bg-gradient-to-r from-accent to-accent-light rounded-xl p-8 text-center card">
        <h3 class="text-4xl font-bold text-white mb-2">Welcome Back!</h3>
        <p class="text-blue-100 text-lg font-medium">' . htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) . '</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="card rounded-xl p-6">
        <div class="flex items-center">
            <div class="p-3 bg-accent rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="ml-4">
                <h4 class="text-lg font-semibold text-text-primary">Profile Status</h4>
                <p class="text-text-secondary">Active Student</p>
            </div>
        </div>
    </div>

    <div class="card rounded-xl p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-600 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <h4 class="text-lg font-semibold text-text-primary">Account Type</h4>
                <p class="text-text-secondary">' . ucfirst($auth['role']) . '</p>
            </div>
        </div>
    </div>

    <div class="card rounded-xl p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-600 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <h4 class="text-lg font-semibold text-text-primary">Member Since</h4>
                <p class="text-text-secondary">' . date('M j, Y', strtotime($student['created_at'])) . '</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Profile Information -->
    <div class="card rounded-xl p-6">
        <h3 class="text-xl font-semibold text-text-primary mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Profile Information
        </h3>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center py-3 border-b border-border-color">
                <span class="text-text-secondary">Full Name:</span>
                <span class="text-text-primary font-medium">' . htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) . '</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-border-color">
                <span class="text-text-secondary">Email:</span>
                <span class="text-text-primary font-medium">' . htmlspecialchars($student['email']) . '</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-border-color">
                <span class="text-text-secondary">Username:</span>
                <span class="text-text-primary font-medium">' . htmlspecialchars($auth['username']) . '</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-color">
                <span class="text-text-secondary">Role:</span>
                <span class="text-text-primary font-medium">' . ucfirst($auth['role']) . '</span>
            </div>
        </div>

        <div class="mt-6">
            <a href="' . site_url('dashboard/update_profile') . '" class="btn-primary px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Edit Profile</span>
            </a>
        </div>
    </div>

    <!-- Profile Image -->
    <div class="card rounded-xl p-6">
        <h3 class="text-xl font-semibold text-text-primary mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Profile Picture
        </h3>
        
        <div class="text-center">
            <div class="w-32 h-32 mx-auto mb-4 rounded-full overflow-hidden bg-gray-700 flex items-center justify-center">
                ' . (($auth['profile_image'] || (isset($_SESSION['profile_image']) && $_SESSION['profile_image'])) ? 
                    '<img src="' . base_url() . '/public/' . ($auth['profile_image'] ?: $_SESSION['profile_image']) . '" alt="Profile Picture" class="w-full h-full object-cover">' : 
                    '<svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>'
                ) . '
            </div>
            
            <form method="POST" action="' . site_url('dashboard/upload_profile_image') . '" enctype="multipart/form-data" class="mt-4">
                <div class="flex items-center justify-center space-x-4">
                    <input type="file" 
                           name="profile_image" 
                           accept="image/jpeg,image/jpg,image/png,image/gif" 
                           class="hidden" 
                           id="profile_image_input" 
                           onchange="this.form.submit()">
                    <label for="profile_image_input" 
                           class="btn-primary px-4 py-2 rounded-lg font-medium cursor-pointer inline-flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Upload Photo</span>
                    </label>
                </div>
            </form>
        </div>
    </div>
</div>';

include 'layout.php';
?>
