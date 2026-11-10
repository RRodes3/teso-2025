<?php 
if (!isset($_SESSION)) {
  session_start();
} 
if ( isset($_SESSION['U_spprivilegio']) and in_array("sm_clientes", $_SESSION['U_spprivilegio'])) { 
	 
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
alert("Verifique su número de Documento, El N. de RCliente que ingresaste ya se encuentra registrado")
history.go(-1);
</script>';}}else{echo "<script type=\"text/javascript\">
           history.go(-1);
       </script>";
	   
echo '<script language=javascript>
alert("El Cliente no debe estar vacio")
</script>';
  }
}
//$query_lista = "SELECT idproveedor, codproveedor, idcliente, razonsocial, direccion, personacontacto FROM proveedor where proveedor.idproveedor between 0 and 2000";
//$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
//$row_lista = mysqli_fetch_assoc($lista);
//$totalRows_lista = mysqli_num_rows($lista);


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
          <i class="fa fa-user-o"></i>Lista de Clientes o Empresas
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
        <div class="row">
          <div class="col-md-12">
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Registra un nuevo Cliente o empresa</h3>
              </div>
              <form class="form-horizontal" method="post" name="form1" action="<?php echo $editFormAction; ?>">
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                    <div class="form-group">
                        <label for="id" class="col-sm-3 control-label">ID Empresa:</label>
                        <div class="col-sm-9">
                          <input class="form-control" autofocus type="text" name="codproveedor" value="<?php
                           $query_lista2 = "SELECT MAX(idproveedor) as idd FROM proveedor";
                           $lista2 = mysqli_query($conexion, $query_lista2) or die();
                           $roow2 = mysqli_fetch_array($lista2);
                           $totalRows_lista2 = mysqli_num_rows($lista2);
                           $codigo2 = $roow2['idd']-7;                                                                                                                                            
                           $cl2 = "EM" . $codigo2;
                           echo $cl2;?>"
                           required
                            size="32">
                        </div>
                      </div>
                      <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Cliente:</label>
                      <div class="col-sm-9">
                        <select class="form-control select2" required name="idcliente" id="idcliente">
                          <option value="">-- Seleccione -- </option>
                          <?php
                          $rs = mysqli_query($conexion, "SELECT * FROM clientes");
                          while ($off = mysqli_fetch_array($rs)) {
                          ?>
                            <OPTION VALUE="<?php echo $off['idcl']; ?>"><?php echo $off['idclient'] . ' - ' . $off['razonsocial']; ?></OPTION>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                      <div class="form-group">
                        <label for="id" class="col-sm-3 control-label">Razón Social:</label>
                        <div class="col-sm-9">
                          <input class="form-control" type="text" name="razonsocial" value="" required size="32">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="id" class="col-sm-3 control-label">Rfc:</label>
                        <div class="col-sm-9">
                          <input class="form-control" type="text" name="giro" value="" size="32">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="id" class="col-sm-3 control-label">Dirección:</label>
                        <div class="col-sm-9">
                          <input class="form-control" type="text" required name="direccion" value="" size="32">
                        </div>
                      </div>
                    </div>
                    <!-- <div class="col-md-12">
                    <div class="form-group"> -->
                        <label for="id" class="col-sm-3 control-label">Representante Legal:</label>
                        <div class="col-sm-9">
                          <input class="form-control" type="text" required name="personacontacto" value="" size="32">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="id" class="col-sm-3 control-label">E-mail:</label>
                        <div class="col-sm-9">
                          <input class="form-control" type="email" name="email" value="" size="32">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="id" class="col-sm-3 control-label">Teléfono/Celular:</label>
                        <div class="col-sm-9">
                          <input class="form-control" type="text" name="telefono" value="" size="32">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                          <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                          <button type="submit" class="btn btn-info pull-right" title="Grabar registro">GUARDAR</button>
                          <input type="hidden" name="MM_insert" value="form1">
                        </div>
                      </div>
                    </div>
                  </div>
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
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>


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