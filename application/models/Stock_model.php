<?php

require_once 'DatatableModel.php';
class Stock_model extends DatatableModel
{
    function __construct() {
        // Set table name
        $this->table = 'stock_master_data';
        // Set orderable column fields
        $this->column_order = array('category', 'stock_id', 'created_on');
        // Set searchable column fields
        $this->column_search = array('category', 'stock_id');
        // Set default order
        $this->order = array('category' => 'asc');
        $this->where = "";
        date_default_timezone_set('Asia/Kolkata');
    }

    public function generation_stock_id() {


        $stock_id = 'st_' . rand(10, 1000);
        $this->db->select('stock_id');
        $this->db->from($this->table);
        $this->db->where('stock_id', $stock_id);
        $this->db->get();
        if ($this->db->affected_rows() > 0) {
            return $this->generation_stock_id();
        } else {
            return $stock_id;
        }

    }

    public function insert_stock($data,$update_stock_id) {
        try {
            $this->db->trans_start();
            if($update_stock_id=='0'){
                $this->db->insert($this->table, $data);
            } else {
                $this->db->where('stock_id',$update_stock_id);
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

    public function insert_stock_by_excel($data) {
        try {
            $this->db->trans_start();

                $this->db->insert_batch($this->table, $data);


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

    function get_stock_data_by_id($stock_id){
        try {

            $this->db->select('*');
            $this->db->from($this->table);
            $this->db->where('stock_id',$stock_id);
           return $this->db->get()->row();
        } catch (Exception $exc) {
            log_message('error', $exc->getMessage());
            return null;
        }
    }

}