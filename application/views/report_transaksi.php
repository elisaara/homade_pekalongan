<div class="col-md-6">
  <div class="card card-success">
    <div class="card-header">
    <h3 class="card-title">Pemasukan</h3>
    </div>
    <div class="card-body">
      <table class="table w-100 table-bordered table-hover" id="table_in_<?php echo time(); ?>">
        <thead>
          <tr>
            <th>No</th>
            <th>ID Transaksi</th>
            <th>Tanggal</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1;$tot=0; ?>
          <?php foreach($pemasukan as $key => $value){ ?>
          <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $value['nota']; ?></td>
            <td><?php echo date('Y-m-d H:i:s', strtotime($value['tanggal'])); ?></td>
            <td><?php echo $value['total_bayar']; ?></td>
          </tr>
          <?php $no++;$tot=$tot+$value['total_bayar']; ?>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      <h3>Total Pemasukan : Rp.<?php echo number_format($tot,2,',','.') ?></h3>
    </div>

  </div>
</div>
<div class="col-md-6">
  <div class="card card-danger">
    <div class="card-header">
    <h3 class="card-title">Pengeluaran</h3>
    </div>
    <div class="card-body">
      <table class="table w-100 table-bordered table-hover" id="table_out_<?php echo time(); ?>">
        <thead>
          <tr>
            <th>No</th>
            <th>ID Transaksi</th>
            <th>Tanggal</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1;$tot=0; ?>
          <?php foreach($pengeluaran as $key => $value){ ?>
          <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $value['nota']; ?></td>
            <td><?php echo date('Y-m-d H:i:s', strtotime($value['tanggal'])); ?></td>
            <td><?php echo $value['jumlah']; ?></td>
          </tr>
          <?php $no++;$tot=$tot+$value['jumlah']; ?>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      <h3>Total Pengeluaran : Rp.<?php echo number_format($tot,2,',','.') ?></h3>
    </div>

  </div>
</div>