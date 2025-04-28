<?php
require_once 'utils.php';
$errorList = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formAction']) && $_POST['formAction'] === 'register') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $userData = getUserData();
    if (empty($username) || empty($password)) {
        $errorList[] = 'Username and password are required.';
    } elseif (checkUserExists($username, $userData)) {
        $errorList[] = 'Username already taken.';
    } elseif (strlen($password) < 6) {
        $errorList[] = 'Password must be at least 6 characters.';
    } else {
        $userData[] = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        if (saveJson('users.json', $userData)) {
            session_regenerate_id(true);
            $_SESSION['user'] = $username;
            header('Location: tasks.php');
            exit;
        } else {
            $errorList[] = 'Failed to register user. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Task Manager</title>
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
            Task Manager - Register
        </h1>

        <?php if (!empty($errorList)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-xs sm:text-sm">
                <?php foreach ($errorList as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="flex flex-col gap-2" onsubmit="startSpinner()">
            <input type="hidden" name="formAction" value="register">
            <input type="text" name="username" placeholder="Choose a username" required
                   class="p-2 sm:p-3 border border-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 text-xs sm:text-sm bg-white/50">
            <input type="password" name="password" placeholder="Choose a password" required
                   class="p-2 sm:p-3 border border-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 text-xs sm:text-sm bg-white/50">
            <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-2 sm:p-3 rounded-lg hover:scale-105 transition-transform flex items-center justify-center gap-2 text-xs sm:text-sm">
                <span>Register</span>
                <div id="submit-spinner" class="submit-spinner"></div>
            </button>
        </form>
        <p class="text-center text-xs sm:text-sm text-gray-600 mt-2">
            Already have an account? <a href="login.php" class="text-indigo-600 hover:text-indigo-800">Login</a>
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