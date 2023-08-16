<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengeluaran_jenis_model extends CI_Model {

	private $table = 'jenis_pengeluaran';

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function read()
	{
		return $this->db->get($this->table);
	}

	public function update($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table, $data);
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}

	public function getJenis($id)
	{
		$this->db->where('id', $id);
		return $this->db->get($this->table);
	}

	public function search($search="")
	{
		$this->db->like('jenis', $search);
		return $this->db->get($this->table)->result();
	}

}

/* End of file Pengeluaran_jenis_model.php */
/* Location: ./application/models/Pengeluaran_jenis_model.php */