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


if (in_array("m_tododepedencia", $_SESSION['U_spprivilegio'])) {
$query_lista = "SELECT * FROM usuario ORDER BY tipo, nombres ASC";
}else
{
	$query_lista = 'SELECT * FROM usuario inner join oficina on usuario.idoficina = oficina.idoficina where oficina.iddependencia = "'.$_SESSION['U_spiddependencia'].'"  ORDER BY tipo, nombres ASC';
}
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$row_lista = mysqli_fetch_assoc($lista);
$totalRows_lista = mysqli_num_rows($lista);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema Teso</title>
  <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
  <meta name="author" content="Eduardo Sandoval">
  <!--Desarrollado por Eduardo Sandoval -->
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <link rel="stylesheet" href="estilos/jquery.dataTables.min.css">
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <?php include('header.php'); ?>
    </header>

    <?php 
  $activarmenu='tservicio';
  include('aside.php');
  ?>

    <div class="content-wrapper">

      <section class="content-header">
        <h1>
          <i class="fa fa-list-alt"></i>
          Lista de Usuarios a editar
        </h1>
        <ol class="breadcrumb">

          <a href="registrar-usuario.php" class="btn btn-primary btn-sm">Nuevo <span class="glyphicon glyphicon-plus"
              aria-hidden="true"></span></a>
          <a href='javascript:window.print(); void 0;' id="btnImprimirPantalla" class="btn btn-warning btn-sm "
            role="button" title="Imprimir reporte" stsyle="font-size:10px"><span
              class="glyphicon glyphicon-print"></span> Imprimir</a>
        </ol>
      </section>
      <!--Cabecera de impresión-->
      <?php include('header_impr.php'); ?>
      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <!--<div class="box-header">
<h3 class="box-title">Hover Data Table</h3>
</div>-->
              <div class="box-body">
                <div class="table-responsive">

                  <table id="servicios" width="100%" class="table table-hover display">
                    <thead>

                      <tr class="titulotabla">
                        <td width="13%">N. Doc (Usuario)</td>
                        <td width="17%">NOMBRES</td>
                        <td width="17%">APELLIDOS</td>
                        <td width="14%">TIPO DE USUARIO</td>
                        <td width="7%">CLAVE</td>
                        <td width="10%">ESTADO</td>
                        <td width="22%">ACCION</td>
                      </tr>
                    </thead>
                    <?php do { ?>
                    <tr>
                      <td><?php echo $row_lista['user']; ?></td>
                      <td><?php echo $row_lista['nombres']; ?></td>
                      <td><?php echo $row_lista['apellidos']; ?></td>
                      <td><?php echo $row_lista['tipo']; ?></td>
                      <td><a href="restablecer-clave.php?us=<?php echo $row_lista['user']; ?>" title="Restablecer Clave"
                          class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"
                            aria-hidden="true"></span></a></td>
                      <td><?php echo $row_lista['estado']; ?></td>
                      <td><a href="cambiar-estado.php?us=<?php echo $row_lista['user']; ?>" title="MOD. TIPO O ESTADO"
                          class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"
                            aria-hidden="true"></span></a>
                            <a href="registrar-privilegiousuario.php?codus=<?php echo $row_lista['idusuario']; ?>&us=<?php echo $row_lista['user']; ?>"
                          title="Configurar privilegio" class="btn btn-default btn-sm"> <span
                            class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>

                        <?php
					  $usuario = $row_lista['idusuario'];
            $consultas="SELECT * FROM movimiento where idusuarioreg = '".$usuario."'";
            $rcons=mysqli_query($conexion, $consultas);
            $fc=mysqli_num_rows($rcons);
        ?>
                        <a href="#" data-toggle="modal" <?php if ($fc!=0){echo "disabled";} else { echo "enabled"; } ?>
                          data-target="#elimusuario<?php echo $row_lista['idusuario']; ?>" title="Eliminar"
                          stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-danger btn-sm"><span
                            class="glyphicon glyphicon-remove"></span></a>

                        <!-- reporte en Eliminar -->
                        <form action="eliminar.php" method="post" name="frmimv">
                          <!-- Modal -->
                          <div class="modal fade" id="elimusuario<?php echo $row_lista['idusuario']; ?>" tabindex="-1"
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
                                  <strong>Usuario:</strong> <?php echo $row_lista['user']; ?><br>
                                  <strong>Datos del usuario:</strong>
                                  <?php echo $row_lista['nombres'] . ' '.$row_lista['apellidos']; ?>

                                </div>
                                <div class="modal-footer">
                                  <?php if ($fc!=0){echo '<label style ="font-size:14px"  class="label label-danger">No se puede eliminar</label>'; } ?>
                                  <input name="idusuario" type="hidden" required id="idusuario"
                                    value="<?php echo $row_lista['idusuario']; ?>" size="32">
                                  <input name="nomtabla" type="hidden" required id="nomtabla" value="usuario" size="32">
                                  <button type="button" class="btn btn-default" data-dismiss="modal"
                                    title="Cancelar">Cancelar</button>
                                  <input name="Grabar" type="submit" class=" btn btn-primary"
                                    <?php if ($fc!=0){echo "disabled";} else { echo "enabled"; } ?> title="Eliminar"
                                    value="Si, Eliminar">
                                </div>
                              </div>
                            </div>
                          </div>

                        </form> <!-- Fin de eliminar -->

                      </td>
                    </tr>
                    <?php } while ($row_lista = mysqli_fetch_assoc($lista)); ?>
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
  <!-- jQuery 2.2.3 -->
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script>
  $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.6 -->
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="plugins/fastclick/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/app.min.js"></script>
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" language="javascript" class="init">
  $(document).ready(function() {
    $('#servicios').DataTable();
  });
  </script>

</body>

</html>