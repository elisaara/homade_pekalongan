<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <?php $this->load->view('partials/head'); ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php $this->load->view('includes/nav'); ?>

  <?php $this->load->view('includes/aside'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-md-10">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-md-2">
            <a href="<?php echo base_url('laporan/rugi_laba'); ?>" type="button" class="btn btn-primary btn-block btn-md"><i class="fa fa-file"></i> Laporan Laba Rugi</a>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>


    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-success">
              <div class="inner">
                <p><h5>PENDAPATAN (HARI INI)</h5></p>
                <h3 id="transaksi_hari">0</h3>
              </div>
              <div class="icon">
                <i class="fas fa-briefcase"></i>
              </div>
              <div class="small-box-footer">Mingguan : <span id="transaksi_hari_week"></span></div>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <p><h5>PENGELUARAN (HARI INI)</h5></p>
                <h3 id="pengeluaran_hari">0</h3>
              </div>
              <div class="icon">
                <i class="fas fa-dollar-sign"></i>
              </div>
              <div class="small-box-footer">Mingguan : <span id="pengeluaran_hari_week"></span></div>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-info">
              <div class="inner">
                <p><h5>SISA UANG</h5></p>
                <h3 id="sisa_uang">0</h3>
              </div>
              <div class="icon">
                <i class="fas fa-clipboard-list"></i>
              </div>
              <div class="small-box-footer" id="sisa_uang_persen">%%</div>
            </div>
          </div>
          <?php  if ($this->session->userdata('role') === 'admin'){ ?>
          <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <p><h5>PENGGUNA</h5></p>
                <h3 id="karyawan">0</h3>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
              <div class="small-box-footer">&nbsp;</div>
            </div>
          </div>
          <?php } ?>
          <div class="col-md-8">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">PENDAPATAN (MINGGU INI)</h3> 
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="bulanIni" style="min-height: 250px; height: 350px; max-height: 350px; max-width: 100%"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">PERBANDINGAN (HARI INI)</h3>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="perbandingan" style="min-height: 250px; height: 350px; max-height: 350px; max-width: 100%"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<?php $this->load->view('includes/footer'); ?>

<?php $this->load->view('partials/footer'); ?>
<script src="<?php echo base_url('assets/vendor/adminlte/plugins/chart.js/Chart.min.js') ?>"></script>
<script>
  var transaksi_hariUrl = '<?php echo site_url('transaksi/transaksi_hari') ?>';
  var pengeluaran_hariUrl = '<?php echo site_url('transaksi/pengeluaran_hari') ?>';
  var sisa_uangUrl = '<?php echo site_url('transaksi/sisa_uang') ?>';
  var perbandinganUrl = '<?php echo site_url('transaksi/perbandingan') ?>';
  var karyawanUrl = '<?php echo site_url('pengguna/allPengguna') ?>';
  var transaksi_terakhirUrl = '<?php echo site_url('transaksi/transaksi_terakhir') ?>';
  var stok_hariUrl = '<?php echo site_url('stok_masuk/stok_hari') ?>';
  var produk_terlarisUrl = '<?php echo site_url('produk/produk_terlaris') ?>';
  var data_stokUrl = '<?php echo site_url('produk/data_stok') ?>';
  var penjualan_bulanUrl = '<?php echo site_url('transaksi/penjualan_bulan') ?>';
</script>
<script src="<?php echo base_url('assets/js/unminify/dashboard.js') ?>"></script>
</body>
</html>
