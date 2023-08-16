<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persetujuan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login' ) {
			redirect('/');
		}
		$this->load->model('persetujuan_model');
		$this->load->model('pengguna_model');
	}

	public function index()
	{
		$this->session->set_userdata('breadcrumb','Persetujuan');
		$this->load->view('persetujuan');
	}

	public function convert_array_to_table($array = array(),$f){
		#print_r($array);die();
		$html = '<div class="col-md-6">';
		$html .= $f == 0 ? '<div><b>Before</b></div>' : '<div><b>After</b></div>';
		foreach($array as $key=>$value){
			$html .= '<div>';
			$html .= '<span>'.$key.'</span>';
			$html .= ' : ';
			$html .= '<span>'.$value.'</span>';
			$html .= '</div>';
        }
        $html .= '</div>';
        #echo $html;
        return $html;
	}

	public function read()
	{
		header('Content-type: application/json');
		if ($this->persetujuan_model->read()->num_rows() > 0) {
			foreach ($this->persetujuan_model->read()->result() as $persetujuan) {
				$table_data = json_decode($persetujuan->table_data,true);
				$status = $persetujuan->status;
				$data[] = array(
					'id' => $persetujuan->id,
					'action' => $persetujuan->action,
					'transaction' => $persetujuan->transaction,
					'requested_by' => $this->pengguna_model->getPengguna($persetujuan->requested_by)->row()->nama,
					'requested_date' => date('d M Y H:i:s', strtotime($persetujuan->requested_date)),
					'table_data' => $persetujuan->action == 'delete' ? $this->convert_array_to_table($table_data['after'],1) : '<div class="row">'.$this->convert_array_to_table($table_data['before'],0).''.$this->convert_array_to_table($table_data['after'],1).'</div>',
					'approval' => $status == 0 ? ('<button class="btn btn-sm btn-success" onclick="update('.$persetujuan->id.',1)"><i class="fa fa-check"></i></button> <button class="btn btn-sm btn-danger" onclick="update('.$persetujuan->id.',2)"><i class="fa fa-exclamation-triangle"></i></button>') : ($status == 1 ? 'Approved' : 'Rejected')
				);
			}
		} else {
			$data = array();
		}
		$persetujuan = array(
			'data' => $data
		);
		echo json_encode($persetujuan);
	}

	public function add()
	{
		$data = array(
			'nama' => $this->input->post('nama'),
			'alamat' => $this->input->post('alamat'),
			'telepon' => $this->input->post('telepon'),
			'jenis_kelamin' => $this->input->post('jenis_kelamin')
		);
		if ($this->persetujuan_model->create($data)) {
			echo json_encode('sukses');
		}
	}

	public function delete()
	{
		$id = $this->input->post('id');
		if ($this->persetujuan_model->delete($id)) {
			echo json_encode('sukses');
		}
	}

	public function edit()
	{
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$data = array(
			'status' => $status
		);
		if($status == 1){
			#print_r($this->input->post());die();
			$persetujuan = $this->persetujuan_model->getpersetujuan($id)->row_array();
			$action = $persetujuan['action'];
			$table_name = $persetujuan['table_name'];
			$table_id = $persetujuan['table_id'];
			$table_data = json_decode($persetujuan['table_data'], true);
			$table_data = $table_data['script'];
			$mdl = 'transaksi_model';
			$this->load->model($mdl);
			switch($action){
				case 'update':
					$execute = $table_name == 'pengeluaran' ? $this->$mdl->update_pengeluaran($table_id,$table_data) : $execute = $this->$mdl->update($table_id,$table_data);
				break;
				case 'delete':
					$execute = $table_name == 'pengeluaran' ? $this->$mdl->pengeluaran_delete($table_id) : $this->$mdl->delete($table_id);
				break;
			}

			if ($execute && $this->persetujuan_model->update($id,$data)) {
				echo json_encode('sukses');
			}
		}
		else{
			#echo '2';print_r($this->input->post());die();
			if ($this->persetujuan_model->update($id,$data)) {
				echo json_encode('sukses');
			}
		}
	}

	public function get_persetujuan()
	{
		$id = $this->input->post('id');
		$persetujuan = $this->persetujuan_model->getSupplier($id);
		if ($persetujuan->row()) {
			echo json_encode($persetujuan->row());
		}
	}

	public function search()
	{
		header('Content-type: application/json');
		$persetujuan = $this->input->post('persetujuan');
		$search = $this->persetujuan_model->search($persetujuan);
		foreach ($search as $persetujuan) {
			$data[] = array(
				'id' => $persetujuan->id,
				'text' => $persetujuan->nama
			);
		}
		echo json_encode($data);
	}

}

/* End of file persetujuan.php */
/* Location: ./application/controllers/persetujuan.php */