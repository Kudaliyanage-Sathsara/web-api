<?php
class Api_key_model extends CI_Model {

    public function validate_key($key, $required_permissions = [])
    {
        $api_key = $this->db->get_where('api_keys', ['key_value' => $key, 'is_active' => 1])->row();
        if (!$api_key) {
            return false;
        }

        $permissions = json_decode($api_key->permissions, true);
        if (!empty($required_permissions)) {
            foreach ($required_permissions as $perm) {
                if (!in_array($perm, $permissions)) {
                    return false;
                }
            }
        }

        // Log usage
        $this->log_usage($api_key->id);

        return $api_key;
    }

    public function log_usage($api_key_id)
    {
        $data = [
            'api_key_id' => $api_key_id,
            'endpoint' => $this->uri->uri_string(),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent()
        ];
        $this->db->insert('api_key_usage', $data);
    }

    public function get_keys()
    {
        return $this->db->get('api_keys')->result();
    }

    public function create_key($data)
    {
        $data['key_value'] = bin2hex(random_bytes(32)); // Generate secure random key
        $data['permissions'] = json_encode($data['permissions']);
        return $this->db->insert('api_keys', $data);
    }
}