<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Alumni Dashboard</title>
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

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .form-control {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.75rem;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
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

            .form-control {
                font-size: 0.95rem;
                padding: 0.6rem;
            }

            .form-label {
                font-size: 0.95rem;
            }

            .btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }

            .row > [class*='col-'] {
                margin-bottom: 1rem;
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

            .card-header {
                padding: 0.75rem;
            }

            .card-body {
                padding: 0.75rem;
            }

            .form-control {
                font-size: 0.9rem;
                padding: 0.5rem;
            }

            .form-label {
                font-size: 0.9rem;
                margin-bottom: 0.25rem;
            }

            .btn {
                padding: 0.3rem 0.6rem;
                font-size: 0.75rem;
                margin-right: 0.25rem;
                margin-bottom: 0.25rem;
            }

            .btn-block {
                display: block;
                width: 100%;
            }

            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .col-md-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            textarea.form-control {
                min-height: 80px;
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

            .card-header h5 {
                font-size: 0.95rem;
            }

            .form-control {
                font-size: 0.85rem;
            }

            .form-label {
                font-size: 0.85rem;
            }

            .btn {
                display: block;
                width: 100%;
                margin-bottom: 0.5rem;
            }

            textarea.form-control {
                min-height: 70px;
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
                <h2><i class="fas fa-user-edit" style="color: #667eea; margin-right: 0.5rem;"></i>Edit Profile</h2>
                <div>
                    <a href="<?php echo base_url('profile'); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Profile
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

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 2rem;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo $this->session->flashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo base_url('profile/update'); ?>" method="post" enctype="multipart/form-data">
                <!-- Personal Information -->
                <div class="card profile-card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-user me-2"></i>Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name"
                                           value="<?php echo htmlspecialchars($personal_info->full_name ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email (Read-only)</label>
                                    <input type="email" class="form-control" id="email" readonly
                                           value="<?php echo htmlspecialchars($this->session->userdata('email')); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="biography" class="form-label">Biography</label>
                            <textarea class="form-control" id="biography" name="biography" rows="4"
                                      placeholder="Tell us about yourself..."><?php echo htmlspecialchars($personal_info->biography ?? ''); ?></textarea>
                        </div>

                        <!-- Profile Image Upload -->
                        <div class="mb-3">
                            <label for="profile_image" class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                            <div class="form-text">
                                Upload a profile image (max 2MB, JPG/PNG/GIF)
                                <?php if ($personal_info->profile_image_url): ?>
                                    <br><small class="text-muted">Current image will be replaced if you upload a new one.</small>
                                <?php endif; ?>
                            </div>
                            <?php if ($personal_info->profile_image_url): ?>
                                <div class="mt-2">
                                    <img src="<?php echo htmlspecialchars($personal_info->profile_image_url); ?>" alt="Current Profile Image" class="img-thumbnail" style="max-width: 150px;">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Alumni Information -->
                <div class="card profile-card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-graduation-cap me-2"></i>Alumni Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="programme" class="form-label">Programme</label>
                                    <select class="form-select" id="programme" name="programme">
                                        <option value="">Select Programme</option>
                                        <option value="Computer Science" <?php echo ($alumni_info->programme ?? '') == 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
                                        <option value="Business Administration" <?php echo ($alumni_info->programme ?? '') == 'Business Administration' ? 'selected' : ''; ?>>Business Administration</option>
                                        <option value="Engineering" <?php echo ($alumni_info->programme ?? '') == 'Engineering' ? 'selected' : ''; ?>>Engineering</option>
                                        <option value="Information Technology" <?php echo ($alumni_info->programme ?? '') == 'Information Technology' ? 'selected' : ''; ?>>Information Technology</option>
                                        <option value="Data Science" <?php echo ($alumni_info->programme ?? '') == 'Data Science' ? 'selected' : ''; ?>>Data Science</option>
                                        <option value="Cybersecurity" <?php echo ($alumni_info->programme ?? '') == 'Cybersecurity' ? 'selected' : ''; ?>>Cybersecurity</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="graduation_year" class="form-label">Graduation Year</label>
                                    <select class="form-select" id="graduation_year" name="graduation_year">
                                        <option value="">Select Year</option>
                                        <?php for ($year = date('Y'); $year >= 1990; $year--): ?>
                                            <option value="<?php echo $year; ?>" <?php echo ($alumni_info->graduation_year ?? '') == $year ? 'selected' : ''; ?>><?php echo $year; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="industry_sector" class="form-label">Industry Sector</label>
                                    <select class="form-select" id="industry_sector" name="industry_sector">
                                        <option value="">Select Industry</option>
                                        <option value="Technology" <?php echo ($alumni_info->industry_sector ?? '') == 'Technology' ? 'selected' : ''; ?>>Technology</option>
                                        <option value="Finance" <?php echo ($alumni_info->industry_sector ?? '') == 'Finance' ? 'selected' : ''; ?>>Finance</option>
                                        <option value="Healthcare" <?php echo ($alumni_info->industry_sector ?? '') == 'Healthcare' ? 'selected' : ''; ?>>Healthcare</option>
                                        <option value="Education" <?php echo ($alumni_info->industry_sector ?? '') == 'Education' ? 'selected' : ''; ?>>Education</option>
                                        <option value="Manufacturing" <?php echo ($alumni_info->industry_sector ?? '') == 'Manufacturing' ? 'selected' : ''; ?>>Manufacturing</option>
                                        <option value="Consulting" <?php echo ($alumni_info->industry_sector ?? '') == 'Consulting' ? 'selected' : ''; ?>>Consulting</option>
                                        <option value="Retail" <?php echo ($alumni_info->industry_sector ?? '') == 'Retail' ? 'selected' : ''; ?>>Retail</option>
                                        <option value="Government" <?php echo ($alumni_info->industry_sector ?? '') == 'Government' ? 'selected' : ''; ?>>Government</option>
                                        <option value="Other" <?php echo ($alumni_info->industry_sector ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="current_location" class="form-label">Current Location</label>
                                    <input type="text" class="form-control" id="current_location" name="current_location"
                                           value="<?php echo htmlspecialchars($alumni_info->current_location ?? ''); ?>"
                                           placeholder="e.g., London, UK">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>

                <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>