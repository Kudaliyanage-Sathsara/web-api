<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Rate Limiting Hook
 *
 * Implements rate limiting for API endpoints
 */
class Rate_limiter {

    private $ci;
    private $max_requests = 100; // requests per hour
    private $window = 3600; // 1 hour in seconds

    public function __construct()
    {
        $this->ci =& get_instance();
    }

    public function check_rate_limit()
    {
        // Only apply to API routes
        if (strpos($_SERVER['REQUEST_URI'], '/api/') !== 0) {
            return;
        }

        $ip = $this->ci->input->ip_address();
        $key = 'rate_limit_' . md5($ip);

        // Get current count from session (in production, use Redis or database)
        $requests = $this->ci->session->userdata($key) ?: [];

        // Clean old requests
        $now = time();
        $requests = array_filter($requests, function($timestamp) use ($now) {
            return ($now - $timestamp) < $this->window;
        });

        // Check if limit exceeded
        if (count($requests) >= $this->max_requests) {
            header('HTTP/1.1 429 Too Many Requests');
            header('Retry-After: 3600');
            echo json_encode(['error' => 'Rate limit exceeded. Try again in 1 hour.']);
            exit;
        }

        // Add current request
        $requests[] = $now;
        $this->ci->session->set_userdata($key, $requests);
    }
}