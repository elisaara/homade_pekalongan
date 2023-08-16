<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {	
	public function index()
	{
		$this->session->set_userdata('breadcrumb','dashboard');
		if ($this->session->userdata('status') == 'login' ) {
			$this->load->view('dashboard');
		} else {
			$this->load->view('login');
		}
	}
}
?>