<?php
session_start();
ini_set('display_errors', 0);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartTrack • RFID School Attendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    
    <style>
 @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap');
        body { font-family: 'Inter', system_ui, sans-serif; }
        .logo-font { font-family: 'Space Grotesk', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.97); backdrop-filter: blur(24px); }
        .dark .glass { background: rgba(15, 23, 42, 0.97); }
        .input-focus:focus { box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.2); border-color: #00d4ff; }

        .sidebar-link {
            position: relative;
        }
        .sidebar-link.active::after {
            content: '';
            position: absolute;
            left: 0; top: 50%; transform: translateY(-50%);
            height: 28px; width: 5px;
            background: linear-gradient(#00d4ff, #0099cc);
            border-radius: 9999px;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px -15px rgba(0, 212, 255, 0.25);
        }

        .scan-pulse { animation: scanPulse 2.5s infinite; }
        @keyframes scanPulse { 0%,100% { opacity: 1; } 50% { opacity: 0.6; } }

.input-focus:focus {
    box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.2);
    border-color: #00d4ff;
}
        .signup-scroll::-webkit-scrollbar { width: 6px; }
        .signup-scroll::-webkit-scrollbar-thumb {
            background: #00d4ff;
            border-radius: 20px;}
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen">

<!-- AUTH SCREEN -->
    <div id="auth-screen" class="fixed inset-0 bg-gradient-to-br from-cyan-500 via-blue-600 to-indigo-600 flex items-center justify-center p-6 z-[10000]">
        <div class="glass rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white p-12 text-center">
                <div class="mx-auto w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center text-6xl mb-6">📡</div>
                <h1 class="logo-font text-5xl font-bold tracking-tighter">SmartTrack</h1>
                <p class="text-white/90 text-lg mt-2">RFID School Attendance System</p>
            </div>

            <div class="p-10">
                <div class="flex border-b mb-8">
                    <button onclick="switchAuthTab(0)" id="tab-signup" 
                            class="flex-1 py-4 font-semibold text-lg border-b-4 border-cyan-500">Register School</button>
                    <button onclick="switchAuthTab(1)" id="tab-login" 
                            class="flex-1 py-4 font-semibold text-lg">Login</button>
                </div>

                <!-- REGISTER FORM -->
                <div id="signup-form">
                    <div class="signup-scroll">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium mb-2">School Name <span class="text-red-500">*</span></label>
                                <input id="school-name" type="text" class="input-focus w-full px-6 py-4 rounded-3xl border border-slate-300 bg-white text-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Admin Name <span class="text-red-500">*</span></label>
                                <input id="admin-name" type="text" class="input-focus w-full px-6 py-4 rounded-3xl border border-slate-300 bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">School Email <span class="text-red-500">*</span></label>
                                <input id="school-email" type="email" class="input-focus w-full px-6 py-4 rounded-3xl border border-slate-300 bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Password <span class="text-red-500">*</span></label>
                                <input id="school-password" type="password" class="input-focus w-full px-6 py-4 rounded-3xl border border-slate-300 bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Entry Time</label>
                                <input id="entry-time" type="time" value="08:00" class="input-focus w-full px-6 py-4 rounded-3xl border border-slate-300 bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Absent Threshold</label>
                                <input id="absent-threshold" type="number" value="5" min="1" class="input-focus w-full px-6 py-4 rounded-3xl border border-slate-300 bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Late Threshold</label>
                                <input id="late-threshold" type="number" value="3" min="1" class="input-focus w-full px-6 py-4 rounded-3xl border border-slate-300 bg-white">
                            </div>
                        </div>
                    </div>

                    <button onclick="handleSchoolSignup()" 
                            class="mt-8 w-full py-6 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-semibold text-xl rounded-3xl shadow-lg hover:scale-105 transition-transform">
                        Register School &amp; Create Account
                    </button>
                </div>

                <!-- LOGIN FORM -->
                <div id="login-form" class="hidden">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">School Email</label>
                            <input id="login-email" type="email" class="input-focus w-full px-6 py-4 rounded-3xl border border-slate-300 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Password</label>
                            <input id="login-password" type="password" class="input-focus w-full px-6 py-4 rounded-3xl border border-slate-300 bg-white">
                        </div>
                        <button onclick="handleSchoolLogin()" 
                                class="w-full py-6 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-semibold text-xl rounded-3xl shadow-lg hover:scale-105 transition-transform">
                            Login to Dashboard
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="toast" class="hidden fixed bottom-8 right-8 bg-slate-800 text-white px-8 py-4 rounded-3xl shadow-2xl z-[100000]">
        <span id="toast-text" class="font-medium"></span>
    </div>
    
    <!-- MAIN APP -->
    <div id="main-app" class="hidden">
        <nav class="glass border-b sticky top-0 z-50">
            <div class="max-w-screen-2xl mx-auto px-8 py-5 flex items-center justify-between">
                <div class="flex items-center gap-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-3xl flex items-center justify-center text-white text-3xl">
                        <i class="fa-solid fa-rfid"></i>
                    </div>
                    <div>
                        <h1 id="school-name-header" class="logo-font text-3xl font-bold tracking-tighter"></h1>
                        <p id="admin-name-header" class="text-sm text-slate-500"></p>
                    </div>
                </div>
                <div class="flex items-center gap-x-6">
                    <div id="esp-status" class="flex items-center gap-x-3 bg-red-100 px-6 py-3 rounded-3xl">
                        <div id="esp-dot" class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span id="esp-text" class="font-medium">ESP32 Offline</span>
                    </div>
                    <button onclick="toggleDarkMode()" class="text-2xl p-3 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-3xl">
                        <i id="theme-icon" class="fa-solid fa-moon"></i>
                    </button>
                    <div onclick="logout()" class="cursor-pointer flex items-center gap-x-3">
                        <span class="font-medium">Logout</span>
                        <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-fuchsia-500 rounded-3xl flex items-center justify-center text-white text-2xl">👋</div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex max-w-screen-2xl mx-auto">
            <!-- Sidebar -->
            <div class="w-80 bg-white dark:bg-slate-900 border-r min-h-[calc(100vh-81px)] p-6 flex flex-col">
                <nav class="space-y-2 flex-1">
                    <a onclick="navigateTo('dashboard')" id="nav-dashboard" class="sidebar-link active flex items-center gap-x-4 px-6 py-4 rounded-3xl hover:bg-slate-100 dark:hover:bg-slate-800 font-medium">
                        <i class="fa-solid fa-house w-5"></i> Dashboard
                    </a>
                    <a onclick="navigateTo('students')" id="nav-students" class="sidebar-link flex items-center gap-x-4 px-6 py-4 rounded-3xl hover:bg-slate-100 dark:hover:bg-slate-800 font-medium">
                        <i class="fa-solid fa-users w-5"></i> Students
                    </a>
                    <a onclick="navigateTo('register')" id="nav-register" class="sidebar-link flex items-center gap-x-4 px-6 py-4 rounded-3xl hover:bg-slate-100 dark:hover:bg-slate-800 font-medium">
                        <i class="fa-solid fa-circle-plus w-5"></i> Register New Card
                    </a>
                    <a onclick="navigateTo('reports')" id="nav-reports" class="sidebar-link flex items-center gap-x-4 px-6 py-4 rounded-3xl hover:bg-slate-100 dark:hover:bg-slate-800 font-medium">
                        <i class="fa-solid fa-chart-bar w-5"></i> Reports
                    </a>
                    <a onclick="navigateTo('notifications')" id="nav-notifications" class="sidebar-link flex items-center gap-x-4 px-6 py-4 rounded-3xl hover:bg-slate-100 dark:hover:bg-slate-800 font-medium">
                        <i class="fa-solid fa-bell w-5"></i> Notifications
                    </a>
                </nav>

                <button onclick="showBulkImport()" class="mt-auto flex items-center justify-center gap-x-3 py-4 bg-slate-100 dark:bg-slate-800 hover:bg-cyan-50 rounded-3xl font-medium">
                    <i class="fa-solid fa-file-excel text-xl"></i> Bulk Import Excel
                </button>
            </div>

            <!-- Main Content -->
            <div class="flex-1 p-10" id="main-content">

                <!-- DASHBOARD -->
                <div id="page-dashboard">
                    <div class="flex justify-between items-end mb-12">
                        <div>
                            <h2 class="text-5xl font-bold tracking-tighter logo-font">Good morning, <span id="greeting-name"></span>!</h2>
                            <p class="text-slate-500 mt-2 text-lg" id="current-date"></p>
                        </div>

                    </div>

                    <div class="grid grid-cols-4 gap-8">
                        <div class="glass border rounded-3xl p-8 card-hover">
                            <p class="text-slate-500">Total Students</p>
                            <p id="stat-total" class="text-6xl font-bold mt-6">00</p>
                        </div>
                        <div class="glass border rounded-3xl p-8 card-hover">
                            <p class="text-slate-500">Present Today</p>
                            <p id="stat-present" class="text-6xl font-bold mt-6 text-emerald-600">00</p>
                        </div>
                        <div class="glass border rounded-3xl p-8 card-hover">
                            <p class="text-slate-500">Absent Today</p>
                            <p id="stat-absent" class="text-6xl font-bold mt-6 text-amber-600">00</p>
                        </div>
                        <div class="glass border rounded-3xl p-8 card-hover">
                            <p class="text-slate-500">Cards Registered</p>
                            <p id="stat-registered" class="text-6xl font-bold mt-6 text-violet-600">00</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-7 gap-8 mt-12">
                        <div class="col-span-5 glass border rounded-3xl p-8">
                            <h3 class="font-semibold text-xl mb-6">Attendance Trend (Last 14 Days)</h3>
                            <canvas id="trendChart" height="130"></canvas>
                        </div>
                        <div class="col-span-2 glass border rounded-3xl p-8">
                            <h3 class="font-semibold text-xl mb-6">Grade Distribution</h3>
                            <canvas id="gradeChart" height="280"></canvas>
                        </div>
                    </div>
                </div>

                <!-- STUDENTS PAGE -->
                <div id="page-students" class="hidden">
                    <h2 class="text-4xl font-bold tracking-tighter mb-8">All Students</h2>
                    <div class="flex gap-4 mb-8">
                        <input id="student-search" type="text" placeholder="Search by name or RFID..." class="flex-1 px-6 py-4 rounded-3xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 focus:outline-none">
                        <button onclick="searchStudents()" class="px-10 bg-slate-900 text-white rounded-3xl font-medium">Search</button>
                    </div>
                    <div class="glass rounded-3xl overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-slate-100 dark:bg-slate-800">
                                <tr>
                                    <th class="px-8 py-5 text-left font-medium">RFID UID</th>
                                    <th class="px-8 py-5 text-left font-medium">Name</th>
                                    <th class="px-8 py-5 text-left font-medium">Grade</th>
                                    <th class="px-8 py-5 text-left font-medium">Parent Phone</th>
                                    <th class="px-8 py-5 text-center font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="students-table" class="divide-y"></tbody>
                        </table>
                    </div>
                </div>

                <!-- REGISTER PAGE -->
                <div id="page-register" class="hidden max-w-2xl mx-auto">
                    <div class="glass rounded-3xl p-12">
                        <h2 class="text-4xl font-bold tracking-tighter mb-10">Register New Student + RFID Card</h2>
                        <form id="register-form" onsubmit="handleRegister(event)" class="space-y-8">
                            <div>
                                <label class="block text-sm font-medium mb-2">RFID UID <span class="text-red-500">*</span></label>
                                <div class="flex gap-3">
                                    <input id="rfid-uid" type="text" readonly placeholder="Waiting for scan..." required class="flex-1 px-6 py-4 rounded-3xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-lg font-mono">
                                    <button type="button" onclick="simulateRFIDRead()" class="px-8 bg-cyan-100 dark:bg-cyan-900 text-cyan-600 rounded-3xl">
                                        <i class="fa-solid fa-rfid text-3xl"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Full Name</label>
                                    <input id="name" type="text" required class="w-full px-6 py-4 rounded-3xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Grade Level</label>
                                    <select id="grade" required class="w-full px-6 py-4 rounded-3xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                                        <option value="">Select Grade</option>
                                        <option value="1">Grade 1</option><option value="2">Grade 2</option><option value="3">Grade 3</option>
                                        <option value="4">Grade 4</option><option value="5">Grade 5</option><option value="6">Grade 6</option>
                                        <option value="7">Grade 7</option><option value="8">Grade 8</option><option value="9">Grade 9</option>
                                        <option value="10">Grade 10</option><option value="11">Grade 11</option><option value="12">Grade 12</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Parent Phone</label>
                                    <input id="parent-phone" type="tel" required placeholder="+251 9XX XXX XXX" class="w-full px-6 py-4 rounded-3xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Gender</label>
                                    <div class="flex gap-8 px-6 py-4 border border-slate-300 dark:border-slate-600 rounded-3xl bg-white dark:bg-slate-800">
                                        <label class="flex items-center gap-2"><input type="radio" name="gender" value="Male" checked> Male</label>
                                        <label class="flex items-center gap-2"><input type="radio" name="gender" value="Female"> Female</label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Address</label>
                                <textarea id="address" rows="3" class="w-full px-6 py-4 rounded-3xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800"></textarea>
                            </div>
                            <button type="submit" class="w-full py-5 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-semibold text-xl rounded-3xl">Register Student &amp; Card</button>
                        </form>
                    </div>
                </div>

                <!-- REPORTS PAGE -->
                <div id="page-reports" class="hidden">
                    <h2 class="text-4xl font-bold tracking-tighter mb-8">Student Attendance Reports</h2>
                    <div class="flex gap-4 mb-8">
                        <input id="report-search" type="text" placeholder="Search student name or RFID..." class="flex-1 px-6 py-4 rounded-3xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                        <button onclick="searchStudentReport()" class="px-10 bg-slate-900 text-white rounded-3xl font-medium">Find Report</button>
                    </div>
                    <div id="report-result" class="hidden glass rounded-3xl p-10"></div>
                    <div id="report-placeholder" class="glass rounded-3xl p-20 text-center text-slate-400">
                        Search for a student to view detailed attendance history
                    </div>
                </div>

                <!-- NOTIFICATIONS PAGE -->
                <div id="page-notifications" class="hidden">
                    <h2 class="text-4xl font-bold tracking-tighter mb-8 flex items-center gap-3">
                        <i class="fa-solid fa-bell"></i> Notifications &amp; Alerts
                    </h2>
                    <div id="notifications-list" class="space-y-6"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCAN MODAL -->
    <div id="scan-modal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-[99999]">
        <div class="glass rounded-3xl max-w-md w-full p-10 text-center">
            <div class="text-7xl mb-6">✅</div>
            <h3 id="modal-name" class="text-3xl font-bold"></h3>
            <p id="modal-rfid" class="font-mono text-cyan-600 mt-2"></p>
            <p id="modal-grade" class="text-slate-500 mt-1"></p>
            <div class="mt-10 pt-8 border-t">
                <p class="text-sm">Scanned at <span id="modal-time" class="font-semibold"></span></p>
            </div>
            <button onclick="hideScanModal()" class="mt-10 w-full py-4 bg-slate-900 text-white rounded-3xl font-medium">Close</button>
        </div>
    </div>


    <script>
        // ====================== FRONTEND LOGIC ======================
        const API_BASE = "api/";
        let schoolData = null
        let allStudents = []
        let trendChartInstance = null
        let gradeChartInstance = null
        let attendanceLog = [];
console.log("%cSmartTrack JS loaded", "color: #00d4ff; font-weight: bold");
function switchAuthTab(tab) {
            document.getElementById('signup-form').classList.toggle('hidden', tab !== 0);
            document.getElementById('login-form').classList.toggle('hidden', tab !== 1);
            
            document.getElementById('tab-signup').classList.toggle('border-b-4', tab === 0);
            document.getElementById('tab-signup').classList.toggle('border-cyan-500', tab === 0);
            document.getElementById('tab-login').classList.toggle('border-b-4', tab === 1);
            document.getElementById('tab-login').classList.toggle('border-cyan-500', tab === 1);
        }

 function showToast(message) {
    const toast = document.getElementById('toast');
    document.getElementById('toast-text').innerHTML = message;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 4000);
}
window.handleSchoolSignup = async function () {
    try {
        const payload = {
            username: document.getElementById('school-email').value.trim(),
            password: document.getElementById('school-password').value,
            school_name: document.getElementById('school-name').value.trim(),
            admin_name: document.getElementById('admin-name').value.trim(),
            email: document.getElementById('school-email').value.trim()
        };

        const res = await fetch("api/register.php", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const text = await res.text();

        let data;
        try {
            data = JSON.parse(text);
        } catch {
            console.error("Invalid JSON:", text);
            alert("Server returned invalid response");
            return;
        }

        if (data.success) {
            alert("✅ Registered successfully");
        } else {
            alert("❌ " + (data.message || "Registration failed"));
        }

    } catch (err) {
        console.error(err);
        alert("❌ Network error");
    }
};
async function handleSchoolLogin() {
    const email = document.getElementById("login-email").value.trim();
    const password = document.getElementById("login-password").value.trim();

    if (!email || !password) {
        showToast("❌ Please enter email and password");
        return;
    }

    try {
        const res = await fetch(API_BASE + "login.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ username: email, password: password })
        });

        const text = await res.text();
        console.log("Login RAW Response:", text);

        if (text.includes("<html")) {
            showToast("⚠️ Server returned HTML (error)");
            return;
        }

        const data = JSON.parse(text);

if (data.success) {
    showToast("✅ Login Successful!");

    // ✅ SAVE DATA
    schoolData = {
        schoolName: data.schoolName,
        adminName: data.adminName
    };

    // ✅ INIT APP
    initializeMainApp();

    document.getElementById("auth-screen").classList.add("hidden");
    document.getElementById("main-app").classList.remove("hidden");
} else {
            showToast("❌ " + (data.message || "Login failed"));
        }

    } catch (e) {
        console.error(e);
        showToast("❌ Server error");
    }
}

        function initializeMainApp() {
            document.getElementById('school-name-header').textContent = schoolData.schoolName
            document.getElementById('admin-name-header').textContent = schoolData.adminName + " • Unit Leader"
            const fullName = (schoolData.adminName || "").trim();

const firstName = (schoolData.adminName || "Admin").trim().split(/\s+/)[0];
document.getElementById('greeting-name').textContent = firstName;
            console.log("ADMIN NAME RAW:", schoolData.adminName);
            navigateTo('dashboard')
            loadAttendanceLog();
        }

        function navigateTo(page) {
            document.querySelectorAll('#main-content > div').forEach(d => d.classList.add('hidden'))
            document.getElementById(`page-${page}`).classList.remove('hidden')

            document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'))
            document.getElementById(`nav-${page}`).classList.add('active')

            if (page === 'dashboard') refreshDashboard()
            if (page === 'students') loadStudentsTable()
            if (page === 'notifications') loadNotifications()
            if (page === 'reports') loadAttendanceLog();
        }

        function refreshDashboard() {
            document.getElementById('stat-total').textContent = allStudents.length || "00"
            document.getElementById('stat-present').textContent = "00"
            document.getElementById('stat-absent').textContent = "00"
            document.getElementById('stat-registered').textContent = allStudents.length || "00"
            document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' })

            updateTrendChart()
            updateGradeChart()
        }
async function loadAttendanceLog() {
    try {
        const res = await fetch(API_BASE + "get_attendance.php", {
            cache: "no-store"
        });
        if (!res.ok) throw new Error("HTTP " + res.status);
        
        const data = await res.json();
        if (data.success) {
            attendanceLog = data.attendance || [];
            console.log("Attendance loaded:", attendanceLog.length, "records");
        }
    } catch (e) {
        console.warn("Failed to load attendance (this is normal if server is slow or offline):", e.message);
        // Don't crash the page
    }
}
        function updateTrendChart() {
            if (trendChartInstance) trendChartInstance.destroy()
            const ctx = document.getElementById('trendChart')
            trendChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Array.from({length:14}, (_,i) => `Mar ${15+i}`),
                    datasets: [{ label: 'Present', data: Array.from({length:14}, () => Math.floor(Math.random()*20)+10), borderColor: '#00d4ff', tension: 0.4, borderWidth: 4 }]
                },
                options: { plugins: { legend: { display: false } } }
            })
        }

        function updateGradeChart() {
            if (gradeChartInstance) gradeChartInstance.destroy()
            const ctx = document.getElementById('gradeChart')
            gradeChartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Grade 1-4', 'Grade 5-8', 'Grade 9-12'],
                    datasets: [{ data: [12, 18, 15], backgroundColor: ['#06b67f', '#00d4ff', '#7c3aed'] }]
                },
                options: { cutout: 85, plugins: { legend: { position: 'bottom' } } }
            })
        }

    async function loadStudentsTable() {
    const res = await fetch("api/get_students.php")
    const data = await res.json()

    if (!data.success) {
        showToast("Error loading students")
        return
    }

    allStudents = data.students

    const tbody = document.getElementById('students-table')

    tbody.innerHTML = allStudents.map((s, i) => `
        <tr>
            <td>${s.rfid}</td>
            <td>${s.name}</td>
            <td>${s.grade}</td>
            <td>${s.parent_phone || 'N/A'}</td>
            <td>
                <button onclick="viewStudentReport(${i})">View</button>
            </td>
        </tr>
    `).join('')
}

async function handleRegister(e) {
    e.preventDefault()

    const student = {
        rfid: document.getElementById('rfid-uid').value,
        name: document.getElementById('name').value,
        grade: document.getElementById('grade').value,
        parentPhone: document.getElementById('parent-phone').value,
        gender: document.querySelector('input[name="gender"]:checked').value,
        address: document.getElementById('address').value
    }

    const res = await fetch("api/add_student.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(student)
    })

    const data = await res.json()

    if (data.success) {
        showToast("✅ Student added")
        navigateTo('students')
    } else {
        showToast("❌ " + data.error)
    }
}

async function waitForRFID() {
    // ONLY run on register page
    if (document.getElementById('page-register').classList.contains('hidden')) {
        return;
    }

    try {
        const res = await fetch("api/get_last_uid.php?nocache=" + Date.now());
        const text = await res.text();

        if (text.startsWith("<")) return;

        const data = JSON.parse(text);

        if (data.uid) {
            document.getElementById("rfid-uid").value = data.uid;
        }

    } catch (err) {
        console.error("RFID fetch error", err);
    }
}

// check every 2 seconds
setInterval(waitForRFID, 8000);
        
function markAttendance(student) {
    const now = new Date()
    const timeStr = now.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})
    const dateStr = now.toISOString().slice(0,10)

    attendanceLog.unshift({
        rfid: student.rfid,
        name: student.name,
        time: timeStr,
        date: dateStr
    })

    // Show modal
    document.getElementById('modal-name').textContent = student.name
    document.getElementById('modal-rfid').textContent = student.rfid
    document.getElementById('modal-grade').textContent = `Grade ${student.grade} • ${student.parentPhone}`
    document.getElementById('modal-time').textContent = timeStr
    document.getElementById('scan-modal').classList.remove('hidden')

    showToast(`📲 Attendance recorded for ${student.name}`)
    refreshDashboard()
}

        function hideScanModal() {
            document.getElementById('scan-modal').classList.add('hidden')
        }

        function searchStudents() {
            loadStudentsTable() // Simple demo - add filter if needed
        }

        function viewStudentReport(i) {
            navigateTo('reports')
            const student = allStudents[i]
            document.getElementById('report-search').value = student.name
            searchStudentReport()
        }

        function searchStudentReport() {
            const query = document.getElementById('report-search').value.toLowerCase().trim()
            const student = allStudents.find(s => s.name.toLowerCase().includes(query) || s.rfid.toLowerCase() === query)
            if (!student) return showToast("Student not found")

            document.getElementById('report-placeholder').classList.add('hidden')
            const resultDiv = document.getElementById('report-result')
            resultDiv.classList.remove('hidden')

            const history = attendanceLog.filter(l => l.rfid === student.rfid)

            resultDiv.innerHTML = `
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-3xl font-bold">${student.name}</h3>
                        <p class="font-mono text-cyan-600">${student.rfid} • Grade ${student.grade}</p>
                    </div>
                    <div>
                        <button onclick="printPDF('${student.rfid}')" class="px-6 py-3 border rounded-3xl flex items-center gap-2"><i class="fa-solid fa-file-pdf"></i> Download PDF</button>
                    </div>
                </div>
                <table class="w-full mt-10 text-sm">
                    <thead><tr class="border-b"><th class="py-4 text-left">Date</th><th class="py-4 text-left">Time</th><th class="py-4 text-left">Status</th></tr></thead>
                    <tbody class="divide-y">
                        ${history.map(h => `<tr><td>${h.date}</td><td>${h.time}</td><td class="text-emerald-600 font-medium">PRESENT</td></tr>`).join('')}
                    </tbody>
                </table>
            `
        }

        function printPDF(rfid) {
            const student = allStudents.find(s => s.rfid === rfid)
            const { jsPDF } = window.jspdf
            const doc = new jsPDF()
            doc.text(`Attendance Report - ${student.name}`, 20, 20)
            doc.autoTable({
                startY: 40,
                head: [['Date', 'Time', 'Status']],
                body: attendanceLog.filter(l => l.rfid === rfid).map(l => [l.date, l.time, 'Present'])
            })
            doc.save(`${student.name.replace(' ', '_')}_attendance.pdf`)
            showToast("📄 PDF downloaded successfully")
        }

        function loadNotifications() {
            const container = document.getElementById('notifications-list')
            container.innerHTML = `
                <div class="glass rounded-3xl p-12 text-center">
                    <i class="fa-solid fa-bell text-6xl text-amber-400 mb-6"></i>
                    <p class="text-xl">No critical alerts right now</p>
                    <p class="text-slate-400 mt-2">All students are within the set thresholds</p>
                </div>
            `
        }

        function showBulkImport() {
            const csvContent = `RFID_UID,Full_Name,Grade,Parent_Phone,Gender,Address\nA3:4F:2B:9C,Abebe Tesfaye,7,+251912345678,Male,Bole\nB7:1D:8E:4A,Meron Kebede,4,+251911234567,Female,Piassa`
            const blob = new Blob([csvContent], { type: 'text/csv' })
            const url = URL.createObjectURL(blob)
            const a = document.createElement('a')
            a.href = url
            a.download = 'smarttrack_student_template.csv'
            a.click()
            showToast("📥 Sample Excel (CSV) template downloaded")
        }

        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark')
            const icon = document.getElementById('theme-icon')
            icon.classList.toggle('fa-moon')
            icon.classList.toggle('fa-sun')
        }

        function logout() {
            if (confirm("Logout from SmartTrack?")) {
                location.reload()
            }
        }

async function checkESPStatus() {
    try {
        const res = await fetch('api/status.php')
        const data = await res.json()

        const dot = document.getElementById('esp-dot')
        const text = document.getElementById('esp-text')

        if (data.online === true) {
            dot.className = "w-3 h-3 bg-green-500 rounded-full"
            text.textContent = "ESP32 Online"
        } else {
            dot.className = "w-3 h-3 bg-red-500 rounded-full"
            text.textContent = "ESP32 Offline"
        }

    } catch {
        document.getElementById('esp-text').textContent = "ESP32 Offline"
    }
}
        // ====================== BACKEND SIMULATION (PHP-like) ======================
        // In a real deployment, save this file as index.php and create these endpoints:
        // 1. api/register.php
        // 2. api/attendance.php (ESP32 target)
        // 3. api/send-sms.php (Twilio)

        console.log("%c🚀 SmartTrack Full Stack Demo Ready (Frontend + Simulated Backend)", "color:#00d4ff; font-weight:700")
window.onload = () => {
            switchAuthTab(0);
            console.log("%c✅ SmartTrack Fixed Version Loaded", "color:#00d4ff; font-weight:bold");
        };
    window.addEventListener('load', () => {
    console.log("%c🚀 SmartTrack Ready - Check browser console for any issues", "color:#00d4ff; font-weight:700");
});
        // For real deployment:
        // - Rename this file to index.php
        // - Use PDO for MySQL connection
        // - Add Twilio SDK for real SMS
        // - ESP32 should POST JSON to yourserver.com/api/attendance.php

        // Example ESP32 snippet (Arduino):
        /*
        HTTPClient http;
        http.begin("http://yourdomain.com/api/attendance.php");
        http.addHeader("Content-Type", "application/json");
        String payload = "{\"rfid_uid\":\"" + uid + "\"}";
        http.POST(payload);
        */

        setInterval(checkESPStatus, 3000)
    </script>
</body>
</html>