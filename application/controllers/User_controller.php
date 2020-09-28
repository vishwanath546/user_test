<?php
require_once APPPATH . "/PHPExcel/PHPExcel.php";

class User_controller extends CI_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->model('User_model');


    }


    public function add_user()
    {  $mobile_no = $this->input->post_get("mobile_no");
        $f_name = $this->input->post_get("f_name");
        $l_name = $this->input->post_get("l_name");
        $email = $this->input->post_get("email_id");
        $address = $this->input->post_get("address");
        $role_type = $this->input->post_get("role_type");
        $update_user_id = $this->input->post_get("update_user_id");
        $user_id = $this->User_model->generation_user_id();

        if (!is_null($mobile_no) && !is_null($f_name) && !is_null($l_name) && !is_null($email)  && !is_null($address)) {
            $data=array();


  $data=array(
    'mobile_no'=>$mobile_no,
    'first_name'=>$f_name,
      'last_name'=>$l_name,
      'email'=>$email,
    'address'=>$address,
    'role_type'=>$role_type,
    'password'=>rand(10,1000),
    'created_by'=>'u_101',
    'created_on'=>date('Y-m-d'),
      'status'=>1

);
            if($update_user_id=='0'){
                $data['user_id']=$user_id;

            }

            $final_result=$this->User_model->insert_user($data,$update_user_id);
            if($final_result==TRUE){
                $response["status"] = true;
                $response["body"] = "Successfully User added";
            } else {
                $response["status"] = false;
                $response["body"] = "Failed to add User";
            }

        } else {
            $response["status"] = false;
            $response["body"] = "Invalid Parameter";
        }
        echo json_encode($response);
    }

    /*
 * check whether email is aleady exist or not
 */

    function email_validation()
    {
        if (!is_null($this->input->post_get("email"))) {
            if (!is_null($this->User_model->is_email_available($this->input->post_get("email")))) {

                echo json_encode(false);
            } else {

                echo json_encode(true);
            }
        } else {
            echo json_encode(false);
        }
    }
    /*
 * check whether mobile is aleady exist or not
 */

    function mobile_validation()
    {
        if (!is_null($this->input->post_get("mobile_no"))) {
            if (!is_null($this->User_model->is_mobile_available($this->input->post_get("mobile_no")))) {

                echo json_encode(false);
            } else {

                echo json_encode(true);
            }
        } else {
            echo json_encode(false);
        }
    }
    function delete_user_data(){
        if (!is_null($this->input->post_get("user_id"))) {
            $data=array(
                'status'=>0
            );
            $result=$this->User_model->delete_user($this->input->post_get("user_id"),$data);
            if($result===TRUE){
                $response["status"] = true;
                $response["body"] = 'Successfully user deleted ';
            } else {
                $response["status"] = false;
                $response["body"] = 'Failed to delete user';
            }
        } else {
            $response["status"] = false;
            $response["body"] ='Invalid Parameter';

        }
        echo json_encode($response);
    }
    function fetch_user_data(){
        $result=array();
        if(!is_null($this->input->post_get("user_id"))){
            $user_id = $this->input->post_get("user_id");
            $result=$this->User_model->get_user_data_by_id($user_id);
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

    function load_user_table() {

$role_type=$this->input->post_get('role_type');
        $start_date=$this->input->post_get('start_date');
        $end_date=$this->input->post_get('end_date');
        $user_id=$this->session->user_session->user_id;
        $array=array('created_by' => $user_id, 'status' => 1);
if($role_type!=''){
    $array = array('created_by' => $user_id, 'status' => 1, 'role_type' => $role_type);

} elseif ($start_date!='' ||  $end_date!=''){
    $array = "created_by='$user_id'  AND status=1 AND  created_on between '$start_date' and '$end_date' ";

}

        $this->db->select("*");
        $this->db->where($array);
        $this->db->order_by("id", " Desc");
        $memData1 = $this->db->get("user_master_data")->result();
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
            0 => 'first_name',
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
        $this->db->where($array);
        $this->db->order_by("id", " Desc");
        $memData = $this->db->get("user_master_data")->result();
//echo $this->db->last_query();
        $data = array();
        foreach ($memData as $index=>$item) {

                $data[] = array(
                   $item->first_name.' '. $item->last_name,
                    $item->email,
                    $item->mobile_no,
                    $item->role_type,
                    $item->created_on,
                     $item->user_id,
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



}