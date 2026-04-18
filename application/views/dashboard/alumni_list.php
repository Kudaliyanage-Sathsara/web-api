<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Directory - Alumni Dashboard</title>
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

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
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
            font-weight: 600;
        }

        .card-header h5 {
            margin: 0;
            color: white;
        }

        .table-responsive {
            border-radius: 8px;
        }

        .table {
            margin-bottom: 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
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

            .table-responsive {
                font-size: 0.9rem;
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

            .card {
                margin-bottom: 1rem;
            }

            .card-header h5 {
                font-size: 1rem;
            }

            .card-body {
                padding: 1.2rem;
            }

            .btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
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

            .card-header {
                padding: 0.75rem;
            }

            .card-body {
                padding: 0.75rem;
            }

            .btn {
                padding: 0.3rem 0.6rem;
                font-size: 0.75rem;
                margin-right: 0.25rem;
                margin-bottom: 0.25rem;
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

            .col-md-4 {
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

            .btn {
                display: block;
                width: 100%;
                margin-bottom: 0.5rem;
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
                    <a class="nav-link" href="<?php echo base_url('dashboard/analysis'); ?>">
                        <i class="fas fa-microscope me-2"></i>Analysis
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo base_url('dashboard/alumni_list'); ?>">
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
                <h2><i class="fas fa-users" style="color: #667eea; margin-right: 0.5rem;"></i>Alumni Directory</h2>
                <div>
                    <span class="badge bg-primary">Welcome, <?php echo htmlspecialchars($user_email); ?></span>
                </div>
            </div>

            <!-- Filters -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-filter me-2"></i>Filter Alumni
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo base_url('dashboard/alumni_list'); ?>" class="row g-3">
                        <div class="col-md-4">
                            <label for="programme" class="form-label">Programme</label>
                            <select class="form-select" id="programme" name="programme">
                                <option value="">All Programmes</option>
                                <option value="Computer Science" <?php echo ($filters['programme'] ?? '') == 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
                                <option value="Business Administration" <?php echo ($filters['programme'] ?? '') == 'Business Administration' ? 'selected' : ''; ?>>Business Administration</option>
                                <option value="Engineering" <?php echo ($filters['programme'] ?? '') == 'Engineering' ? 'selected' : ''; ?>>Engineering</option>
                            </select>
                        </div>
                        <div class="col-md-4">
    <label for="graduation_year" class="form-label">Graduation Year</label>
    <input 
        type="number" 
        class="form-control" 
        id="graduation_year" 
        name="graduation_year"
        min="1990" 
        max="<?php echo date('Y'); ?>"
        value="<?php echo $filters['graduation_year'] ?? ''; ?>"
        placeholder="Enter Graduation Year">
</div>
                        <div class="col-md-4">
                            <label for="industry_sector" class="form-label">Industry Sector</label>
                            <select class="form-select" id="industry_sector" name="industry_sector">
                                <option value="">All Sectors</option>
                                <option value="Technology" <?php echo ($filters['industry_sector'] ?? '') == 'Technology' ? 'selected' : ''; ?>>Technology</option>
                                <option value="Finance" <?php echo ($filters['industry_sector'] ?? '') == 'Finance' ? 'selected' : ''; ?>>Finance</option>
                                <option value="Manufacturing" <?php echo ($filters['industry_sector'] ?? '') == 'Manufacturing' ? 'selected' : ''; ?>>Manufacturing</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                            <a href="<?php echo base_url('dashboard/alumni_list'); ?>" class="btn btn-outline-secondary">Reset</a>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="d-flex flex-wrap gap-2 align-items-end">
                                <div class="flex-grow-1">
                                    <label for="presetName" class="form-label">Preset name</label>
                                    <input id="presetName" type="text" class="form-control" placeholder="Save current filters as preset">
                                </div>
                                <div class="d-flex gap-2 align-items-end">
                                    <button type="button" class="btn btn-success" onclick="saveFilterPreset()">Save Preset</button>
                                    <select id="savedPresets" class="form-select">
                                        <option value="">Load saved preset</option>
                                    </select>
                                    <button type="button" class="btn btn-outline-primary" onclick="applyPreset()">Apply</button>
                                    <button type="button" class="btn btn-outline-danger" onclick="removeSelectedPreset()">Delete</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-file-alt me-2"></i>Report Builder</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Select metrics for export</label>
                            <div class="form-check">
                                <input class="form-check-input report-metric" type="checkbox" value="full_name" id="metricName" checked>
                                <label class="form-check-label" for="metricName">Full Name</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input report-metric" type="checkbox" value="email" id="metricEmail" checked>
                                <label class="form-check-label" for="metricEmail">Email</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input report-metric" type="checkbox" value="programme" id="metricProgramme" checked>
                                <label class="form-check-label" for="metricProgramme">Programme</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input report-metric" type="checkbox" value="graduation_year" id="metricGraduation" checked>
                                <label class="form-check-label" for="metricGraduation">Graduation Year</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input report-metric" type="checkbox" value="industry_sector" id="metricSector" checked>
                                <label class="form-check-label" for="metricSector">Industry Sector</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input report-metric" type="checkbox" value="current_location" id="metricLocation" checked>
                                <label class="form-check-label" for="metricLocation">Current Location</label>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex flex-column justify-content-between">
                            <div>
                                <button type="button" class="btn btn-primary w-100 mb-2" onclick="downloadReportPDF()">Download Custom Report</button>
                                <button type="button" class="btn btn-outline-secondary w-100 mb-2" onclick="exportToCSV()">Export Filtered CSV</button>
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="exportToPDF()">Export Current List as PDF</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alumni List -->
            <div class="card">
                <div class="card-header">
                    <h5>Alumni List (<?php echo count($alumni); ?> results)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($alumni)): ?>
                        <div class="alert alert-info">
                            No alumni found matching the selected criteria.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Programme</th>
                                        <th>Graduation Year</th>
                                        <th>Industry Sector</th>
                                        <th>Location</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alumni as $alum): ?>
                                        <tr data-full_name="<?php echo htmlspecialchars($alum->full_name ?: 'N/A'); ?>" data-email="<?php echo htmlspecialchars($alum->email); ?>" data-programme="<?php echo htmlspecialchars($alum->programme ?: 'N/A'); ?>" data-graduation_year="<?php echo htmlspecialchars($alum->graduation_year ?: 'N/A'); ?>" data-industry_sector="<?php echo htmlspecialchars($alum->industry_sector ?: 'N/A'); ?>" data-current_location="<?php echo htmlspecialchars($alum->current_location ?: 'N/A'); ?>">
                                            <td>
                                                <?php if ($alum->profile_image_url): ?>
                                                    <img src="<?php echo $alum->profile_image_url; ?>" alt="Profile" class="rounded-circle" width="40" height="40">
                                                <?php else: ?>
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($alum->full_name ?: 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($alum->email); ?></td>
                                            <td><?php echo htmlspecialchars($alum->programme ?: 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($alum->graduation_year ?: 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($alum->industry_sector ?: 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($alum->current_location ?: 'N/A'); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewProfile(<?php echo $alum->id; ?>)">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination would go here if needed -->
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alumni Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="profileContent">
                    <!-- Profile content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script>
        const filterPresetKey = 'alumni_filter_presets';

        document.addEventListener('DOMContentLoaded', () => {
            loadPresets();
        });

        function viewProfile(userId) {
            alert('Profile view for user ' + userId + ' would be implemented here');
        }

        function getFilterParams() {
            const programme = document.getElementById('programme').value;
            const graduation_year = document.getElementById('graduation_year').value;
            const industry_sector = document.getElementById('industry_sector').value;
            const params = new URLSearchParams();

            if (programme) params.append('programme', programme);
            if (graduation_year) params.append('graduation_year', graduation_year);
            if (industry_sector) params.append('industry_sector', industry_sector);

            return params;
        }

        function exportToCSV() {
            let url = '<?php echo base_url("dashboard/alumni_export_csv"); ?>';
            const params = getFilterParams();
            if (params.toString()) {
                url += '?' + params.toString();
            }
            window.location.href = url;
        }

        function exportToPDF() {
            const headers = ['Name', 'Email', 'Programme', 'Graduation Year', 'Industry Sector', 'Location'];
            const rows = Array.from(document.querySelectorAll('tbody tr')).map(row => [
                row.dataset.full_name,
                row.dataset.email,
                row.dataset.programme,
                row.dataset.graduation_year,
                row.dataset.industry_sector,
                row.dataset.current_location
            ]);

            if (!rows.length) {
                alert('No data available to export.');
                return;
            }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape' });
            doc.setFontSize(16);
            doc.text('Alumni Directory Export', 14, 20);
            doc.setFontSize(11);
            doc.text(`Filters: ${formatFilterSummary()}`, 14, 28);
            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 36,
                styles: { fontSize: 9, cellPadding: 3 },
                headStyles: { fillColor: [102, 126, 234] }
            });
            doc.save('alumni_directory_export.pdf');
        }

        function downloadReportPDF() {
            const selectedMetrics = Array.from(document.querySelectorAll('.report-metric:checked')).map(input => input.value);
            if (!selectedMetrics.length) {
                alert('Please select at least one metric for the report.');
                return;
            }

            const metricsLabelMap = {
                full_name: 'Full Name',
                email: 'Email',
                programme: 'Programme',
                graduation_year: 'Graduation Year',
                industry_sector: 'Industry Sector',
                current_location: 'Current Location'
            };

            const headers = selectedMetrics.map(field => metricsLabelMap[field] || field);
            const rows = Array.from(document.querySelectorAll('tbody tr')).map(row => selectedMetrics.map(field => row.dataset[field] || 'N/A'));

            if (!rows.length) {
                alert('No data available to include in the report.');
                return;
            }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape' });
            doc.setFontSize(16);
            doc.text('Custom Alumni Report', 14, 20);
            doc.setFontSize(11);
            doc.text(`Filters: ${formatFilterSummary()}`, 14, 28);
            doc.text(`Metrics: ${headers.join(', ')}`, 14, 34);
            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 40,
                styles: { fontSize: 9, cellPadding: 3 },
                headStyles: { fillColor: [17, 153, 142] }
            });
            doc.save('custom_alumni_report.pdf');
        }

        function formatFilterSummary() {
            const programme = document.getElementById('programme').value || 'Any';
            const graduation_year = document.getElementById('graduation_year').value || 'Any';
            const industry_sector = document.getElementById('industry_sector').value || 'Any';
            return `Programme: ${programme}, Year: ${graduation_year}, Industry: ${industry_sector}`;
        }

        function saveFilterPreset() {
            const presetName = document.getElementById('presetName').value.trim();
            if (!presetName) {
                alert('Enter a name for the preset.');
                return;
            }

            const presets = JSON.parse(localStorage.getItem(filterPresetKey) || '{}');
            presets[presetName] = {
                programme: document.getElementById('programme').value,
                graduation_year: document.getElementById('graduation_year').value,
                industry_sector: document.getElementById('industry_sector').value
            };
            localStorage.setItem(filterPresetKey, JSON.stringify(presets));
            document.getElementById('presetName').value = '';
            loadPresets();
            alert('Preset saved successfully.');
        }

        function loadPresets() {
            const presets = JSON.parse(localStorage.getItem(filterPresetKey) || '{}');
            const select = document.getElementById('savedPresets');
            select.innerHTML = '<option value="">Load saved preset</option>';
            Object.keys(presets).forEach(name => {
                const option = document.createElement('option');
                option.value = name;
                option.textContent = name;
                select.appendChild(option);
            });
        }

        function applyPreset() {
            const select = document.getElementById('savedPresets');
            const presetName = select.value;
            if (!presetName) {
                alert('Choose a preset to apply.');
                return;
            }

            const presets = JSON.parse(localStorage.getItem(filterPresetKey) || '{}');
            const preset = presets[presetName];
            if (!preset) {
                alert('Selected preset was not found.');
                return;
            }

            document.getElementById('programme').value = preset.programme || '';
            document.getElementById('graduation_year').value = preset.graduation_year || '';
            document.getElementById('industry_sector').value = preset.industry_sector || '';
            document.querySelector('form').submit();
        }

        function removeSelectedPreset() {
            const select = document.getElementById('savedPresets');
            const presetName = select.value;
            if (!presetName) {
                alert('Choose a preset to delete.');
                return;
            }

            const presets = JSON.parse(localStorage.getItem(filterPresetKey) || '{}');
            if (presets[presetName]) {
                delete presets[presetName];
                localStorage.setItem(filterPresetKey, JSON.stringify(presets));
                loadPresets();
                alert('Preset deleted.');
            }
        }
    </script>
</body>
</html>