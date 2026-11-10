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
  $updateSQL = sprintf("UPDATE biencatalogo SET nombien=%s WHERE codbiencat=%s",
                       GetSQLValueString($_POST['nombien'], "text"),                    
                       GetSQLValueString($_POST['codbiencat'], "int"));
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());
  $updateGoTo = "registrar-catalogo.php";
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

$query_modificaroficina = sprintf("SELECT * FROM biencatalogo where codbiencat =  %s", GetSQLValueString($colname_modificaroficina, "int"));
$modificaroficina = mysqli_query($conexion, $query_modificaroficina) or die(mysqli_error());
$row_modificaroficina = mysqli_fetch_assoc($modificaroficina);
$totalRows_modificaroficina = mysqli_num_rows($modificaroficina);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sistema teso</title>
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
    $activarmenu='modificar-familia';
    include('aside.php');
    ?>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <span class="glyphicon glyphicon-edit"></span> Modificar Item
        </h1>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-md-7">
            <div class="box box-info">
              <form class="form-horizontal" method="post" name="form1" action="<?php echo $editFormAction; ?>">
              <div class="box-body">

                <div class="form-group">
                <label for="id" class="col-sm-3 control-label">item:</label>
                <div class="col-sm-9">
                    <input name="codbiencat" type="hidden" required id="codbiencat"
                    value="<?php echo htmlentities($row_modificaroficina['codbiencat'], ENT_COMPAT, 'utf-8'); ?>"
                    size="32">
                    <input class="form-control" name="nombien" type="text" autofocus required
                    id="nombien"
                    value="<?php echo htmlentities($row_modificaroficina['nombien'], ENT_COMPAT, 'utf-8'); ?>"
                    size="32"></td>
                </div>
                </div>


                <input type="hidden" name="MM_update" value="form1">

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                  <button type="submit" class="btn btn-info pull-right" title="Grabar registro">ACTUALIZAR</button>
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
mysqli_free_result($modificaroficina);
?>