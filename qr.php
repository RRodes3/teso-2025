<?php require_once('Connections/conexion.php');
if (!isset($_SESSION)) {
  session_start();
} ?>
<?php
$idbien = '';
$accion = '';
if (isset($_GET['id'])) {
  $idbien = $_GET['id'];
}
if (isset($_GET['accion'])) {
  $accion = $_GET['accion'];
}

$query_lista = "SELECT bien.*,biencatalogo.nombien,  proveedor.codproveedor, proveedor.razonsocial, familiabien.codfamiliabien, familiabien.familiabien FROM bien inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat   inner join proveedor on bien.idproveedor = proveedor.idproveedor inner join familiabien on bien.idfamiliabien = familiabien.idfamiliabien  WHERE bien.idbien ='$idbien' ORDER BY biencatalogo.nombien ASC";
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$roowfila = mysqli_fetch_array($lista);
$totalRows_lista = mysqli_num_rows($lista);


$query_listamov = "SELECT * FROM movimiento where idbien = '" . $roowfila['idbien'] . "' order by idmov desc limit 1 ";
$listamov = mysqli_query($conexion, $query_listamov) or die(mysqli_error());
$roowfilamov = mysqli_fetch_array($listamov);
$totalRows_listamov = mysqli_num_rows($listamov);


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

<body>
  <div class="wrapper">
    <?php
    $activarmenu = 'imprimir-qr';
    // include('aside.php');
    ?>
    <div class="content-wrapper" style="max-width: 50%; margin:0 auto;">
      <section class="content-header" style="padding-top: 90px;">
        <h1 style="text-align: center;">
          Utiliza el siguiente codigo Qr para insertarlo en el informe. <?php echo "$roowfila[codbieninv]"  ?>
        </h1>
        <ol class="breadcrumb">
          <a href="<?php if ($accion == 'REGISTRO') {
                      echo 'registro-bien.php';
                    } else {
                      echo 'javascript:history.back(-1);';
                    } ?>" class="btn btn-default btn-sm" title="Volver atras">Volver atras <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
        </ol>
      </section>
      <section class="content" style="display: grid; align-items: center; justify-items: center;">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="row">
                <div class="col-md-4 text-center" style="margin:0px; padding:0px; width:200px; ">
                  <div style="width:100%; font-size:14px ;margin:0px; padding:0px; ">
                    <?php
                    //set it to writable location, a place for temp generated PNG files
                    $PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
                    //html PNG location prefix
                    $PNG_WEB_DIR = 'temp/';
                    $files = glob('temp/*'); // obtiene todos los archivos
                    foreach ($files as $file) {
                      if (is_file($file)) // si se trata de un archivo
                        unlink($file); // lo elimina
                    }
                    include "codigoqr/qrlib.php";
                    //ofcourse we need rights to create temp dir
                    if (!file_exists($PNG_TEMP_DIR))
                      mkdir($PNG_TEMP_DIR);
                    $filename = $PNG_TEMP_DIR . 'test.png';
                    $matrixPointSize = 20;
                    $errorCorrectionLevel = 'L';
                    $data = "https://www.labteso.com/validacionqr/index.php?clave=$roowfila[codunicoinfor]";
                    $clave = "$roowfila[codunicoinfor]";
                    $filename = $PNG_TEMP_DIR . 'test' . md5(rand(3, 50) . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
                    QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
                    echo '
                    <a href="https://www.labteso.com/validacionqr/index.php?clave='.$roowfila["codunicoinfor"].'"><img style="width:100%;margin:0px; padding:0px " src="' . $PNG_WEB_DIR . basename($filename) . '" /></a>
                   ';
                    ?>
                  </div>

                </div>
              </div>
            </div>
            <!-- /.box -->
          </div>
        </div>
      </section>
      <section>
        <div class="content-qr-logo" style="display: grid; align-items: center; justify-items: center; padding-top: 10px;">
          <img src="./img/LOGO.jpg" alt="">
        </div>
      </section>
    </div>
    <?php // include('footer.html'); 
    ?>
  </div>
  <!-- jQuery 2.2.3 -->
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script>
    // $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.6 -->
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <!--<script src="plugins/fastclick/fastclick.js"></script>
 
<script src="dist/js/app.min.js"></script>-->
</body>

</html>