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

if ((isset($_POST["codproveedor"])) && ($_POST["razonsocial"] != "")) {

	if($_POST['codproveedor'] != ''){
		
$rss=mysqli_query($conexion, "SELECT * FROM proveedor where codproveedor = '".$_POST['codproveedor']."'");
$totalpersonas = mysqli_num_rows($rss);

     if($totalpersonas == 0){	

      $insertSQL = sprintf("INSERT INTO proveedor (codproveedor, idcliente, razonsocial, giro, direccion, email, telefono, personacontacto, fregistro) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, Now())",
                   GetSQLValueString($_POST['codproveedor'], "text"),
                   GetSQLValueString($_POST['idcliente'], "int"),
                   GetSQLValueString($_POST['razonsocial'], "text"),
                   GetSQLValueString($_POST['giro'], "text"),
                   GetSQLValueString($_POST['direccion'], "text"),
                   GetSQLValueString($_POST['email'], "text"),
                   GetSQLValueString($_POST['telefono'], "text"),
                   GetSQLValueString($_POST['personacontacto'], "text"));        
        $Result1 = mysqli_query($conexion, $insertSQL) or die(mysqli_error());
       $accion = 'crear';
       
	}else{	   
echo '<script language=javascript>
alert("Verifique su número de Empresa, El N. de RCliente que ingresaste ya se encuentra registrado")
history.go(-1);
</script>';}}else{echo "<script type=\"text/javascript\">
           history.go(-1);
       </script>";
	   
echo '<script language=javascript>
alert("El Cliente no debe estar vacio")
</script>';
  }
}
$query_lista = "SELECT proveedor.idproveedor, proveedor.codproveedor, proveedor.idcliente, proveedor.razonsocial, 
proveedor.direccion, proveedor.personacontacto, clientes.idcl, clientes.idclient, clientes.razonsocial AS clrazonsocial FROM proveedor
LEFT JOIN clientes ON clientes.idcl = proveedor.idcliente";
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
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
          <i class="fa fa-user-o"></i>Lista de Empresas
        </h1>
      </section>
      <section class="content">
        <?php if ($accion =='crear'){echo '<div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>¡Satisfactorio!</strong> Has registrado un nuevo Cliente / Empresa: <strong>'.@$_POST['razonsocial'].'</strong>
        </div>'; }elseif (isset( $_GET['modificar'])){echo '<div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>¡Satisfactorio!</strong> Se modificó datos del Cliente / Empresa.
        </div>'; }?>

        <!--    --- Inicio Lista 1-->
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-body">
                <div class="table-responsive">
                  <table id="servicios" width="100%" class="table table-hover display">
                    <thead>
                      <tr class="<?php echo 'titulotabla'; ?>">
                        <td>N°</td>
                        <td>Razon social</td>
                        <td>Cliente</td>
                        <td>ID Empresa</td>
                        <td>Razon social</td>                      
                        <td>Dirección</td>
                        <td>Representante</td>                    
                        <td>Editar</td>
                      </tr>
                    </thead>
                    <?php if ($totalRows_lista > 0){ $i = 0 ; do {  $i += 1; ?>
                    <tr <?php if($i % 2){echo 'filapar';}else{ echo 'filaimpar';} ?>>
                      <th class="active"><?php echo $i; ?></th>
                      <td><?php echo $row_lista['idclient']; ?></td>
                      <td><?php echo $row_lista['clrazonsocial']; ?></td>
                      <td><?php echo $row_lista['codproveedor']; ?>                                     
                      </td>
                      <td><?php echo $row_lista['razonsocial']; ?></td>
                      
                      <td><?php echo $row_lista['direccion']; ?></td>

                      <td><?php echo $row_lista['personacontacto']; ?></td>
                                            
                      <td style=" padding:0px"><a
                          href="modificar-proveedor.php?codigo=<?php echo $row_lista['idproveedor']; ?>" title="Editar"
                          class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>

                        <!-- <a href="#" data-toggle="modal" <?php if ($fc!=0){echo "disabled";} else { echo "enabled"; } ?>
                          data-target="#elimbien<?php echo $row_lista['idproveedor']; ?>" title="Eliminar"
                          stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-danger btn-xs"><span
                            class="glyphicon glyphicon-remove"></span></a> -->
                      </td>
                    
                    </tr>
                    <?php } while ($row_lista = mysqli_fetch_assoc($lista));} ?>
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
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script src="plugins/select2/select2.full.min.js"></script>
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