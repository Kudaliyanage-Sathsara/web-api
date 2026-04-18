<?php
class Token_model extends CI_Model {

    public function create_verification_token($user_id,$token)
    {
        $data = [
            "user_id"=>$user_id,
            "token"=>$token,
            "expires_at"=>date("Y-m-d H:i:s",strtotime("+1 day"))
        ];
        return $this->db->insert("email_verification_tokens",$data);
    }

    public function verify_token($token)
    {
        $this->db->where("token",$token);
        $this->db->where("used",0);
        $this->db->where("expires_at >",date("Y-m-d H:i:s"));
        return $this->db->get("email_verification_tokens")->row();
    }

    public function create_password_reset_token($user_id,$token)
    {
        $data = [
            "user_id"=>$user_id,
            "token"=>$token,
            "expires_at"=>date("Y-m-d H:i:s",strtotime("+1 hour"))
        ];
        return $this->db->insert("password_reset_tokens",$data);
    }

    public function verify_password_reset_token($token)
    {
        $this->db->where("token",$token);
        $this->db->where("used",0);
        $this->db->where("expires_at >",date("Y-m-d H:i:s"));
        return $this->db->get("password_reset_tokens")->row();
    }

    public function mark_password_reset_token_used($token_id)
    {
        return $this->db->where("id",$token_id)->update("password_reset_tokens",["used"=>1]);
    }
}