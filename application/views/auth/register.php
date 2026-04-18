<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Alumni Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .register-container {
            max-width: 500px;
            margin: 2rem auto;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .password-requirements {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }
        .password-requirements ul {
            margin: 0;
            padding-left: 1.2rem;
        }
        .password-requirements li {
            margin-bottom: 0.25rem;
        }
        .password-container {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
        }
        .password-toggle:hover {
            color: #495057;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="register-container">
            <div class="card">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Create Account</h2>
                    <p class="text-center text-muted mb-4">Join the Alumni Network</p>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo base_url('auth/process_register'); ?>" method="post" id="registerForm">
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">University Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required
                                   placeholder="your.email@my.westminster.ac.uk or your.email@iit.ac.lk">
                            <div class="form-text">
                                Only university emails (@my.westminster.ac.uk or @iit.ac.lk) are allowed
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="password-container">
                                <input type="password" class="form-control" id="password" name="password" required
                                       placeholder="Create a strong password">
                                <i class="fas fa-eye password-toggle" id="passwordToggle" onclick="togglePasswordVisibility('password', 'passwordToggle')"></i>
                            </div>
                            <div class="password-requirements">
                                Password must contain:
                                <ul>
                                    <li>At least 8 characters</li>
                                    <li>One uppercase letter (A-Z)</li>
                                    <li>One lowercase letter (a-z)</li>
                                    <li>One number (0-9)</li>
                                    <li>One special character (!@#$%^&*)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <div class="password-container">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                                       placeholder="Confirm your password">
                                <i class="fas fa-eye password-toggle" id="confirmPasswordToggle" onclick="togglePasswordVisibility('confirm_password', 'confirmPasswordToggle')"></i>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                            </label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary" id="registerBtn">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                Create Account
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <p class="mb-0">Already have an account?
                            <a href="<?php echo base_url('auth/login'); ?>" class="text-decoration-none">Sign In</a>
                        </p>
                    </div>

                    <hr class="my-4">

                    <div class="text-center">
                        <small class="text-muted">
                            After registration, check your email for verification instructions.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const email = document.getElementById('email').value;
            const registerBtn = document.getElementById('registerBtn');
            const spinner = registerBtn.querySelector('.spinner-border');

            // Email domain validation
            const allowedDomains = ['my.westminster.ac.uk', 'iit.ac.lk'];
            const emailDomain = email.split('@')[1];

            if (!allowedDomains.includes(emailDomain)) {
                e.preventDefault();
                alert('Please use a valid university email address (@my.westminster.ac.uk or @iit.ac.lk)');
                return;
            }

            // Password strength validation
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?])[A-Za-z\d!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]{8,}$/;

            if (!passwordRegex.test(password)) {
                e.preventDefault();
                alert('Password must be at least 8 characters and contain uppercase, lowercase, number, and special character.');
                return;
            }

            // Password confirmation
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match.');
                return;
            }

            // Show loading state
            registerBtn.disabled = true;
            spinner.classList.remove('d-none');
            registerBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Creating Account...';
        });

        // Real-time password validation
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password),
                special: /[@$!%*?&]/.test(password)
            };

            // Update visual feedback (you can enhance this with colors)
            console.log('Password requirements:', requirements);
        });

        // Password visibility toggle function
        function togglePasswordVisibility(inputId, toggleId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(toggleId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>