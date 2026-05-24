<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';

require_guest();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        if (check_login($username, $password)) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Shah Jee Courier Portal</title>
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        lionex: {
                            orange: '#EF5A24',
                            darkblue: '#0B2D5B',
                            accent: '#F47A20',
                            bg: '#F8FAFC'
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-lionex-darkblue flex items-center justify-center min-h-screen p-4 overflow-hidden relative">
    
    <!-- Background Decorative Gradients -->
    <div class="absolute w-[500px] h-[500px] rounded-full bg-lionex-orange/10 blur-[100px] -top-40 -left-40"></div>
    <div class="absolute w-[400px] h-[400px] rounded-full bg-lionex-orange/5 blur-[80px] -bottom-20 -right-20"></div>

    <div class="w-full max-w-md bg-slate-900/60 backdrop-blur-xl border border-slate-800 rounded-3xl p-8 shadow-2xl relative z-10 transition-all duration-300 hover:border-slate-700/80">
        
        <!-- Brand Header -->
        <div class="flex flex-col items-center mb-6">
            <img src="logo.png" class="h-24 w-auto object-contain mb-4" alt="Shah Jee Logo">
            <h2 class="text-2xl font-bold text-white tracking-wide text-center">
                Shah Jee Courier
            </h2>
            <p class="text-slate-400 text-[10px] mt-1 uppercase tracking-widest font-bold text-center">Customer & Operator Portal</p>
        </div>

        <!-- Login Form -->
        <form action="login.php" method="POST" class="space-y-6">
            <?php if (!empty($error)): ?>
                <div class="bg-red-500/10 border border-red-500/30 text-red-200 text-sm px-4 py-3 rounded-xl flex items-center space-x-2 animate-pulse">
                    <i class="fa-solid fa-circle-exclamation text-red-500 text-base"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <!-- Username Input -->
            <div class="space-y-2">
                <label for="username" class="text-slate-300 text-xs font-semibold uppercase tracking-wider block">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <input type="text" id="username" name="username" required
                           class="w-full bg-slate-800/40 border border-slate-700/80 rounded-xl py-3.5 pl-11 pr-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-lionex-orange/50 focus:border-lionex-orange transition-all duration-200"
                           placeholder="Enter username">
                </div>
            </div>

            <!-- Password Input -->
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <label for="password" class="text-slate-300 text-xs font-semibold uppercase tracking-wider block">Password</label>
                    <a href="#" class="text-xs text-lionex-orange hover:text-lionex-accent transition-all duration-150">Forgot?</a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input type="password" id="password" name="password" required
                           class="w-full bg-slate-800/40 border border-slate-700/80 rounded-xl py-3.5 pl-11 pr-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-lionex-orange/50 focus:border-lionex-orange transition-all duration-200"
                           placeholder="••••••••">
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input type="checkbox" id="remember" class="w-4 h-4 rounded text-lionex-orange bg-slate-800 border-slate-700 focus:ring-lionex-orange focus:ring-offset-slate-900 focus:ring-offset-2">
                <label for="remember" class="ml-2 text-slate-300 text-xs cursor-pointer select-none">Remember this device</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full py-4 bg-gradient-to-r from-lionex-orange to-lionex-accent text-white font-semibold rounded-xl shadow-lg hover:shadow-lionex-orange/30 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-lionex-orange/50">
                Sign In <i class="fa-solid fa-arrow-right-to-bracket ml-2"></i>
            </button>
        </form>

        <!-- Divider -->
        <div class="relative flex py-5 items-center">
            <div class="flex-grow border-t border-slate-800"></div>
            <span class="flex-shrink mx-4 text-slate-500 text-xs font-medium uppercase tracking-widest">Demo Credentials</span>
            <div class="flex-grow border-t border-slate-800"></div>
        </div>

        <!-- Credential Badge Helper -->
        <div class="bg-slate-800/40 border border-slate-800 rounded-2xl p-4 flex flex-col space-y-2 text-xs text-slate-400">
            <div class="flex justify-between items-center">
                <span>Admin Login:</span>
                <span class="font-mono text-slate-200">admin / admin123</span>
            </div>
            <div class="flex justify-between items-center">
                <span>Operator Login:</span>
                <span class="font-mono text-slate-200">shahjee / courier2026</span>
            </div>
        </div>
        
        <!-- Public Tracking Redirect -->
        <div class="mt-6 text-center">
            <p class="text-xs text-slate-400">
                Just want to track a package? 
                <a href="track.php" class="text-lionex-orange hover:underline font-semibold ml-1">Track Shipment</a>
            </p>
        </div>
    </div>
</body>
</html>
