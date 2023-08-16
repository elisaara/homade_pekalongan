<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <div style="font-size: 20px;padding:.25rem 1rem;">
        <?php echo $this->session->userdata['breadcrumb'] == 'dashboard' ? 'Selamat Datang, '.strtoupper($this->session->userdata['username']) : $this->session->userdata['breadcrumb']; ?></div>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a href="#" class="nav-link" data-toggle="dropdown">
        <?php echo strtoupper($this->session->userdata['username']); ?> <i class="far fa-user"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
        <a href="<?php echo site_url('auth/logout') ?>" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </div>
    </li>
  </ul>
 
</nav>
<!-- /.navbar -->