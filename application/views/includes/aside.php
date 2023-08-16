<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?php echo site_url('') ?>" class="brand-link">
    <i class="fa fa-home brand-image" style="opacity: .8;margin-top:10px;padding-left:5px;width: 33px; height:33px;"></i>
    <!--img src="<?php #echo base_url('assets/vendor/adminlte/') ?>dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"-->
    <span class="brand-text font-weight-light"><?php echo $this->session->userdata('toko')->nama ?></span>
  </a>
  <?php $uri = $this->uri->segment(1) ?>
  <?php $uri2 = $this->uri->segment(2) ?>
  <?php $role = $this->session->userdata('role'); ?>

  <!-- Sidebar -->
  <div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item user-panel pb-2">
          <a href="<?php echo site_url('dashboard') ?>" class="nav-link <?php echo $uri == 'dashboard' || $uri == '' ? 'active' : 'no' ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>        
        <li class="nav-header">TRANSAKSI</li>
        <li class="nav-item">
          <a href="<?php echo site_url('transaksi') ?>" class="nav-link <?php echo $uri == 'transaksi' && empty($uri2) ? 'active' : 'no' ?>">
            <i class="fas fa-plus nav-icon"></i>
            <p>Pemasukan</p>
          </a>
        </li>
        <li class="nav-item user-panel pb-2">
          <a href="<?php echo site_url('transaksi/pengeluaran') ?>" class="nav-link <?php echo $uri == 'transaksi' && $uri2 == 'pengeluaran' ? 'active' : 'no' ?>">
            <i class="fas fa-minus nav-icon"></i>
            <p>Pengeluaran</p>
          </a>
        </li>
        <li class="nav-header">PENGATURAN</li>
        <?php if ($role === 'admin'){ ?>
        <li class="nav-item">
          <a href="<?php echo site_url('pengguna') ?>" class="nav-link <?php echo $uri == 'pengguna' ? 'active' : 'no' ?>">
            <i class="fas fa-user nav-icon"></i>
            <p>Pengguna</p>
          </a>
        </li>
        <?php } ?>
        <li class="nav-item">
          <a href="<?php echo site_url('pelanggan') ?>" class="nav-link <?php echo $uri == 'pelanggan' ? 'active' : 'no' ?>">
            <i class="nav-icon fas fa-address-book"></i>
            <p>Pelanggan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo site_url('satuan_produk') ?>" class="nav-link <?php echo $uri == 'satuan_produk' ? 'active' : 'no' ?>">
            <i class="fas fa-tag nav-icon"></i>
            <p>Satuan Produk</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo site_url('kategori_produk') ?>" class="nav-link <?php echo $uri == 'kategori_produk' ? 'active' : 'no' ?>">
            <i class="fas fa-bookmark nav-icon"></i>
            <p>Jenis Produk</p>
          </a>
        </li>
        <li class="nav-item user-panel pb-2">
          <a href="<?php echo site_url('produk') ?>" class="nav-link <?php echo $uri == 'produk' ? 'active' : 'no' ?>">
            <i class="fas fa-briefcase nav-icon"></i>
            <p>Produk</p>
          </a>
        </li>
        <li class="nav-header">ADMINISTRASI</li>
        <li class="nav-item">
          <a href="<?php echo site_url('laporan') ?>" class="nav-link <?php echo $uri == 'laporan' && empty($uri2)  ? 'active' : 'no' ?>">
            <i class="fas fa-table nav-icon"></i>
            <p>Laporan Transaksi</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo site_url('laporan/rugi_laba') ?>" class="nav-link <?php echo $uri == 'laporan' && $uri2 == 'rugi_laba' ? 'active' : 'no' ?>">
            <i class="fas fa-table nav-icon"></i>
            <p>Laporan Rugi Laba</p>
          </a>
        </li>
        <?php if ($role === 'admin'){ ?>
        <li class="nav-item user-panel pb-2">
          <a href="<?php echo site_url('persetujuan') ?>" class="nav-link <?php echo $uri == 'persetujuan' ? 'active' : 'no' ?>">
            <i class="fas fa-check nav-icon"></i>
            <p>Persetujuan</p>
          </a>
        </li>
        <?php } ?>
       
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>