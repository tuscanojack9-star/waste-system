<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Load users from JSON
$usersFile = 'users.json';
$users = [];

if (file_exists($usersFile)) {
    $users = json_decode(file_get_contents($usersFile), true);
    if (!is_array($users)) {
        $users = [];
    }
}

// Check credentials
$authenticated = false;

foreach ($users as $user) {
    if ($user['username'] === $username && $user['password'] === $password) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role'];
        $authenticated = true;
        break;
    }
}

if ($authenticated) {

    switch ($_SESSION['role']) {
        case 'admin':
            header('Location: dashboard_admin.php');
            break;

        case 'collector':
            header('Location: dashboard_collector.php');
            break;

        case 'user':
        default:
            header('Location: dashboard_user.php');
            break;
    }

    exit;

} else {
    $msg = urlencode('Invalid credentials');
    header("Location: login.php?error={$msg}");
    exit;
}
