<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengeluaran_jenis extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login' ) {
			redirect('/');
		}
		$this->load->model('pengeluaran_jenis_model');
	}

	public function index()
	{
		$this->session->set_userdata('breadcrumb','Pengeluaran');
		$this->load->view('pengeluaran_jenis');
	}

	public function read()
	{
		header('Content-type: application/json');
		if ($this->pengeluaran_jenis_model->read()->num_rows() > 0) {
			foreach ($this->pengeluaran_jenis_model->read()->result() as $pengeluaran_jenis) {
				$data[] = array(
					'jenis' => $pengeluaran_jenis->jenis,
					'action' => '<button class="btn btn-sm btn-success" onclick="edit('.$pengeluaran_jenis->id.')"><i class="fa fa-edit"></i></button> <button class="btn btn-sm btn-danger" onclick="remove('.$pengeluaran_jenis->id.')"><i class="fa fa-trash"></i></button>'
				);
			}
		} else {
			$data = array();
		}
		$pengeluaran_jenis = array(
			'data' => $data
		);
		echo json_encode($pengeluaran_jenis);
	}

	public function add()
	{
		$data = array(
			'jenis' => $this->input->post('jenis')
		);
		if ($this->pengeluaran_jenis_model->create($data)) {
			echo json_encode('sukses');
		}
	}

	public function delete()
	{
		$id = $this->input->post('id');
		if ($this->pengeluaran_jenis_model->delete($id)) {
			echo json_encode('sukses');
		}
	}

	public function edit()
	{
		$id = $this->input->post('id');
		$data = array(
			'jenis' => $this->input->post('jenis')
		);
		if ($this->pengeluaran_jenis_model->update($id,$data)) {
			echo json_encode('sukses');
		}
	}

	public function get_jenis()
	{
		$id = $this->input->post('id');
		$jenis = $this->pengeluaran_jenis_model->getJenis($id);
		if ($jenis->row()) {
			echo json_encode($jenis->row());
		}
	}

	public function search()
	{
		header('Content-type: application/json');
		$jenis = $this->input->post('jenis');
		$search = $this->pengeluaran_jenis_model->search($jenis);
		foreach ($search as $jenis) {
			$data[] = array(
				'id' => $jenis->id,
				'text' => $jenis->jenis
			);
		}
		echo json_encode($data);
	}

}

/* End of file Pengeluaran_jenis.php */
/* Location: ./application/controllers/Pengeluaran_jenis.php */