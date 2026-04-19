<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Directory - Alumni Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* ===== CSS Variables ===== */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-width: 250px;
            --transition: all 0.3s ease;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        body.dark-mode {
            --bg-primary: #1a1a2e;
            --bg-secondary: #16213e;
            --text-primary: #eaeaea;
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

        .navbar-top .logo {
            font-weight: 700;
            font-size: 1.3rem;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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

        .user-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
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

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 1rem 1.5rem;
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

        /* ===== Filters and Search ===== */
        .filter-section {
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        body.dark-mode .filter-section {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .filter-section input,
        .filter-section select {
            flex: 1;
            min-width: 200px;
        }

        .filter-section button {
            flex-shrink: 0;
        }

        /* ===== Alumni Cards / Table ===== */
        .alumni-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .alumni-card {
            background: white;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        body.dark-mode .alumni-card {
            background: var(--bg-secondary);
        }

        .alumni-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
        }

        .alumni-card .card-header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .alumni-card .card-body {
            padding: 1.5rem;
        }

        .alumni-card h5 {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        body.dark-mode .alumni-card h5 {
            color: var(--text-primary);
        }

        .alumni-card .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            color: #666;
        }

        body.dark-mode .alumni-card .info-item {
            color: #aaa;
        }

        .alumni-card .info-item i {
            color: #667eea;
            width: 20px;
            text-align: center;
        }

        /* ===== Table Responsive ===== */
        .table-responsive {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        body.dark-mode .table-responsive {
            background: var(--bg-secondary);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: var(--primary-gradient);
            color: white;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 1rem;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 1rem;
            border-color: rgba(0, 0, 0, 0.1);
        }

        body.dark-mode .table tbody td {
            border-color: rgba(255, 255, 255, 0.1);
        }

        .table tbody tr {
            transition: var(--transition);
        }

        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        /* ===== Section Title ===== */
        .section-title {
            font-weight: 700;
            color: #333;
            margin: 1.5rem 0 1rem 0;
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

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 4px;
        }

        /* ===== Responsive Breakpoints ===== */
        @media (max-width: 1024px) {
            .main-content {
                padding: 1.5rem;
            }

            .alumni-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1.5rem;
            }

            .filter-section {
                padding: 1rem 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .navbar-top .hamburger {
                display: block;
            }

            .navbar-top .logo {
                flex: 1;
            }

            .sidebar {
                width: 280px;
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

            .main-content {
                margin-left: 0;
                padding: 1rem;
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

            .filter-section {
                flex-direction: column;
                gap: 0.75rem;
                padding: 1rem;
            }

            .filter-section input,
            .filter-section select,
            .filter-section button {
                width: 100%;
                min-width: auto;
            }

            .alumni-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .table {
                font-size: 0.85rem;
            }

            .table th,
            .table td {
                padding: 0.75rem 0.5rem;
            }

            .section-title {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .navbar-top {
                padding: 0.75rem 1rem;
            }

            .navbar-top .logo {
                font-size: 1.1rem;
                gap: 0.25rem;
            }

            .main-content {
                padding: 0.75rem;
            }

            .top-bar {
                padding: 0.75rem;
            }

            .top-bar h2 {
                font-size: 1.2rem;
            }

            .filter-section {
                padding: 0.75rem;
            }

            .alumni-card .card-header {
                padding: 1rem;
            }

            .alumni-card .card-body {
                padding: 1rem;
            }

            .alumni-card h5 {
                font-size: 1rem;
            }

            .alumni-card .info-item {
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
            }

            .user-badge {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }

            .table {
                font-size: 0.75rem;
            }

            .table th,
            .table td {
                padding: 0.5rem 0.25rem;
            }

            .section-title {
                font-size: 1rem;
            }

            .sidebar {
                width: 260px;
            }
        }

        @media (max-width: 375px) {
            .navbar-top {
                padding: 0.5rem 0.75rem;
            }

            .top-bar h2 {
                font-size: 1rem;
            }

            .user-badge {
                font-size: 0.75rem;
                padding: 0.3rem 0.6rem;
            }

            .alumni-card .card-header {
                padding: 0.75rem;
            }

            .alumni-card h5 {
                font-size: 0.95rem;
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
                <a class="nav-link" href="<?php echo base_url('dashboard'); ?>">
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
                <a class="nav-link active" href="<?php echo base_url('dashboard/alumni_list'); ?>">
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
            <h2><i class="fas fa-users"></i>Alumni Directory</h2>
            <div class="user-badge">Welcome, <?php echo htmlspecialchars($user_email); ?></div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <input type="text" class="form-control" placeholder="Search by name..." id="searchInput">
            <select class="form-select" id="programmeFilter">
                <option value="">Filter by Programme</option>
            </select>
            <select class="form-select" id="yearFilter">
                <option value="">Filter by Year</option>
            </select>
            <button class="btn btn-primary" onclick="applyFilters()">
                <i class="fas fa-filter me-1"></i>Apply Filters
            </button>
            <button class="btn btn-secondary" onclick="clearFilters()">
                <i class="fas fa-redo me-1"></i>Reset
            </button>
        </div>

        <h4 class="section-title"><i class="fas fa-user-tie"></i>Alumni Records</h4>

        <!-- Alumni Grid/Table Container -->
        <div id="alumniContainer" class="alumni-grid"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ===== Sidebar Toggle Functionality =====
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

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

        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', closeSidebarMenu);
        });

        // ===== Dark Mode Toggle =====
        function initDarkMode() {
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            if (isDarkMode) enableDarkMode();
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
            body.classList.contains('dark-mode') ? disableDarkMode() : enableDarkMode();
        });

        initDarkMode();

        // ===== Filter Functions =====
        function applyFilters() {
            console.log('Filters applied');
            // Add filter logic here
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('programmeFilter').value = '';
            document.getElementById('yearFilter').value = '';
            loadAlumniData();
        }

        // ===== Load Alumni Data =====
        function loadAlumniData() {
            // Placeholder for alumni data loading
            console.log('Loading alumni data...');
        }

        // ===== Window Resize Handler =====
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (window.innerWidth > 768) closeSidebarMenu();
            }, 250);
        });

        // Initialize
        loadAlumniData();
    </script>
</body>
</html>
