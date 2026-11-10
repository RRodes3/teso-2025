<?php require_once('Connections/conexion.php');
$menuactivo ='usuario';
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE usuario SET pass=%s WHERE `user`=%s",
                       GetSQLValueString($_POST['user'], "text"),
                       GetSQLValueString($_POST['user'], "text"));
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());

echo '<script language=javascript>
alert("Se Restauro Su Clave con su mismo USUARIO")
self.location="lista-usuarioedit.php"
</script>';
}

$colname_restablecer = "-1";
if (isset($_GET['us'])) {
  $colname_restablecer = $_GET['us'];
}

$query_restablecer = sprintf("SELECT * FROM usuario WHERE `user` = %s", GetSQLValueString($colname_restablecer, "text"));
$restablecer = mysqli_query($conexion, $query_restablecer) or die(mysqli_error());
$row_restablecer = mysqli_fetch_assoc($restablecer);
$totalRows_restablecer = mysqli_num_rows($restablecer);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sistema de Inventario</title>
  <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
  <meta name="author" content="Eduardo Sandoval">
  <!--Desarrollado por: Eduardo Sandoval -->
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
          <i class="fa fa-unlock-alt"></i> Restablecer clave
        </h1>
      </section>

      <section class="content">

        <div class="row">
          <div class="col-md-7">
            <div class="box box-info">

              <form class="form-horizontal" method="post" name="form1" action="<?php echo $editFormAction; ?>">
                <div class="box-body">
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Usuario</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled type="text" id="id" placeholder="Usuario"
                        value="<?php echo $row_restablecer['user'] ?>" name="Usuario">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Nombres y Apellidos</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled type="text" id="id" placeholder="nomap"
                        value="<?php echo $row_restablecer['nombres'] ?>  <?php echo $row_restablecer['apellidos'] ?> "
                        name="nomap">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Clave</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled type="text" id="id" placeholder="Clave"
                        value="<?php echo $row_restablecer['user'] ?>" name="Clave">
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                  <button type="submit" class="btn btn-info pull-right" title="Grabar registro">GUARDAR</button>
                  <input type="hidden" name="MM_update" value="form1">
                  <input type="hidden" name="user" value="<?php echo $row_restablecer['user']; ?>">
                </div>

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
<?php
mysqli_free_result($restablecer);
?>