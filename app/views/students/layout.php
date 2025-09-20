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
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }
        
        .pagination ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 0.5rem;
        }
        
        .pagination li {
            display: inline-block;
        }
        
        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            margin: 0;
            background: rgba(36, 36, 36, 0.8);
            border: 1px solid #374151;
            color: #ffffff;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
            min-width: 2.5rem;
        }
        
        .pagination a:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        
        .pagination .current {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-color: #2563eb;
            color: #ffffff;
            font-weight: 600;
        }
        
        .pagination .disabled {
            background: rgba(36, 36, 36, 0.4);
            border-color: #374151;
            color: #6b7280;
            cursor: not-allowed;
        }
        
        .pagination .disabled:hover {
            background: rgba(36, 36, 36, 0.4);
            border-color: #374151;
            transform: none;
            box-shadow: none;
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

        <!-- Back Button -->
        <div class="mb-6">
            <a href="<?php echo site_url('dashboard'); ?>" class="btn-secondary px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Dashboard</span>
            </a>
        </div>

        <!-- Navigation -->
        <div class="card rounded-xl p-6 mb-8">
            <div class="flex justify-center space-x-4 flex-wrap gap-y-4">
                <a href="<?php echo site_url('students'); ?>" class="btn-primary px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>All Students</span>
                </a>
                <a href="<?php echo site_url('students/create'); ?>" class="btn-primary px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span>Add New Student</span>
                </a>
                <a href="<?php echo site_url('students/deleted'); ?>" class="btn-secondary px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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