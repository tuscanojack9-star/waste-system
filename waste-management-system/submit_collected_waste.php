<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'collector') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard_collector.php');
    exit;
}

$collector = $_SESSION['username'];
$type = isset($_POST['type']) ? trim($_POST['type']) : '';
$quantity = isset($_POST['quantity']) ? (float) $_POST['quantity'] : 0;
$location = isset($_POST['location']) ? trim($_POST['location']) : '';
$collectionDate = isset($_POST['collection_date']) && !empty($_POST['collection_date']) ? $_POST['collection_date'] : date('Y-m-d H:i');
$notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

// Validation
if (empty($type) || empty($location) || $quantity <= 0) {
    $error = urlencode('Waste type, location, and quantity are required');
    header("Location: dashboard_collector.php?error={$error}");
    exit;
}

$collectedFile = 'collected_waste.json';
$collected = [];
if (file_exists($collectedFile)) {
    $collected = json_decode(file_get_contents($collectedFile), true);
    if (!is_array($collected)) {
        $collected = [];
    }
}

// Create new collection record
$newCollection = [
    'id' => time(),
    'collector' => $collector,
    'type' => $type,
    'quantity' => $quantity,
    'location' => $location,
    'notes' => $notes,
    'date' => date('Y-m-d H:i:s', strtotime($collectionDate))
];

$collected[] = $newCollection;

if (file_put_contents($collectedFile, json_encode($collected, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
    $success = urlencode('Collection logged successfully');
    header("Location: dashboard_collector.php?success={$success}");
    exit;
} else {
    $error = urlencode('Could not log collection. Please try again.');
    header("Location: dashboard_collector.php?error={$error}");
    exit;
}
