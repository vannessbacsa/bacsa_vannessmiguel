<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Management System</title>
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
    </style>
</head>
<body class="text-text-primary">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <!-- Header -->
            <div class="card rounded-2xl p-8 mb-8 text-center">
                <h1 class="text-4xl font-bold mb-2">
                    <span class="text-text-primary">STUDENT</span>
                    <span class="gradient-text block text-xl font-light tracking-[0.3em] mt-2">MANAGEMENT SYSTEM</span>
                </h1>
                <p class="text-text-secondary mt-4">Sign in to your account</p>
            </div>

            <!-- Login Form -->
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

                <form method="POST" action="">
                    <div class="space-y-6">
                        <div>
                            <label for="username" class="block text-sm font-medium text-text-secondary mb-2">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Username
                            </label>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   class="input-field w-full px-4 py-3 rounded-lg text-text-primary placeholder-text-muted focus:outline-none" 
                                   placeholder="Enter your username"
                                   required>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-text-secondary mb-2">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Password
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="input-field w-full px-4 py-3 rounded-lg text-text-primary placeholder-text-muted focus:outline-none" 
                                   placeholder="Enter your password"
                                   required>
                        </div>

                        <button type="submit" 
                                class="btn-primary w-full px-6 py-3 rounded-lg font-medium text-white focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 focus:ring-offset-primary">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-text-muted">
                        Don't have an account? 
                        <a href="<?php echo site_url('auth/register'); ?>" class="text-accent hover:text-accent-light font-medium transition-colors">
                            Register here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
