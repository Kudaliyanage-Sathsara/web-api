<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Alumni_model');
        $this->load->library('session');
        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data['user_email'] = $this->session->userdata('email');
        $this->load->view('dashboard/index', $data);
    }

    public function graphs()
    {
        $data['user_email'] = $this->session->userdata('email');
        $this->load->view('dashboard/graphs', $data);
    }

    public function alumni_list()
    {
        $filters = [];
        if ($this->input->get('programme')) $filters['programme'] = $this->input->get('programme');
        if ($this->input->get('graduation_year')) $filters['graduation_year'] = $this->input->get('graduation_year');
        if ($this->input->get('industry_sector')) $filters['industry_sector'] = $this->input->get('industry_sector');

        $data['alumni'] = $this->Alumni_model->get_alumni($filters);
        $data['filters'] = $filters;
        $data['user_email'] = $this->session->userdata('email');

        $this->load->view('dashboard/alumni_list', $data);
    }

    public function alumni_export_csv()
    {
        $filters = [];
        if ($this->input->get('programme')) $filters['programme'] = $this->input->get('programme');
        if ($this->input->get('graduation_year')) $filters['graduation_year'] = $this->input->get('graduation_year');
        if ($this->input->get('industry_sector')) $filters['industry_sector'] = $this->input->get('industry_sector');

        $alumni = $this->Alumni_model->get_alumni($filters);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="alumni_export.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Full Name', 'Email', 'Programme', 'Graduation Year', 'Industry Sector', 'Current Location']);

        foreach ($alumni as $alum) {
            fputcsv($output, [
                $alum->id,
                $alum->full_name,
                $alum->email,
                $alum->programme,
                $alum->graduation_year,
                $alum->industry_sector,
                $alum->current_location
            ]);
        }

        fclose($output);
        exit;
    }

    public function analysis()
    {
        $data['user_email'] = $this->session->userdata('email');
        $data['analysis'] = $this->Alumni_model->get_analysis_data();
        $this->load->view('dashboard/analysis', $data);
    }
}