<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Token_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('security_helper');
    }

    public function login()
    {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        $this->load->view('auth/login');
    }

    public function register()
    {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        $this->load->view('auth/register');
    }
    public function process_register()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');
        $terms = $this->input->post('terms');

        // Validation
        if (empty($email) || empty($password) || empty($confirm_password)) {
            $this->session->set_flashdata('error', 'All fields are required');
            redirect('auth/register');
        }

        if ($password !== $confirm_password) {
            $this->session->set_flashdata('error', 'Passwords do not match');
            redirect('auth/register');
        }

        if (!$terms) {
            $this->session->set_flashdata('error', 'You must agree to the terms and conditions');
            redirect('auth/register');
        }

        // Email domain validation
        if (!validate_university_email($email)) {
            $this->session->set_flashdata('error', 'Invalid university email. Only @my.westminster.ac.uk and @iit.ac.lk domains are allowed');
            redirect('auth/register');
        }

        // Password strength validation
        if (strlen($password) < 8) {
            $this->session->set_flashdata('error', 'Password must be at least 8 characters long');
            redirect('auth/register');
        }

        if (!preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W]/', $password)) {
            $this->session->set_flashdata('error', 'Password must include uppercase, lowercase, number, and special character');
            redirect('auth/register');
        }

        // Check if email already exists
        if ($this->User_model->get_by_email($email)) {
            $this->session->set_flashdata('error', 'Email already exists');
            redirect('auth/register');
        }

        // Create user
        $this->User_model->create_user($email, $password);
        $user = $this->User_model->get_by_email($email);

        // Generate verification token
        $token = generate_secure_token();
        $this->Token_model->create_verification_token($user->id, $token);

        // In a real application, send email here
        $verification_url = base_url('api/auth/verify_email?token=' . $token);

        $this->session->set_flashdata('success',
            'Registration successful! Please check your email and click the verification link to activate your account. ' .
            '<br><br><strong>For testing purposes, use this verification URL:</strong><br>' .
            '<a href="' . $verification_url . '" target="_blank">' . $verification_url . '</a>'
        );

        redirect('auth/register');
    }
    public function process_login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->User_model->get_by_email($email);

        if ($user && password_verify($password, $user->password) && $user->is_verified) {
            $this->session->set_userdata([
                'user_id' => $user->id,
                'email' => $user->email,
                'logged_in' => TRUE
            ]);
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Invalid credentials or unverified account');
            redirect('auth/login');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    public function forgot_password()
    {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        $this->load->view('auth/forgot_password');
    }

    public function process_forgot_password()
    {
        $email = $this->input->post('email');

        if (empty($email)) {
            $this->session->set_flashdata('error', 'Email is required');
            redirect('auth/forgot_password');
        }

        // Validate university email
        if (!validate_university_email($email)) {
            $this->session->set_flashdata('error', 'Invalid university email. Only @my.westminster.ac.uk and @iit.ac.lk domains are allowed');
            redirect('auth/forgot_password');
        }

        $user = $this->User_model->get_by_email($email);

        if (!$user) {
            $this->session->set_flashdata('error', 'No account found with this email address');
            redirect('auth/forgot_password');
        }

        // Generate reset token
        $token = generate_secure_token();
        $this->Token_model->create_password_reset_token($user->id, $token);

        // Create reset URL
        $reset_url = base_url('auth/reset_password?token=' . $token);

        $this->session->set_flashdata('success',
            'Password reset link sent! Please check your email and click the reset link. ' .
            '<br><br><strong>For testing purposes, use this reset URL:</strong><br>' .
            '<a href="' . $reset_url . '" target="_blank">' . $reset_url . '</a>'
        );

        redirect('auth/forgot_password');
    }

    public function reset_password()
    {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        $token = $this->input->get('token');

        if (empty($token)) {
            $this->session->set_flashdata('error', 'Invalid reset link');
            redirect('auth/login');
        }

        // Verify token is valid
        $token_data = $this->Token_model->verify_password_reset_token($token);

        if (!$token_data) {
            $this->session->set_flashdata('error', 'Invalid or expired reset link');
            redirect('auth/login');
        }

        $this->load->view('auth/reset_password');
    }

    public function process_reset_password()
    {
        $token = $this->input->post('token');
        $password = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');

        if (empty($token) || empty($password) || empty($confirm_password)) {
            $this->session->set_flashdata('error', 'All fields are required');
            redirect('auth/reset_password?token=' . $token);
        }

        if ($password !== $confirm_password) {
            $this->session->set_flashdata('error', 'Passwords do not match');
            redirect('auth/reset_password?token=' . $token);
        }

        // Password strength validation
        if (strlen($password) < 8) {
            $this->session->set_flashdata('error', 'Password must be at least 8 characters long');
            redirect('auth/reset_password?token=' . $token);
        }

        if (!preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W]/', $password)) {
            $this->session->set_flashdata('error', 'Password must include uppercase, lowercase, number, and special character');
            redirect('auth/reset_password?token=' . $token);
        }

        // Verify token
        $token_data = $this->Token_model->verify_password_reset_token($token);

        if (!$token_data) {
            $this->session->set_flashdata('error', 'Invalid or expired reset link');
            redirect('auth/login');
        }

        // Update password
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $this->User_model->update_password($token_data->user_id, $hash);

        // Mark token as used
        $this->Token_model->mark_password_reset_token_used($token_data->id);

        $this->session->set_flashdata('success', 'Password reset successful! You can now login with your new password.');
        redirect('auth/login');
    }
}