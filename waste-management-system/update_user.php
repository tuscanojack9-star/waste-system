<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard_admin.php');
    exit;
}

$index = isset($_POST['index']) ? (int) $_POST['index'] : -1;
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$role = isset($_POST['role']) ? $_POST['role'] : 'user';

$usersFile = 'users.json';

if (!file_exists($usersFile)) {
    header('Location: dashboard_admin.php?error=User%20file%20not%20found');
    exit;
}

$users = json_decode(file_get_contents($usersFile), true);
if (!is_array($users) || !isset($users[$index])) {
    header('Location: dashboard_admin.php?error=User%20not%20found');
    exit;
}

// Validation
if (empty($username) || empty($email)) {
    $error = urlencode('All fields are required');
    header("Location: edit_user.php?index={$index}&error={$error}");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = urlencode('Invalid email format');
    header("Location: edit_user.php?index={$index}&error={$error}");
    exit;
}

if (strlen($username) < 3) {
    $error = urlencode('Username must be at least 3 characters');
    header("Location: edit_user.php?index={$index}&error={$error}");
    exit;
}

if ($role !== 'user' && $role !== 'admin') {
    $role = 'user';
}

// Check if username or email already exists (excluding current user)
foreach ($users as $i => $user) {
    if ($i !== $index) {
        if ($user['username'] === $username) {
            $error = urlencode('Username already taken');
            header("Location: edit_user.php?index={$index}&error={$error}");
            exit;
        }
        if ($user['email'] === $email) {
            $error = urlencode('Email already registered');
            header("Location: edit_user.php?index={$index}&error={$error}");
            exit;
        }
    }
}

// Update user
$users[$index]['username'] = $username;
$users[$index]['email'] = $email;
$users[$index]['role'] = $role;

// Update password only if provided
if (!empty($password)) {
    if (strlen($password) < 6) {
        $error = urlencode('Password must be at least 6 characters');
        header("Location: edit_user.php?index={$index}&error={$error}");
        exit;
    }
    $users[$index]['password'] = $password;
}

// Save to file
if (file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
    $success = urlencode('User updated successfully');
    header("Location: dashboard_admin.php?success={$success}");
    exit;
} else {
    $error = urlencode('Could not save changes. Please try again.');
    header("Location: edit_user.php?index={$index}&error={$error}");
    exit;
}
