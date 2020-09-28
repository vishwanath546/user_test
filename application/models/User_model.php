<?php

require_once 'DatatableModel.php';
class User_model extends DatatableModel
{
    function __construct() {
        // Set table name
        $this->table = 'user_master_data';
        // Set orderable column fields
        $this->column_order = array('first_name', 'user_id', 'created_on');
        // Set searchable column fields
        $this->column_search = array('first_name', 'user_id');
        // Set default order
        $this->order = array('f_name' => 'asc');
        $this->where = "";
        date_default_timezone_set('Asia/Kolkata');
    }

    public function generation_user_id() {


        $user_id = 'user_' . rand(10, 1000);
        $this->db->select('user_id');
        $this->db->from($this->table);
        $this->db->where('user_id', $user_id);
        $this->db->get();
        if ($this->db->affected_rows() > 0) {
            return $this->generation_user_id();
        } else {
            return $user_id;
        }

    }

    function is_mobile_available($mobile_no) {
        try {
            return $this->db->where('mobile_no', $mobile_no)->get($this->table)->row();
        } catch (Exception $exc) {
            log_message('error', $exc->getMessage());
            return null;
        }
    }

    function delete_user($user_id,$data){
        try {
            $this->db->trans_start();
                $this->db->where('user_id',$user_id);
                $this->db->update($this->table, $data);


            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                log_message('info', "insert user Transaction Rollback");
                $result = FALSE;
            } else {
                $this->db->trans_commit();
                log_message('info', "insert user Transaction Commited");
                $result = TRUE;
            }
            $this->db->trans_complete();
        } catch (Exception $exc) {
            log_message('error', $exc->getMessage());
            $result = FALSE;
        }
        return $result;
    }
    function is_email_available($email) {
        try {
            return $this->db->where('email', $email)->get($this->table)->row();
        } catch (Exception $exc) {
            log_message('error', $exc->getMessage());
            return null;
        }
    }

    public function insert_user($data,$update_user_id) {
        try {
            $this->db->trans_start();
            if($update_user_id=='0'){
                $this->db->insert($this->table, $data);
            } else {
                $this->db->where('user_id',$update_user_id);
                $this->db->update($this->table, $data);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                log_message('info', "insert user Transaction Rollback");
                $result = FALSE;
            } else {
                $this->db->trans_commit();
                log_message('info', "insert user Transaction Commited");
                $result = TRUE;
            }
            $this->db->trans_complete();
        } catch (Exception $exc) {
            log_message('error', $exc->getMessage());
            $result = FALSE;
        }
        return $result;
    }



    function get_user_data_by_id($user_id){
        try {

            $this->db->select('*');
            $this->db->from($this->table);
            $this->db->where('user_id',$user_id);
           return $this->db->get()->row();
        } catch (Exception $exc) {
            log_message('error', $exc->getMessage());
            return null;
        }
    }

}