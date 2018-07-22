<?php 
include 'template/Header.php'; 
include 'template/Sidebar.php';
include_once("Database/koneksi.php");
?>

<?php

//autonumber
$cariindex = mysqli_query($koneksi,"select max(no_ganti) from tb_gantibuku") or die(mysqli_error());
$dataindex = mysqli_fetch_array($cariindex);
if($dataindex){
    $nilaiindex = substr($dataindex[0],3);
    $index = (int) $nilaiindex;
    $index = $index + 1;
    $hasilindex = "701".str_pad($index,4,"0",STR_PAD_LEFT);

} else {
    $hasilindex = "7010001";}

//insert
if(isset($_POST['ganti'])) {
  $no_ganti = $_POST['no_ganti'];
  $no_hilang = $_POST['no_hilang'];
  $tgl_ganti = $_POST['tgl_ganti'];
  $no_hilang = $_POST['no_hilang'];
  $no_pengembalian = $_POST['no_pengembalian'];
    
  $date = date('Y-m-d');
  if($tgl_ganti < $date){
    echo "<script type='text/javascript'>
            alert ('Tanggal Tidak Valid !');
            window.location.replace('http://localhost/SistemPerpustakaan/belumGanti.php');
          </script>";
  }

  
  $result3 = mysqli_query($koneksi, "SELECT * FROM detil_kembali,tb_pengembalian,tb_peminjaman,tb_hilang WHERE detil_kembali.no_pengembalian = tb_pengembalian.no_pengembalian AND tb_peminjaman.no_peminjaman = tb_pengembalian.no_peminjaman AND tb_hilang.no_peminjaman = tb_peminjaman.no_peminjaman AND tb_hilang.no_hilang = '$no_hilang' and ket != '' ");

  $result4 = mysqli_query($koneksi, "SELECT jml_hilang FROM detil_hilang WHERE no_hilang = '$no_hilang'");
  $baris = mysqli_num_rows($result4);

  $result7 = mysqli_query($koneksi, "SELECT * FROM tb_pengembalian,tb_peminjaman,tb_hilang,detil_kembali WHERE tb_peminjaman.no_peminjaman = tb_pengembalian.no_peminjaman AND tb_hilang.no_peminjaman = tb_peminjaman.no_peminjaman AND detil_kembali.no_pengembalian = tb_pengembalian.no_pengembalian AND tb_hilang.no_hilang = '$no_hilang' and ket != '' ");

  $tampung = array();
  while ($jml = mysqli_fetch_assoc($result3)) {
    $tampung[] = $jml['jml_kembali'];
  }



  $tampung2 = array();
  while ($jml_hlg = mysqli_fetch_assoc($result4)) {
    $tampung2[] = $jml_hlg['jml_hilang'];
  }

  $array = array();
    while ($data6 = mysqli_fetch_array($result7)){
      $array[] = $data6['no_copy'];
  }

  $m=0;
  $q=0;
  $a=0;
  for($i=0; $i<$baris; $i++){
    $nocop = $array[$a++];
    $data = $tampung2[$m++]+$tampung[$q++];
    $update = mysqli_query($koneksi, "UPDATE detil_kembali SET jml_kembali = '$data' WHERE no_pengembalian = '$no_pengembalian' AND no_copy = '$nocop'");
  }  

  $result = mysqli_query($koneksi, "INSERT INTO tb_gantibuku(no_ganti,no_hilang,tgl_ganti) VALUES('$no_ganti','$no_hilang','$tgl_ganti')");

  $status = '1';
  $result4 = mysqli_query($koneksi, "UPDATE tb_hilang SET status = '$status' WHERE no_hilang = '$no_hilang'");

  if($result){
    echo "<script type='text/javascript'>
            alert ('Data Berhasil Disimpan !');
            window.location.replace('http://localhost/SistemPerpustakaan/belumGanti.php');
          </script>"; 
  } else {
    echo "<script type='text/javascript'>
            alert ('Data Gagal Disimpan !');
            window.location.replace('http://localhost/SistemPerpustakaan/belumGanti.php');
          </script>";
  }
}

?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <small><b>Halaman Data Penggantian</b></small>
    </h1>
  </section>
<section class="content">

<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <label>Daftar Penggantian</label>
      </div>
      <div class="panel-body">
        <table style="table-layout:fixed" class="table table-striped table-bordered table-hover" id="anggota">
          <thead>
            <tr>
              <th align="center" width="20px">No. </th>
              <th align="center"><center>Nomor Hilang</center></th>
              <th align="center"><center>Nomor Peminjaman</center></th>
              <th align="center"><center>Tanggal Hilang</center> </th>
              <th align="center"><center>Detail</center> </th>
              <th align="center"><center>Proses</center></th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; ?>
            <?php $result = mysqli_query($koneksi, "SELECT * FROM tb_hilang order by no_hilang desc"); ?>
            <?php while($data2 = mysqli_fetch_array($result)) { ?>
            <tr>
              <td><center><?php echo $no++ ?></center></td>
              <td><center><?php echo $data2['no_hilang'] ?></center></td>
              <td><center><?php echo $data2['no_peminjaman'] ?></center></td>
              <td><center><?php echo $data2['tgl_hilang'] ?></center></td>
              <td><center><a data-toggle="modal" href="#detail<?php echo $data2['no_peminjaman'] ?>" class="btn btn-info"><i class="fa fa-folder-open"></i></a></center></td>

              <?php $z = $data2['no_hilang']; ?>
              <?php $result3 = mysqli_query($koneksi, "SELECT no_ganti FROM tb_gantibuku WHERE no_hilang = '$z'"); ?>
              <?php $validasi = mysqli_fetch_assoc($result3); ?>

              <?php if($validasi['no_ganti'] == null) { ?>
                <td><center><a data-toggle="modal" href="#proses<?php echo $data2['no_hilang'] ?>" class="btn btn-primary"><i class="fa fa-gears"></i></a></center></td>
              <?php } else { ?>
                <td><center><b>-</b></center></td>
              <?php } ?>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
     </div>
    </div>
  </div>
</section>
</div>

<?php $result2 = mysqli_query($koneksi, "SELECT * FROM tb_hilang order by no_hilang desc"); ?>
<?php while($data3 = mysqli_fetch_array($result2)) { ?>
<div class="modal fade" id="proses<?php echo $data3['no_hilang'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Input Data Penggantian</b></h4>
      </div>

      <?php $q = $data3['no_hilang'] ?>
      <?php $result4 = mysqli_query($koneksi, "SELECT * FROM tb_pengembalian,tb_peminjaman,tb_hilang,detil_kembali WHERE tb_peminjaman.no_peminjaman = tb_pengembalian.no_peminjaman AND tb_hilang.no_peminjaman = tb_peminjaman.no_peminjaman AND detil_kembali.no_pengembalian = tb_pengembalian.no_pengembalian AND tb_hilang.no_hilang = '$q'"); ?>

      <?php $data5 = mysqli_fetch_array($result4) ?>
      <div class="modal-body">
        <form method="POST" action="belumGanti.php">
        <div class="form-group"><label>Nomor Ganti</label>
            <input required class="form-control required text-capitalize" placeholder="Input Nama Anggota" value="<?php echo $hasilindex ?>" data-placement="top" readonly data-trigger="manual" type="text" name="no_ganti">
          </div>

        <div class="form-group"><label>Nomor Hilang</label>
            <input required class="form-control required text-capitalize" placeholder="Input Nama Anggota" value="<?php echo $data3['no_hilang'] ?>" readonly data-placement="top" data-trigger="manual" type="text" name="no_hilang">
          </div>

        <div class="form-group"><label>Tanggal ganti</label>
            <input required class="form-control required text-capitalize" data-placement="top" data-trigger="manual" type="date" name="tgl_ganti">
          </div>

          <input type="hidden" name="no_pengembalian" value="<?php echo $data5['no_pengembalian'] ?>">
          <input type="hidden" name="no_hilang" value="<?php echo $q ?>">

      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
          <button type="submit" name="ganti" class="btn btn-success"><i class="fa fa-save"></i> Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>
<?php } ?>

<?php $result2 = mysqli_query($koneksi, "SELECT * FROM tb_hilang order by no_hilang desc"); ?>
<?php while($data3 = mysqli_fetch_array($result2)) { ?>
<div class="modal fade" id="detail<?php echo $data3['no_peminjaman'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Detail Buku Hilang</b></h4>
      </div>
      <?php $id = $data3['no_hilang']; ?>
      <?php $result1 = mysqli_query($koneksi, "SELECT * FROM tb_hilang,tb_peminjaman,detil_hilang,tb_anggota,tb_copybuku,tb_buku WHERE tb_hilang.no_peminjaman = tb_peminjaman.no_peminjaman AND detil_hilang.no_peminjaman = tb_peminjaman.no_peminjaman AND tb_anggota.no_anggota = tb_peminjaman.no_anggota AND tb_copybuku.no_copy = detil_hilang.no_copy AND tb_buku.no_buku = tb_copybuku.no_buku AND tb_hilang.no_hilang = '$id'"); $data_a = mysqli_fetch_array($result1) ?>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <table style="table-layout:fixed" class="table table-bordered">
              <tbody>
                <tr>
                  <td width="150">Nomor Anggota</td>
                  <td width="20">:</td>
                  <td><?php echo $data_a['no_anggota'] ?></td>
                </tr>
                <tr>
                  <td>Nama Anggota</td>
                  <td>:</td>
                  <td><?php echo $data_a['nama_anggota'] ?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-6">
            <table style="table-layout:fixed" class="table table-bordered">
              <tbody>
                <tr>
                  <td width="150">Nomor Hilang</td>
                  <td width="20">:</td>
                  <td><?php echo $data_a['no_hilang'] ?></td>
                </tr>
                <tr>
                  <td width="150">Nomor Peminjaman</td>
                  <td width="20">:</td>
                  <td><?php echo $data_a['no_peminjaman'] ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

         <table style="table-layout:fixed" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th align="center" width="50px">No. </th>
              <th align="center"><center>Nomor Copy</center></th>
              <th align="center"><center>Judul Buku</center></th>
              <th align="center"><center>Tanggal Pinjam</center> </th>
              <th align="center"><center>Jumlah Ganti</center> </th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; ?>
            <?php $id = $data3['no_hilang']; ?>
            <?php $result4 = mysqli_query($koneksi, "SELECT * FROM tb_hilang,tb_peminjaman,detil_hilang,tb_anggota,tb_copybuku,tb_buku WHERE tb_hilang.no_peminjaman = tb_peminjaman.no_peminjaman AND detil_hilang.no_peminjaman = tb_peminjaman.no_peminjaman AND tb_anggota.no_anggota = tb_peminjaman.no_anggota AND tb_copybuku.no_copy = detil_hilang.no_copy AND tb_buku.no_buku = tb_copybuku.no_buku AND tb_hilang.no_hilang = '$id'"); ?>
            <?php while($data2 = mysqli_fetch_array($result4)) { ?>
            <tr>
              <td><center><?php echo $no++ ?></center></td>
              <td><center><?php echo $data2['no_copy'] ?></center></td>
              <td><center><?php echo $data2['judul_buku'] ?></center></td>
              <td><center><?php echo $data2['tgl_pinjam'] ?></center></td>
              <td><center><?php echo $data2['jml_hilang'] ?></center></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
    </div>
  </div>
</div>
<?php } ?>

<?php include "template/Footer.php"; ?>