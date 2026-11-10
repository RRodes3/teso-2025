
<?php 
if (!isset($_SESSION)) {
  session_start();
} 
if ( isset($_SESSION['U_spprivilegio']) and in_array("sm_items", $_SESSION['U_spprivilegio'])) { 
	 
require_once('Connections/conexion.php');
$menuactivo ='mantenimiento';
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

$query_lista = "SELECT * FROM biencatalogo where nombien = '".$_POST['nombien']."'";
$listaa = mysqli_query($conexion, $query_lista) or die(mysqli_error());

if (mysqli_num_rows($listaa) == 0){
  $insertSQL = sprintf("INSERT INTO biencatalogo (nombien) VALUES (%s)",
                      GetSQLValueString($_POST['nombien'], "text"));

  
  $Result1 = mysqli_query($conexion, $insertSQL) or die(mysqli_error());


$mensaje = "Se registró un nuevo Item";
    }else{
$msgerror = "Éste item ya se encuentra registrado en el catalogo";
}
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_lista = 100;
$pageNum_lista = 0;
if (isset($_GET['pageNum_lista'])) {
  $pageNum_lista = $_GET['pageNum_lista'];
}
$startRow_lista = $pageNum_lista * $maxRows_lista;


$query_lista = "SELECT * FROM biencatalogo ORDER BY codbiencat ASC";
$query_limit_lista = sprintf("%s LIMIT %d, %d", $query_lista, $startRow_lista, $maxRows_lista);
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$row_lista = mysqli_fetch_assoc($lista);

if (isset($_GET['totalRows_lista'])) {
  $totalRows_lista = $_GET['totalRows_lista'];
} else {
  $all_lista = mysqli_query($conexion, $query_lista);
  $totalRows_lista = mysqli_num_rows($all_lista);
}
$totalPages_lista = ceil($totalRows_lista/$maxRows_lista)-1;

$queryString_lista = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_lista") == false && 
        stristr($param, "totalRows_lista") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_lista = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_lista = sprintf("&totalRows_lista=%d%s", $totalRows_lista, $queryString_lista);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sistema Teso</title>
  <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
  <meta name="author" content="Eduardo Sandoval">
  <!--Desarrollado por: Eduardo Sandoval -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <link rel="stylesheet" href="estilos/jquery.dataTables.min.css">
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
  <link rel="stylesheet" href="plugins/select2/select2.min.css">

</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <?php include('header.php'); ?>
    </header>
    <?php 
    $activarmenu='registrar-catalogo';
    include('aside.php');
    ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <span class="glyphicon glyphicon-plus"></span> Catálogo de Items
        </h1>
      </section>

      <section class="content">

        <?php if (isset($msgerror) ){echo '<div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>¡Aviso!</strong> '. $msgerror.'
        </div>'; }?>
        <?php if (isset($mensaje) ){echo '<div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>¡Aviso!</strong> '. $mensaje.'
        </div>'; }?>

        <div class="row">
          <div class="col-md-7">
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Crear nuevo Item para el Catalogo</h3>
              </div>

              <form class="form-horizontal" method="post" name="form1" action="<?php echo $editFormAction; ?>">
                <div class="box-body">
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Nombre del item</label>
                    <div class="col-sm-9">
                      <input class="form-control" autofocus required type="text" name="nombien" value="" size="32"
                        placeholder="NOMBRE DEL ITEM">
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                  <button type="submit" class="btn btn-info pull-right" title="Grabar registro">GUARDAR</button>
                  <input type="hidden" name="MM_insert" value="form1">
                </div>

              </form>


            </div>
          </div>
        </div>

        <!--    --- Inicio Lista-->
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-body">
                <div class="table-responsive">

                  <table id="servicios" width="100%" class="table table-hover display">
                    <thead>
                      <tr class="<?php echo 'titulotabla'; ?>">
                        <td width="10%">N°</td>
                        <td width="90%">Items</td>
                        <td width="">--</td>
                      </tr>
                    </thead>
                    <?php if ($totalRows_lista > 0) { $i = 0;do { $i += 1; ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $row_lista['nombien']; ?></td>
                      <td style="padding:1px; margin:0px"><a
                          href="modificar-item.php?codigo=<?php echo $row_lista['codbiencat']; ?>" title="Editar"
                          stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-warning btn-sm"><span
                            class="glyphicon glyphicon-pencil"></span></a>
                        
                      </td>
                    </tr>
                    <?php } while ($row_lista = mysqli_fetch_assoc($lista)); }?>
                  </table>

                </div>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
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
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" language="javascript" class="init">
  $(document).ready(function() {
    $('#servicios').DataTable();
  });
  </script>
  <script>
  $(function() {
    $(".select2").select2();
  });
  </script>
</body>

</html>
<?php }else{
header("Location: pagina-restringida.php");
}?>