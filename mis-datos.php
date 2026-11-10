<?php require_once('Connections/conexion.php');
$menuactivo ='misdatos';
if (!isset($_SESSION)) {
  session_start();
} 
 ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  //$theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_misdatos = "-1";
if (isset($_SESSION["U_sdni"])) {
  $colname_misdatos = $_SESSION["U_sdni"];
}

$query_misdatos = sprintf("SELECT * FROM usuario WHERE `user` = %s", GetSQLValueString($colname_misdatos, "text"));
$misdatos = mysqli_query($conexion,$query_misdatos ) or die(mysqli_error());
$row_misdatos = mysqli_fetch_assoc($misdatos);
$totalRows_misdatos = mysqli_num_rows($misdatos);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sistema Teso</title>
  <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
  <meta name="author" content="Eduardo Sandoval">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <link rel="stylesheet" href="plugins/select2/select2.min.css">

</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <?php include('header.php'); ?>
    </header>
    <?php 
    $activarmenu='rvehiculo';
    include('aside.php');
    ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <span class="glyphicon glyphicon-user	"></span> Mis Datos
        </h1>
      </section>

      <section class="content">



        <div class="row">
          <div class="col-md-7">
            <div class="box box-info">
              <!--<div class="box-header with-border">
    <h3 class="box-title">TITULO FORMULARIO</h3>
	</div>-->

              <form class="form-horizontal" action="iu_lavador.php" method="post">
                <div class="box-body">
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">USUARIO</label>
                    <div class="col-sm-9">
                      <?php echo $row_misdatos['user']; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">NOMBRES</label>
                    <div class="col-sm-9">
                      <?php echo $row_misdatos['nombres']; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">APELLIDOS</label>
                    <div class="col-sm-9">
                      <?php echo $row_misdatos['apellidos']; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">TIPO</label>
                    <div class="col-sm-9">
                      <?php echo $row_misdatos['tipo']; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">ESTADO</label>
                    <div class="col-sm-9">
                      <?php echo $row_misdatos['estado']; ?>
                    </div>
                  </div>

                </div>


                <!-- /.box-body -->


              </form>


            </div>
          </div>
        </div>
      </section>
    </div>

    <?php include('footer.html'); ?>

  </div>
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script>
  $.widget.bridge('uibutton', $.ui.button);
  </script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="plugins/fastclick/fastclick.js"></script>
  <script src="dist/js/app.min.js"></script>
  <script src="plugins/select2/select2.full.min.js"></script>
  <script>
  $(function() {
    $(".select2").select2();
  });
  </script>
</body>

</html>