<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dist extends CI_Controller {

	public function index() {
        $data = array(
            'title' => "Login"
        );
       // $this->load->view('dist/auth-login', $data);
        session_destroy();
        redirect('login');
	}

	public function components_table() {
	    if(!is_null($this->session->user_session)){
            $data = array(
                'title' => "Components &rsaquo; Table"
            );
            $this->load->view('dist/components-table', $data);
        } else {
            redirect('logout');
        }

	}




	public function auth_login() {
		$data = array(
			'title' => "Login"
		);
		$this->load->view('dist/auth-login', $data);
	}











}
