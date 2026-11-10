<?php 
if (!isset($_SESSION)) {
  session_start();
} 
if ( isset($_SESSION['U_spprivilegio']) and in_array("s_superv", $_SESSION['U_spprivilegio'])) { 	 
require_once('Connections/conexion.php');
$menuactivo ='supervision';
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
//Lista
$query_lista = "SELECT l.*,b.*, c.nombien, u.nombres, u.apellidos   FROM `licencia` l 
INNER JOIN `bien` b ON l.id_bien = b.idbien
INNER JOIN `usuario` u ON l.idusuario = u.idusuario
INNER JOIN `biencatalogo` c ON b.codbiencat = c.codbiencat 
WHERE l.flg_anulado != 1 AND l.id_licencia between 4001 and 9000";
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
    $activarmenu='registrar-licencia';
    include('aside.php');
    ?>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <i class="fa fa-file-archive-o"></i> Informes Asignados
          <!-- <a href="#info1"
            class="inf btn pull-right btn-xs btn-default" title="Agregar mas registros"
            style=" font-size:12px; color:#C10000"><span class="glyphicon glyphicon-plus"></span></a> -->
        </h1>
      </section>
      <section class="content">
        <!--    --- Inicio Lista-->
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-body">
                <div class="table-responsive">
                  <div class="table-responsive">
                    <table id="servicios" width="100%" class="table table-hover display">
                      <thead>
                        <tr class="<?php echo 'titulotabla'; ?>">
                          <td width="">N.</td>
                          <td>No. INFORME</td>                          
                          <td>DATOS INFORME</td> 
                          <td>ING. ASIGNADO</td>                                                    
                          <td>F. INICIO</td>
                          <td>F. ENTREGA PROPUESTA
                          </td>                          
                          <td width="8%">-</td>
                        </tr>
                      </thead>
                      <?php if ($totalRows_lista > 0){ $i = 0 ; do {  $i += 1; ?>
                      <tr <?php if($i % 2){echo 'filapar';}else{ echo 'filaimpar';} ?>>
                        <th class="active"><?php echo $i; ?></th>
                        <td><?php echo $row_lista['codbieninv']; ?></td>
                        <td><?php echo $row_lista['nombien'].' '.$row_lista['codmarca'].''.$row_lista['codmodelo']; ?></td>
                        <td><?php echo $row_lista['nombres'].' '.$row_lista['apellidos']; ?></td>                                                                                            
                        <td><?php echo $row_lista['fechaadq']; ?></td>
                        <td><?php echo $row_lista['tiempovida']; ?></td>                        
                        <td style="padding:1px; margin:0px"><a
                            href="modificar-asignacion-infor.php?codigo=<?php echo $row_lista['id_licencia']; ?>" title="Editar"
                            stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-warning btn-sm"><span
                              class="glyphicon glyphicon-pencil"></span></a>
                          <?php						
                              $fc = 0;						
                          ?>
                          <a href="#" data-toggle="modal"
                            <?php if ($fc!=0){echo "disabled";} else { echo "enabled"; } ?>
                            data-target="#elimbien<?php echo $row_lista['id_licencia']; ?>" title="Eliminar"
                            stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-danger btn-sm"><span
                              class="glyphicon glyphicon-remove"></span></a>
                          <form action="eliminar.php" method="post" name="frmimv">
                            <div class="modal fade" id="elimbien<?php echo $row_lista['id_licencia']; ?>" tabindex="-1"
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
                                    <strong>Equipo:</strong>
                                    <?php echo $row_lista['codbieninv'].' '.$row_lista['nombien']; ?><br>
                                  </div>
                                  <div class="modal-footer">
                                    <?php if ($fc!=0){echo '<label style ="font-size:14px"  class="label label-danger">No se puede eliminar</label>'; } ?>
                                    <input name="id_licencia" type="hidden" required id="id_licencia"
                                      value="<?php echo $row_lista['id_licencia']; ?>" size="32">
                                    <input name="nomtabla" type="hidden" required id="nomtabla" value="licencia"
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
                      </tr>
                      <?php } while ($row_lista = mysqli_fetch_assoc($lista));} ?>
                    </table>
                  </div>              
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
  <script type="text/javascript" language="javascript" class="init">
  $(document).ready(function() {
    $('#example').DataTable();
  });
  jQuery(document).ready(function() {
    $(".oculto").hide();
    $(".inf").click(function() {
      var nodo = $(this).attr("href");

      if ($(nodo).is(":visible")) {
        $(nodo).hide();
        return false;
      } else {
        $(".oculto").hide("slow");
        $(nodo).fadeToggle("fast");
        return false;
      }
    });
  });
  </script>
</body>
</html>
<?php }else{
header("Location: pagina-restringida.php");
}?>