<?php
class Profile_model extends CI_Model {
    public function get_user_id() {
        return $this->session->userdata('user_id');
    }

    public function get_personal_info($user_id) {
        return $this->db->get_where('user_personal_infos', ['user_id' => $user_id])->row();
    }

    public function save_personal_info($user_id, $data) {
        $exists = $this->get_personal_info($user_id);
        if ($exists) {
            $this->db->where('user_id', $user_id);
            return $this->db->update('user_personal_infos', $data);
        }
        $data['user_id'] = $user_id;
        return $this->db->insert('user_personal_infos', $data);
    }

    public function save_linkedin($user_id, $data) {
        $data['user_id'] = $user_id;
        return $this->db->insert('user_linkedin_profiles', $data);
    }

    public function get_linkedin($user_id) {
        return $this->db->get_where('user_linkedin_profiles', ['user_id' => $user_id])->result();
    }

    public function get_linkedin_by_id($id, $user_id) {
        return $this->db->get_where('user_linkedin_profiles', ['id' => $id, 'user_id' => $user_id])->row();
    }

    public function update_linkedin($id, $user_id, $data) {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('user_linkedin_profiles', $data);
    }

    public function delete_linkedin($id, $user_id) {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->delete('user_linkedin_profiles');
    }

    public function create_record($table, $user_id, $data) {
        $data['user_id'] = $user_id;
        return $this->db->insert($table, $data);
    }

    public function list_records($table, $user_id) {
        return $this->db->get_where($table, ['user_id' => $user_id])->result();
    }

    public function get_record($table, $id, $user_id) {
        return $this->db->get_where($table, ['id' => $id, 'user_id' => $user_id])->row();
    }

    public function update_record($table, $id, $user_id, $data) {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->update($table, $data);
    }

    public function delete_record($table, $id, $user_id) {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        return $this->db->delete($table);
    }
}
