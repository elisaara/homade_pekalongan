<?php 
  $table = array(
    'pendapatan' => array()
    , 'biaya' => array()
  );
  $totm = 0;
  foreach($pemasukan as $key => $value){ 
    $totm = $totm + $value['total_bayar'];
  }
  $table['pendapatan'] = array(
    array(
      'desc' => '<b>PENDAPATAN</b>'
      , 'id' => '4-000'
      , 'debet' => 0
      , 'kredit' => 0
    )
    , array(
      'desc' => '<span style="padding-left:5%;">Pendapatan dari Penjualan</span>'
      , 'id' => '4-100'
      , 'debet' => 0
      , 'kredit' => $totm
    )
    , array(
      'desc' => '<b>TOTAL PENDAPATAN</b>'
      , 'id' => null
      , 'debet' => 0
      , 'kredit' => $totm
    )
  );
  $table['biaya'][0] = array(
    'desc' => '<b>BIAYA</b>'
    , 'id' => '6-000'
    , 'debet' => 0
    , 'kredit' => 0
  );
  $totk = 0;
  $tp=[];
  foreach($pengeluaran as $key => $value){ 
    if(!isset($tp[$value['jenis']])){
      $tp[$value['jenis']] = array('debet' => 0);
    }
    $tp[$value['jenis']]['debet'] = $tp[$value['jenis']]['debet'] + $value['jumlah'];
    $totk = $totk + $value['jumlah'];
  }
  foreach($jenis as $key => $value){ 
    $table['biaya'][$value['id']] = array(
      'desc' => '<span style="padding-left:5%;">Biaya '.$value['jenis'].'</span>'
      , 'id' => '6-'.$value['id'].'00'
      , 'debet' => (isset($tp[$value['id']]['debet']) ? $tp[$value['id']]['debet'] : 0)
      , 'kredit' => 0
    );
  }
  $table['biaya'][$value['jenis']+1] = array(
    'desc' => '<b>TOTAL BIAYA</b>'
      , 'id' => null
    , 'debet' => $totk
    , 'kredit' => 0
  );
  $table['biaya'][$value['jenis']+2] = array(
    'desc' => '<b>LABA/RUGI</b>'
      , 'id' => null
    , 'debet' => 0
    , 'kredit' => $totm-$totk
  );
?>

<div class="col-md-12">
  <div class="card card-success">
    <div class="card-header">
    <h3 class="card-title">Laporan Rugi laba</h3>
    </div>
    <div class="card-body">
      <table class="table w-100 table-bordered table-hover" id="table_in_<?php echo time(); ?>">
        <thead>
          <tr>
            <th class="text-center">No. Rek</th>
            <th class="text-center">Keterangan</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; ?>
          <?php foreach($table as $k => $v){ ?>
          <?php foreach($v as $key => $value){ ?>
          <tr>
            <td align="center"><?php echo $value['id']; ?></td>
            <td><?php echo $value['desc']; ?></td>
            <td align="right"><?php echo number_format($value['debet'],2,',','.'); ?></td>
            <td align="right"><?php echo number_format($value['kredit'],2,',','.'); ?></td>
          </tr>
          <?php $no++; ?>
          <?php } ?>
          <?php } ?>
        </tbody>
      </table>
    </div>

  </div>
</div>