<?php
require_once 'utils.php';
$errorList = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formAction']) && $_POST['formAction'] === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $userData = getUserData();
    $user = array_filter($userData, fn($u) => strtolower($u['username']) === strtolower($username));
    $user = reset($user);
    if (!$user || !password_verify($password, $user['password'])) {
        $errorList[] = 'Invalid username or password.';
    } else {
        session_regenerate_id(true);
        $_SESSION['user'] = $username;
        header('Location: tasks.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .modal-container { animation: zoomIn 0.3s ease-out; }
        .submit-spinner {
            border: 2px solid #e5e7eb; border-top: 2px solid #a855f7;
            border-radius: 50%; width: 16px; height: 16px;
            animation: spin 0.8s linear infinite; display: none;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white/70 backdrop-blur-lg border border-white/20 p-4 sm:p-5 rounded-3xl shadow-lg w-full max-w-[420px] sm:max-w-[600px]">
        <h1 class="text-xl sm:text-2xl font-bold text-center mb-4 sm:mb-5 text-transparent bg-clip-text bg-gradient-to-r from-indigo-700 to-pink-600">
            Task Manager - Login
        </h1>

        <?php if (!empty($errorList)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-xs sm:text-sm">
                <?php foreach ($errorList as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="flex flex-col gap-2" onsubmit="startSpinner()">
            <input type="hidden" name="formAction" value="login">
            <input type="text" name="username" placeholder="Username" required
                   class="p-2 sm:p-3 border border-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 text-xs sm:text-sm bg-white/50">
            <input type="password" name="password" placeholder="Password" required
                   class="p-2 sm:p-3 border border-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 text-xs sm:text-sm bg-white/50">
            <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-2 sm:p-3 rounded-lg hover:scale-105 transition-transform flex items-center justify-center gap-2 text-xs sm:text-sm">
                <span>Login</span>
                <div id="submit-spinner" class="submit-spinner"></div>
            </button>
        </form>
        <p class="text-center text-xs sm:text-sm text-gray-600 mt-2">
            Don't have an account? <a href="register.php" class="text-indigo-600 hover:text-indigo-800">Register</a>
        </p>
    </div>

    <script>
        function startSpinner() {
            document.querySelectorAll('.submit-spinner').forEach(spinner => spinner.style.display = 'block');
            setTimeout(() => document.querySelectorAll('.submit-spinner').forEach(spinner => spinner.style.display = 'none'), 800);
        }
    </script>
</body>
</html>