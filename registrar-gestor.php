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

if ((isset($_POST["nombre"])) && ($_POST["apellidos"] != "")) {

	if($_POST['nombre'] != ''){
		
$rss=mysqli_query($conexion, "SELECT * FROM gestor where nombre = '".$_POST['nombre']."'");
$totalpersonas = mysqli_num_rows($rss);

     if($totalpersonas == 0){	
      $insertSQL = sprintf("INSERT INTO gestor (idcliente, nombre, apellidos, direccion, email, telefono, fecha) VALUES (%s, %s, %s, %s, %s, %s, Now())",
                   GetSQLValueString($_POST['idcliente'], "int"),
                   GetSQLValueString($_POST['nombre'], "text"),
                   GetSQLValueString($_POST['apellidos'], "text"),                   
                   GetSQLValueString($_POST['direccion'], "text"), 
                   GetSQLValueString($_POST['email'], "text"),
                   GetSQLValueString($_POST['telefono'], "int"));
      
        $Result1 = mysqli_query($conexion, $insertSQL) or die(mysqli_error());
       $accion = 'crear';


	}else{	   
echo '<script language=javascript>
alert("Verifique su número de Documento, El Gestor que ingresaste ya se encuentra registrado")
history.go(-1);
</script>';}}else{echo "<script type=\"text/javascript\">
           history.go(-1);
       </script>";
	   
echo '<script language=javascript>
alert("El Gestor no debe estar vacio")
</script>';
  }
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
                <h3 class="box-title">Registra un nuevo Gestor</h3>
              </div>
              <form class="form-horizontal" method="post" name="form1" action="<?php echo $editFormAction; ?>">
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-6">
                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Codigo cliente:</label>
                      <div class="col-sm-9">
                        <select class="form-control select2" required name="idcliente" id="idcliente">
                          <option value="">-- Seleccione -- </option>
                          <?php
                          $rs = mysqli_query($conexion, "SELECT * FROM clientes");
                          while ($off = mysqli_fetch_array($rs)) {
                          ?>
                            <OPTION VALUE="<?php echo $off['idcl'];?>"><?php echo $off['idclient'] . ' - ' . $off['razonsocial']; ?></OPTION>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                      <div class="form-group">
                        <label for="id" class="col-sm-3 control-label">Nombre:</label>
                        <div class="col-sm-9">
                          <input class="form-control" type="text" name="nombre" value="" size="32">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="id" class="col-sm-3 control-label">Apellidos:</label>
                        <div class="col-sm-9">
                          <input class="form-control" type="text" required name="apellidos" value="" size="32">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="id" class="col-sm-3 control-label">Dirección:</label>
                        <div class="col-sm-9">
                          <input class="form-control" type="text" required name="direccion" value="" size="32">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
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
                        <td>Cliente</td>
                        <td>Nombre Gestor</td>                      
                        <td>Apellidos</td>
                        <td>Dirección</td>
                        <td>email</td>                    
                        <td>Telefono</td>
                      </tr>
                    </thead>
                    <?php if ($totalRows_lista > 0){ $i = 0 ; do {  $i += 1; ?>
                    <tr <?php if($i % 2){echo 'filapar';}else{ echo 'filaimpar';} ?>>
                      <th class="active"><?php echo $i; ?></th>

                      <td><?php echo $row_lista['razonsocial']; ?>                        
                        <?php
                          $consultas="SELECT * FROM bien where idproveedor = '".$row_lista['idcliente']."'";
                          $rcons=mysqli_query($conexion, $consultas);
                          $fc=mysqli_num_rows($rcons);
                        ?>
                        <form action="eliminar.php" method="post" name="frmimv">
                          <!-- Modal -->
                          <div class="modal fade" id="elimbien<?php echo $row_lista['idcliente']; ?>" tabindex="-1"
                            role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                      aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title color" id="myModalLabel">¿Estas seguro eliminar el registro?
                                  </h4>
                                </div>
                                <div class="modal-body">
                                  <strong class="alert-warning">Detalles:</strong><br>
                                  <strong>Codigo Cliente:</strong> <?php echo $row_lista['codproveedor']; ?><br>
                                  <strong>Cliente:</strong> <?php echo $row_lista['razonsocial']; ?>
                                </div>
                                <div class="modal-footer">
                                  <?php if ($fc!=0){echo '<label style ="font-size:14px"  class="label label-danger">No se puede eliminar</label>'; } ?>
                                  <input name="idproveedor" type="hidden" required id="idproveedor"
                                    value="<?php echo $row_lista['idproveedor']; ?>" size="32">
                                  <input name="nomtabla" type="hidden" required id="nomtabla" value="proveedor"
                                    size="32">
                                  <button type="button" class="btn btn-default" data-dismiss="modal"
                                    title="Cancelar">Cancelar</button>
                                  <input name="Grabar" type="submit" class=" btn btn-primary"
                                    <?php if ($fc!=0){echo "disabled";} else { echo "enabled"; } ?> title="Eliminar"
                                    value="Si, Eliminar">
                                </div>
                              </div>
                            </div>
                          </div>
                        </form> 
                      </td>
                      <td><?php echo $row_lista['nombre']; ?></td>
                      
                      <td><?php echo $row_lista['apellidos']; ?></td>
                      <td><?php echo $row_lista['direccion']; ?></td>

                      <td><?php echo $row_lista['email']; ?></td>
                      
                      <td><?php echo $row_lista['telefono']; ?></td>
                      
                      <!-- <td style=" padding:0px"><a
                          href="modificar-proveedor.php?codigo=<?php echo $row_lista['idproveedor']; ?>" title="Editar"
                          class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>

                        <a href="#" data-toggle="modal" <?php if ($fc!=0){echo "disabled";} else { echo "enabled"; } ?>
                          data-target="#elimbien<?php echo $row_lista['idproveedor']; ?>" title="Eliminar"
                          stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-danger btn-xs"><span
                            class="glyphicon glyphicon-remove"></span></a>
                      </td> -->
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