<?php 
if (!isset($_SESSION)) {
  session_start();
} 
if ( isset($_SESSION['U_spprivilegio']) and in_array("f-fact-control", $_SESSION['U_spprivilegio']) || in_array("sm_clientes", $_SESSION['U_spprivilegio'])) { 
	 
require_once('Connections/conexion.php');
$menuactivo = 'facturacion' || 'mantenimiento';
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
$accion ='';
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$query_lista = "SELECT gestor.*, clientes.* FROM gestor
LEFT JOIN clientes ON clientes.idcl = gestor.idcliente";
$lista = mysqli_query($conexion, $query_lista) or die();
$row_lista = mysqli_fetch_assoc($lista);
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
    $activarmenu='registrar-proveedor';
    include('aside.php');
    ?>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <i class="fa fa-user-o"></i>Lista de Gestor
        </h1>
      </section>
      <section class="content">               
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-body">
                <div class="table-responsive">
                  <table id="servicios" width="100%" class="table table-hover display">
                    <thead>
                      <tr class="<?php echo 'titulotabla'; ?>">
                        <td>N°</td>
                        <td>Cliente</td>
                        <td>Nombre Gestor</td>                      
                        <td>Apellidos</td>
                        <td>Dirección</td>
                        <td>email</td>                                            
                      </tr>
                    </thead>
                    <?php if ($totalRows_lista > 0){ $i = 0 ; do {  $i += 1; ?>
                    <tr <?php if($i % 2){echo 'filapar';}else{ echo 'filaimpar';} ?>>
                      <th class="active"><?php echo $i; ?></th>
                      <td><?php echo $row_lista['idclient']; ?></td>
                      <td><?php echo $row_lista['nombre']; ?></td>                      
                      <td><?php echo $row_lista['apellidos']; ?></td>
                      <td><?php echo $row_lista['direccion']; ?></td>
                      <td><?php echo $row_lista['email']; ?></td>                                         
                    </tr>
                    <?php } while ($row_lista = mysqli_fetch_assoc($lista));} ?>
                  </table>
                </div>
              </div>              
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
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" language="javascript" class="init">
  $(document).ready(function() {
    $('#servicios').DataTable();
  });
  </script>
   <script type="text/javascript" language="javascript" class="init">
  $(document).ready(function() {
    $('#servicioss').DataTable();
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