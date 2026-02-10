<?php
session_start();
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: dashboard_admin.php');
        exit;
    } else {
        header('Location: dashboard_user.php');
        exit;
    }
}
$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - Waste Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-green-50 font-sans">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow">
            <h2 class="text-2xl font-bold text-green-700 mb-4">Create Account</h2>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 p-2 rounded mb-4"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form action="register_handler.php" method="post" class="space-y-4">
                <input type="email" name="email" placeholder="Email Address" class="w-full border p-2 rounded"
                    required />
                <input type="text" name="username" placeholder="Username" class="w-full border p-2 rounded" required />
                <input type="password" name="password" placeholder="Password" class="w-full border p-2 rounded"
                    required />
                <input type="password" name="confirm_password" placeholder="Confirm Password"
                    class="w-full border p-2 rounded" required />
                <select name="role" class="w-full border p-2 rounded" required>
                    <option value="">Select Role</option>
                    <option value="user">User</option>
                </select>
                <button type="submit" class="w-full bg-green-700 text-white px-4 py-2 rounded">Register</button>
            </form>
            <p class="text-sm text-gray-700 mt-4">
                Already have an account? <a href="login.php" class="text-green-700 hover:underline">Sign In</a>
            </p>
        </div>
    </div>
</body>

</html>