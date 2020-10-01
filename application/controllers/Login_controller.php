<?php


class Login_controller extends CI_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->model('Login_model');


    }

    public function login_validation()
    {

        $json_data = $this->input->raw_input_stream;
var_dump($json_data);
        if (empty($json_data) && !is_null( $this->input->post_get("email"))) {
            $username = $this->input->post_get("email");
            $password = $this->input->post_get("password");
            $response["formate"] = 2;
        } else {
            $aa = json_decode($json_data);
            $username = $aa->email;
            $password = $aa->password;
            $response["formate"] = 1;
        }




        if (!is_null($username) && !is_null($password)) {

            $resultData = $this->Login_model->login($username, $password);
            if ($resultData["status"] == 200) {
                $this->session->user_session = $resultData["body"];


                $response["status"] = 200;
                $response["body"] = $resultData["body"];
            } else if ($resultData["status"] == 202) {
                $response["status"] = 202;
                $response["body"] = $resultData["body"];
            } else if ($resultData["status"] == 201) {
                $response["status"] = 202;
                $response["body"] = $resultData["body"];
            } else {
                $response = $resultData;
            }
        } else {
            $response["status"] = 200;
            $response["body"] = "Invalid Parameter";
        }
        echo json_encode($response);
    }

    function logout()
    {

        session_destroy();
        redirect("login");
    }
}