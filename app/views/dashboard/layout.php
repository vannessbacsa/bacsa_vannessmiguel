<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Dashboard - Student Management System'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0f0f0f',
                        secondary: '#1a1a1a',
                        'card-bg': '#242424',
                        accent: '#2563eb',
                        'accent-hover': '#1d4ed8',
                        'accent-light': '#3b82f6',
                        'blue-soft': '#60a5fa',
                        'text-primary': '#ffffff',
                        'text-secondary': '#d1d5db',
                        'text-muted': '#9ca3af',
                        'border-color': '#374151'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 50%, #242424 100%);
            min-height: 100vh;
        }
        
        .card {
            background: rgba(36, 36, 36, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid #374151;
            transition: all 0.3s ease;
            overflow: visible;
        }
        
        .card:hover {
            border-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #374151, #4b5563);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #4b5563, #6b7280);
            transform: translateY(-1px);
        }
        
        .input-field {
            background: rgba(26, 26, 26, 0.6);
            border: 1px solid #374151;
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            background: rgba(36, 36, 36, 0.8);
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Ensure dropdown appears above all content */
        .user-dropdown {
            z-index: 9999 !important;
            position: relative;
        }
        
        .user-dropdown .dropdown-menu {
            z-index: 10000 !important;
            position: absolute;
        }

    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');

            userMenuButton.addEventListener('click', function() {
                userMenu.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        });
    </script>
</head>
<body class="text-text-primary">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Header with User Profile -->
        <div class="card rounded-2xl p-8 mb-8" style="overflow: visible; position: relative; z-index: 10;">
            <div class="flex justify-between items-center" style="overflow: visible;">
                <div>
                    <h1 class="text-5xl font-bold mb-2">
                        <span class="text-text-primary">STUDENT</span>
                        <span class="gradient-text block text-2xl font-light tracking-[0.3em] mt-2">MANAGEMENT SYSTEM</span>
                    </h1>
                </div>
                
                <!-- User Profile Dropdown -->
                <div class="relative inline-block text-left z-50 user-dropdown">
                    <div>
                        <button type="button" class="flex items-center space-x-3 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-600 flex items-center justify-center">
                                <?php if (isset($_SESSION['profile_image']) && $_SESSION['profile_image']): ?>
                                    <img src="<?php echo base_url() . '/public/' . $_SESSION['profile_image']; ?>" 
                                         alt="Profile Picture" 
                                         class="w-full h-full object-cover">
                                <?php else: ?>
                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div class="text-left">
                                <div class="text-white font-medium"><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></div>
                                <div class="text-gray-300 text-xs"><?php echo ucfirst($_SESSION['role']); ?></div>
                            </div>
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>

                    <div class="origin-top-right absolute right-0 mt-4 w-48 rounded-md shadow-lg py-1 bg-gray-800 ring-1 ring-gray-600 focus:outline-none hidden z-50 dropdown-menu" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-menu">
                        <a href="<?php echo site_url('dashboard'); ?>" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" role="menuitem">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                </svg>
                                Dashboard
                            </div>
                        </a>
                        <a href="<?php echo site_url('dashboard/update_profile'); ?>" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" role="menuitem">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Profile
                            </div>
                        </a>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="<?php echo site_url('students'); ?>" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" role="menuitem">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                Manage Students
                            </div>
                        </a>
                        <?php endif; ?>
                        <div class="border-t border-gray-600"></div>
                        <a href="<?php echo site_url('auth/logout'); ?>" class="block px-4 py-2 text-sm text-red-300 hover:bg-red-900 hover:text-red-100 transition-colors duration-200" role="menuitem">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span class="font-medium">Logout</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="card rounded-2xl p-8">
            <!-- Flash Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="mb-6 bg-green-600 border border-green-500 text-white px-4 py-3 rounded-lg relative">
                    <span class="block sm:inline"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                        <svg class="fill-current h-6 w-6 text-green-200" role="button" onclick="this.parentElement.parentElement.remove()">
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15z"/>
                        </svg>
                    </span>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-6 bg-red-600 border border-red-500 text-white px-4 py-3 rounded-lg relative">
                    <span class="block sm:inline"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                        <svg class="fill-current h-6 w-6 text-red-200" role="button" onclick="this.parentElement.parentElement.remove()">
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15z"/>
                        </svg>
                    </span>
                </div>
            <?php endif; ?>

            <?php echo $content ?? ''; ?>
        </div>
    </div>
</body>
</html>
