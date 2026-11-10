<?php require_once('Connections/conexion.php');
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
  $nomdoclic = '';
  $nombien = $_POST['nombrelic'];
  if (empty($_FILES['archivolic']['name'])) {
    $nomdoclic =  $_POST["nomarchivolic"];
    $conarchivo = ' no reemplazo archivo';
  } else {
    $extnuevoimagen = pathinfo($_FILES['archivolic']['name']);

    $numaleatorio = mt_rand(5, 30);
    $nomdoclic = 'Lic-' . $_POST['id_bien'] . '_' . substr(preg_replace('#([^.a-z0-9]+)#i', '', $nombien), 0, 15) . $numaleatorio . '_' . date('d-m-Y') . '.' . $extnuevoimagen['extension'];
    $conarchivo = ' reemplazo archivo por: ' . $nomdoclic;
  }
  $updateSQL = sprintf(
    "UPDATE licencia SET tipodehallazgo=%s, organismo=%s, finicio=%s, fvencimiento=%s WHERE id_licencia=%s",
    GetSQLValueString($_POST['tipodehallazgo'], "text"),
    GetSQLValueString($_POST['organismo'], "text"),
    GetSQLValueString($_POST['finicio'], "text"),
    GetSQLValueString($_POST['fvencimiento'], "text"),
    GetSQLValueString($_POST['id_licencia'], "int")
  );
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());

  $query_auditoria = "insert into auditoria  (fecharegistro,idusuario,accion,numacta) values (Now(),'" . $idusuario . "','Registro una incidencia: " . $_POST['tipodehallazgo'] . $_POST['id_bien'] . "','')";
  $lista_audit = mysqli_query($conexion, $query_auditoria) or die(mysqli_error());
  $dir_subida = 'doc/';
  $fichero_subido = $dir_subida . $nomdoclic;
  move_uploaded_file($_FILES['archivolic']['tmp_name'], $fichero_subido);

  $updateGoTo = "informes-asig.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_modificaroficina = "-1";
if (isset($_GET['codigo'])) {
  $colname_modificaroficina = $_GET['codigo'];
}

$query_lista = "SELECT licencia.*,bien.idbien, bien.codbieninv FROM licencia
inner join bien on licencia.id_bien = bien.idbien where licencia.id_licencia = '$colname_modificaroficina' ";
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$row_lista = mysqli_fetch_array($lista);
$totalRows_lista = mysqli_num_rows($lista);

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
    $activarmenu = 'registrar-licencia';
    include('aside.php');
    ?>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <span class="glyphicon glyphicon-edit"></span> Modificar Asignación <?php echo htmlentities($row_lista['codbieninv'], ENT_COMPAT, 'utf-8'); ?>
        </h1>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-md-7">
            <div class="box box-info">
              <form class="form-horizontal" method="post" name="form1" action="<?php echo $editFormAction; ?>" enctype='multipart/form-data'>
                <div class="box-body">

                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Notificacion / Correccion:</label>
                    <div class="col-sm-9">
                      <input class="form-control" type="text" name="tipodehallazgo" value="<?php echo htmlentities($row_lista['tipodehallazgo'], ENT_COMPAT, ''); ?>" placeholder="Escribe la incidencia">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Organismo Certificador:</label>
                    <div class="col-sm-9">
                      <input class="form-control" type="text" name="organismo" value="<?php echo htmlentities($row_lista['organismo'], ENT_COMPAT, ''); ?>" placeholder="Escribe el Organismo Certificador">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label"> Fecha de Recepcion de Notificacion / correccion:</label>
                    <div class="col-sm-9">
                      <input type="date" class="form-control" name="finicio" value="<?php echo htmlentities($row_lista['finicio'], ENT_COMPAT, ''); ?>" size="32">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label"> Fecha de respuesta:</label>
                    <div class="col-sm-9">
                      <input type="date" class="form-control" name="fvencimiento" value="<?php echo htmlentities($row_lista['fvencimiento'], ENT_COMPAT, ''); ?>" size="32">
                    </div>
                  </div>
                  <input type="hidden" name="MM_update" value="form1">
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                  <button type="submit" class="btn btn-info pull-right" title="Grabar registro">ACTUALIZAR</button>
                  <input name="id_licencia" type="hidden" required id="id_licencia" value="<?php echo htmlentities($row_lista['id_licencia'], ENT_COMPAT, 'utf-8'); ?>" size="32">
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