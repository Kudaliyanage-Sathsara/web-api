<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Alumni Dashboard</title>
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
            --text-secondary: #b0b0b0;
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
            white-space: nowrap;
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

        /* ===== Profile Cards ===== */
        .profile-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
            background: white;
        }

        body.dark-mode .profile-card {
            background: var(--bg-secondary);
        }

        .profile-card:hover {
            box-shadow: var(--shadow-md);
        }

        .profile-card .card-header {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .profile-card .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
        }

        .profile-card .card-body {
            padding: 2rem;
        }

        /* ===== Profile Header ===== */
        .profile-header-section {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
            transition: var(--transition);
        }

        body.dark-mode .profile-header-section {
            background: var(--bg-secondary);
        }

        .profile-header-content {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 2rem;
            align-items: center;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            box-shadow: var(--shadow-md);
        }

        .profile-info h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .profile-info p {
            margin: 0.25rem 0;
            opacity: 0.95;
            font-size: 0.95rem;
        }

        .profile-actions {
            display: flex;
            gap: 0.75rem;
        }

        /* ===== Form Elements ===== */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        body.dark-mode .form-label {
            color: var(--text-primary);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 0.6rem 1rem;
            transition: var(--transition);
        }

        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background: var(--bg-primary);
            border-color: rgba(255, 255, 255, 0.2);
            color: var(--text-primary);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        /* ===== Buttons ===== */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
        }

        /* ===== Grid Layouts ===== */
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .degree-grid,
        .certification-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .degree-card,
        .certification-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1rem;
            background: #f8f9fa;
            transition: var(--transition);
        }

        body.dark-mode .degree-card,
        body.dark-mode .certification-card {
            background: var(--bg-primary);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .degree-card:hover,
        .certification-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-4px);
        }

        /* ===== Alerts ===== */
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        body.dark-mode .alert-success {
            background-color: rgba(212, 237, 218, 0.2);
            color: #90ee90;
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

            .profile-header-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .profile-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .degree-grid,
            .certification-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .profile-card .card-body {
                padding: 1.5rem;
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

            .top-bar {
                flex-direction: column;
                align-items: flex-start;
                padding: 1rem;
                gap: 0.75rem;
                margin-bottom: 1.5rem;
            }

            .top-bar h2 {
                font-size: 1.3rem;
                width: 100%;
            }

            .top-bar button {
                width: 100%;
            }

            .profile-header-content {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 1.5rem;
            }

            .profile-image {
                width: 80px;
                height: 80px;
            }

            .profile-info h3 {
                font-size: 1.5rem;
            }

            .profile-info p {
                font-size: 0.9rem;
            }

            .profile-actions {
                width: 100%;
                flex-direction: column;
            }

            .profile-actions button {
                width: 100%;
            }

            .profile-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .degree-grid,
            .certification-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .profile-card .card-header {
                padding: 1rem;
                flex-wrap: wrap;
            }

            .profile-card .card-header h5 {
                font-size: 1rem;
                flex: 1;
            }

            .profile-card .card-header button {
                width: 100%;
                margin-top: 0.5rem;
            }

            .profile-card .card-body {
                padding: 1rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .user-badge {
                width: 100%;
                text-align: center;
                font-size: 0.85rem;
            }
        }

        /* Small Mobile - 576px */
        @media (max-width: 576px) {
            .navbar-top {
                padding: 0.75rem 1rem;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .navbar-top .logo {
                font-size: 1.1rem;
                gap: 0.25rem;
            }

            .navbar-top .right-section {
                gap: 0.75rem;
                width: 100%;
                justify-content: flex-end;
            }

            .main-content {
                padding: 0.75rem;
            }

            .top-bar {
                padding: 0.75rem;
                margin-bottom: 1rem;
            }

            .top-bar h2 {
                font-size: 1.2rem;
            }

            .profile-header-content {
                padding: 1rem;
                gap: 0.75rem;
            }

            .profile-image {
                width: 70px;
                height: 70px;
                border-width: 3px;
            }

            .profile-info h3 {
                font-size: 1.3rem;
                margin-bottom: 0.25rem;
            }

            .profile-info p {
                font-size: 0.85rem;
            }

            .profile-actions button {
                padding: 0.5rem 0.8rem;
                font-size: 0.85rem;
            }

            .profile-card .card-header {
                padding: 0.75rem;
                flex-direction: column;
            }

            .profile-card .card-header h5 {
                font-size: 0.95rem;
                margin-bottom: 0.5rem;
            }

            .profile-card .card-body {
                padding: 0.75rem;
            }

            .form-label {
                font-size: 0.9rem;
            }

            .form-control,
            .form-select,
            .btn {
                font-size: 0.9rem;
                padding: 0.5rem 0.75rem;
            }

            .degree-card,
            .certification-card {
                padding: 0.75rem;
            }

            .degree-card h6,
            .certification-card h6 {
                font-size: 0.95rem;
                margin-bottom: 0.5rem;
            }

            .user-badge {
                font-size: 0.8rem;
                padding: 0.4rem 0.75rem;
            }

            .alert {
                padding: 0.75rem;
                font-size: 0.9rem;
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
                gap: 0.5rem;
            }

            .main-content {
                padding: 0.5rem;
            }

            .top-bar {
                padding: 0.5rem;
                margin-bottom: 0.75rem;
            }

            .top-bar h2 {
                font-size: 1rem;
            }

            .profile-header-content {
                padding: 0.75rem;
                gap: 0.5rem;
            }

            .profile-image {
                width: 60px;
                height: 60px;
                border-width: 2px;
            }

            .profile-info h3 {
                font-size: 1.1rem;
            }

            .profile-info p {
                font-size: 0.8rem;
            }

            .profile-actions {
                gap: 0.25rem;
            }

            .profile-actions button {
                padding: 0.4rem 0.6rem;
                font-size: 0.75rem;
            }

            .profile-card .card-header {
                padding: 0.5rem;
            }

            .profile-card .card-header h5 {
                font-size: 0.9rem;
            }

            .profile-card .card-body {
                padding: 0.5rem;
            }

            .form-label {
                font-size: 0.85rem;
                margin-bottom: 0.25rem;
            }

            .form-control,
            .form-select,
            .btn {
                font-size: 0.8rem;
                padding: 0.4rem 0.5rem;
            }

            .degree-grid,
            .certification-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .degree-card,
            .certification-card {
                padding: 0.5rem;
            }

            .user-badge {
                font-size: 0.7rem;
                padding: 0.3rem 0.5rem;
            }

            .alert {
                padding: 0.5rem;
                font-size: 0.8rem;
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
            <div class="user-badge"><?php echo htmlspecialchars($this->session->userdata('email')); ?></div>
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
                <a class="nav-link" href="<?php echo base_url('dashboard/alumni_list'); ?>">
                    <i class="fas fa-users"></i>
                    <span>Alumni List</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="<?php echo base_url('profile'); ?>">
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
            <h2><i class="fas fa-user-circle" style="color: #667eea;"></i>My Profile</h2>
            <a href="<?php echo base_url('profile/edit'); ?>" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Profile
            </a>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 1.5rem;">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Profile Header -->
        <div class="profile-header-section">
            <div class="profile-header-content">
                <div>
                    <?php if ($personal_info->profile_image_url): ?>
                        <img src="<?php echo htmlspecialchars($personal_info->profile_image_url); ?>" 
                             alt="Profile" class="profile-image">
                    <?php else: ?>
                        <div class="profile-image d-flex align-items-center justify-content-center bg-white">
                            <i class="fas fa-user fa-2x text-primary"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="profile-info">
                    <h3><?php echo htmlspecialchars($personal_info->full_name ?? 'Not Set'); ?></h3>
                    <p><?php echo htmlspecialchars($this->session->userdata('email')); ?></p>
                    <?php if ($alumni_info && $alumni_info->programme): ?>
                        <p><?php echo htmlspecialchars($alumni_info->programme); ?> - Class of <?php echo htmlspecialchars($alumni_info->graduation_year ?? 'N/A'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="profile-actions">
                    <a href="<?php echo base_url('profile/edit'); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Grid -->
        <div class="profile-grid">
            <!-- Personal Information -->
            <div class="profile-card">
                <div class="card-header">
                    <h5><i class="fas fa-user me-2"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <p><?php echo htmlspecialchars($personal_info->full_name ?? 'Not set'); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <p><?php echo htmlspecialchars($this->session->userdata('email')); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Biography</label>
                        <p><?php echo htmlspecialchars($personal_info->biography ?? 'Not set'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Alumni Information -->
            <div class="profile-card">
                <div class="card-header">
                    <h5><i class="fas fa-graduation-cap me-2"></i>Alumni Information</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Programme</label>
                        <p><?php echo htmlspecialchars(isset($alumni_info->programme) ? $alumni_info->programme : 'Not set'); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Graduation Year</label>
                        <p><?php echo htmlspecialchars(isset($alumni_info->graduation_year) ? $alumni_info->graduation_year : 'Not set'); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Industry Sector</label>
                        <p><?php echo htmlspecialchars(isset($alumni_info->industry_sector) ? $alumni_info->industry_sector : 'Not set'); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Current Location</label>
                        <p><?php echo htmlspecialchars(isset($alumni_info->current_location) ? $alumni_info->current_location : 'Not set'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Degrees Section -->
        <div class="profile-card">
            <div class="card-header">
                <h5><i class="fas fa-graduation-cap me-2"></i>Degrees</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDegreeModal">
                    <i class="fas fa-plus me-1"></i>Add Degree
                </button>
            </div>
            <div class="card-body">
                <?php if (empty($degrees)): ?>
                    <p style="color: #999;">No degrees added yet.</p>
                <?php else: ?>
                    <div class="degree-grid">
                        <?php foreach ($degrees as $degree): ?>
                            <div class="degree-card">
                                <h6 style="margin: 0 0 0.5rem 0; color: #667eea;"><?php echo htmlspecialchars($degree->degree); ?></h6>
                                <p style="margin: 0.25rem 0; color: #666; font-size: 0.9rem;"><?php echo htmlspecialchars($degree->institution); ?></p>
                                <?php if ($degree->field): ?>
                                    <p style="margin: 0.25rem 0; font-size: 0.85rem;"><strong>Field:</strong> <?php echo htmlspecialchars($degree->field); ?></p>
                                <?php endif; ?>
                                <?php if ($degree->completion_date): ?>
                                    <p style="margin: 0.25rem 0; font-size: 0.85rem;"><strong>Completed:</strong> <?php echo date('M Y', strtotime($degree->completion_date)); ?></p>
                                <?php endif; ?>
                                <div style="margin-top: 0.75rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <button class="btn btn-outline-secondary btn-sm" onclick="editDegree(<?php echo $degree->id; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?php echo base_url('profile/delete_section/degrees?id=' . $degree->id); ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this degree?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Certifications Section -->
        <div class="profile-card">
            <div class="card-header">
                <h5><i class="fas fa-certificate me-2"></i>Certifications</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCertificationModal">
                    <i class="fas fa-plus me-1"></i>Add Certification
                </button>
            </div>
            <div class="card-body">
                <?php if (empty($certifications)): ?>
                    <p style="color: #999;">No certifications added yet.</p>
                <?php else: ?>
                    <div class="certification-grid">
                        <?php foreach ($certifications as $cert): ?>
                            <div class="certification-card">
                                <h6 style="margin: 0 0 0.5rem 0; color: #667eea;"><?php echo htmlspecialchars($cert->title); ?></h6>
                                <?php if ($cert->provider): ?>
                                    <p style="margin: 0.25rem 0; color: #666; font-size: 0.9rem;"><?php echo htmlspecialchars($cert->provider); ?></p>
                                <?php endif; ?>
                                <?php if ($cert->completion_date): ?>
                                    <p style="margin: 0.25rem 0; font-size: 0.85rem;"><strong>Completed:</strong> <?php echo date('M Y', strtotime($cert->completion_date)); ?></p>
                                <?php endif; ?>
                                <div style="margin-top: 0.75rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <button class="btn btn-outline-secondary btn-sm" onclick="editCertification(<?php echo $cert->id; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?php echo base_url('profile/delete_section/certifications?id=' . $cert->id); ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this certification?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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

        // ===== Window Resize Handler =====
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (window.innerWidth > 768) {
                    closeSidebarMenu();
                }
            }, 250);
        });
    </script>
</body>
</html>
