<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Profile Controller
 * 
 * Manages user profile information and educational/professional credentials.
 * Handles creation, reading, updating, and deletion (CRUD) of profile sections
 * including personal info, degrees, certifications, licenses, courses, and employment.
 * All endpoints require authentication.
 * 
 * @property CI_Input $input
 * @property CI_DB_query_builder $db
 * @property CI_Session $session
 * @property CI_Output $output
 * @property CI_Upload $upload
 * @property Profile_model $Profile_model
 */
class Profile extends CI_Controller {
    /**
     * Mapping of profile sections to database table names
     * Enables dynamic CRUD operations on multiple related profile tables
     * 
     * @var array Maps section name => database table name
     */
    private $sectionTables = [
        'degrees' => 'user_degrees',
        'certifications' => 'user_certifications',
        'licenses' => 'user_licenses',
        'short_courses' => 'user_short_courses',
        'employment_history' => 'user_employment_history',
    ];

    public function __construct() {
        parent::__construct();
        $this->load->model('Profile_model');
        $this->load->library('session');
        $this->load->helper(['url', 'file']);
    }

    /**
     * Parse JSON request body into an array.
     * 
     * Provides cached JSON parsing for request bodies. Uses static variable caching
     * to avoid redundant parsing of php://input stream. Allows API to accept both
     * application/json and application/x-www-form-urlencoded content types.
     * 
     * @return array Decoded JSON data as associative array, empty array if invalid
     */
    protected function getJsonInput() {
        static $json;
        if ($json !== null) {
            return $json;
        }
        $raw = trim(file_get_contents('php://input') ?? '');
        $json = $raw ? json_decode($raw, true) : [];
        return is_array($json) ? $json : [];
    }

    /**
     * Authentication Check Middleware
     * 
     * Validates that user is logged in by checking session data.
     * Terminates request with 401 Unauthorized if no authenticated user found.
     * Called at the start of all profile endpoint methods.
     * 
     * @return int User ID from session userdata
     */
    private function require_auth() {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['error' => 'Authentication required']);
            exit;
        }
        return $user_id;
    }

    /**
     * URL Validation Helper
     * 
     * Validates URL format using PHP's FILTER_VALIDATE_URL filter.
     * Used for LinkedIn profiles, degree URLs, certification URLs, etc.
     * Accepts http, https, and other valid URL schemes.
     * 
     * @param string $url The URL to validate
     * @return bool True if valid URL format, false otherwise
     */
    private function is_valid_url($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Date Format Validation Helper
     * 
     * Validates that a date string conforms to YYYY-MM-DD format.
     * Returns true if date is null/empty (optional field).
     * Uses PHP's DateTime class with strict format checking.
     * 
     * @param string $date The date string to validate
     * @return bool True if valid YYYY-MM-DD format or empty, false otherwise
     */
    private function is_valid_date($date) {
        if (!$date) return true;
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    // ========================================================================
    // PROFILE RETRIEVAL
    // ========================================================================

    /**
     * Get Complete User Profile
     * 
     * Retrieves all profile data for authenticated user including personal info,
     * LinkedIn profiles, and all credential sections (degrees, certs, etc).
     * 
     * Data Structure Returned:
     * - personal: user_personal_infos row (full_name, biography, profile_image_url)
     * - linkedin: array of user_linkedin_profiles rows
     * - degrees: array of user_degrees rows
     * - certifications: array of user_certifications rows
     * - licenses: array of user_licenses rows
     * - short_courses: array of user_short_courses rows
     * - employment_history: array of user_employment_history rows
     * 
     * @return void Outputs JSON response with complete profile data
     */
    public function get_profile() {
        $user_id = $this->require_auth();
        $personal = $this->Profile_model->get_personal_info($user_id);

        $result = [
            'personal' => $personal,
            'linkedin' => $this->Profile_model->get_linkedin($user_id),
            'degrees' => $this->Profile_model->list_records('user_degrees', $user_id),
            'certifications' => $this->Profile_model->list_records('user_certifications', $user_id),
            'licenses' => $this->Profile_model->list_records('user_licenses', $user_id),
            'short_courses' => $this->Profile_model->list_records('user_short_courses', $user_id),
            'employment_history' => $this->Profile_model->list_records('user_employment_history', $user_id),
        ];

        echo json_encode($result);
    }

    // ========================================================================
    // PERSONAL PROFILE MANAGEMENT
    // ========================================================================

    /**
     * Update Personal Profile Information
     * 
     * Updates user's personal profile including name and biography.
     * Preserves existing profile image URL if not provided.
     * Creates personal_info record if it doesn't exist.
     * 
     * @return void Outputs JSON response with message or error
     */
    public function update_profile() {
        $user_id = $this->require_auth();
        $input = $this->getJsonInput();

        $full_name = $this->input->post('full_name', TRUE) ?: ($input['full_name'] ?? null);
        $biography = $this->input->post('biography', TRUE) ?: ($input['biography'] ?? null);

        $data = [
            'full_name' => $full_name,
            'biography' => $biography,
            'profile_image_url' => $this->Profile_model->get_personal_info($user_id)->profile_image_url ?? null
        ];

        $this->Profile_model->save_personal_info($user_id, $data);
        echo json_encode(['message' => 'Profile updated']);
    }

    /**
     * Upload Profile Image
     * 
     * Handles profile image file upload with validation and storage.
     * 
     * Upload Process:
     * 1. Check that file was uploaded (not empty)
     * 2. Create upload directory if it doesn't exist (uploads/profile_images/)
     * 3. Configure CodeIgniter Upload library with constraints:
     *    - Max file size: 2 MB
     *    - Allowed types: gif, jpg, jpeg, png
     *    - Encrypt filename to prevent collisions
     * 4. Execute upload and capture file info
     * 5. Generate public URL using base_url()
     * 6. Update or create personal_info record with new image URL
     * 7. Return success with image URL
     * 
     * @return void Outputs JSON response with message and profile_image_url or error
     */
    public function upload_profile_image() {
        $user_id = $this->require_auth();

        if (empty($_FILES['profile_image']['name'])) {
            echo json_encode(['error' => 'No file uploaded']); return;
        }

        $upload_path = FCPATH . 'uploads/profile_images/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $config = [
            'upload_path' => $upload_path,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size' => 2048,
            'encrypt_name' => TRUE,
        ];

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('profile_image')) {
            echo json_encode(['error' => $this->upload->display_errors('', '')]); return;
        }

        $file_info = $this->upload->data();
        $url = base_url('uploads/profile_images/' . $file_info['file_name']);

        $current = $this->Profile_model->get_personal_info($user_id);
        $this->Profile_model->save_personal_info($user_id, array_merge((array)$current, ['profile_image_url' => $url]));

        echo json_encode(['message' => 'Image uploaded', 'profile_image_url' => $url]);
    }

    // ========================================================================
    // PROFILE SECTIONS - GENERIC CRUD OPERATIONS
    // ========================================================================

    /**
     * List Profile Section Records
     * 
     * Retrieves all records for a specific profile section (degrees, certs, etc).
     * Returns empty array if section has no records.
     * Returns 404 if section name is invalid.
     * 
     * @param string $section Section name from sectionTables mapping
     * @return void Outputs JSON array of records
     */
    public function list_section($section) {
        $user_id = $this->require_auth();
        if (!isset($this->sectionTables[$section])) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Unknown section']); return;
        }

        echo json_encode($this->Profile_model->list_records($this->sectionTables[$section], $user_id));
    }

    /**
     * Add New Profile Section Record
     * 
     * Creates a new record in a profile section. Performs section-specific validation
     * including field presence checks, URL validation, and date format validation.
     * 
     * Validation Logic by Section:
     * 
     * DEGREES: Requires institution + degree, optional field, degree_url, completion_date
     * - Validates degree_url if provided (must be valid URL)
     * - Validates completion_date if provided (must be YYYY-MM-DD)
     * 
     * CERTIFICATIONS: Requires title, optional provider, cert_url, completion_date
     * - Validates cert_url if provided
     * - Validates completion_date if provided
     * 
     * LICENSES: Requires title, optional issuer, license_url, completion_date
     * - Validates license_url if provided
     * - Validates completion_date if provided
     * 
     * SHORT_COURSES: Requires title, optional provider, course_url, completion_date
     * - Validates course_url if provided
     * - Validates completion_date if provided
     * 
     * EMPLOYMENT_HISTORY: Requires company + role + start_date, optional end_date, description
     * - Validates both start_date and end_date if provided
     * - Allows end_date to be null for current employment
     * 
     * @param string $section Section name from sectionTables mapping
     * @return void Outputs JSON response with message or validation error
     */
    public function add_section($section) {
        $user_id = $this->require_auth();
        if (!isset($this->sectionTables[$section])) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Unknown section']); return;
        }

        $input = $this->getJsonInput();
        $data = [];

        switch ($section) {
            case 'degrees':
                $data = [
                    'institution' => $this->input->post('institution', TRUE) ?: ($input['institution'] ?? null),
                    'degree' => $this->input->post('degree', TRUE) ?: ($input['degree'] ?? null),
                    'field' => $this->input->post('field', TRUE) ?: ($input['field'] ?? null),
                    'degree_url' => $this->input->post('degree_url', TRUE) ?: ($input['degree_url'] ?? null),
                    'completion_date' => $this->input->post('completion_date', TRUE) ?: ($input['completion_date'] ?? null),
                ];
                if (!$data['institution'] || !$data['degree']) {
                    echo json_encode(['error' => 'Institution and degree are required']); return;
                }
                if ($data['degree_url'] && !$this->is_valid_url($data['degree_url'])) {
                    echo json_encode(['error' => 'Invalid degree URL']); return;
                }
                if ($data['completion_date'] && !$this->is_valid_date($data['completion_date'])) {
                    echo json_encode(['error' => 'Invalid completion date, use YYYY-MM-DD']); return;
                }
                break;

            case 'certifications':
                $data = [
                    'title' => $this->input->post('title', TRUE) ?: ($input['title'] ?? null),
                    'provider' => $this->input->post('provider', TRUE) ?: ($input['provider'] ?? null),
                    'cert_url' => $this->input->post('cert_url', TRUE) ?: ($input['cert_url'] ?? null),
                    'completion_date' => $this->input->post('completion_date', TRUE) ?: ($input['completion_date'] ?? null),
                ];
                if (!$data['title']) {
                    echo json_encode(['error' => 'Title is required']); return;
                }
                if ($data['cert_url'] && !$this->is_valid_url($data['cert_url'])) {
                    echo json_encode(['error' => 'Invalid certification URL']); return;
                }
                if ($data['completion_date'] && !$this->is_valid_date($data['completion_date'])) {
                    echo json_encode(['error' => 'Invalid completion date, use YYYY-MM-DD']); return;
                }
                break;

            case 'licenses':
                $data = [
                    'title' => $this->input->post('title', TRUE) ?: ($input['title'] ?? null),
                    'issuer' => $this->input->post('issuer', TRUE) ?: ($input['issuer'] ?? null),
                    'license_url' => $this->input->post('license_url', TRUE) ?: ($input['license_url'] ?? null),
                    'completion_date' => $this->input->post('completion_date', TRUE) ?: ($input['completion_date'] ?? null),
                ];
                if (!$data['title']) {
                    echo json_encode(['error' => 'Title is required']); return;
                }
                if ($data['license_url'] && !$this->is_valid_url($data['license_url'])) {
                    echo json_encode(['error' => 'Invalid license URL']); return;
                }
                if ($data['completion_date'] && !$this->is_valid_date($data['completion_date'])) {
                    echo json_encode(['error' => 'Invalid completion date, use YYYY-MM-DD']); return;
                }
                break;

            case 'short_courses':
                $data = [
                    'title' => $this->input->post('title', TRUE) ?: ($input['title'] ?? null),
                    'provider' => $this->input->post('provider', TRUE) ?: ($input['provider'] ?? null),
                    'course_url' => $this->input->post('course_url', TRUE) ?: ($input['course_url'] ?? null),
                    'completion_date' => $this->input->post('completion_date', TRUE) ?: ($input['completion_date'] ?? null),
                ];
                if (!$data['title']) {
                    echo json_encode(['error' => 'Title is required']); return;
                }
                if ($data['course_url'] && !$this->is_valid_url($data['course_url'])) {
                    echo json_encode(['error' => 'Invalid course URL']); return;
                }
                if ($data['completion_date'] && !$this->is_valid_date($data['completion_date'])) {
                    echo json_encode(['error' => 'Invalid completion date, use YYYY-MM-DD']); return;
                }
                break;

            case 'employment_history':
                $data = [
                    'company' => $this->input->post('company', TRUE) ?: ($input['company'] ?? null),
                    'role' => $this->input->post('role', TRUE) ?: ($input['role'] ?? null),
                    'start_date' => $this->input->post('start_date', TRUE) ?: ($input['start_date'] ?? null),
                    'end_date' => $this->input->post('end_date', TRUE) ?: ($input['end_date'] ?? null),
                    'description' => $this->input->post('description', TRUE) ?: ($input['description'] ?? null),
                ];
                if (!$data['company'] || !$data['role'] || !$data['start_date']) {
                    echo json_encode(['error' => 'Company, role, and start date are required']); return;
                }
                if (!$this->is_valid_date($data['start_date']) || ($data['end_date'] && !$this->is_valid_date($data['end_date']))) {
                    echo json_encode(['error' => 'Invalid date format, use YYYY-MM-DD']); return;
                }
                break;
        }

        $this->Profile_model->create_record($this->sectionTables[$section], $user_id, $data);
        echo json_encode(['message' => ucfirst(str_replace('_', ' ', $section)) . ' item created']);
    }

    /**
     * Update Profile Section Record
     * 
     * Updates existing record in profile section. Merges existing data with provided
     * fields to allow partial updates (only changed fields need to be provided).
     * Performs same validation as add_section before updating.
     * Prevents modification of id, user_id, and created_at fields.
     * 
     * Update Algorithm:
     * 1. Verify record exists and belongs to authenticated user
     * 2. Retrieve current record data
     * 3. For each field (excluding id, user_id, created_at):
     *    - Use provided value if given, otherwise keep existing value
     * 4. Validate all URL fields if present
     * 5. Validate all date fields if present
     * 6. Update record in database
     * 7. Return success message
     * 
     * @param string $section Section name from sectionTables mapping
     * @param int $id Record ID to update
     * @return void Outputs JSON response with message or error
     */
    public function update_section($section, $id) {
        $user_id = $this->require_auth();
        if (!isset($this->sectionTables[$section])) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Unknown section']); return;
        }

        $record = $this->Profile_model->get_record($this->sectionTables[$section], $id, $user_id);
        if (!$record) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Item not found']); return;
        }

        $input = $this->getJsonInput();
        $data = [];

        // Merge existing and provided fields, skipping system fields.
        foreach ((array)$record as $key => $value) {
            if ($key === 'id' || $key === 'user_id' || $key === 'created_at') continue;
            $data[$key] = $this->input->post($key, TRUE) ?: ($input[$key] ?? $value);
        }

        if (isset($data['degree_url']) && $data['degree_url'] && !$this->is_valid_url($data['degree_url'])) {
            echo json_encode(['error' => 'Invalid degree URL']); return;
        }
        if (isset($data['cert_url']) && $data['cert_url'] && !$this->is_valid_url($data['cert_url'])) {
            echo json_encode(['error' => 'Invalid certification URL']); return;
        }
        if (isset($data['license_url']) && $data['license_url'] && !$this->is_valid_url($data['license_url'])) {
            echo json_encode(['error' => 'Invalid license URL']); return;
        }
        if (isset($data['course_url']) && $data['course_url'] && !$this->is_valid_url($data['course_url'])) {
            echo json_encode(['error' => 'Invalid course URL']); return;
        }

        if ((isset($data['completion_date']) && $data['completion_date'] && !$this->is_valid_date($data['completion_date']))
            || (isset($data['start_date']) && $data['start_date'] && !$this->is_valid_date($data['start_date']))
            || (isset($data['end_date']) && $data['end_date'] && !$this->is_valid_date($data['end_date']))) {
            echo json_encode(['error' => 'Invalid date format, use YYYY-MM-DD']); return;
        }

        $this->Profile_model->update_record($this->sectionTables[$section], $id, $user_id, $data);
        echo json_encode(['message' => ucfirst(str_replace('_', ' ', $section)) . ' item updated']);
    }

    /**
     * Delete Profile Section Record
     * 
     * Removes record from profile section. Verifies record exists and belongs
     * to authenticated user before deletion.
     * 
     * @param string $section Section name from sectionTables mapping
     * @param int $id Record ID to delete
     * @return void Outputs JSON response with message or error
     */
    public function delete_section($section, $id) {
        $user_id = $this->require_auth();
        if (!isset($this->sectionTables[$section])) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Unknown section']); return;
        }

        $record = $this->Profile_model->get_record($this->sectionTables[$section], $id, $user_id);
        if (!$record) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Item not found']); return;
        }

        $this->Profile_model->delete_record($this->sectionTables[$section], $id, $user_id);
        echo json_encode(['message' => ucfirst(str_replace('_', ' ', $section)) . ' item deleted']);
    }

    // ========================================================================
    // LINKEDIN PROFILE MANAGEMENT
    // ========================================================================

    /**
     * Add LinkedIn Profile
     * 
     * Adds a LinkedIn profile URL with optional label/title.
     * Validates URL format before storing.
     * Users can have multiple LinkedIn profiles.
     * 
     * @return void Outputs JSON response with message or error
     */
    public function add_linkedin() {
        $user_id = $this->require_auth();
        $input = $this->getJsonInput();
        $url = $this->input->post('url', TRUE) ?: ($input['url'] ?? null);
        $label = $this->input->post('label', TRUE) ?: ($input['label'] ?? null);

        if (!$url || !$this->is_valid_url($url)) {
            echo json_encode(['error' => 'Valid LinkedIn URL is required']); return;
        }

        $this->Profile_model->save_linkedin($user_id, ['url' => $url, 'label' => $label]);
        echo json_encode(['message' => 'LinkedIn profile added']);
    }

    /**
     * List LinkedIn Profiles
     * 
     * Returns all LinkedIn profile URLs for authenticated user.
     * 
     * @return void Outputs JSON array of LinkedIn profiles
     */
    public function list_linkedin() {
        $user_id = $this->require_auth();
        echo json_encode($this->Profile_model->get_linkedin($user_id));
    }

    /**
     * Update LinkedIn Profile
     * 
     * Updates an existing LinkedIn profile entry. Allows updating
     * either URL, label, or both. Validates URL if provided.
     * 
     * @param int $id LinkedIn profile record ID
     * @return void Outputs JSON response with message or error
     */
    public function update_linkedin($id) {
        $user_id = $this->require_auth();
        $input = $this->getJsonInput();
        $url = $this->input->post('url', TRUE) ?: ($input['url'] ?? null);
        $label = $this->input->post('label', TRUE) ?: ($input['label'] ?? null);

        if ($url && !$this->is_valid_url($url)) {
            echo json_encode(['error' => 'Invalid LinkedIn URL']); return;
        }

        if (!$this->Profile_model->get_linkedin_by_id($id, $user_id)) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'LinkedIn entry not found']); return;
        }

        $this->Profile_model->update_linkedin($id, $user_id, array_filter(['url' => $url, 'label' => $label], function($v){return $v !== null;}));
        echo json_encode(['message' => 'LinkedIn profile updated']);
    }

    /**
     * Delete LinkedIn Profile
     * 
     * Removes a LinkedIn profile entry.
     * 
     * @param int $id LinkedIn profile record ID
     * @return void Outputs JSON response with message or error
     */
    public function delete_linkedin($id) {
        $user_id = $this->require_auth();
        if (!$this->Profile_model->get_linkedin_by_id($id, $user_id)) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'LinkedIn entry not found']); return;
        }
        $this->Profile_model->delete_linkedin($id, $user_id);
        echo json_encode(['message' => 'LinkedIn profile removed']);
    }
}
