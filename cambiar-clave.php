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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $pasac = $_SESSION['U_sppassus'];
 if($_POST['pass'] == $_POST['passrep']){ 
 if($_POST['passactual'] == $pasac){ if($_POST['pass'] != $pasac){ 
  $updateSQL = sprintf("UPDATE usuario SET pass=%s WHERE `user`=%s",
                       GetSQLValueString($_POST['pass'], "text"),
                       GetSQLValueString($_POST['userr'], "text"));

  
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());

echo '<script language=javascript>
alert("Se Cambio Mi contraseña")
self.location="cerrar-sesion.php"
</script>';
}else{$mensaje = 'Tu contraseña actual, con la nueva clave no deben ser iguales';}}else{$mensaje = 'La contraseña actual que ingresaste no es válido';}
}else{$mensaje ='Las nuevas contraseñas no cohenciden, intentelo de nuevo';}

}
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
          <span class="glyphicon glyphicon-refresh"></span> Cambiar Mi Contraseña
        </h1>
      </section>

      <section class="content">


        <?php if (isset($mensaje)){echo '<div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>¡Aviso!</strong> '. $mensaje.'
        </div>'; }?>

        <div class="row">
          <div class="col-md-7">
            <div class="box box-info">
              <!--<div class="box-header with-border">
    <h3 class="box-title">TITULO FORMULARIO</h3>
	</div>-->

              <form class="form-horizontal" action="<?php echo $editFormAction; ?>" method="post">
                <div class="box-body">
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Usuario</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled type="text" id="id" placeholder="Usuario"
                        value="<?php echo $_SESSION['U_sdni']; ?>" name="sueldo">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Nombres y Apellidos</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled type="text" id="id" placeholder="Nombres y apellidos"
                        value="<?php echo $_SESSION['U_spnombres'].' ' .	$_SESSION['U_spapellidos'] ; ?>" name="sueldo">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Contraseña Actual</label>
                    <div class="col-sm-9">
                      <input class="form-control" type="text" id="id" placeholder="Clave actual" value=""
                        name="passactual" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Nueva Contraseña</label>
                    <div class="col-sm-9">
                      <input class="form-control" type="text" id="id" placeholder="Nueva clave" value="" name="passrep"
                        required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Repita Nueva Contraseña</label>
                    <div class="col-sm-9">
                      <input class="form-control" type="text" id="id" placeholder="Nueva clave" value="" name="pass"
                        required>
                    </div>
                  </div>

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                  <button type="submit" class="btn btn-info pull-right" title="Grabar registro">GUARDAR</button>
                </div>

                <input type="hidden" name="MM_update" value="form1">
                <input type="hidden" name="userr" value="<?php echo $_SESSION['U_sdni']; ?>">
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