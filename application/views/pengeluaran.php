<?php $role = $this->session->userdata('role'); ?>
<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pengeluaran</title>
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/select2/css/select2.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') ?>">
  <?php $this->load->view('partials/head'); ?>
  <style>
    @media(max-width: 576px){
      .nota{
        justify-content: center !important;
        text-align: center !important;
      }
    }
  </style>
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
            <h1 class="m-0 text-dark">Transaksi Keluar</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <?php if ($role != 'admin'){ ?>
            <button class="btn btn-success" data-toggle="modal" data-target="#modal" onclick="add()"><i class="fa fa-plus"></i> Tambah</button>
            <a href="<?php echo base_url('pengeluaran_jenis'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> Jenis</a>
            <?php } else { ?>
              &nbsp;
            <?php } ?>
          </div>
          <div class="card-body">
            <table class="table w-100 table-bordered table-hover" id="pengeluaran">
              <thead>
                <tr>
                  <th style="display: none;">ID</th>
                  <th>ID Pengeluaran</th>
                  <th>Tanggal</th>
                  <th>Jenis</th>
                  <th>Deskripsi</th>
                  <th>Total</th>
                  <th>Aksi</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

</div>

<div class="modal fade" id="modal">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <h5 class="modal-title">Tambah Pengeluaran</h5>
    <button class="close" data-dismiss="modal">
      <span>&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <form id="form">
      <input type="hidden" name="id">
      <div class="form-group">
        <label>ID Pengeluaran</label>
        <input placeholder="ID Pengeluaran" type="text" class="form-control" name="nota" id="nota" required readonly>
      </div>
      <div class="form-group">
        <label>Jenis</label>
        <select class="form-control" placeholder="Jenis" name="jenis" id="jenis" required>
          <?php foreach($jenis as $key => $value){ ?>
            <option value="<?php echo $value['id']; ?>"><?php echo $value['jenis']; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label>Tanggal</label>
        <input type="text" class="form-control" name="tanggal" id="tanggal" required>
      </div>
      <div class="form-group">
        <label>Jumlah</label>
        <input placeholder="Jumlah" type="number" class="form-control" name="jumlah" required>
      </div>
      <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" placeholder="Deskripsi" required></textarea>
      </div>
      <button class="btn btn-success" type="submit">Simpan</button>
      <button class="btn btn-danger" data-dismiss="modal">Keluar</button>
    </form>
  </div>
</div>
</div>
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
  var addUrl = '<?php echo site_url('transaksi/pengeluaran_add') ?>';
  var deleteUrl = '<?php echo site_url('transaksi/pengeluaran_delete') ?>';
  var editUrl = '<?php echo site_url('transaksi/pengeluaran_edit') ?>';
  var getDataUrl = '<?php echo site_url('transaksi/get_pengeluaran') ?>';
  var readUrl = '<?php echo site_url('transaksi/pengeluaran_read') ?>';
</script>
<script src="<?php echo base_url('assets/js/unminify/pengeluaran.js') ?>"></script>
</body>
</html>
