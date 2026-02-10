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
    <title>Login - Waste Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-green-50 font-sans">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow">
            <h2 class="text-2xl font-bold text-green-700 mb-4">Sign In</h2>
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 p-2 rounded mb-4"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="authenticate.php" method="post" class="space-y-4">
                <input type="text" name="username" placeholder="Username" class="w-full border p-2 rounded" required />
                <input type="password" name="password" placeholder="Password" class="w-full border p-2 rounded"
                    required />
                <button type="submit" class="w-full bg-green-700 text-white px-4 py-2 rounded">Login</button>
            </form>
            <div class="mt-4 text-sm space-y-2">
                <p><a href="reset_password.php" class="text-green-700 hover:underline">Forgot your password?</a></p>
                <p>Don't have an account? <a href="register.php" class="text-green-700 hover:underline">Register
                        here</a></p>
                <p><a href="index.php" class="text-green-700 hover:underline">Back to Home</a></p>
            </div>
        </div>
    </div>
</body>

</html>