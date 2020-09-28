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


	public function auth_forgot_password() {
		$data = array(
			'title' => "Forgot Password"
		);
		$this->load->view('dist/auth-forgot-password', $data);
	}

	public function auth_login() {
		$data = array(
			'title' => "Login"
		);
		$this->load->view('dist/auth-login', $data);
	}

	public function auth_register() {
		$data = array(
			'title' => "Register"
		);
		$this->load->view('dist/auth-register', $data);
	}

	public function auth_reset_password() {
		$data = array(
			'title' => "Reset Password"
		);
		$this->load->view('dist/auth-reset-password', $data);
	}









}
