<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Profile_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);

        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');

        // Get user profile data
        $personal_info = $this->Profile_model->get_personal_info($user_id);
        if (!$personal_info) {
            $personal_info = (object) [
                'full_name' => '',
                'biography' => '',
                'profile_image_url' => ''
            ];
        }
        $data['personal_info'] = $personal_info;
        $data['linkedin_profiles'] = $this->Profile_model->get_linkedin($user_id);

        // Get all profile sections
        $data['degrees'] = $this->Profile_model->list_records('user_degrees', $user_id);
        $data['certifications'] = $this->Profile_model->list_records('user_certifications', $user_id);
        $data['licenses'] = $this->Profile_model->list_records('user_licenses', $user_id);
        $data['short_courses'] = $this->Profile_model->list_records('user_short_courses', $user_id);
        $data['employment_history'] = $this->Profile_model->list_records('user_employment_history', $user_id);

        // Get alumni info if exists
        $this->load->model('Alumni_model');
        $alumni_info = $this->db->get_where('alumni_info', ['user_id' => $user_id])->row();
        $data['alumni_info'] = $alumni_info;

        $this->load->view('profile/index', $data);
    }

    public function edit()
    {
        $user_id = $this->session->userdata('user_id');

        // Get existing data
        $personal_info = $this->Profile_model->get_personal_info($user_id);
        if (!$personal_info) {
            $personal_info = (object) [
                'full_name' => '',
                'biography' => '',
                'profile_image_url' => ''
            ];
        }
        $data['personal_info'] = $personal_info;
        $data['linkedin_profiles'] = $this->Profile_model->get_linkedin($user_id);

        // Get all profile sections
        $data['degrees'] = $this->Profile_model->list_records('user_degrees', $user_id);
        $data['certifications'] = $this->Profile_model->list_records('user_certifications', $user_id);
        $data['licenses'] = $this->Profile_model->list_records('user_licenses', $user_id);
        $data['short_courses'] = $this->Profile_model->list_records('user_short_courses', $user_id);
        $data['employment_history'] = $this->Profile_model->list_records('user_employment_history', $user_id);

        // Get alumni info
        $this->load->model('Alumni_model');
        $alumni_info = $this->db->get_where('alumni_info', ['user_id' => $user_id])->row();
        $data['alumni_info'] = $alumni_info;

        $this->load->view('profile/edit', $data);
    }

    public function update()
    {
        $user_id = $this->session->userdata('user_id');

        // Handle profile image upload
        if (!empty($_FILES['profile_image']['name'])) {
            $this->load->library('upload');
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

            $this->upload->initialize($config);

            if ($this->upload->do_upload('profile_image')) {
                $file_info = $this->upload->data();
                $image_url = base_url('uploads/profile_images/' . $file_info['file_name']);

                // Update personal info with new image
                $current = $this->Profile_model->get_personal_info($user_id);
                $personal_data = array_merge((array)$current, ['profile_image_url' => $image_url]);
                $this->Profile_model->save_personal_info($user_id, $personal_data);
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                redirect('profile/edit');
            }
        }

        // Update personal info
        $personal_data = [
            'full_name' => $this->input->post('full_name'),
            'biography' => $this->input->post('biography')
        ];

        $current = $this->Profile_model->get_personal_info($user_id);
        $this->Profile_model->save_personal_info($user_id, array_merge((array)$current, $personal_data));

        // Update alumni info
        $alumni_data = [
            'programme' => $this->input->post('programme'),
            'graduation_year' => $this->input->post('graduation_year'),
            'industry_sector' => $this->input->post('industry_sector'),
            'current_location' => $this->input->post('current_location')
        ];

        $this->load->model('Alumni_model');
        $this->Alumni_model->save_alumni_info($user_id, $alumni_data);

        $this->session->set_flashdata('success', 'Profile updated successfully!');
        redirect('profile');
    }

    public function add_linkedin()
    {
        $user_id = $this->session->userdata('user_id');

        $data = [
            'url' => $this->input->post('url'),
            'label' => $this->input->post('label')
        ];

        $this->Profile_model->save_linkedin($user_id, $data);

        $this->session->set_flashdata('success', 'LinkedIn profile added!');
        redirect('profile/edit');
    }

    public function delete_linkedin($id)
    {
        $user_id = $this->session->userdata('user_id');

        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $this->db->delete('user_linkedin_profiles');

        $this->session->set_flashdata('success', 'LinkedIn profile removed!');
        redirect('profile/edit');
    }

    // ========================================================================
    // PROFILE SECTIONS MANAGEMENT
    // ========================================================================

    private $sectionTables = [
        'degrees' => 'user_degrees',
        'certifications' => 'user_certifications',
        'licenses' => 'user_licenses',
        'short_courses' => 'user_short_courses',
        'employment_history' => 'user_employment_history',
    ];

    public function add_section($section)
    {
        $user_id = $this->session->userdata('user_id');

        if (!isset($this->sectionTables[$section])) {
            $this->session->set_flashdata('error', 'Unknown section');
            redirect('profile');
        }

        $data = [];

        switch ($section) {
            case 'degrees':
                $data = [
                    'institution' => $this->input->post('institution'),
                    'degree' => $this->input->post('degree'),
                    'field' => $this->input->post('field'),
                    'degree_url' => $this->input->post('degree_url'),
                    'completion_date' => $this->input->post('completion_date'),
                ];
                if (!$data['institution'] || !$data['degree']) {
                    $this->session->set_flashdata('error', 'Institution and degree are required');
                    redirect('profile');
                }
                break;

            case 'certifications':
                $data = [
                    'title' => $this->input->post('title'),
                    'provider' => $this->input->post('provider'),
                    'cert_url' => $this->input->post('cert_url'),
                    'completion_date' => $this->input->post('completion_date'),
                ];
                if (!$data['title']) {
                    $this->session->set_flashdata('error', 'Title is required');
                    redirect('profile');
                }
                break;

            case 'licenses':
                $data = [
                    'title' => $this->input->post('title'),
                    'issuer' => $this->input->post('issuer'),
                    'license_url' => $this->input->post('license_url'),
                    'completion_date' => $this->input->post('completion_date'),
                ];
                if (!$data['title']) {
                    $this->session->set_flashdata('error', 'Title is required');
                    redirect('profile');
                }
                break;

            case 'short_courses':
                $data = [
                    'title' => $this->input->post('title'),
                    'provider' => $this->input->post('provider'),
                    'course_url' => $this->input->post('course_url'),
                    'completion_date' => $this->input->post('completion_date'),
                ];
                if (!$data['title']) {
                    $this->session->set_flashdata('error', 'Title is required');
                    redirect('profile');
                }
                break;

            case 'employment_history':
                $data = [
                    'company' => $this->input->post('company'),
                    'role' => $this->input->post('role'),
                    'start_date' => $this->input->post('start_date'),
                    'end_date' => $this->input->post('end_date'),
                    'description' => $this->input->post('description'),
                ];
                if (!$data['company'] || !$data['role'] || !$data['start_date']) {
                    $this->session->set_flashdata('error', 'Company, role, and start date are required');
                    redirect('profile');
                }
                break;
        }

        $this->Profile_model->create_record($this->sectionTables[$section], $user_id, $data);
        $this->session->set_flashdata('success', ucfirst(str_replace('_', ' ', $section)) . ' added successfully!');
        redirect('profile');
    }

    public function update_section($section)
    {
        $user_id = $this->session->userdata('user_id');

        if (!isset($this->sectionTables[$section])) {
            $this->session->set_flashdata('error', 'Unknown section');
            redirect('profile');
        }

        $id = $this->input->post('id');
        if (!$id) {
            $this->session->set_flashdata('error', 'ID is required');
            redirect('profile');
        }

        $record = $this->Profile_model->get_record($this->sectionTables[$section], $id, $user_id);
        if (!$record) {
            $this->session->set_flashdata('error', 'Record not found');
            redirect('profile');
        }

        $data = [];
        foreach ((array)$record as $key => $value) {
            if ($key === 'id' || $key === 'user_id' || $key === 'created_at') continue;
            $data[$key] = $this->input->post($key, TRUE) ?: $value;
        }

        $this->Profile_model->update_record($this->sectionTables[$section], $id, $user_id, $data);
        $this->session->set_flashdata('success', ucfirst(str_replace('_', ' ', $section)) . ' updated successfully!');
        redirect('profile');
    }

    public function delete_section($section)
    {
        $user_id = $this->session->userdata('user_id');

        if (!isset($this->sectionTables[$section])) {
            $this->session->set_flashdata('error', 'Unknown section');
            redirect('profile');
        }

        $id = $this->input->get('id');
        if (!$id) {
            $this->session->set_flashdata('error', 'ID is required');
            redirect('profile');
        }

        $record = $this->Profile_model->get_record($this->sectionTables[$section], $id, $user_id);
        if (!$record) {
            $this->session->set_flashdata('error', 'Record not found');
            redirect('profile');
        }

        $this->Profile_model->delete_record($this->sectionTables[$section], $id, $user_id);
        $this->session->set_flashdata('success', ucfirst(str_replace('_', ' ', $section)) . ' deleted successfully!');
        redirect('profile');
    }
}