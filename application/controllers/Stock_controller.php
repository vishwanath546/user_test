<?php
require_once APPPATH . "/PHPExcel/PHPExcel.php";

class Stock_controller extends CI_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->model('Stock_model');


    }


    public function add_stock()
    {  $model_no = $this->input->post_get("model_no");
        $Category = $this->input->post_get("Category");
        $p_mrp = $this->input->post_get("p_mrp");
        $total = $this->input->post_get("total");
        $update_stock_id = $this->input->post_get("update_stock_id");
        $stock_id = $this->Stock_model->generation_stock_id();

        if (!is_null($model_no) && !is_null($Category) && !is_null($p_mrp)) {
            $data=array();
            $des_path = "Stock_images";
            $this->load->model('Global_model');
            $result = $this->Global_model->upload_file($des_path); //upload_multiple_file_new

            if ($result["status"] == 200) {
                if (count($result["body"]) > 0) {

                        $des_path = $result["body"];


                } else {
                    $des_path = "";
                }
            }

  $data=array(
    'model_no'=>$model_no,
    'category'=>$Category,
    'mrp'=>$p_mrp,
    'created_by'=>'u_101',
    'created_on'=>date('Y-m-d h:i:s'),
      'total'=>$total,
      'image'=>$des_path
);
            if($update_stock_id=='0'){
                $data['stock_id']=$stock_id;

            }
            $final_result=$this->Stock_model->insert_stock($data,$update_stock_id);
            if($final_result==TRUE){
                $response["status"] = true;
                $response["body"] = "Successfully Stock added";
            } else {
                $response["status"] = false;
                $response["body"] = "Failed to add Stock";
            }

        } else {
            $response["status"] = false;
            $response["body"] = "Invalid Parameter";
        }
        echo json_encode($response);
    }


    function fetch_stock_data(){
        if(!is_null($this->input->post_get("stock_id"))){
            $stock_id = $this->input->post_get("stock_id");
            $result=$this->Stock_model->get_stock_data_by_id($stock_id);
            if(!is_null($result)){
                $response["status"] = true;
                $response["body"] = $result;
            } else {
                $response["status"] = false;
                $response["body"] = $result;
            }
        } else {
            $response["status"] = false;
            $response["body"] = $result;
        }
        echo json_encode($response);
    }

    function load_stock_table() {


        $this->db->select("*");
        $this->db->where(array('created_by'=>'u_101','status'=>1));
        $this->db->order_by("id", " Desc");
        $memData1 = $this->db->get("stock_master_data")->result();
// $this->db->last_query();

        $data = $row = array();
        $column_search = array('category');
        $i = 0;
        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search = $this->input->post("search");
        $search = $search['value'];
        $col = 0;
        $dir = "";
        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }

        if ($dir != "asc" && $dir != "desc") {
            $dir = "desc";
        }
        $valid_columns = array(
            0 => 'category',
            1 => "created_on"
        );
        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];
        }
        if ($order != null) {
            $this->db->order_by($order, $dir);
        }


        if (!empty($search)) {
            $x = 0;
            foreach ($valid_columns as $sterm) {
                if ($x == 0) {
                    $this->db->like($sterm, $search);
                } else {
                    $this->db->or_like($sterm, $search);
                }
                $x++;
            }
        }


        $this->db->limit($length, $start);
        $this->db->select("*");
        $this->db->where(array('created_by'=>'u_101','status'=>1));
        $this->db->order_by("id", " Desc");
        $memData = $this->db->get("stock_master_data")->result();
//echo $this->db->last_query();
        $data = array();
        foreach ($memData as $index=>$item) {

                $data[] = array(
                   $item->model_no,
                    $item->category,
                    $item->mrp,
                    $item->stock_id,
                    );

        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($memData),
            "recordsFiltered" => count($memData1),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function add_stock_file(){
        $file = $_FILES["file_data"]["tmp_name"];
        $this->load->library('excel');
//read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);

//get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

//extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

            //The header will/should be in row 1 only. of course, this can be modified to suit your need.
            if ($row == 1) {
                $header[$row][$column] = $data_value;
            } else {
                $arr_data[$row][$column] = $data_value;
            }
        }
        $data=array();
        foreach ($arr_data as $row1) {

            $data[]=array(
                'stock_id'=>$this->Stock_model->generation_stock_id(),
                'model_no'=>$row1['C'],
                'category'=>$row1['B'],
                'mrp'=>$row1['D'],
                'created_by'=>'u_101',
                'created_on'=>date('Y-m-d h:i:s'),
                'total'=>$row1['E']

            );
        }
        $result=$this->Stock_model->insert_stock_by_excel($data);
        if($result==TRUE){
            $response["status"] = true;
            $response["body"] = 'Successfully Stock added';
        } else {
            $response["status"] = false;
            $response["body"] = 'Failed to add Stock';
        }

echo json_encode($response);
    }

}