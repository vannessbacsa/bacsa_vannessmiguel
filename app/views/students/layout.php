<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Student Management System'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        
        .table-header {
            background: linear-gradient(135deg, #1a1a1a, #242424);
            border-bottom: 2px solid #2563eb;
        }
        
        .table-row {
            background: rgba(36, 36, 36, 0.4);
            border-bottom: 1px solid #374151;
            transition: all 0.2s ease;
        }
        
        .table-row:hover {
            background: rgba(37, 99, 235, 0.1);
        }
        
        .accent-text {
            color: #60a5fa;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="text-text-primary">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Header -->
        <div class="card rounded-2xl p-8 mb-8">
            <h1 class="text-5xl font-bold mb-4 text-center">
                <span class="text-text-primary">STUDENT</span>
                <span class="gradient-text block text-2xl font-light tracking-[0.3em] mt-2">MANAGEMENT SYSTEM</span>
            </h1>
        </div>

        <!-- Navigation -->
        <div class="card rounded-xl p-6 mb-8">
            <div class="flex justify-center space-x-4 flex-wrap gap-y-4">
                <a href="<?php echo site_url('students'); ?>" class="btn-primary px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    <span>All Students</span>
                </a>
                <a href="<?php echo site_url('students/create'); ?>" class="btn-primary px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add New Student</span>
                </a>
                <a href="<?php echo site_url('students/deleted'); ?>" class="btn-secondary px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v1.586a1 1 0 00.293.707l1.5 1.5A2 2 0 007.414 11H8v10a2 2 0 002 2h4a2 2 0 002-2V11h.586a2 2 0 001.414-.586l1.5-1.5A1 1 0 0020 8.414V7a2 2 0 00-2-2h-2"/>
                    </svg>
                    <span>Soft Deleted</span>
                </a>
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