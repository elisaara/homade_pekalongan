<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengguna extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login' ) {
			redirect('/');
		}
		$this->load->model('pengguna_model');
	}

	public function index()
	{
		$this->session->set_userdata('breadcrumb','Pengguna');
		$data['level'] = $this->get_level();
		$this->load->view('pengguna', $data);
	}

	public function get_level($id=null)
	{
		$levels = array(
			array('id' => 1, 'text'=> 'Administrator'),
			array('id' => 2, 'text'=> 'User')
		);
		if(empty($id)){
			return $levels;
		}
		else{
			return $levels[$id-1]['text'];
		}
	}

	public function read()
	{
		header('Content-type: application/json');
		if ($this->pengguna_model->read()->num_rows() > 0) {
			foreach ($this->pengguna_model->read()->result() as $pengguna) {
				$data[] = array(
					'username' => $pengguna->username,
					'password' => $pengguna->password,
					'nama' => $pengguna->nama,
					'email' => $pengguna->email,
					'telepon' => $pengguna->telepon,
					'role' => $this->get_level($pengguna->role),
					'action' => '<button class="btn btn-sm btn-success" onclick="edit('.$pengguna->id.')"><i class="fa fa-edit"></i></button> <button class="btn btn-sm btn-danger" onclick="remove('.$pengguna->id.')"><i class="fa fa-trash"></i></button>'
				);
			}
		} else {
			$data = array();
		}
		$pengguna = array(
			'data' => $data
		);
		#print_r($pengguna);die();
		echo json_encode($pengguna);
	}

	public function add()
	{
		$data = array(
			'username' => $this->input->post('username'),
			'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
			'nama' => $this->input->post('nama'),
			'email' => $this->input->post('email'),
			'telepon' => $this->input->post('telepon'),
			'role' => $this->input->post('level')
		);
		if ($this->pengguna_model->create($data)) {
			echo json_encode('sukses');
		}
	}

	public function delete()
	{
		$id = $this->input->post('id');
		if ($this->pengguna_model->delete($id)) {
			echo json_encode('sukses');
		}
	}

	public function edit()
	{
		$id = $this->input->post('id');
		$data = array(
			'username' => $this->input->post('username'),
			'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
			'nama' => $this->input->post('nama'),
			'email' => $this->input->post('email'),
			'telepon' => $this->input->post('telepon'),
			'role' => $this->input->post('level')
		);
		if ($this->pengguna_model->update($id,$data)) {
			echo json_encode('sukses');
		}
	}

	public function get_pengguna()
	{
		$id = $this->input->post('id');
		$pengguna = $this->pengguna_model->getPengguna($id);
		if ($pengguna->row()) {
			echo json_encode($pengguna->row());
		}
	}

	public function allPengguna()
	{
		$pengguna = $this->pengguna_model->read();
		echo json_encode(count($pengguna->result_array()));
	}

}

/* End of file Pengguna.php */
/* Location: ./application/controllers/Pengguna.php */