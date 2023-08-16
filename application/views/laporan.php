<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan</title>
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/select2/css/select2.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') ?>">
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
          <div class="col">
            <h1 class="m-0 text-dark">Laporan Transaksi</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="laporan_harian  " data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Harian</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="laporan_bulanan" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Bulanan</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="laporan_range" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Range Tanggal</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content" id="custom-tabs-four-tabContent">
              <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="laporan_harian">
                <div class="card">
                  <div class="card-header">
                    <h1 class="m-0 text-dark">Laporan Harian</h1>
                  </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label for="" class="col-md-1 col-form-label">Tanggal</label>
                      <div class="col-md-2">
                          <input type="text" class="form-control" name="tanggal" id="tanggal" required>
                      </div>
                      <div class="col-md-2">
                          <button class="btn btn-success" type="button" data-laporan='harian' onclick="getLaporan(this)"><i class="fa fa-search"></i> Lihat Laporan</button>
                      </div>
                    </div>
                    
                    <div class="row row-result row-harian">
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="laporan_bulanan">
                <div class="card">
                  <div class="card-header">
                    <h1 class="m-0 text-dark">Laporan Bulanan</h1>
                  </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label for="" class="col-md-1 col-form-label">Bulan</label>
                      <div class="col-md-2">
                        <select class="form-control" placeholder="Bulan" name="bulan" id="bulan" required>
                          <?php for ($i = 0; $i < 10; $i++) { ?>
                            <option value="<?php echo date('Y-m', strtotime("-$i month")); ?>"><?php echo date('Y-m', strtotime("-$i month")); ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-md-2">
                          <button class="btn btn-success" type="button" data-laporan='bulanan' onclick="getLaporan(this)"><i class="fa fa-search"></i> Lihat Laporan</button>
                      </div>
                    </div>
                    <div class="row row-result row-bulanan">
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="laporan_range">
                <div class="card">
                  <div class="card-header">
                    <h1 class="m-0 text-dark">Laporan Range Tanggal</h1>
                  </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label for="" class="col-md-1 col-form-label">Tanggal</label>
                      <div class="col-md-3">
                        <div class="input-group input-daterange">
                          <input type="text" class="form-control" id="start">
                        <div class="input-group-addon"></div>
                          <input type="text" class="form-control" id="end">
                        </div>
                      </div>
                      <div class="col-md-2">
                          <button class="btn btn-success" type="button" data-laporan='range' onclick="getLaporan(this)"><i class="fa fa-search"></i> Lihat Laporan</button>
                      </div>
                    </div>
                    
                    <div class="row row-result row-range">
                    </div>
                  </div>
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
<script src="<?php echo base_url('assets/vendor/adminlte/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/adminlte/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/adminlte/plugins/select2/js/select2.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/adminlte/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/adminlte/plugins/moment/moment.min.js') ?>"></script>
<script>
  var getLaporanUrl = '<?php echo site_url('laporan/get_laporan') ?>';
</script>
<script src="<?php echo base_url('assets/js/laporan.min.js') ?>"></script>
</body>
</html>
