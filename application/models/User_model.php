<?php
class User_model extends CI_Model {

    public function create_user($email,$password)
    {
        $data = [
            "email"=>$email,
            "password"=>password_hash($password,PASSWORD_BCRYPT),
            "is_verified"=>0
        ];
        return $this->db->insert("users",$data);
    }

    public function get_by_email($email)
    {
        return $this->db->get_where("users",["email"=>$email])->row();
    }

    public function verify_user($id)
    {
        $this->db->where("id",$id);
        return $this->db->update("users",["is_verified"=>1]);
    }
}