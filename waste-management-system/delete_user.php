<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['index'])) {
    header('Location: dashboard_admin.php');
    exit;
}

$index = (int) $_GET['index'];
$usersFile = 'users.json';

if (!file_exists($usersFile)) {
    header('Location: dashboard_admin.php');
    exit;
}

$users = json_decode(file_get_contents($usersFile), true);
if (!is_array($users) || !isset($users[$index])) {
    header('Location: dashboard_admin.php');
    exit;
}

// Prevent deleting yourself
if ($users[$index]['username'] === $_SESSION['username']) {
    header('Location: dashboard_admin.php?error=Cannot%20delete%20your%20own%20account');
    exit;
}

// Remove user and save
unset($users[$index]);
$users = array_values($users); // Re-index array

file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

header('Location: dashboard_admin.php?success=User%20deleted%20successfully');
exit;
