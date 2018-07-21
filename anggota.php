<?php 
include 'template/Header.php'; 
include 'template/Sidebar.php';
include_once("Database/koneksi.php");
?>

<?php

//insert
if(isset($_POST['submit'])) {
  $no_anggota = $_POST['no_anggota'];
  $nama_anggota = $_POST['nama_anggota'];
  $jabatan = $_POST['jabatan'];
  $no_telp = $_POST['no_telp'];

  $result = mysqli_query($koneksi, "INSERT INTO tb_anggota(no_anggota,nama_anggota,jabatan,no_telp) VALUES('$no_anggota','$nama_anggota','$jabatan','$no_telp')");
    
  if($result){
  echo "<script type='text/javascript'>
            alert ('Data Berhasil Disimpan !');
            window.location.replace('http://localhost/SistemPerpustakaan/anggota.php');
          </script>"; 
  } else {
    echo "<script type='text/javascript'>
            alert ('Data Gagal Disimpan !');
            window.location.replace('http://localhost/SistemPerpustakaan/anggota.php');
          </script>";
  }
}

//update
if(isset($_POST['update'])) {
  $no_anggota = $_POST['no_anggota'];
  $nama_anggota = $_POST['nama_anggota'];
  $jabatan = $_POST['jabatan'];
  $no_telp = $_POST['no_telp'];

  $result = mysqli_query($koneksi, "UPDATE tb_anggota SET nama_anggota = '$nama_anggota',jabatan = '$jabatan', no_telp = '$no_telp' WHERE no_anggota = $no_anggota");
    
  if($result){
  echo "<script type='text/javascript'>
            alert ('Data Berhasil Disimpan !');
            window.location.replace('http://localhost/SistemPerpustakaan/anggota.php');
          </script>"; 
  } else {
    echo "<script type='text/javascript'>
            alert ('Data Gagal Disimpan !');
            window.location.replace('http://localhost/SistemPerpustakaan/anggota.php');
          </script>";
  }
}

//delete
if(isset($_POST['delete'])) {
  $no_anggota = $_POST['no_anggota'];
  $result = mysqli_query($koneksi, "DELETE FROM tb_anggota WHERE no_anggota = $no_anggota");
    
  if($result){
  echo "<script type='text/javascript'>
            window.location.replace('http://localhost/SistemPerpustakaan/anggota.php');
          </script>"; 
  } else {
    echo "<script type='text/javascript'>
            window.location.replace('http://localhost/SistemPerpustakaan/anggota.php');
          </script>";
  }
}

?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <small><b>Halaman Data Anggota</b></small>
    </h1>
  </section>
<section class="content">

<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <button class="btn btn-success" data-toggle="modal" href="#" data-target="#ModalEntryAnggota"><i class="fa fa-plus"></i> Tambah Data Anggota</button>
      </div>
      <div class="panel-body">
        <table style="table-layout:fixed" class="table table-striped table-bordered table-hover" id="anggota">
          <thead>
            <tr>
              <th align="center" width="20px">No. </th>
              <th align="center"><center>Nomor Anggota</center></th>
              <th align="center"><center>Nama Anggota</center></th>
              <th align="center"><center>Jabatan</center> </th>
              <th align="center"><center>Telepon</center></th>
              <th align="center" width="70px"><center>Edit</center></th>
              <th align="center" width="70px"><center>Hapus</center></th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; ?>
            <?php $result = mysqli_query($koneksi, "SELECT * FROM tb_anggota ORDER BY nama_anggota asc"); ?>
            <?php while($data = mysqli_fetch_array($result)) { ?>
            <tr>
              <td><center><?php echo $no++; ?></center></td>
              <td><?php echo $data['no_anggota'] ?></td>
              <td><center><?php echo $data['nama_anggota'] ?></center></td>
              <td><center><?php echo $data['jabatan'] ?></center></td>
              <td><center><?php echo $data['no_telp'] ?></center></td> 
              <td><center>
                <a href="#Update<?php echo $data['no_anggota'] ?>" class="btn btn-warning btn-circle" data-toggle="modal"><span class="glyphicon glyphicon-edit"></span> </a></center>
              </td>
              <td><center>
                <a href="#Hapus<?php echo $data['no_anggota'] ?>" class="btn btn-danger btn-circle" data-toggle="modal"><span class="glyphicon glyphicon-trash"></span> </a></center>
             </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
     </div>
    </div>
  </div>
</section>


<div class="modal fade" id="ModalEntryAnggota" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Input Data Anggota</h4>
      </div>
      <form method="POST" action="anggota.php" enctype="multipart/form-data">
        <div class="modal-body">
          
          <div class="form-group"><label>Nomor Anggota</label>
            <input required class="form-control required text-capitalize" data-placement="top" placeholder="Input Nomor Anggota" data-trigger="manual" type="text" name="no_anggota">
          </div>
                
          <div class="form-group"><label>Nama Anggota</label>
            <input required class="form-control required text-capitalize" placeholder="Input Nama Anggota" data-placement="top" data-trigger="manual" type="text" name="nama_anggota">
          </div>

          <div class="form-group"><label>Jabatan</label>
            <div class="custom-select my-1 mr-sm-2 ">
              <select class="form-control" name="jabatan">
                <option value="Siswa">Siswa</option>
                <option value="Guru">Guru</option>
              </select>
            </div>
          </div>

          <div class="form-group"><label>Nomor Telepon</label>
            <input required class="form-control required text-capitalize" placeholder="Input Nomor Telepon" data-placement="top" data-trigger="manual" type="text" name="no_telp">
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
          <button type="submit" name="submit" class="btn btn-success"><i class="fa fa-save"></i> Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $result = mysqli_query($koneksi, "SELECT * FROM tb_anggota ORDER BY nama_anggota asc"); ?>
<?php while($data = mysqli_fetch_array($result)) { ?>
<div class="modal fade" id="Update<?php echo $data['no_anggota'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Ubah Data Anggota</h4>
      </div>
      <form method="POST" action="anggota.php" enctype="multipart/form-data">
        <div class="modal-body">
          
          <div class="form-group"><label>Nomor Anggota</label>
            <input required class="form-control required text-capitalize" value="<?php echo $data['no_anggota'] ?>" data-placement="top" readonly placeholder="Input Nomor Anggota" data-trigger="manual" type="text" name="no_anggota">
          </div>
                
          <div class="form-group"><label>Nama Anggota</label>
            <input required class="form-control required text-capitalize" placeholder="Input Nama Anggota" value="<?php echo $data['nama_anggota'] ?>" data-placement="top" data-trigger="manual" type="text" name="nama_anggota">
          </div>

          <div class="form-group"><label>Jabatan</label>
            <div class="custom-select my-1 mr-sm-2 ">
              <select class="form-control" name="jabatan">
                <option <?php if( $data['jabatan'] == 'Siswa'){echo "selected"; } ?>  value="Siswa">Siswa</option>
                <option <?php if( $data['jabatan'] == 'Guru'){echo "selected"; } ?>  value="Guru">Guru</option>
              </select>
            </div>
          </div>

          <div class="form-group"><label>Nomor Telepon</label>
            <input required class="form-control required text-capitalize" placeholder="Input Nomor Telepon" data-placement="top" data-trigger="manual" value="<?php echo $data['no_telp'] ?>" type="text" name="no_telp">
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
          <button type="submit" name="update" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php } ?>


<?php $result = mysqli_query($koneksi, "SELECT * FROM tb_anggota ORDER BY nama_anggota asc"); ?>
<?php while($data = mysqli_fetch_array($result)) { ?>
<div class="modal fade" id="Hapus<?php echo $data['no_anggota']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Konfirmasi Hapus</h4>
      </div>
      <div class='modal-body'>Anda yakin ingin menghapus ?
      </div>
      <div class='modal-footer'>
        <form class="" action="anggota.php" method="post">
          <input type="hidden" value="<?php echo $data['no_anggota'] ?>" name="no_anggota">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
          <button class="btn btn-danger" aria-label="Delete" type="submit" name="delete"><i class="fa fa-trash fa-fw"></i> Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<?php include "template/Footer.php"; ?>