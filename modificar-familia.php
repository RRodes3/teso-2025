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
  $updateSQL = sprintf("UPDATE familiabien SET codfamiliabien=%s, familiabien=%s, precio=%s, tiempoentrega=%s WHERE idfamiliabien=%s",
                       GetSQLValueString($_POST['codfamiliabien'], "text"),
                       GetSQLValueString($_POST['familiabien'], "text"),
                       GetSQLValueString($_POST['precio'], "float"),
                       GetSQLValueString($_POST['tiempoentrega'], "int"),
                       GetSQLValueString($_POST['idfamiliabien'], "int"));

  
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());

  $updateGoTo = "registrar-familia.php";
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

$query_modificaroficina = sprintf("SELECT * FROM familiabien where idfamiliabien =  %s", GetSQLValueString($colname_modificaroficina, "int"));
$modificaroficina = mysqli_query($conexion, $query_modificaroficina) or die(mysqli_error());
$row_modificaroficina = mysqli_fetch_assoc($modificaroficina);
$totalRows_modificaroficina = mysqli_num_rows($modificaroficina);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sistema de inventario</title>
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
          <span class="glyphicon glyphicon-edit"></span> Modificar Norma
        </h1>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-md-7">
            <div class="box box-info">
              <form class="form-horizontal" method="post" name="form1" action="<?php echo $editFormAction; ?>">
                <div class="box-body">
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">N. de Norma:</label>
                    <div class="col-sm-9">
                      <input name="idfamiliabien" type="hidden" required id="idfamiliabien"
                        value="<?php echo htmlentities($row_modificaroficina['idfamiliabien'], ENT_COMPAT, 'utf-8'); ?>"
                        size="32">
                      <input class="form-control" name="codfamiliabien" type="text" autofocus required
                        id="codfamiliabien"
                        value="<?php echo htmlentities($row_modificaroficina['codfamiliabien'], ENT_COMPAT, 'utf-8'); ?>"
                        size="32"></td>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Norma:</label>
                    <div class="col-sm-9">
                      <input class="form-control" name="familiabien" type="text" required id="familiabien"
                        value="<?php echo htmlentities($row_modificaroficina['familiabien'], ENT_COMPAT, 'utf-8'); ?>"
                        size="32"></td>
                    </div>
                  </div>
                  <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Precio:</label>
                      <div class="col-sm-9">
                        <div class="input-group">
                          <div class="input-group-addon">$</div>
                          <input name="precio" type="number" class="form-control" min="0.0" step="any" required id="precio" placeholder="0.00" value="<?php echo htmlentities($row_modificaroficina['precio'], ENT_COMPAT, 'utf-8'); ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Dias para entrega:</label>
                      <div class="col-sm-9">
                        <div class="input-group">                          
                          <input name="tiempoentrega" type="number" class="form-control" required id="tiempoentrega" placeholder="0" value="<?php echo htmlentities($row_modificaroficina['tiempoentrega'], ENT_COMPAT, 'utf-8'); ?>">
                      </div>
                    </div>
                  </div>
                  <input type="hidden" name="MM_update" value="form1">
                </div>              
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
