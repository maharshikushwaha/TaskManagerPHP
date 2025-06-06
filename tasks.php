<?php
require_once 'utils.php';
requireLogin();
$errorList = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formAction'])) {
    [$taskFile, $taskData] = getUserTasks($_SESSION['user']);
    if ($_POST['formAction'] === 'create') {
        $inputText = trim($_POST['taskText'] ?? '');
        if (empty($inputText)) {
            $errorList[] = 'Task cannot be empty.';
        } elseif (checkTaskExists($inputText, $taskData)) {
            $errorList[] = 'Task already exists.';
        } else {
            $taskData[] = ['text' => $inputText, 'completed' => false];
            if (!saveJson($taskFile, $taskData)) {
                $errorList[] = 'Failed to save task. Please try again.';
            }
        }
    } elseif ($_POST['formAction'] === 'update') {
        $taskId = (int)$_POST['taskId'];
        $updatedText = trim($_POST['taskText']);
        if (isset($taskData[$taskId]) && !empty($updatedText)) {
            $taskData[$taskId]['text'] = $updatedText;
            if (!saveJson($taskFile, $taskData)) {
                $errorList[] = 'Failed to update task. Please try again.';
            }
        } else {
            $errorList[] = 'Invalid task or empty input.';
        }
    }
}

if (isset($_GET['remove'])) {
    [$taskFile, $taskData] = getUserTasks($_SESSION['user']);
    $taskId = (int)$_GET['remove'];
    if (isset($taskData[$taskId])) {
        unset($taskData[$taskId]);
        $taskData = array_values($taskData);
        if (!saveJson($taskFile, $taskData)) {
            $errorList[] = 'Failed to delete task. Please try again.';
        }
    }
}

if (isset($_GET['toggleStatus'])) {
    [$taskFile, $taskData] = getUserTasks($_SESSION['user']);
    $taskId = (int)$_GET['toggleStatus'];
    if (isset($taskData[$taskId])) {
        $taskData[$taskId]['completed'] = !$taskData[$taskId]['completed'];
        if (!saveJson($taskFile, $taskData)) {
            $errorList[] = 'Failed to toggle task status. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks - Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .task-card { animation: slideIn 0.4s ease-out; }
        .modal-container { animation: zoomIn 0.3s ease-out; }
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
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
            Task Manager
        </h1>

        <div class="flex justify-between items-center mb-4 sm:mb-5">
            <span class="text-xs sm:text-sm text-gray-700">Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?></span>
            <a href="logout.php" class="text-red-600 hover:text-red-800 text-xs sm:text-sm">Logout</a>
        </div>

        <?php if (!empty($errorList)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-xs sm:text-sm">
                <?php foreach ($errorList as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mb-4 sm:mb-5 flex flex-col sm:flex-row gap-2" onsubmit="startSpinner()">
            <input type="hidden" name="formAction" value="create">
            <input type="text" name="taskText" placeholder="New task" required
                   class="flex-1 p-2 sm:p-3 border border-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 text-xs sm:text-sm bg-white/50">
            <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-2 sm:p-3 rounded-lg hover:scale-105 transition-transform flex items-center justify-center gap-2 text-xs sm:text-sm">
                <span>Add</span>
                <div id="submit-spinner" class="submit-spinner"></div>
            </button>
        </form>

        <h3 class="text-base sm:text-lg font-medium mb-3 text-gray-700">Tasks</h3>
        <?php
        [$taskFile, $taskData] = getUserTasks($_SESSION['user']);
        if (empty($taskData)):
        ?>
            <p class="text-gray-500 text-center text-xs sm:text-sm">No tasks yet.</p>
        <?php else: ?>
            <?php foreach ($taskData as $index => $task): ?>
                <div class="task-card flex items-center justify-between p-3 mb-2 rounded-lg transition-transform <?php echo $task['completed'] ? 'bg-emerald-100/60' : 'bg-amber-100/60'; ?> bg-white/50 hover:scale-[1.02]">
                    <div class="flex items-center">
                        <input type="checkbox" <?php echo $task['completed'] ? 'checked' : ''; ?>
                               onchange="window.location.href='?toggleStatus=<?php echo $index; ?>'"
                               class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-400">
                        <span class="ml-2 text-xs sm:text-sm <?php echo $task['completed'] ? 'line-through text-gray-500' : 'text-gray-700'; ?>">
                            <?php echo htmlspecialchars($task['text']); ?>
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="openEditModal(<?php echo $index; ?>, '<?php echo addslashes($task['text']); ?>')"
                                class="text-indigo-600 hover:text-indigo-800 text-xs sm:text-sm">Edit</button>
                        <a href="?remove=<?php echo $index; ?>" onclick="return confirm('Remove task?');"
                           class="text-red-600 hover:text-red-800 text-xs sm:text-sm">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="edit-task-modal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-3">
        <div class="bg-white/70 backdrop-blur-lg border border-white/20 p-4 sm:p-5 rounded-3xl w-full max-w-[360px] sm:max-w-[480px] modal-container">
            <h2 class="text-base sm:text-lg font-medium mb-3 text-gray-700">Edit Task</h2>
            <form method="POST" onsubmit="startSpinner()">
                <input type="hidden" name="formAction" value="update">
                <input type="hidden" name="taskId" id="edit-task-id">
                <input type="text" name="taskText" id="edit-task-text" required
                       class="w-full p-2 sm:p-3 border border-gray-100 rounded-lg mb-3 focus:outline-none focus:ring-2 focus:ring-purple-400 text-xs sm:text-sm bg-white/50">
                <div class="flex gap-2">
                    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-2 sm:p-3 rounded-lg hover:scale-105 transition-transform flex items-center gap-2 text-xs sm:text-sm">
                        <span>Save</span>
                        <div id="edit-spinner" class="submit-spinner"></div>
                    </button>
                    <button type="button" onclick="closeEditModal()"
                            class="bg-gray-100 text-gray-700 p-2 sm:p-3 rounded-lg hover:scale-105 transition-transform text-xs sm:text-sm">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(index, taskText) {
            document.getElementById('edit-task-id').value = index;
            document.getElementById('edit-task-text').value = taskText;
            document.getElementById('edit-task-modal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-task-modal').classList.add('hidden');
        }

        function startSpinner() {
            document.querySelectorAll('.submit-spinner').forEach(spinner => spinner.style.display = 'block');
            setTimeout(() => document.querySelectorAll('.submit-spinner').forEach(spinner => spinner.style.display = 'none'), 800);
        }
    </script>
</body>
</html>