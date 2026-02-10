<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Waste Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-green-50 font-sans">

    <!-- Navbar -->
    <nav class="bg-green-700 text-white p-4 shadow-lg">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Waste Management System</h1>
            <div class="space-x-4">
                <a href="#home" class="hover:underline">Home</a>
                <a href="#report" class="hover:underline">Report Waste</a>
                <a href="#schedule" class="hover:underline">Collection Schedule</a>
                <a href="#about" class="hover:underline">About</a>
                <a href="login.php" class="bg-white text-green-700 px-3 py-1 rounded hover:underline">Login</a>
            </div>
        </div>
    </nav>

    <!-- Landing Section -->
    <section id="home" class="text-center py-20 bg-green-100">
        <h2 class="text-4xl font-bold text-green-800 mb-4">Community Waste Management</h2>
        <p class="text-gray-700 max-w-2xl mx-auto">
            This system helps communities report waste issues, monitor garbage collection schedules,
            and promote proper waste segregation for a cleaner environment.
        </p>
        <button onclick="scrollToSection('report')"
            class="mt-6 bg-green-700 text-white px-6 py-3 rounded-2xl shadow hover:bg-green-800">
            Report Waste Now
        </button>
    </section>

    <!-- Report Waste Section -->
    <section id="report" class="py-16">
        <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow">
            <h3 class="text-2xl font-semibold mb-4 text-green-700">Report Waste Issue</h3>

            <form id="wasteForm" class="space-y-4">
                <input type="text" id="name" placeholder="Your Name" class="w-full border p-2 rounded" required />
                <input type="text" id="location" placeholder="Waste Location" class="w-full border p-2 rounded"
                    required />

                <select id="type" class="w-full border p-2 rounded" required>
                    <option value="">Select Waste Type</option>
                    <option>Plastic</option>
                    <option>Organic</option>
                    <option>Metal</option>
                    <option>Electronic</option>
                </select>

                <textarea id="notes" placeholder="Additional details" class="w-full border p-2 rounded"></textarea>

                <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded">
                    Submit Report
                </button>
            </form>

            <p id="message" class="text-green-700 mt-4"></p>
        </div>
    </section>

    <!-- Schedule Section -->
    <section id="schedule" class="py-16 bg-green-100">
        <div class="max-w-4xl mx-auto">
            <h3 class="text-2xl font-semibold text-green-800 mb-6 text-center">Collection Schedule</h3>

            <div class="grid md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-2xl shadow">
                    <h4 class="font-bold">Monday</h4>
                    <p>Biodegradable Waste</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow">
                    <h4 class="font-bold">Wednesday</h4>
                    <p>Recyclable Waste</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow">
                    <h4 class="font-bold">Friday</h4>
                    <p>Residual Waste</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16">
        <div class="max-w-3xl mx-auto text-center">
            <h3 class="text-2xl font-semibold text-green-700">About the System</h3>
            <p class="mt-4 text-gray-700">
                The Waste Management System is designed for schools and local communities to track waste
                reports and improve garbage collection coordination. This prototype demonstrates a simple
                reporting workflow using HTML, TailwindCSS, and JavaScript.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-green-700 text-white text-center p-4">
        © 2026 Waste Management System
    </footer>

    <script>
        function scrollToSection(sectionId) {
            document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' });
        }

        document.getElementById('wasteForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const location = document.getElementById('location').value;
            const type = document.getElementById('type').value;

            document.getElementById('message').textContent =
                `Thank you, ${name}! Your ${type} waste report at ${location} has been submitted.`;

            this.reset();
        });
    </script>

</body>

</html>