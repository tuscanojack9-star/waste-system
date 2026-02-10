<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
$role = isset($_POST['role']) ? $_POST['role'] : 'user';

// Validation
if (empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
    $error = urlencode('All fields are required');
    header("Location: register.php?error={$error}");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = urlencode('Invalid email format');
    header("Location: register.php?error={$error}");
    exit;
}

if (strlen($username) < 3) {
    $error = urlencode('Username must be at least 3 characters');
    header("Location: register.php?error={$error}");
    exit;
}

if (strlen($password) < 6) {
    $error = urlencode('Password must be at least 6 characters');
    header("Location: register.php?error={$error}");
    exit;
}

if ($password !== $confirm_password) {
    $error = urlencode('Passwords do not match');
    header("Location: register.php?error={$error}");
    exit;
}

if ($role !== 'user' && $role !== 'admin') {
    $role = 'user';
}

// Load users
$usersFile = 'users.json';
$users = [];
if (file_exists($usersFile)) {
    $users = json_decode(file_get_contents($usersFile), true);
    if (!is_array($users)) {
        $users = [];
    }
}

// Check if username exists
foreach ($users as $user) {
    if ($user['username'] === $username) {
        $error = urlencode('Username already taken');
        header("Location: register.php?error={$error}");
        exit;
    }
    if ($user['email'] === $email) {
        $error = urlencode('Email already registered');
        header("Location: register.php?error={$error}");
        exit;
    }
}

// Create new user
$newUser = [
    'username' => $username,
    'password' => $password, // In production, use password_hash()
    'role' => $role,
    'email' => $email
];

$users[] = $newUser;

// Save to file
if (file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
    $success = urlencode('Registration successful. Please login.');
    header("Location: login.php?success={$success}");
    exit;
} else {
    $error = urlencode('Could not save user. Please try again.');
    header("Location: register.php?error={$error}");
    exit;
}
