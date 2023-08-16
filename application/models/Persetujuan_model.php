<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persetujuan_model extends CI_Model {

	private $table = 'persetujuan';

	public function create($data)
	{
		$data['requested_by'] = $this->session->userdata('id');
		$data['table_data'] = json_encode($data['table_data']);
		return $this->db->insert($this->table, $data);
	}

	public function read($params = array())
	{
		#$this->db->where('role', '2');

		if(isset($params['id'])){
			$this->db->where('table_id', $params['id']);
		}
		if(isset($params['transaction'])){
			$this->db->where('transaction', $params['transaction']);
		}
		if(isset($params['status'])){
			$this->db->where('status', $params['status']);
		}
		return $this->db->get($this->table);
	}

	public function update($id, $data)
	{
		$data['approved_by'] = $this->session->userdata('id');
		$data['approved_date'] = date('Y-m-d H:i:s');
		$this->db->where('id', $id);
		return $this->db->update($this->table, $data);
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}

	public function getpersetujuan($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);
		return $this->db->get($this->table);
	}

	public function search($search="")
	{
		$this->db->like('kategori', $search);
		return $this->db->get($this->table)->result();
	}

}

/* End of file Persetujuan_model.php */
/* Location: ./application/models/Persetujuan_model.php */