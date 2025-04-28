<?php
session_start();
ini_set('error_log', 'php_errors.log');

function saveJson($file, $data) {
    if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT)) === false) {
        error_log("Failed to write to $file");
        return false;
    }
    return true;
}

function checkUserExists($username, $users) {
    return array_search(strtolower($username), array_column(array_map('strtolower', $users), 'username')) !== false;
}

function checkTaskExists($taskText, $tasks) {
    $taskTexts = array_map('strtolower', array_column($tasks, 'text'));
    return in_array(strtolower($taskText), $taskTexts);
}

function sanitizeUsername($username) {
    return preg_replace('/[^a-zA-Z0-9_-]/', '', $username);
}

function getUserTasks($username) {
    $sanitizedUsername = sanitizeUsername($username);
    $taskFile = "tasks_$sanitizedUsername.json";
    $tasks = [];
    if (file_exists($taskFile)) {
        $content = file_get_contents($taskFile);
        if ($content !== false) {
            $tasks = json_decode($content, true);
            if (!is_array($tasks)) {
                $tasks = [];
                error_log("Invalid task file content for $taskFile, resetting to empty array");
            }
        } else {
            error_log("Failed to read $taskFile");
        }
    }
    return [$taskFile, $tasks];
}

function getUserData() {
    $userFile = 'users.json';
    $userData = [];
    if (file_exists($userFile)) {
        $content = file_get_contents($userFile);
        if ($content !== false) {
            $userData = json_decode($content, true);
            if (!is_array($userData)) {
                $userData = [];
                error_log('Invalid users.json content, resetting to empty array');
            }
        } else {
            error_log('Failed to read users.json');
        }
    }
    return $userData;
}

function requireLogin() {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}
?>