<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Alumni API Controller
 *
 * Handles alumni data retrieval with API key authentication and permissions.
 *
 * @property CI_Input $input
 * @property CI_DB_query_builder $db
 * @property Alumni_model $Alumni_model
 * @property Api_key_model $Api_key_model
 */
class Alumni extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Alumni_model');
        $this->load->model('Api_key_model');
    }

    protected function getJsonInput()
    {
        static $json;
        if ($json !== null) {
            return $json;
        }
        $raw = trim(file_get_contents('php://input') ?? '');
        $json = $raw ? json_decode($raw, true) : [];
        return is_array($json) ? $json : [];
    }

    private function require_api_key($permissions = [])
    {
        $api_key = $this->input->get_request_header('X-API-Key');
        if (!$api_key) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['error' => 'API key required']);
            exit;
        }

        $key_data = $this->Api_key_model->validate_key($api_key, $permissions);
        if (!$key_data) {
            header('HTTP/1.1 403 Forbidden');
            echo json_encode(['error' => 'Invalid API key or insufficient permissions']);
            exit;
        }
        return $key_data;
    }

    /**
     * Get alumni list with optional filters
     * Requires read:alumni permission
     */
    public function index()
    {
        $this->require_api_key(['read:alumni']);

        $filters = [];
        $programme = $this->input->get('programme');
        $graduation_year = $this->input->get('graduation_year');
        $industry_sector = $this->input->get('industry_sector');

        if ($programme) $filters['programme'] = $programme;
        if ($graduation_year) $filters['graduation_year'] = $graduation_year;
        if ($industry_sector) $filters['industry_sector'] = $industry_sector;

        $alumni = $this->Alumni_model->get_alumni($filters);

        echo json_encode(['data' => $alumni]);
    }

    /**
     * Get alumni statistics for dashboard
     * Requires read:analytics permission
     */
    public function stats()
    {
        $this->require_api_key(['read:analytics']);

        $stats = $this->Alumni_model->get_alumni_stats();

        echo json_encode(['data' => $stats]);
    }

    /**
     * Export alumni data to CSV
     * Requires read:alumni permission
     */
    public function export_csv()
    {
        $this->require_api_key(['read:alumni']);

        $filters = [];
        $programme = $this->input->get('programme');
        $graduation_year = $this->input->get('graduation_year');
        $industry_sector = $this->input->get('industry_sector');

        if ($programme) $filters['programme'] = $programme;
        if ($graduation_year) $filters['graduation_year'] = $graduation_year;
        if ($industry_sector) $filters['industry_sector'] = $industry_sector;

        $alumni = $this->Alumni_model->get_alumni($filters);

        // Generate CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="alumni_export.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Email', 'Full Name', 'Programme', 'Graduation Year', 'Industry Sector', 'Current Location']);

        foreach ($alumni as $alum) {
            fputcsv($output, [
                $alum->id,
                $alum->email,
                $alum->full_name,
                $alum->programme,
                $alum->graduation_year,
                $alum->industry_sector,
                $alum->current_location
            ]);
        }

        fclose($output);
    }

    /**
     * Debug endpoint to check database counts
     */
    public function debug_stats()
    {
        $this->require_api_key(['read:analytics']);

        // Count total users
        $total_users = $this->db->select('COUNT(*) as count')
            ->from('users')
            ->get()->row()->count;

        $verified_users = $this->db->select('COUNT(*) as count')
            ->from('users')
            ->where('is_verified', 1)
            ->get()->row()->count;

        // Count degrees
        $degrees = $this->db->select('COUNT(DISTINCT degree) as count')
            ->from('user_degrees')
            ->get()->row()->count;

        // Get all degrees
        $all_degrees = $this->db->select('degree, COUNT(DISTINCT user_id) as count')
            ->from('user_degrees')
            ->group_by('degree')
            ->get()->result();

        echo json_encode([
            'debug' => [
                'total_users' => $total_users,
                'verified_users' => $verified_users,
                'unique_degrees' => $degrees,
                'degrees_breakdown' => $all_degrees
            ]
        ]);
    }
}