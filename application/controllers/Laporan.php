<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Laporan extends CI_Controller {
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
    $this->session->set_userdata('breadcrumb','Laporan Transaksi');
    if ($this->session->userdata('status') !== 'login' ) {
      redirect('/');
    }
    $this->load->view('laporan');
  }
  public function rugi_laba()
  {
    $this->session->set_userdata('breadcrumb','Laporan Rugi Laba');
    if ($this->session->userdata('status') !== 'login' ) {
      redirect('/');
    }
    $this->load->view('rugi_laba');
  }
  public function get_laporan()
  {
    $post = $this->input->post();
    $laporan = $post['laporan'];
    $tanggal = isset($post['tanggal']) ? date('Y-m-d', strtotime($post['tanggal'])) : null;
    $bulan = isset($post['bulan']) ? date('Y-m-d', strtotime($post['bulan'])) : null;
    $start = isset($post['start']) ? date('Y-m-d', strtotime($post['start'])) : null;
    $end = isset($post['end']) ? date('Y-m-d', strtotime($post['end'])) : null;
    $params = array(
      'laporan' => $laporan
      , 'tanggal' => $tanggal
      , 'bulan' => $bulan
      , 'start' => $start
      , 'end' => $end
      , 'bayar' => 1
    );
    $data['jenis'] = $this->pengeluaran_jenis_model->read()->result_array();
    $data['pemasukan'] = $this->transaksi_model->read($params)->result_array();
    $data['pengeluaran'] = $this->transaksi_model->pengeluaran_read($params)->result_array();
    $report = $laporan == 'rugi_laba' ? 'report_rugi_laba' : 'report_transaksi';
    $this->load->view($report, $data);
  }
}
?>
/* End of file Laporan.php */
/* Location: ./application/controllers/Laporan.php */