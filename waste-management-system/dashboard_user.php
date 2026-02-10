<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

// Load reports
$reportsFile = 'reports.json';
$reports = [];
if (file_exists($reportsFile)) {
    $reports = json_decode(file_get_contents($reportsFile), true);
    if (!is_array($reports)) {
        $reports = [];
    }
}

// Calculate statistics
$totalReports = count($reports);
$userReports = array_filter($reports, function ($r) {
    return $r['username'] === $_SESSION['username'];
});
$userReportCount = count($userReports);

$reportsByType = [];
foreach ($reports as $report) {
    $type = $report['type'];
    if (!isset($reportsByType[$type])) {
        $reportsByType[$type] = 0;
    }
    $reportsByType[$type]++;
}

// Collection schedule
$schedule = [
    ['day' => 'Monday', 'type' => 'Biodegradable Waste'],
    ['day' => 'Wednesday', 'type' => 'Recyclable Waste'],
    ['day' => 'Friday', 'type' => 'Residual Waste']
];

$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Dashboard - Waste Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">
    <nav class="bg-green-700 text-white p-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold">User Dashboard</h1>
            <div class="space-x-4">
                <span>Signed in as <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="bg-white text-green-700 px-3 py-1 rounded">Logout</a>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-6">Welcome <?php echo htmlspecialchars($_SESSION['username']); ?></h2>

        <?php if ($success): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Statistics Section -->
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-gray-600 text-sm font-semibold">Your Reports</h3>
                <p class="text-3xl font-bold text-green-700"><?php echo $userReportCount; ?></p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-gray-600 text-sm font-semibold">Total Reports</h3>
                <p class="text-3xl font-bold text-blue-700"><?php echo $totalReports; ?></p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-gray-600 text-sm font-semibold">Waste Types</h3>
                <p class="text-3xl font-bold text-purple-700"><?php echo count($reportsByType); ?></p>
            </div>
        </div>

        <!-- Submit Report Section -->
        <div class="bg-white p-6 rounded shadow mb-6">
            <h3 class="text-xl font-semibold mb-4 text-green-700">Report Waste Issue</h3>
            <form action="submit_report.php" method="post" class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Waste Type *</label>
                        <select name="type" class="w-full border p-2 rounded" required>
                            <option value="">Select Waste Type</option>
                            <option>Plastic</option>
                            <option>Organic</option>
                            <option>Metal</option>
                            <option>Electronic</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Location *</label>
                        <input type="text" name="location" placeholder="Street or Area"
                            class="w-full border p-2 rounded" required />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Description</label>
                    <textarea name="description" placeholder="Additional details about the waste"
                        class="w-full border p-2 rounded h-24"></textarea>
                </div>
                <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">Submit
                    Report</button>
            </form>
        </div>

        <!-- Collection Schedule -->
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-xl font-semibold mb-4 text-green-700">Collection Schedule</h3>
                <div class="space-y-3">
                    <?php foreach ($schedule as $item): ?>
                        <div class="border-l-4 border-green-700 pl-4">
                            <h4 class="font-semibold"><?php echo $item['day']; ?></h4>
                            <p class="text-gray-600 text-sm"><?php echo $item['type']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Reports by Type -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-xl font-semibold mb-4 text-green-700">Reports by Type</h3>
                <div class="space-y-3">
                    <?php if (count($reportsByType) > 0): ?>
                        <?php foreach ($reportsByType as $type => $count): ?>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700"><?php echo htmlspecialchars($type); ?></span>
                                <span
                                    class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm font-semibold"><?php echo $count; ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">No reports yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-semibold mb-4 text-green-700">Your Recent Reports</h3>
            <?php if (count($userReports) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-green-100">
                                <th class="border p-3 text-left">Date</th>
                                <th class="border p-3 text-left">Type</th>
                                <th class="border p-3 text-left">Location</th>
                                <th class="border p-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Sort by date descending
                            usort($userReports, function ($a, $b) {
                                return strtotime($b['date']) - strtotime($a['date']);
                            });
                            foreach ($userReports as $report):
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3"><?php echo date('M d, Y H:i', strtotime($report['date'])); ?></td>
                                    <td class="border p-3"><?php echo htmlspecialchars($report['type']); ?></td>
                                    <td class="border p-3"><?php echo htmlspecialchars($report['location']); ?></td>
                                    <td class="border p-3 text-center">
                                        <span
                                            class="px-3 py-1 rounded text-sm font-semibold 
                                                <?php echo $report['status'] === 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800'; ?>">
                                            <?php echo ucfirst($report['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500">No reports submitted yet. Start by reporting a waste issue above!</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>