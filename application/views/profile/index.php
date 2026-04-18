<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Alumni Dashboard</title>
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

        .profile-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 2rem;
        }

        .profile-header h3 {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .profile-header p {
            margin: 0.2rem 0;
            opacity: 0.95;
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

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-outline-primary {
            color: #667eea;
            border-color: #667eea;
        }

        .btn-outline-primary:hover {
            background-color: #667eea;
            border-color: #667eea;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }

        .card.border {
            border: 1px solid #dee2e6;
        }

        .me-2 {
            margin-right: 0.5rem;
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

            .profile-header {
                padding: 1.5rem;
            }

            .profile-header h3 {
                font-size: 1.5rem;
            }

            .profile-header p {
                font-size: 0.9rem;
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

            .col-md-2 {
                flex: 0 0 100%;
                max-width: 100%;
                text-align: left !important;
            }

            .col-md-10 {
                flex: 0 0 100%;
                max-width: 100%;
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

            .profile-header {
                padding: 1rem;
            }

            .profile-header h3 {
                font-size: 1.3rem;
            }

            .profile-header p {
                font-size: 0.8rem;
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

            .form-label {
                font-size: 0.9rem;
                margin-bottom: 0.25rem;
            }

            .card.border {
                margin-bottom: 0.75rem;
            }

            h6 {
                font-size: 0.95rem;
            }

            .rounded-circle {
                width: 60px !important;
                height: 60px !important;
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

            .profile-header h3 {
                font-size: 1.1rem;
            }

            .btn {
                display: block;
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .rounded-circle {
                width: 50px !important;
                height: 50px !important;
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
                    <a class="nav-link" href="<?php echo base_url('dashboard/alumni_list'); ?>">
                        <i class="fas fa-users me-2"></i>Alumni List
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo base_url('profile'); ?>">
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
                <h2><i class="fas fa-user" style="color: #667eea; margin-right: 0.5rem;"></i>My Profile</h2>
                <div>
                    <a href="<?php echo base_url('profile/edit'); ?>" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                </div>
            </div>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 2rem;">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $this->session->flashdata('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Profile Header -->
            <div class="card profile-card mb-4">
                <div class="card-body profile-header">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <?php if ($personal_info->profile_image_url): ?>
                                <img src="<?php echo htmlspecialchars($personal_info->profile_image_url); ?>" 
                                     alt="Profile Image" 
                                     class="rounded-circle border border-white border-3" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mx-auto border border-white border-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-user fa-2x text-primary"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-10">
                            <h3><?php echo htmlspecialchars($personal_info->full_name ?? 'Not Set'); ?></h3>
                            <p class="mb-0"><?php echo htmlspecialchars($this->session->userdata('email')); ?></p>
                            <?php if ($alumni_info && $alumni_info->programme): ?>
                                <p class="mb-0"><?php echo htmlspecialchars($alumni_info->programme); ?> Graduate</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Personal Information -->
                <div class="col-md-6">
                    <div class="card profile-card mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-user me-2"></i>Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Name</label>
                                <p class="mb-0"><?php echo htmlspecialchars($personal_info->full_name ?? 'Not set'); ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p class="mb-0"><?php echo htmlspecialchars($this->session->userdata('email')); ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Biography</label>
                                <p class="mb-0"><?php echo htmlspecialchars($personal_info->biography ?? 'Not set'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alumni Information -->
                <div class="col-md-6">
                    <div class="card profile-card mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-graduation-cap me-2"></i>Alumni Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Programme</label>
                                <p class="mb-0"><?php echo htmlspecialchars(isset($alumni_info->programme) ? $alumni_info->programme : 'Not set'); ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Graduation Year</label>
                                <p class="mb-0"><?php echo htmlspecialchars(isset($alumni_info->graduation_year) ? $alumni_info->graduation_year : 'Not set'); ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Industry Sector</label>
                                <p class="mb-0"><?php echo htmlspecialchars(isset($alumni_info->industry_sector) ? $alumni_info->industry_sector : 'Not set'); ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Current Location</label>
                                <p class="mb-0"><?php echo htmlspecialchars(isset($alumni_info->current_location) ? $alumni_info->current_location : 'Not set'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Degrees Section -->
            <div class="card profile-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-graduation-cap me-2"></i>Degrees</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addDegreeModal">
                        <i class="fas fa-plus me-1"></i>Add Degree
                    </button>
                </div>
                <div class="card-body">
                    <?php if (empty($degrees)): ?>
                        <p class="text-muted mb-0">No degrees added yet.</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($degrees as $degree): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6><?php echo htmlspecialchars($degree->degree); ?></h6>
                                            <p class="text-muted mb-1"><?php echo htmlspecialchars($degree->institution); ?></p>
                                            <?php if ($degree->field): ?>
                                                <p class="mb-1"><strong>Field:</strong> <?php echo htmlspecialchars($degree->field); ?></p>
                                            <?php endif; ?>
                                            <?php if ($degree->completion_date): ?>
                                                <p class="mb-1"><strong>Completed:</strong> <?php echo date('M Y', strtotime($degree->completion_date)); ?></p>
                                            <?php endif; ?>
                                            <?php if ($degree->degree_url): ?>
                                                <p class="mb-2"><a href="<?php echo htmlspecialchars($degree->degree_url); ?>" target="_blank" class="text-primary">View Certificate <i class="fas fa-external-link-alt"></i></a></p>
                                            <?php endif; ?>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editDegreeModal" onclick="editDegree(<?php echo $degree->id; ?>, '<?php echo addslashes($degree->institution); ?>', '<?php echo addslashes($degree->degree); ?>', '<?php echo addslashes($degree->field ?? ''); ?>', '<?php echo $degree->degree_url ?? ''; ?>', '<?php echo $degree->completion_date ?? ''; ?>')">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </button>
                                                <a href="<?php echo base_url('profile/delete_section/degrees?id=' . $degree->id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this degree?')">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Certifications Section -->
            <div class="card profile-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-certificate me-2"></i>Certifications</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCertificationModal">
                        <i class="fas fa-plus me-1"></i>Add Certification
                    </button>
                </div>
                <div class="card-body">
                    <?php if (empty($certifications)): ?>
                        <p class="text-muted mb-0">No certifications added yet.</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($certifications as $cert): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6><?php echo htmlspecialchars($cert->title); ?></h6>
                                            <?php if ($cert->provider): ?>
                                                <p class="text-muted mb-1"><?php echo htmlspecialchars($cert->provider); ?></p>
                                            <?php endif; ?>
                                            <?php if ($cert->completion_date): ?>
                                                <p class="mb-1"><strong>Completed:</strong> <?php echo date('M Y', strtotime($cert->completion_date)); ?></p>
                                            <?php endif; ?>
                                            <?php if ($cert->cert_url): ?>
                                                <p class="mb-2"><a href="<?php echo htmlspecialchars($cert->cert_url); ?>" target="_blank" class="text-primary">View Certificate <i class="fas fa-external-link-alt"></i></a></p>
                                            <?php endif; ?>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editCertificationModal" onclick="editCertification(<?php echo $cert->id; ?>, '<?php echo addslashes($cert->title); ?>', '<?php echo addslashes($cert->provider ?? ''); ?>', '<?php echo $cert->cert_url ?? ''; ?>', '<?php echo $cert->completion_date ?? ''; ?>')">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </button>
                                                <a href="<?php echo base_url('profile/delete_section/certifications?id=' . $cert->id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this certification?')">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Licenses Section -->
            <div class="card profile-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-id-badge me-2"></i>Licenses</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addLicenseModal">
                        <i class="fas fa-plus me-1"></i>Add License
                    </button>
                </div>
                <div class="card-body">
                    <?php if (empty($licenses)): ?>
                        <p class="text-muted mb-0">No licenses added yet.</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($licenses as $license): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6><?php echo htmlspecialchars($license->title); ?></h6>
                                            <?php if ($license->issuer): ?>
                                                <p class="text-muted mb-1"><?php echo htmlspecialchars($license->issuer); ?></p>
                                            <?php endif; ?>
                                            <?php if ($license->completion_date): ?>
                                                <p class="mb-1"><strong>Issued:</strong> <?php echo date('M Y', strtotime($license->completion_date)); ?></p>
                                            <?php endif; ?>
                                            <?php if ($license->license_url): ?>
                                                <p class="mb-2"><a href="<?php echo htmlspecialchars($license->license_url); ?>" target="_blank" class="text-primary">View License <i class="fas fa-external-link-alt"></i></a></p>
                                            <?php endif; ?>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editLicenseModal" onclick="editLicense(<?php echo $license->id; ?>, '<?php echo addslashes($license->title); ?>', '<?php echo addslashes($license->issuer ?? ''); ?>', '<?php echo $license->license_url ?? ''; ?>', '<?php echo $license->completion_date ?? ''; ?>')">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </button>
                                                <a href="<?php echo base_url('profile/delete_section/licenses?id=' . $license->id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this license?')">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Short Courses Section -->
            <div class="card profile-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-book me-2"></i>Short Courses</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                        <i class="fas fa-plus me-1"></i>Add Course
                    </button>
                </div>
                <div class="card-body">
                    <?php if (empty($short_courses)): ?>
                        <p class="text-muted mb-0">No short courses added yet.</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($short_courses as $course): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6><?php echo htmlspecialchars($course->title); ?></h6>
                                            <?php if ($course->provider): ?>
                                                <p class="text-muted mb-1"><?php echo htmlspecialchars($course->provider); ?></p>
                                            <?php endif; ?>
                                            <?php if ($course->completion_date): ?>
                                                <p class="mb-1"><strong>Completed:</strong> <?php echo date('M Y', strtotime($course->completion_date)); ?></p>
                                            <?php endif; ?>
                                            <?php if ($course->course_url): ?>
                                                <p class="mb-2"><a href="<?php echo htmlspecialchars($course->course_url); ?>" target="_blank" class="text-primary">View Certificate <i class="fas fa-external-link-alt"></i></a></p>
                                            <?php endif; ?>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editCourseModal" onclick="editCourse(<?php echo $course->id; ?>, '<?php echo addslashes($course->title); ?>', '<?php echo addslashes($course->provider ?? ''); ?>', '<?php echo $course->course_url ?? ''; ?>', '<?php echo $course->completion_date ?? ''; ?>')">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </button>
                                                <a href="<?php echo base_url('profile/delete_section/short_courses?id=' . $course->id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this course?')">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Employment History Section -->
            <div class="card profile-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-briefcase me-2"></i>Employment History</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addEmploymentModal">
                        <i class="fas fa-plus me-1"></i>Add Employment
                    </button>
                </div>
                <div class="card-body">
                    <?php if (empty($employment_history)): ?>
                        <p class="text-muted mb-0">No employment history added yet.</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($employment_history as $employment): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6><?php echo htmlspecialchars($employment->role); ?> at <?php echo htmlspecialchars($employment->company); ?></h6>
                                            <p class="mb-1"><strong>Period:</strong> <?php echo date('M Y', strtotime($employment->start_date)); ?> - <?php echo $employment->end_date ? date('M Y', strtotime($employment->end_date)) : 'Present'; ?></p>
                                            <?php if ($employment->description): ?>
                                                <p class="mb-2"><?php echo htmlspecialchars($employment->description); ?></p>
                                            <?php endif; ?>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editEmploymentModal" onclick="editEmployment(<?php echo $employment->id; ?>, '<?php echo addslashes($employment->company); ?>', '<?php echo addslashes($employment->role); ?>', '<?php echo $employment->start_date; ?>', '<?php echo $employment->end_date ?? ''; ?>', '<?php echo addslashes($employment->description ?? ''); ?>')">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </button>
                                                <a href="<?php echo base_url('profile/delete_section/employment_history?id=' . $employment->id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this employment record?')">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- LinkedIn Profiles -->
            <div class="card profile-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fab fa-linkedin me-2"></i>LinkedIn Profiles</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addLinkedInModal">
                        <i class="fas fa-plus me-1"></i>Add Profile
                    </button>
                </div>
                <div class="card-body">
                    <?php if (empty($linkedin_profiles)): ?>
                        <p class="text-muted mb-0">No LinkedIn profiles added yet.</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($linkedin_profiles as $profile): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6><?php echo htmlspecialchars($profile->label ?? 'LinkedIn Profile'); ?></h6>
                                            <a href="<?php echo htmlspecialchars($profile->url); ?>" target="_blank" class="text-primary">
                                                <?php echo htmlspecialchars($profile->url); ?>
                                                <i class="fas fa-external-link-alt ms-1"></i>
                                            </a>
                                            <div class="mt-2">
                                                <a href="<?php echo base_url('profile/delete_linkedin/' . $profile->id); ?>"
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Are you sure you want to delete this LinkedIn profile?')">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add LinkedIn Modal -->
    <div class="modal fade" id="addLinkedInModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add LinkedIn Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo base_url('profile/add_linkedin'); ?>" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="url" class="form-label">LinkedIn URL</label>
                            <input type="url" class="form-control" id="url" name="url" required
                                   placeholder="https://linkedin.com/in/yourprofile">
                        </div>
                        <div class="mb-3">
                            <label for="label" class="form-label">Label (Optional)</label>
                            <input type="text" class="form-control" id="label" name="label"
                                   placeholder="e.g., Professional Profile">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Profile</button>
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Degree Modal -->
    <div class="modal fade" id="addDegreeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Degree</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo base_url('profile/add_section/degrees'); ?>" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="institution" class="form-label">Institution *</label>
                                    <input type="text" class="form-control" id="institution" name="institution" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="degree" class="form-label">Degree *</label>
                                    <input type="text" class="form-control" id="degree" name="degree" required placeholder="e.g., Bachelor of Science">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="field" class="form-label">Field of Study</label>
                                    <input type="text" class="form-control" id="field" name="field" placeholder="e.g., Computer Science">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="completion_date" class="form-label">Completion Date</label>
                                    <input type="date" class="form-control" id="completion_date" name="completion_date">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="degree_url" class="form-label">Degree URL</label>
                            <input type="url" class="form-control" id="degree_url" name="degree_url" placeholder="https://...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Degree</button>
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Degree Modal -->
    <div class="modal fade" id="editDegreeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Degree</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo base_url('profile/update_section/degrees'); ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="edit_degree_id" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_institution" class="form-label">Institution *</label>
                                    <input type="text" class="form-control" id="edit_institution" name="institution" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_degree" class="form-label">Degree *</label>
                                    <input type="text" class="form-control" id="edit_degree" name="degree" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_field" class="form-label">Field of Study</label>
                                    <input type="text" class="form-control" id="edit_field" name="field">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_completion_date" class="form-label">Completion Date</label>
                                    <input type="date" class="form-control" id="edit_completion_date" name="completion_date">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_degree_url" class="form-label">Degree URL</label>
                            <input type="url" class="form-control" id="edit_degree_url" name="degree_url">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Degree</button>
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Certification Modal -->
    <div class="modal fade" id="addCertificationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Certification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo base_url('profile/add_section/certifications'); ?>" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cert_title" class="form-label">Title *</label>
                                    <input type="text" class="form-control" id="cert_title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cert_provider" class="form-label">Provider</label>
                                    <input type="text" class="form-control" id="cert_provider" name="provider" placeholder="e.g., AWS, Google">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cert_completion_date" class="form-label">Completion Date</label>
                                    <input type="date" class="form-control" id="cert_completion_date" name="completion_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cert_url" class="form-label">Certificate URL</label>
                                    <input type="url" class="form-control" id="cert_url" name="cert_url">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Certification</button>
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Certification Modal -->
    <div class="modal fade" id="editCertificationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Certification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo base_url('profile/update_section/certifications'); ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="edit_cert_id" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_cert_title" class="form-label">Title *</label>
                                    <input type="text" class="form-control" id="edit_cert_title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_cert_provider" class="form-label">Provider</label>
                                    <input type="text" class="form-control" id="edit_cert_provider" name="provider">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_cert_completion_date" class="form-label">Completion Date</label>
                                    <input type="date" class="form-control" id="edit_cert_completion_date" name="completion_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_cert_url" class="form-label">Certificate URL</label>
                                    <input type="url" class="form-control" id="edit_cert_url" name="cert_url">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Certification</button>
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add License Modal -->
    <div class="modal fade" id="addLicenseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add License</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo base_url('profile/add_section/licenses'); ?>" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="license_title" class="form-label">Title *</label>
                                    <input type="text" class="form-control" id="license_title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="license_issuer" class="form-label">Issuer</label>
                                    <input type="text" class="form-control" id="license_issuer" name="issuer" placeholder="e.g., ICAEW, Bar Council">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="license_completion_date" class="form-label">Issue Date</label>
                                    <input type="date" class="form-control" id="license_completion_date" name="completion_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="license_url" class="form-label">License URL</label>
                                    <input type="url" class="form-control" id="license_url" name="license_url">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add License</button>
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit License Modal -->
    <div class="modal fade" id="editLicenseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit License</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo base_url('profile/update_section/licenses'); ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="edit_license_id" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_license_title" class="form-label">Title *</label>
                                    <input type="text" class="form-control" id="edit_license_title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_license_issuer" class="form-label">Issuer</label>
                                    <input type="text" class="form-control" id="edit_license_issuer" name="issuer">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_license_completion_date" class="form-label">Issue Date</label>
                                    <input type="date" class="form-control" id="edit_license_completion_date" name="completion_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_license_url" class="form-label">License URL</label>
                                    <input type="url" class="form-control" id="edit_license_url" name="license_url">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update License</button>
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div class="modal fade" id="addCourseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Short Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo base_url('profile/add_section/short_courses'); ?>" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="course_title" class="form-label">Title *</label>
                                    <input type="text" class="form-control" id="course_title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="course_provider" class="form-label">Provider</label>
                                    <input type="text" class="form-control" id="course_provider" name="provider" placeholder="e.g., Coursera, Udemy">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="course_completion_date" class="form-label">Completion Date</label>
                                    <input type="date" class="form-control" id="course_completion_date" name="completion_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="course_url" class="form-label">Certificate URL</label>
                                    <input type="url" class="form-control" id="course_url" name="course_url">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Course</button>
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div class="modal fade" id="editCourseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Short Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo base_url('profile/update_section/short_courses'); ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="edit_course_id" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_course_title" class="form-label">Title *</label>
                                    <input type="text" class="form-control" id="edit_course_title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_course_provider" class="form-label">Provider</label>
                                    <input type="text" class="form-control" id="edit_course_provider" name="provider">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_course_completion_date" class="form-label">Completion Date</label>
                                    <input type="date" class="form-control" id="edit_course_completion_date" name="completion_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_course_url" class="form-label">Certificate URL</label>
                                    <input type="url" class="form-control" id="edit_course_url" name="course_url">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Course</button>
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Employment Modal -->
    <div class="modal fade" id="addEmploymentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Employment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo base_url('profile/add_section/employment_history'); ?>" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company" class="form-label">Company *</label>
                                    <input type="text" class="form-control" id="company" name="company" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role *</label>
                                    <input type="text" class="form-control" id="role" name="role" required placeholder="e.g., Software Engineer">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date *</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date (leave empty if current)</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Describe your responsibilities and achievements..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Employment</button>
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Employment Modal -->
    <div class="modal fade" id="editEmploymentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Employment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo base_url('profile/update_section/employment_history'); ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="edit_employment_id" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_company" class="form-label">Company *</label>
                                    <input type="text" class="form-control" id="edit_company" name="company" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_role" class="form-label">Role *</label>
                                    <input type="text" class="form-control" id="edit_role" name="role" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_start_date" class="form-label">Start Date *</label>
                                    <input type="date" class="form-control" id="edit_start_date" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_end_date" class="form-label">End Date (leave empty if current)</label>
                                    <input type="date" class="form-control" id="edit_end_date" name="end_date">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Employment</button>
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit Degree Function
        function editDegree(id, institution, degree, field, degreeUrl, completionDate) {
            document.getElementById('edit_degree_id').value = id;
            document.getElementById('edit_institution').value = institution;
            document.getElementById('edit_degree').value = degree;
            document.getElementById('edit_field').value = field || '';
            document.getElementById('edit_degree_url').value = degreeUrl || '';
            document.getElementById('edit_completion_date').value = completionDate || '';
        }

        // Edit Certification Function
        function editCertification(id, title, provider, certUrl, completionDate) {
            document.getElementById('edit_cert_id').value = id;
            document.getElementById('edit_cert_title').value = title;
            document.getElementById('edit_cert_provider').value = provider || '';
            document.getElementById('edit_cert_url').value = certUrl || '';
            document.getElementById('edit_cert_completion_date').value = completionDate || '';
        }

        // Edit License Function
        function editLicense(id, title, issuer, licenseUrl, completionDate) {
            document.getElementById('edit_license_id').value = id;
            document.getElementById('edit_license_title').value = title;
            document.getElementById('edit_license_issuer').value = issuer || '';
            document.getElementById('edit_license_url').value = licenseUrl || '';
            document.getElementById('edit_license_completion_date').value = completionDate || '';
        }

        // Edit Course Function
        function editCourse(id, title, provider, courseUrl, completionDate) {
            document.getElementById('edit_course_id').value = id;
            document.getElementById('edit_course_title').value = title;
            document.getElementById('edit_course_provider').value = provider || '';
            document.getElementById('edit_course_url').value = courseUrl || '';
            document.getElementById('edit_course_completion_date').value = completionDate || '';
        }

        // Edit Employment Function
        function editEmployment(id, company, role, startDate, endDate, description) {
            document.getElementById('edit_employment_id').value = id;
            document.getElementById('edit_company').value = company;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_start_date').value = startDate;
            document.getElementById('edit_end_date').value = endDate || '';
            document.getElementById('edit_description').value = description || '';
        }
    </script>
</body>
</html>