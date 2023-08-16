<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login' ) {
			redirect('/');
		}
		$this->load->model('transaksi_model');
		$this->load->model('pengeluaran_jenis_model');
		$this->role = $this->session->userdata('role');
	}

	public function index()
	{
		$this->session->set_userdata('breadcrumb','Pemasukan');
		$this->load->view('transaksi');
	}

	public function pemasukan()
	{
		$id_transaksi = $this->input->get('id');
		$data['id_transaksi'] = $id_transaksi;
		$this->session->set_userdata('breadcrumb','Info Pesanan');
		$this->load->view('pemasukan',$data);
	}

	public function generate_pemasukan()
	{
		$id_transaksi = $this->input->post('id');
		$data['id_transaksi'] = $id_transaksi;
		$params=array('id'=>$id_transaksi,'status'=>0);
		$data['data'] = array('nota'=>null,'detail'=>array());
		if ($this->transaksi_model->read($params)->num_rows() > 0) {
			foreach ($this->transaksi_model->read($params)->result() as $transaksi) {
				$data['data']['nota'] = $transaksi->nota;
				$barcode = explode(',', $transaksi->barcode);
				$tanggal = new DateTime($transaksi->tanggal);
				$productlist = $this->transaksi_model->getProduk($barcode, $transaksi->qty);
				foreach($productlist as $k => $v){
					$data['data']['detail'][] = array(
						'tanggal' => $tanggal->format('d-m-Y H:i:s'),
						'barcode' => $v->barcode,
						'harga' => $v->harga,
						'qty' => $v->qty,
						'harga_total' => $v->harga*$v->qty,
						'id_produk' => $v->id,
						'nama_produk' => $v->nama_produk,
						'total_bayar' => $transaksi->total_bayar,
						'jumlah_uang' => $transaksi->jumlah_uang,
						'diskon' => $transaksi->diskon,
						'nota' => $transaksi->nota,
						'status' => $transaksi->status,
						'status_desc' => $transaksi->status ? 'Sudah Bayar' : 'Belum Bayar',
						'pelanggan' => $transaksi->pelanggan
					);
				}
			}
		}
		echo json_encode($data);
	}

	public function pengeluaran()
	{
		$data['jenis'] = $this->get_jenis();
		$this->session->set_userdata('breadcrumb','Pengeluaran');
		$this->load->view('pengeluaran',$data);
	}

	public function read()
	{
		// header('Content-type: application/json');
		if ($this->transaksi_model->read()->num_rows() > 0) {
			foreach ($this->transaksi_model->read()->result() as $transaksi) {
				$tid = $transaksi->id;
				$pr=$this->persetujuan_model->read(array('id'=>$tid, 'transaction'=>'Pemasukan', 'status'=>0))->row_array();
				#echo '<pre>';print_r($pr);
				$barcode = explode(',', $transaksi->barcode);
				$tanggal = new DateTime($transaksi->tanggal);
				$data[] = array(
					'id' => $transaksi->id,
					'tanggal' => $tanggal->format('d-m-Y H:i:s'),
					#'nama_produk' => '<table>'.$this->transaksi_model->getProdukDetail($barcode, $transaksi->qty).'</table>',
					'total_bayar' => $transaksi->total_bayar,
					'jumlah_uang' => $transaksi->jumlah_uang,
					'diskon' => $transaksi->diskon,
					'nota' => $transaksi->nota,
					'status' => $transaksi->status,
					'status_desc' => $transaksi->status ? 'Sudah Bayar' : 'Belum Bayar',
					'pelanggan' => $transaksi->pelanggan,
					'action' => $this->role != 'admin' ? (isset($pr['status']) && $pr['status'] == 0 ? 'Dalam Persetujuan' : ($transaksi->status ? '<a class="btn btn-sm btn-primary" target="_blank" href="'.site_url('transaksi/cetak/').$transaksi->id.'"><i class="fa fa-print"></i></a>' : '<a class="btn btn-sm btn-success" href="'.base_url('transaksi/pemasukan?id=').$transaksi->id.'"><i class="fa fa-edit"></i></a> <button class="btn btn-sm btn-danger" onclick="remove('.$transaksi->id.')"><i class="fa fa-trash"></i></button>')) : ''
				);
			}
			#die();
		} else {
			$data = array();
		}
		$transaksi = array(
			'data' => $data
		);
		echo json_encode($transaksi);
	}

	public function pengeluaran_read()
	{
		// header('Content-type: application/json');
		if ($this->transaksi_model->pengeluaran_read()->num_rows() > 0) {
			foreach ($this->transaksi_model->pengeluaran_read()->result() as $transaksi) {
				$tid = $transaksi->id;
				$pr=$this->persetujuan_model->read(array('id'=>$tid, 'transaction'=>'Pengeluaran', 'status'=>0))->row_array();
				#echo '<pre>';print_r($pr);
				$tanggal = new DateTime($transaksi->tanggal);
				$data[] = array(
					'id' => $transaksi->id,
					'jenis' => $transaksi->jenis,
					'nama_jenis' => $transaksi->nama_jenis,
					'tanggal' => $tanggal->format('d-m-Y H:i:s'),
					'jumlah' => $transaksi->jumlah,
					'nota' => $transaksi->nota,
					'deskripsi' => $transaksi->deskripsi,
					'action' => $this->role != 'admin' ? (isset($pr['status']) && $pr['status'] == 0 ? 'Dalam Persetujuan' : ('<button class="btn btn-sm btn-success" onclick="edit('.$transaksi->id.')"><i class="fa fa-edit"></i></button> <button class="btn btn-sm btn-danger" onclick="remove('.$transaksi->id.')"><i class="fa fa-trash"></i></button>')) : ''
				);
			}
			#die();
		} else {
			$data = array();
		}
		$transaksi = array(
			'data' => $data
		);
		echo json_encode($transaksi);
	}

	public function get_pengeluaran()
	{
		header('Content-type: application/json');
		$id = $this->input->post('id');
		$pengeluaran = $this->transaksi_model->get_pengeluaran($id);
		if ($pengeluaran->row()) {
			echo json_encode($pengeluaran->row());
		}
	}

	public function add()
	{
		$id = $this->input->post('id');
		$bayar = $this->input->post('bayar');
		$flag = $this->input->post('flag');
		$produk = $this->input->post('produk');
		$q = $this->input->post('qty');
		#print_r($this->input->post());die();
		$barcode = array();
		foreach ($produk as $kp => $produk) {
			#$this->transaksi_model->removeStok($produk->id, $produk->stok);
			if($flag == 1){
				$this->transaksi_model->addTerjual($produk['id'], $q[$kp]);
			}
			array_push($barcode, $produk['id']);
		}
		if($flag == 1){
			$tanggal = new DateTime($this->input->post('tanggal'));
			$tanggal = $tanggal->format('Y-m-d H:i:s');
			$jumlah_uang = $this->input->post('jumlah_uang');
			$diskon = $this->input->post('diskon');
			$pelanggan = $this->input->post('pelanggan');	
		}else{
			$tanggal = date('Y-m-d H:i:s');
			$jumlah_uang = $diskon = 0;
			$pelanggan = 'N/A';
		}
		$data = array(
			'tanggal' => date('Y-m-d H:i:s', strtotime($tanggal)),
			'barcode' => implode(',', $barcode),
			'qty' => implode(',', $this->input->post('qty')),
			'total_bayar' => $this->input->post('total_bayar'),
			'jumlah_uang' => $jumlah_uang,
			'diskon' => $diskon,
			'pelanggan' => $pelanggan,
			'nota' => $this->input->post('nota'),
			'kasir' => $this->session->userdata('id'),
			'status' => $flag
		);
		#print_r($data);die();
		if(empty($id)){
			if ($this->transaksi_model->create($data)) {
				echo json_encode($this->db->insert_id());
			}
		}
		else{
			if($bayar==1){
				if ($this->transaksi_model->update($id,$data)) {
					echo json_encode($id);
				}
			}
			else{
				$data_persetujuan = array(
					'table_id' => $id
					, 'action' => 'update'
					, 'table_name' => 'transaksi'
					, 'transaction' => 'Pemasukan'
					, 'table_data' => array(
						'before' => $this->transaksi_model->read(array('id'=>$id))->row_array()
						, 'after' => $data
						, 'script' => $data
					)
				);
				$bfr = $this->transaksi_model->read(array('id'=>$id))->row();
				$brcd = explode(',', $bfr->barcode);
				$nama_produk_bfr = $this->transaksi_model->getProdukDetail($brcd, $bfr->qty);
				$data_persetujuan['table_data']['before']['nama_produk'] = $nama_produk_bfr;

				$brcd_aft = explode(',', $data['barcode']);
				$nama_produk_aft = $this->transaksi_model->getProdukDetail($brcd_aft, $data['qty']);
				$data_persetujuan['table_data']['after']['nama_produk'] = $nama_produk_aft;
				if ($this->persetujuan_model->create($data_persetujuan)) {
					echo json_encode($this->db->insert_id());
				}
			}
		}
		$data = $this->input->post('form');
	}

	public function pengeluaran_add()
	{
		$tanggal = new DateTime($this->input->post('tanggal'));
		$data = array(
			'nota' => $this->input->post('nota'),
			'tanggal' => $tanggal->format('Y-m-d H:i:s'),
			'jenis' => $this->input->post('jenis'),
			'jumlah' => $this->input->post('jumlah'),
			'deskripsi' => $this->input->post('deskripsi')
		);
		if ($this->transaksi_model->create_pengeluaran($data)) {
			echo json_encode($this->db->insert_id());
		}
		$data = $this->input->post('form');
	}

	public function pengeluaran_edit()
	{
		$id = $this->input->post('id');
		$tanggal = new DateTime($this->input->post('tanggal'));
		$data = array(
			'nota' => $this->input->post('nota'),
			'tanggal' => $tanggal->format('Y-m-d H:i:s'),
			'jenis' => $this->input->post('jenis'),
			'jumlah' => $this->input->post('jumlah'),
			'deskripsi' => $this->input->post('deskripsi'),
		);
		/*
		if ($this->transaksi_model->update_pengeluaran($id,$data)) {
			echo json_encode('sukses');
		}*/


		$data_persetujuan = array(
			'table_id' => $id
			, 'action' => 'update'
			, 'table_name' => 'pengeluaran'
			, 'transaction' => 'Pengeluaran'
			, 'table_data' => array(
				'before' => $this->transaksi_model->pengeluaran_read(array('id'=>$id))->row_array()
				, 'after' => $data
				, 'script' => $data
			)
		);
		$data_persetujuan['table_data']['after']['nama_jenis'] = $this->pengeluaran_jenis_model->getJenis($this->input->post('jenis'))->row()->jenis;
		if ($this->persetujuan_model->create($data_persetujuan)) {
			echo json_encode('sukses');
		}
	}

	public function delete()
	{
		$id = $this->input->post('id');
		/*
		if ($this->transaksi_model->delete($id)) {
			echo json_encode('sukses');
		}*/

		$data_persetujuan = array(
			'table_id' => $id
			, 'action' => 'delete'
			, 'table_name' => 'transaksi'
			, 'transaction' => 'Pemasukan'
			, 'table_data' => array(
				'before' => array() 
				, 'after' => 
					array (
						'id' => $id
					)
				, 'script' => 
					array (
						'id' => $id
					)
			)
		);
		if ($this->persetujuan_model->create($data_persetujuan)) {
			echo json_encode($this->db->insert_id());
		}
	}

	public function pengeluaran_delete()
	{
		$id = $this->input->post('id');
		/*
		if ($this->transaksi_model->pengeluaran_delete($id)) {
			echo json_encode('sukses');
		}*/
		$data_persetujuan = array(
			'table_id' => $id
			, 'action' => 'delete'
			, 'table_name' => 'pengeluaran'
			, 'transaction' => 'Pengeluaran'
			, 'table_data' => array(
				'before' => array() 
				, 'after' => 
					array (
						'id' => $id
					)
				, 'script' => 
					array (
						'id' => $id
					)
			)
		);
		if ($this->persetujuan_model->create($data_persetujuan)) {
			echo json_encode($this->db->insert_id());
		}
	}

	public function cetak($id)
	{
		$produk = $this->transaksi_model->getAll($id);
		
		$tanggal = new DateTime($produk->tanggal);
		$barcode = explode(',', $produk->barcode);
		$qty = explode(',', $produk->qty);

		$produk->tanggal = $tanggal->format('d m Y H:i:s');

		$dataProduk = $this->transaksi_model->getName($barcode);
		foreach ($dataProduk as $key => $value) {
			$value->total = $qty[$key];
			$value->harga = $value->harga * $qty[$key];
		}

		$data = array(
			'nota' => $produk->nota,
			'tanggal' => $produk->tanggal,
			'produk' => $dataProduk,
			'total' => $produk->total_bayar,
			'bayar' => $produk->jumlah_uang,
			'kembalian' => $produk->jumlah_uang - $produk->total_bayar,
			'kasir' => $produk->kasir
		);
		$this->load->view('cetak', $data);
	}

	public function penjualan_bulan()
	{
		header('Content-type: application/json');
		$day = date('Y-m-d');
		$yesterday = date('Y-m-d', strtotime('-1 day', strtotime($day)));
		$latest_week = date('Y-m-d', strtotime('-1 week', strtotime($day)));
		$i = 7;
		$data = array();
		#echo '<pre>'; print_r($yesterday);print_r($latest_week);die();
		while($latest_week<=$day){
			$data['label'][] = $i == 0 ? 'hari ini' : $i.' hari lalu';
			if ($qty = $this->transaksi_model->penjualanBulan($latest_week) !== []) {
				$data['data'][] = array_sum($this->transaksi_model->penjualanBulan($latest_week));
			} else {
				$data['data'][] = 0;
			}
			$latest_week = date('Y-m-d', strtotime('1 day', strtotime($latest_week)));
			$i--;
		}
		#echo '<pre>'; print_r($data);die();
		echo json_encode($data);
	}

	public function transaksi_hari()
	{
		header('Content-type: application/json');
		$now = date('d m Y');
		$total = $this->transaksi_model->transaksiHari($now);
		echo json_encode($total);
	}

	public function pengeluaran_hari()
	{
		header('Content-type: application/json');
		$now = date('d m Y');
		$total = $this->transaksi_model->pengeluaranHari($now);
		echo json_encode($total);
	}

	public function sisa_uang()
	{
		header('Content-type: application/json');
		$now = date('d m Y');
		$total = $this->transaksi_model->sisaUang($now);
		echo json_encode($total);
	}

	public function perbandingan()
	{
		header('Content-type: application/json');
		#$total = $this->transaksi_model->sisaUang();
		$now = date('d m Y');
		$result = $this->transaksi_model->perbandingan($now);
		echo json_encode($result);
	}

	public function transaksi_terakhir($value='')
	{
		header('Content-type: application/json');
		$now = date('d m Y');
		foreach ($this->transaksi_model->transaksiTerakhir($now) as $key) {
			$total = explode(',', $key);
		}
		echo json_encode($total);
	}

	public function get_jenis()
	{
		$jenis = $this->pengeluaran_jenis_model->read();
		return $jenis->result_array();
	}

}

/* End of file Transaksi.php */
/* Location: ./application/controllers/Transaksi.php */