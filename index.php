<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - Based on Filesystem - No SQL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .container { animation: zoomIn 0.3s ease-out; }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white/70 backdrop-blur-lg border border-white/20 p-4 sm:p-6 rounded-3xl shadow-lg w-full max-w-[420px] sm:max-w-[600px] container">
        <h1 class="text-xl sm:text-2xl font-bold text-center mb-4 sm:mb-6 text-transparent bg-clip-text bg-gradient-to-r from-indigo-700 to-pink-600">
            Welcome to Task Manager
        </h1>
        <p class="text-center text-xs sm:text-sm text-gray-600 mb-6">
            Organize your tasks with ease. <?php echo isset($_SESSION['user']) ? 'Manage your tasks or log out.' : 'Log in or register to get started.'; ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <?php if (isset($_SESSION['user'])): ?>
                <a href="tasks.php" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-2 sm:p-3 rounded-lg hover:scale-105 transition-transform text-xs sm:text-sm text-center">
                    My Tasks
                </a>
                <a href="logout.php" class="bg-gradient-to-r from-red-600 to-pink-600 text-white p-2 sm:p-3 rounded-lg hover:scale-105 transition-transform text-xs sm:text-sm text-center">
                    Logout
                </a>
            <?php else: ?>
                <a href="login.php" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-2 sm:p-3 rounded-lg hover:scale-105 transition-transform text-xs sm:text-sm text-center">
                    Login
                </a>
                <a href="register.php" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-2 sm:p-3 rounded-lg hover:scale-105 transition-transform text-xs sm:text-sm text-center">
                    Register
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
