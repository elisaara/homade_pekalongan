<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model {

	private $table = 'transaksi';
	private $pengeluaran_table = 'pengeluaran';

	public function removeStok($id, $stok)
	{
		$this->db->where('id', $id);
		$this->db->set('stok', $stok);
		return $this->db->update('produk');
	}

	public function addTerjual($id, $jumlah)
	{
		$this->db->select('terjual');
		$this->db->where('id', $id);
		$terjual = $this->db->get('produk')->row();
		$t = isset($terjual->terjual) ? $terjual->terjual : 0;

		$this->db->where('id', $id);
		$this->db->set('terjual', $t + $jumlah);
		return $this->db->update('produk');;
	}

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function update($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table, $data);
	}

	public function create_pengeluaran($data)
	{
		return $this->db->insert($this->pengeluaran_table, $data);
	}

	public function update_pengeluaran($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->pengeluaran_table, $data);
	}

	public function read($params = array())
	{
		$this->db->select('transaksi.id, transaksi.nota, transaksi.status, transaksi.tanggal, transaksi.barcode, transaksi.qty, transaksi.total_bayar, transaksi.jumlah_uang, transaksi.diskon, pelanggan.nama as pelanggan');
		$this->db->from($this->table);
		$this->db->join('pelanggan', 'transaksi.pelanggan = pelanggan.id', 'left outer');
		if(isset($params['bayar'])){
			$this->db->where('transaksi.pelanggan > ', '0');
		}
		if(isset($params['id'])){
			$this->db->where('transaksi.id', $params['id']);
		}
		if(isset($params['status'])){
			$this->db->where('transaksi.status', $params['status']);
		}
		if(isset($params['tanggal'])){
			$this->db->where('cast(transaksi.tanggal as date) = ', $params['tanggal']);
		}
		if(isset($params['bulan'])){
			$ym = explode('-',$params['bulan']);
			$this->db->where('year(transaksi.tanggal) = ', $ym[0]);
			$this->db->where('month(transaksi.tanggal) = ', $ym[1]);
		}
		if(isset($params['start']) && isset($params['end'])){
			$this->db->where('cast(transaksi.tanggal as date) >= ', $params['start']);
			$this->db->where('cast(transaksi.tanggal as date) <= ', $params['end']);
		}
		#print_r($params);die();
		return $this->db->get();
	}

	public function pengeluaran_read($params = array())
	{
		$this->db->select('pengeluaran.*, jenis_pengeluaran.jenis nama_jenis');
		$this->db->from($this->pengeluaran_table);
		$this->db->join('jenis_pengeluaran', 'pengeluaran.jenis = jenis_pengeluaran.id');
		if(isset($params['id'])){
			$this->db->where('pengeluaran.id', $params['id']);
		}
		if(isset($params['tanggal'])){
			$this->db->where('cast(pengeluaran.tanggal as date) = ', $params['tanggal']);
		}
		if(isset($params['bulan'])){
			$ym = explode('-',$params['bulan']);
			$this->db->where('year(pengeluaran.tanggal) = ', $ym[0]);
			$this->db->where('month(pengeluaran.tanggal) = ', $ym[1]);
		}
		if(isset($params['start']) && isset($params['end'])){
			$this->db->where('cast(pengeluaran.tanggal as date) >= ', $params['start']);
			$this->db->where('cast(pengeluaran.tanggal as date) <= ', $params['end']);
		}
		return $this->db->get();
	}

	public function get_pengeluaran($id)
	{
		$this->db->select('pengeluaran.*, jenis_pengeluaran.jenis nama_jenis');
		$this->db->from($this->pengeluaran_table);
		$this->db->join('jenis_pengeluaran', 'pengeluaran.jenis = jenis_pengeluaran.id');
		$this->db->where('pengeluaran.id', $id);
		return $this->db->get();
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}

	public function pengeluaran_delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->pengeluaran_table);
	}

	public function getProduk($barcode, $qty)
	{
		$total = explode(',', $qty);
		foreach ($barcode as $key => $value) {
			$this->db->select('*, '.$total[$key].' qty');
			$this->db->where('id', $value);
			$data[] = $this->db->get('produk')->row();
		}
		return $data;
	}

	public function getProdukDetail($barcode, $qty)
	{
		$total = explode(',', $qty);
		foreach ($barcode as $key => $value) {
			$this->db->select('nama_produk');
			$this->db->where('id', $value);
			$data[] = $key > 0 ? '<br>'.$this->db->get('produk')->row()->nama_produk.' ('.$total[$key].')' : $this->db->get('produk')->row()->nama_produk.' ('.$total[$key].')';
		}
		return join($data);
	}


	public function penjualanBulan($date)
	{
		#print_r("SELECT qty FROM transaksi WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$date'");
		$qty = $this->db->query("SELECT total_bayar qty FROM transaksi WHERE pelanggan>0 and cast(tanggal as date) = '$date'")->result();
		$d = [];
		$data = [];
		foreach ($qty as $key) {
			$d[] = explode(',', $key->qty);
		}
		foreach ($d as $key) {
			$data[] = array_sum($key);
		}
		return $data;
	}

	public function transaksiHari($hari)
	{
		$latest_week = date('d m Y', strtotime('-1 week', strtotime(date('Y-m-d'))));
		#echo "SELECT sum(total_bayar) total FROM transaksi WHERE DATE_FORMAT(tanggal, '%d %m %Y') between '$hari' and '$latest_week'";die();
		$today = $this->db->query("SELECT sum(total_bayar) total FROM transaksi WHERE pelanggan>0 and DATE_FORMAT(tanggal, '%d %m %Y') = '$hari'")->row();
		$week = $this->db->query("SELECT sum(total_bayar) total FROM transaksi WHERE pelanggan>0 and DATE_FORMAT(tanggal, '%d %m %Y') between '$latest_week' and '$hari'")->row();
		return array(
			'today' => 'Rp.'.number_format($today->total,2,',','.')
			, 'week' =>'Rp.'.number_format($week->total,2,',','.')
		);
	}

	public function pengeluaranHari($hari)
	{
		$latest_week = date('d m Y', strtotime('-1 week', strtotime(date('Y-m-d'))));
		$today = $this->db->query("SELECT sum(jumlah) total FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$hari'")->row();
		$week = $this->db->query("SELECT sum(jumlah) total FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%d %m %Y') between '$latest_week' and '$hari'")->row();
		return array(
			'today' => 'Rp.'.number_format($today->total,2,',','.')
			, 'week' =>'Rp.'.number_format($week->total,2,',','.')
		);
	}

	public function sisaUang($hari)
	{
		$m = $this->db->query("SELECT sum(total_bayar) jumlah FROM transaksi WHERE pelanggan>0 and DATE_FORMAT(tanggal, '%d %m %Y') = '$hari'")->row();
		$k = $this->db->query("SELECT sum(jumlah) jumlah FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$hari'")->row();
		$s = $m->jumlah-$k->jumlah;
		return array(
			'sisa' => 'Rp.'.number_format($s,2,',','.')
			, 'persen' =>number_format((($s/$m->jumlah)*100),2,',','')
		);
	}

	public function perbandingan($hari)
	{
		$m = $this->db->query("SELECT sum(total_bayar) jumlah FROM transaksi WHERE pelanggan>0 and DATE_FORMAT(tanggal, '%d %m %Y') = '$hari'")->row();
		$k = $this->db->query("SELECT sum(jumlah) jumlah FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$hari'")->row();
		$sisa = $m->jumlah-$k->jumlah;
		$label = array('Pemasukan','Pengeluaran','Sisa');
		$data = array($m->jumlah,$k->jumlah,$sisa);
		return $result = array(
			'label' => $label,
			'data' => $data,
		);
	}

	public function transaksiTerakhir($hari)
	{
		return $this->db->query("SELECT transaksi.qty FROM transaksi WHERE pelanggan>0 and DATE_FORMAT(tanggal, '%d %m %Y') = '$hari' LIMIT 1")->row();
	}

	public function getAll($id)
	{
		$this->db->select('transaksi.nota, transaksi.tanggal, transaksi.barcode, transaksi.qty, transaksi.total_bayar, transaksi.jumlah_uang, pengguna.nama as kasir');
		$this->db->from('transaksi');
		$this->db->join('pengguna', 'transaksi.kasir = pengguna.id');
		$this->db->where('transaksi.id', $id);
		return $this->db->get()->row();
	}

	public function getName($barcode)
	{
		foreach ($barcode as $b) {
			$this->db->select('nama_produk, harga');
			$this->db->where('id', $b);
			$data[] = $this->db->get('produk')->row();
		}
		return $data;
	}

}

/* End of file Transaksi_model.php */
/* Location: ./application/models/Transaksi_model.php */