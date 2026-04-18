<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Analysis - Alumni Dashboard</title>
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

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1.5rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
        }

        .card-body {
            padding: 2rem;
        }

        .chart-container {
            position: relative;
            height: 350px;
            margin-bottom: 20px;
        }

        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }

        .me-2 {
            margin-right: 0.5rem;
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

        /* Tablet and Mobile Responsiveness */
        @media (max-width: 1024px) {
            .sidebar {
                width: 220px;
            }

            .main-content {
                margin-left: 220px;
                padding: 1.5rem;
            }

            .chart-container {
                height: 300px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
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

            .chart-container {
                height: 250px;
            }

            .card {
                margin-bottom: 1rem;
            }

            .card-header {
                padding: 1rem;
            }

            .card-header h5 {
                font-size: 1rem;
            }

            .card-body {
                padding: 1.2rem;
            }

            .table {
                font-size: 0.85rem;
            }

            .table th {
                padding: 0.5rem;
            }

            .table td {
                padding: 0.5rem;
            }

            .section-title {
                font-size: 1.1rem;
                margin-bottom: 1rem;
            }

            .col-md-3 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
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

            .chart-container {
                height: 200px;
            }

            .card-header {
                padding: 0.75rem;
            }

            .card-body {
                padding: 0.75rem;
            }

            .card-header h5 {
                font-size: 0.95rem;
            }

            .table {
                font-size: 0.75rem;
            }

            .table th {
                padding: 0.25rem;
            }

            .table td {
                padding: 0.25rem;
            }

            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .section-title {
                font-size: 1rem;
                margin-bottom: 0.75rem;
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

            .badge {
                width: 100%;
                margin-top: 0.5rem;
            }

            .stat-card .card-body {
                padding: 0.8rem;
            }

            .stat-card h3 {
                font-size: 1.3rem;
            }

            .chart-container {
                height: 150px;
            }
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
                    <a class="nav-link" href="<?php echo base_url('dashboard'); ?>">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo base_url('dashboard/analysis'); ?>">
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
                <h2><i class="fas fa-microscope" style="color: #667eea; margin-right: 0.5rem;"></i>Database Analysis</h2>
                <div>
                    <span class="badge bg-primary">Welcome, <?php echo htmlspecialchars($user_email); ?></span>
                </div>
            </div>

            <div class="d-flex flex-wrap justify-content-end gap-2 mb-3">
                <button type="button" class="btn btn-sm btn-primary" onclick="downloadChartImage('degreesChart','degrees-by-type')">Download Degrees Chart</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="downloadChartImage('institutionsChart','institutions-by-users')">Download Institutions Chart</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="downloadChartImage('rolesChart','roles-chart')">Download Roles Chart</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="downloadChartImage('companiesChart','companies-chart')">Download Companies Chart</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="downloadChartImage('registrationChart','registration-trends')">Download Registration Chart</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="downloadChartImage('certificationsChart','certifications-chart')">Download Certifications Chart</button>
            </div>

            <!-- User Statistics Overview -->
            <div class="row mb-4">
                <h4 class="section-title"><i class="fas fa-users"></i>User Statistics</h4>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card primary">
                        <div class="card-body">
                            <h5>Total Users</h5>
                            <h3><?php echo $analysis['user_stats']['total_users']; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card success">
                        <div class="card-body">
                            <h5>Verified Users</h5>
                            <h3><?php echo $analysis['user_stats']['verified_users']; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card warning">
                        <div class="card-body">
                            <h5>Unverified Users</h5>
                            <h3><?php echo $analysis['user_stats']['unverified_users']; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card info">
                        <div class="card-body">
                            <h5>Completion Rate</h5>
                            <h3><?php echo round(($analysis['user_stats']['users_with_personal_info'] / max($analysis['user_stats']['total_users'], 1)) * 100); ?>%</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Completeness -->
            <div class="row mb-4">
                <h4 class="section-title"><i class="fas fa-chart-pie"></i>Profile Completeness</h4>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card warning">
                        <div class="card-body">
                            <h5>Personal Info</h5>
                            <h3><?php echo $analysis['user_stats']['users_with_personal_info']; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card primary">
                        <div class="card-body">
                            <h5>With Degrees</h5>
                            <h3><?php echo $analysis['user_stats']['users_with_degrees']; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card success">
                        <div class="card-body">
                            <h5>Employment History</h5>
                            <h3><?php echo $analysis['user_stats']['users_with_employment']; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card info">
                        <div class="card-body">
                            <h5>LinkedIn Profiles</h5>
                            <h3><?php echo $analysis['user_stats']['users_with_linkedin']; ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Degrees Analysis -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-graduation-cap me-2"></i>Top Degrees</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="degreesChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-university me-2"></i>Top Institutions</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="institutionsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Analysis -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-briefcase me-2"></i>Top Roles</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="rolesChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-building me-2"></i>Top Companies</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="companiesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Statistics -->
            <div class="row mb-4">
                <h4 class="section-title"><i class="fas fa-chart-bar"></i>Employment Statistics</h4>
                <div class="col-md-4 col-sm-6">
                    <div class="card stat-card success">
                        <div class="card-body">
                            <h5>Currently Employed</h5>
                            <h3><?php echo $analysis['employment_data']['currently_employed']; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="card stat-card warning">
                        <div class="card-body">
                            <h5>Certifications</h5>
                            <h3><?php echo count($analysis['certifications_data']['by_provider']); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="card stat-card info">
                        <div class="card-body">
                            <h5>Short Courses</h5>
                            <h3><?php echo $analysis['certifications_data']['total_short_courses']; ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Trends -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-line me-2"></i>Registration Trends (Last 12 Months)</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="registrationChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest Registrations -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-user-plus me-2"></i>Latest Registrations</h5>
                        </div>
                        <div class="card-body table-container">
                            <table class="table table-sm table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Registered On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($analysis['trends']['latest_registrations'] as $reg): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($reg->email); ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($reg->created_at)); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Certifications -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-certificate me-2"></i>Certifications by Provider</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="certificationsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart colors
        const chartColors = [
            'rgba(102, 126, 234, 0.7)',
            'rgba(17, 153, 142, 0.7)',
            'rgba(240, 147, 251, 0.7)',
            'rgba(79, 172, 254, 0.7)',
            'rgba(245, 87, 108, 0.7)',
        ];

        const borderColors = [
            'rgba(102, 126, 234, 1)',
            'rgba(17, 153, 142, 1)',
            'rgba(240, 147, 251, 1)',
            'rgba(79, 172, 254, 1)',
            'rgba(245, 87, 108, 1)',
        ];

        const analysisData = <?php echo json_encode($analysis); ?>;

        // Degrees Chart
        const degreesCtx = document.getElementById('degreesChart').getContext('2d');
        new Chart(degreesCtx, {
            type: 'bar',
            data: {
                labels: analysisData.degrees_data.by_type.map(d => d.degree),
                datasets: [{
                    label: 'Number of Users',
                    data: analysisData.degrees_data.by_type.map(d => d.count),
                    backgroundColor: chartColors.slice(0, analysisData.degrees_data.by_type.length),
                    borderColor: borderColors.slice(0, analysisData.degrees_data.by_type.length),
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Institutions Chart
        const institutionsCtx = document.getElementById('institutionsChart').getContext('2d');
        new Chart(institutionsCtx, {
            type: 'bar',
            data: {
                labels: analysisData.degrees_data.institutions.map(i => i.institution.substring(0, 20)),
                datasets: [{
                    label: 'Users',
                    data: analysisData.degrees_data.institutions.map(i => i.count),
                    backgroundColor: chartColors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Roles Chart
        const rolesCtx = document.getElementById('rolesChart').getContext('2d');
        new Chart(rolesCtx, {
            type: 'bar',
            data: {
                labels: analysisData.employment_data.by_role.map(r => r.role.substring(0, 25)),
                datasets: [{
                    label: 'Count',
                    data: analysisData.employment_data.by_role.map(r => r.count),
                    backgroundColor: chartColors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Companies Chart
        const companiesCtx = document.getElementById('companiesChart').getContext('2d');
        new Chart(companiesCtx, {
            type: 'bar',
            data: {
                labels: analysisData.employment_data.by_company.map(c => c.company.substring(0, 20)),
                datasets: [{
                    label: 'Users',
                    data: analysisData.employment_data.by_company.map(c => c.count),
                    backgroundColor: chartColors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Registration Chart
        const registrationCtx = document.getElementById('registrationChart').getContext('2d');
        new Chart(registrationCtx, {
            type: 'line',
            data: {
                labels: analysisData.trends.registration_by_month.map(t => t.month),
                datasets: [{
                    label: 'New Registrations',
                    data: analysisData.trends.registration_by_month.map(t => t.count),
                    borderColor: 'rgba(102, 126, 234, 1)',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Certifications Chart
        const certificationsCtx = document.getElementById('certificationsChart').getContext('2d');
        new Chart(certificationsCtx, {
            type: 'doughnut',
            data: {
                labels: analysisData.certifications_data.by_provider.map(c => c.provider),
                datasets: [{
                    data: analysisData.certifications_data.by_provider.map(c => c.count),
                    backgroundColor: chartColors,
                    borderColor: borderColors,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        function downloadChartImage(chartId, fileName) {
            const canvas = document.getElementById(chartId);
            if (!canvas) {
                alert('Chart element not found.');
                return;
            }
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png');
            link.download = `${fileName}.png`;
            link.click();
        }
    </script>
</body>
</html>
