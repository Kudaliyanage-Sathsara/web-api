<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* ===== CSS Variables ===== */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --sidebar-width: 250px;
            --sidebar-width-collapsed: 60px;
            --transition: all 0.3s ease;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        body.dark-mode {
            --bg-primary: #1a1a2e;
            --bg-secondary: #16213e;
            --text-primary: #eaeaea;
            --text-secondary: #b0b0b0;
            --card-bg: #0f3460;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: var(--transition);
            color: #333;
        }

        body.dark-mode {
            background-color: var(--bg-primary);
            color: var(--text-primary);
        }

        /* ===== Header/Navbar ===== */
        .navbar-top {
            background: white;
            box-shadow: var(--shadow-sm);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            transition: var(--transition);
        }

        body.dark-mode .navbar-top {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .navbar-top .hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #667eea;
            transition: var(--transition);
        }

        .navbar-top .hamburger:hover {
            transform: rotate(90deg);
            color: #764ba2;
        }

        .navbar-top .logo {
            font-weight: 700;
            font-size: 1.3rem;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        body.dark-mode .navbar-top .logo {
            color: #5fd3ff;
        }

        .navbar-top .right-section {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .dark-mode-toggle {
            background: none;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            color: #667eea;
            transition: var(--transition);
        }

        .dark-mode-toggle:hover {
            color: #764ba2;
            transform: rotate(20deg);
        }

        .user-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        body.dark-mode .user-badge {
            background: linear-gradient(135deg, #5fd3ff 0%, #00d4ff 100%);
            color: #1a1a2e;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--primary-gradient);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            padding: 2rem 0 1rem 0;
            z-index: 1000;
            transition: var(--transition);
            margin-top: 0;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar.mobile-open {
            transform: translateX(0);
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        .sidebar-header {
            padding: 0 1.5rem 2rem 1.5rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-header h5 {
            font-weight: 700;
            font-size: 1.3rem;
            color: #fff;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-header .close-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .sidebar-header .close-btn:hover {
            transform: rotate(90deg);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 1rem 1.5rem;
            border-radius: 0;
            transition: var(--transition);
            margin: 0.25rem 0.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: white;
            transform: translateX(-4px);
            transition: var(--transition);
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            padding-left: 2rem;
        }

        .sidebar .nav-link:hover::before {
            transform: translateX(0);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.15);
            padding-left: 2rem;
        }

        .sidebar .nav-link.active::before {
            transform: translateX(0);
            width: 100%;
            background: rgba(255, 255, 255, 0.3);
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar .nav-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 1rem 0;
        }

        /* ===== Main Content ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 0;
            padding: 2rem;
            min-height: 100vh;
            transition: var(--transition);
            background: #f8f9fa;
        }

        body.dark-mode .main-content {
            background: var(--bg-primary);
        }

        /* ===== Top Bar ===== */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            flex-wrap: wrap;
            gap: 1rem;
        }

        body.dark-mode .top-bar {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .top-bar h2 {
            font-weight: 700;
            color: #333;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.8rem;
        }

        body.dark-mode .top-bar h2 {
            color: var(--text-primary);
        }

        /* ===== Stat Cards ===== */
        .stat-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
            position: relative;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: rgba(255, 255, 255, 0.5);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
        }

        .stat-card:hover::before {
            height: 8px;
        }

        .stat-card .card-body {
            padding: 2rem;
            color: white;
        }

        .stat-card.primary {
            background: var(--primary-gradient);
        }

        .stat-card.success {
            background: var(--success-gradient);
        }

        .stat-card.warning {
            background: var(--warning-gradient);
        }

        .stat-card.info {
            background: var(--info-gradient);
        }

        .stat-card h5 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 1rem;
            opacity: 0.95;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -1px;
        }

        /* ===== Chart Cards ===== */
        .chart-card {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 2rem;
            transition: var(--transition);
        }

        body.dark-mode .chart-card {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .chart-card:hover {
            box-shadow: var(--shadow-md);
        }

        .chart-card .card-header {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chart-card .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chart-card .card-body {
            padding: 2rem;
            position: relative;
            height: 350px;
        }

        /* ===== Responsive Grid ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 2rem;
        }

        /* ===== Utilities ===== */
        .section-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        body.dark-mode .section-title {
            color: var(--text-primary);
        }

        .section-title i {
            color: #667eea;
        }

        .text-muted {
            color: rgba(0, 0, 0, 0.5) !important;
        }

        body.dark-mode .text-muted {
            color: var(--text-secondary) !important;
        }

        /* ===== Scrollbar Styling ===== */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        body.dark-mode ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #764ba2;
        }

        /* ===== Responsive Breakpoints ===== */
        /* Tablet - 1024px */
        @media (max-width: 1024px) {
            :root {
                --sidebar-width: 250px;
            }

            .main-content {
                padding: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .chart-card .card-body {
                height: 300px;
            }

            .stat-card h3 {
                font-size: 2.2rem;
            }

            .top-bar {
                padding: 1.2rem 1.5rem;
            }

            .top-bar h2 {
                font-size: 1.5rem;
            }
        }

        /* Mobile - 768px */
        @media (max-width: 768px) {
            :root {
                --sidebar-width: 0;
            }

            .navbar-top .hamburger {
                display: block;
            }

            .navbar-top .logo {
                flex: 1;
            }

            .sidebar {
                width: 280px;
                top: 0;
                left: 0;
                height: auto;
                max-height: 100vh;
                transform: translateX(-100%);
                padding-top: 1rem;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
                box-shadow: 2px 0 20px rgba(0, 0, 0, 0.3);
            }

            .sidebar-header .close-btn {
                display: block;
            }

            .sidebar-header {
                padding: 1rem 1.5rem;
                margin-bottom: 1rem;
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }

            .top-bar {
                flex-direction: column;
                align-items: flex-start;
                padding: 1rem;
                gap: 0.75rem;
            }

            .top-bar h2 {
                font-size: 1.3rem;
                width: 100%;
            }

            .user-badge {
                width: 100%;
                text-align: center;
            }

            .stat-card .card-body {
                padding: 1.2rem;
            }

            .stat-card h3 {
                font-size: 1.8rem;
            }

            .stat-card h5 {
                font-size: 0.85rem;
            }

            .chart-card .card-body {
                height: 250px;
                padding: 1rem;
            }

            .chart-card .card-header {
                padding: 1rem;
            }

            .chart-card .card-header h5 {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.1rem;
                margin-bottom: 1rem;
            }

            .sidebar .nav-link {
                padding: 0.9rem 1.2rem;
                margin: 0.25rem 0;
            }

            .sidebar .nav-link:hover {
                padding-left: 1.5rem;
            }
        }

        /* Small Mobile - 576px */
        @media (max-width: 576px) {
            .navbar-top {
                padding: 0.75rem 1rem;
            }

            .navbar-top .logo {
                font-size: 1.1rem;
            }

            .main-content {
                padding: 0.75rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .charts-grid {
                gap: 1rem;
            }

            .top-bar {
                padding: 0.75rem;
                border-radius: 8px;
            }

            .top-bar h2 {
                font-size: 1.2rem;
            }

            .stat-card .card-body {
                padding: 1rem;
            }

            .stat-card h3 {
                font-size: 1.6rem;
            }

            .stat-card h5 {
                font-size: 0.75rem;
            }

            .chart-card .card-body {
                height: 220px;
                padding: 0.75rem;
            }

            .chart-card .card-header {
                padding: 0.75rem;
            }

            .chart-card .card-header h5 {
                font-size: 0.95rem;
            }

            .user-badge {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }

            .section-title {
                font-size: 1rem;
            }

            .sidebar {
                width: 260px;
            }

            .sidebar .nav-link {
                padding: 0.8rem 1rem;
                margin: 0.2rem 0;
                font-size: 0.95rem;
            }

            .sidebar-header {
                padding: 0.8rem 1rem;
            }

            .sidebar-header h5 {
                font-size: 1.1rem;
            }
        }

        /* Extra Small - 375px */
        @media (max-width: 375px) {
            .navbar-top {
                padding: 0.5rem 0.75rem;
            }

            .navbar-top .logo {
                font-size: 1rem;
                gap: 0.25rem;
            }

            .navbar-top .right-section {
                gap: 0.75rem;
            }

            .main-content {
                padding: 0.5rem;
            }

            .top-bar {
                padding: 0.5rem;
            }

            .top-bar h2 {
                font-size: 1rem;
            }

            .stat-card .card-body {
                padding: 0.8rem;
            }

            .stat-card h3 {
                font-size: 1.4rem;
            }

            .stat-card h5 {
                font-size: 0.7rem;
            }

            .chart-card .card-body {
                height: 200px;
            }

            .user-badge {
                font-size: 0.75rem;
                padding: 0.3rem 0.6rem;
            }

            .sidebar {
                width: 240px;
            }

            .sidebar .nav-link {
                padding: 0.7rem 0.9rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <div class="navbar-top">
        <button class="hamburger" id="hamburger">
            <i class="fas fa-bars"></i>
        </button>
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
            <span>Alumni Hub</span>
        </div>
        <div class="right-section">
            <button class="dark-mode-toggle" id="darkModeToggle" title="Toggle dark mode">
                <i class="fas fa-moon"></i>
            </button>
            <div class="user-badge"><?php echo htmlspecialchars($user_email); ?></div>
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5><i class="fas fa-graduation-cap"></i>Alumni Hub</h5>
            <button class="close-btn" id="closeSidebar">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="<?php echo base_url('dashboard'); ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('dashboard/analysis'); ?>">
                    <i class="fas fa-microscope"></i>
                    <span>Analysis</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('dashboard/alumni_list'); ?>">
                    <i class="fas fa-users"></i>
                    <span>Alumni List</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('profile'); ?>">
                    <i class="fas fa-user-circle"></i>
                    <span>My Profile</span>
                </a>
            </li>
            <div class="nav-divider"></div>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('auth/logout'); ?>">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h2><i class="fas fa-chart-line"></i>Dashboard Overview</h2>
            <div class="user-badge">Welcome, <?php echo htmlspecialchars($user_email); ?></div>
        </div>

        <!-- Quick Stats Cards Section (Uncomment to enable) -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="card-body">
                    <h5><i class="fas fa-users"></i>Total Alumni</h5>
                    <h3 id="totalAlumni">0</h3>
                </div>
            </div>
            <div class="stat-card success">
                <div class="card-body">
                    <h5><i class="fas fa-graduation-cap"></i>Programmes</h5>
                    <h3 id="totalProgrammes">0</h3>
                </div>
            </div>
            <div class="stat-card warning">
                <div class="card-body">
                    <h5><i class="fas fa-industry"></i>Industries</h5>
                    <h3 id="totalIndustries">0</h3>
                </div>
            </div>
            <div class="stat-card info">
                <div class="card-body">
                    <h5><i class="fas fa-calendar"></i>Recent Grads</h5>
                    <h3 id="recentGraduates">0</h3>
                </div>
            </div>
        </div>

        <!-- Analytics Section -->
        <h4 class="section-title"><i class="fas fa-chart-pie"></i>Analytics & Statistics</h4>

        <!-- Charts Grid -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="card-header">
                    <h5><i class="fas fa-bars"></i>Alumni by Programme</h5>
                </div>
                <div class="card-body">
                    <canvas id="programmeChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <div class="card-header">
                    <h5><i class="fas fa-pie-chart"></i>Alumni by Industry</h5>
                </div>
                <div class="card-body">
                    <canvas id="industryChart"></canvas>
                </div>
            </div>
        </div>

        <div class="chart-card">
            <div class="card-header">
                <h5><i class="fas fa-chart-line"></i>Graduation Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="graduationChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ===== DOM Elements =====
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

        // ===== Sidebar Toggle Functionality =====
        function toggleSidebar() {
            sidebar.classList.toggle('mobile-open');
            sidebarOverlay.classList.toggle('active');
        }

        function closeSidebarMenu() {
            sidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.remove('active');
        }

        hamburger.addEventListener('click', toggleSidebar);
        closeSidebar.addEventListener('click', closeSidebarMenu);
        sidebarOverlay.addEventListener('click', closeSidebarMenu);

        // Close sidebar when clicking on nav links
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', closeSidebarMenu);
        });

        // ===== Dark Mode Toggle =====
        function initDarkMode() {
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            if (isDarkMode) {
                enableDarkMode();
            }
        }

        function enableDarkMode() {
            body.classList.add('dark-mode');
            darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            localStorage.setItem('darkMode', 'true');
        }

        function disableDarkMode() {
            body.classList.remove('dark-mode');
            darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            localStorage.setItem('darkMode', 'false');
        }

        darkModeToggle.addEventListener('click', () => {
            if (body.classList.contains('dark-mode')) {
                disableDarkMode();
            } else {
                enableDarkMode();
            }
        });

        // Initialize dark mode on page load
        initDarkMode();

        // ===== Highlight Active Menu Item =====
        function highlightActiveMenu() {
            const currentURL = window.location.pathname;
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentURL.includes(href.replace('<?php echo base_url(); ?>', ''))) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        // Call on page load
        highlightActiveMenu();

        // ===== Dashboard Data & Charts =====
        async function loadDashboardData() {
            try {
                const response = await fetch('<?php echo base_url("api/alumni/stats"); ?>', {
                    headers: {
                        'X-API-Key': 'analytics_dashboard_key_123'
                    }
                });
                const data = await response.json();

                if (data.data) {
                    updateStats(data.data);
                    renderCharts(data.data);
                }
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }

        function updateStats(stats) {
            const totalAlumni = parseInt(stats.total_users || 0);
            document.getElementById('totalAlumni').textContent = totalAlumni.toLocaleString();
            document.getElementById('totalProgrammes').textContent = stats.programme.length;
            document.getElementById('totalIndustries').textContent = stats.industry_sector.length;
            document.getElementById('recentGraduates').textContent = stats.graduation_year.filter(y => y.graduation_year >= 2020).reduce((sum, item) => sum + parseInt(item.count), 0);
        }

        function renderCharts(stats) {
            // Programme Chart
            const programmeCtx = document.getElementById('programmeChart').getContext('2d');
            new Chart(programmeCtx, {
                type: 'bar',
                data: {
                    labels: stats.programme.map(p => p.programme),
                    datasets: [{
                        label: 'Number of Alumni',
                        data: stats.programme.map(p => p.count),
                        backgroundColor: 'rgba(102, 126, 234, 0.7)',
                        borderColor: 'rgba(102, 126, 234, 1)',
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { font: { size: 12 } }
                        },
                        x: {
                            ticks: { font: { size: 12 } }
                        }
                    },
                    plugins: {
                        legend: { display: true, position: 'top' }
                    }
                }
            });

            // Industry Chart
            const industryCtx = document.getElementById('industryChart').getContext('2d');
            new Chart(industryCtx, {
                type: 'doughnut',
                data: {
                    labels: stats.industry_sector.map(s => s.industry_sector),
                    datasets: [{
                        data: stats.industry_sector.map(s => s.count),
                        backgroundColor: [
                            'rgba(102, 126, 234, 0.7)',
                            'rgba(17, 153, 142, 0.7)',
                            'rgba(240, 147, 251, 0.7)',
                            'rgba(79, 172, 254, 0.7)',
                            'rgba(245, 87, 108, 0.7)'
                        ],
                        borderColor: [
                            'rgba(102, 126, 234, 1)',
                            'rgba(17, 153, 142, 1)',
                            'rgba(240, 147, 251, 1)',
                            'rgba(79, 172, 254, 1)',
                            'rgba(245, 87, 108, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { size: 12 }, padding: 15 } }
                    }
                }
            });

            // Graduation Chart
            const graduationCtx = document.getElementById('graduationChart').getContext('2d');
            new Chart(graduationCtx, {
                type: 'line',
                data: {
                    labels: stats.graduation_year.map(y => y.graduation_year),
                    datasets: [{
                        label: 'Graduates per Year',
                        data: stats.graduation_year.map(y => y.count),
                        borderColor: 'rgba(102, 126, 234, 1)',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(102, 126, 234, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { font: { size: 12 } }
                        },
                        x: {
                            ticks: { font: { size: 12 } }
                        }
                    },
                    plugins: {
                        legend: { display: true, position: 'top' }
                    }
                }
            });
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', loadDashboardData);

        // ===== Responsive Adjustments =====
        // Handle window resize for responsive behavior
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                // Close sidebar on larger screens
                if (window.innerWidth > 768) {
                    closeSidebarMenu();
                }
            }, 250);
        });

        // ===== Smooth Scroll Behavior =====
        document.documentElement.style.scrollBehavior = 'smooth';
    </script>
</body>
</html>