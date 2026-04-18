<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar h5 {
            font-weight: 700;
            font-size: 1.3rem;
            color: #fff;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 0.8rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            padding-left: 1.5rem;
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 4px solid #fff;
            padding-left: calc(1rem - 4px);
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .top-bar h2 {
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .badge {
            padding: 0.6rem 1.2rem !important;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 20px !important;
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .stat-card .card-body {
            padding: 2rem;
            color: white;
        }

        .stat-card.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .stat-card.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .stat-card h5 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 1rem;
            opacity: 0.95;
        }

        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
        }

        .stat-card i {
            margin-right: 0.5rem;
        }

        .chart-card {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .chart-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1.5rem;
        }

        .chart-card .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .chart-card .card-body {
            padding: 2rem;
            position: relative;
            height: 350px;
        }

        /* Tablet and Mobile Responsiveness */
        @media (max-width: 1024px) {
            .sidebar {
                width: 220px;
            }

            .main-content {
                margin-left: 220px;
                padding: 1.5rem;
            }

            .chart-card .card-body {
                height: 300px;
            }

            .stat-card h3 {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
                transform: translateX(0);
                z-index: 1000;
                overflow: hidden;
                padding: 1rem 0.5rem;
            }

            .sidebar h5,
            .sidebar .nav-link span {
                display: none;
            }

            .sidebar .nav-link {
                padding: 0.8rem 0.5rem;
                text-align: center;
                margin-bottom: 1rem;
            }

            .sidebar .nav-link i {
                width: 100%;
                text-align: center;
                margin-right: 0;
            }

            .main-content {
                margin-left: 60px;
                padding: 1rem;
            }

            .top-bar {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
                padding: 1rem 1rem;
            }

            .top-bar h2 {
                font-size: 1.5rem;
            }

            .badge {
                padding: 0.4rem 0.8rem !important;
                font-size: 0.8rem;
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

            .row > [class*='col-'] {
                margin-bottom: 1rem;
            }

            .section-title {
                font-size: 1.1rem;
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 50px;
                padding: 0.5rem;
            }

            .main-content {
                margin-left: 50px;
                padding: 0.75rem;
            }

            .top-bar {
                padding: 0.75rem;
            }

            .top-bar h2 {
                font-size: 1.3rem;
            }

            .badge {
                padding: 0.3rem 0.6rem !important;
                font-size: 0.75rem;
            }

            .stat-card .card-body {
                padding: 1rem;
            }

            .stat-card h3 {
                font-size: 1.5rem;
            }

            .stat-card h5 {
                font-size: 0.75rem;
            }

            .stat-card i {
                display: none;
            }

            .chart-card .card-body {
                height: 200px;
                padding: 0.75rem;
            }

            .chart-card .card-header {
                padding: 0.75rem;
            }

            .chart-card .card-header h5 {
                font-size: 0.95rem;
            }

            .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Extra small devices */
        @media (max-width: 375px) {
            .top-bar {
                padding: 0.5rem;
            }

            .top-bar h2 {
                font-size: 1.1rem;
            }

            .stat-card .card-body {
                padding: 0.8rem;
            }

            .stat-card h3 {
                font-size: 1.3rem;
            }

            .badge {
                width: 100%;
                margin-top: 0.5rem;
            }
        }

        .text-muted {
            color: rgba(0, 0, 0, 0.5) !important;
        }

        .section-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }

        .section-title i {
            margin-right: 0.5rem;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar p-3" style="width: 250px;">
            <h5 class="text-white"><i class="fas fa-graduation-cap me-2"></i>Alumni Hub</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo base_url('dashboard'); ?>">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('dashboard/analysis'); ?>">
                        <i class="fas fa-microscope me-2"></i>Analysis
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('dashboard/alumni_list'); ?>">
                        <i class="fas fa-users me-2"></i>Alumni List
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('profile'); ?>">
                        <i class="fas fa-user me-2"></i>My Profile
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link" href="<?php echo base_url('auth/logout'); ?>" style="color: rgba(255, 255, 255, 0.75);">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Top Bar -->
            <div class="top-bar">
                <h2><i class="fas fa-chart-line" style="color: #667eea; margin-right: 0.5rem;"></i>Dashboard Overview</h2>
                <span class="badge bg-primary">Welcome, <?php echo htmlspecialchars($user_email); ?></span>
            </div>

            <!-- Quick Stats Cards -->
            <!-- <div class="row mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card primary">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-users"></i> Total Alumni</h5>
                            <h3 id="totalAlumni">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card success">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-graduation-cap"></i> Programmes</h5>
                            <h3 id="totalProgrammes">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card warning">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-industry"></i> Industries</h5>
                            <h3 id="totalIndustries">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card info">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-calendar"></i> Recent Grads</h5>
                            <h3 id="recentGraduates">0</h3>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Charts Section Title -->
            <!-- <h4 class="section-title"><i class="fas fa-chart-pie"></i>Analytics & Statistics</h4> -->

            <!-- Charts Row -->
            <!-- <div class="row">
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="card-header">
                            <h5><i class="fas fa-bars me-2"></i>Alumni by Programme</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="programmeChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="card-header">
                            <h5><i class="fas fa-pie-chart me-2"></i>Alumni by Industry</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="industryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="chart-card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-line me-2"></i>Graduation Trends</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="graduationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Load dashboard data
        async function loadDashboardData() {
            try {
                const response = await fetch('<?php echo base_url("api/alumni/stats"); ?>', {
                    headers: {
                        'X-API-Key': 'analytics_dashboard_key_123' // In production, get from secure source
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
    </script>
</body>
</html>