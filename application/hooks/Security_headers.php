<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Security Headers Hook
 *
 * Adds security headers to all responses
 */
class Security_headers {

    public function add_headers()
    {
        // Security headers
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        header('Referrer-Policy: strict-origin-when-cross-origin');

        // CORS headers for API
        if (strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');
            header('Access-Control-Max-Age: 86400');

            // Handle preflight requests
            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                exit(0);
            }
        }
    }
}