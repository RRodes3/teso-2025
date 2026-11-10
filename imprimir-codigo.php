<?php require_once('Connections/conexion.php');
$menuactivo ='movimiento';
if (!isset($_SESSION)) {
  session_start();
} ?>
<?php
$idbien = '';
$accion = '';
if(isset( $_GET['id'])){ $idbien = $_GET['id'];} 
 ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema Teso</title>
  <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
  <meta name="author" content="Eduardo Sandoval">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">

  <style type="text/css" media="print">
  @page {
    margin: 0;
  }
  </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <?php include('header.php'); ?>
    </header>

    <?php 
  $activarmenu='imprimir-codigo';
  include('aside.php');
  ?>

    <div class="content-wrapper">


      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">

              <iframe src="imprimir-qr.php?id=<?php echo $idbien;  ?>&bien=" width="100%" height="300px"></iframe>              

            </div>
            <!-- /.box -->
          </div>
        </div>
      </section>

    </div>

    <?php include('footer.html'); ?>

  </div>

  <!-- jQuery 2.2.3 -->
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script>
  //$.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.6 -->
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="plugins/fastclick/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/app.min.js"></script>


</body>

</html>