<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: reset_password.php');
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if (empty($email)) {
    $error = urlencode('Email is required');
    header("Location: reset_password.php?error={$error}");
    exit;
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

// Find user by email
$userFound = false;
$username = '';
foreach ($users as $user) {
    if ($user['email'] === $email) {
        $userFound = true;
        $username = $user['username'];
        break;
    }
}

if ($userFound) {
    // In production: send email with reset token
    // For demo: show temporary password option
    $tempPassword = 'TEMP' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);

    // Update user password
    for ($i = 0; $i < count($users); $i++) {
        if ($users[$i]['email'] === $email) {
            $users[$i]['password'] = $tempPassword;
            break;
        }
    }

    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    $success = urlencode("Reset successful for user: {$username}. Temp password: {$tempPassword}. (In production, this would be emailed.)");
    header("Location: reset_password.php?success={$success}");
    exit;
} else {
    // Security: don't reveal if email exists
    $success = urlencode('If an account with that email exists, you will receive a password reset link.');
    header("Location: reset_password.php?success={$success}");
    exit;
}
