<?php 
  if (!isset($_SESSION)) {
    session_start();
  } 
  if ( isset($_SESSION['U_spprivilegio']) and in_array("sm_eliminf", $_SESSION['U_spprivilegio'])) { 
  require_once('Connections/conexion.php');
?>

<?php
  require_once('Connections/funciones.php');
  if (!isset($_SESSION)) {
    session_start();
  } 
  //metodo = eliminar
  $metodo = '';
  $menuactivo ='reportes';
  if (isset($_GET['metodo'])){
  $metodo=limpiar($_GET['metodo']);
  $metodo=substr($metodo,10);
  $metodo=decrypt($metodo,'URLCODIGO');
  $menuactivo ='mantenimiento';
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
//inventario
$dniusuario = '';
if(isset($_SESSION["U_sdni"])){ $dniusuario =$_SESSION["U_sdni"];}
 if (isset($_POST["Grabar"]) ) {
	   $updateSQL = sprintf("UPDATE bien SET `user`=%s, fechaad=NOW(), inventario=%s WHERE idbien=%s",
                       GetSQLValueString($dniusuario, "text"),
                       GetSQLValueString($_POST['inventario'], "text"),
                       GetSQLValueString($_POST['idbien'], "int"));
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());
}
//DAR DE BAJA
 if (isset($_POST["baja"]) ) {
	   $updateSQL = sprintf("UPDATE bien SET `user`=%s, fechaad=NOW(), debaja='SI' WHERE idbien=%s", GetSQLValueString($dniusuario, "text"),
                       GetSQLValueString($_POST['idbien'], "int"));
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());
}
$currentPage = $_SERVER["PHP_SELF"];
$maxRows_lista = 6000;
$pageNum_lista = 0;
if (isset($_GET['pageNum_lista'])) {
  $pageNum_lista = $_GET['pageNum_lista'];
}
$startRow_lista = $pageNum_lista * $maxRows_lista;

$colname_lista = "-1";
if (in_array("m_tododepedencia", $_SESSION['U_spprivilegio'])) {
  $query_lista = "SELECT  bien.*,biencatalogo.nombien,   m.idmov, m.idoficinadest, m.foto, m.condicion, 
  m.observacion, m.idusuarioreg,m.objetomov, m.doctransf, proveedor.razonsocial, proveedor.personacontacto, familiabien.codfamiliabien FROM bien 
  inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat 
  left join movimiento m on m.idbien = bien.idbien and m.idmov = bien.idmovimiento 
  left join familiabien on familiabien.idfamiliabien = bien.idfamiliabien 
  left join proveedor on proveedor.idproveedor = bien.idproveedor
 where bien.idbien between 0 and 10000";
}else
{
	$query_lista = "SELECT  bien.*,biencatalogo.nombien,   m.idmov, m.idoficinadest, m.foto, m.condicion, m.observacion, m.idusuarioreg,m.objetomov, m.doctransf, oficina.nomoficina,dependencia.dependencia, concat_ws (' ',r.nombres , r.apellidos) as responsable FROM bien 
  inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat 
  left join movimiento m on m.idbien = bien.idbien and m.idmov = bien.idmovimiento 
  left join oficina on oficina.idoficina = m.idoficinadest 
  left join dependencia on dependencia.iddependencia = oficina.iddependencia 
  left join responsable r on r.idresponsable = oficina.idrespoficina where dependencia.iddependencia = '".$_SESSION['U_spiddependencia']."'";
}
$query_limit_lista = sprintf("%s LIMIT %d, %d", $query_lista, $startRow_lista, $maxRows_lista);
$lista = mysqli_query($conexion, $query_limit_lista) or die(mysqli_error());
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
  $activarmenu='lista-historial-bien';
  include('aside.php');
  ?>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <i class="fa fa-list-ol"></i>
          Lista de informes general - Historial <?php 
				  if($metodo =='eliminar'){
				  echo ' - Eliminar';
				  }
				  ?>
        </h1>
        <ol class="breadcrumb">
          <a href='javascript:window.print(); void 0;' id="btnImprimirPantalla" class="btn btn-primary btn-sm "
            role="button" title="Imprimir reporte" stsyle="font-size:10px"><span
              class="glyphicon glyphicon-print"></span> Imprimir</a>
          <a href='desc-lista-historial-bien.php' target="_blank" id="btnDescargar" class="btn btn-warning btn-sm "
            role="button" title="Descargar en Excel" stsyle="font-size:10px"><span
              class="glyphicon glyphicon-download-alt"></span> Descargar</a>
        </ol>
      </section>
      <!--Cabecera de impresión-->
      <?php include('header_impr.php'); ?>
      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-body">
                <div class="table-responsive">
                  <table id="servicios" width="100%" class="table table-hover display">
                    <thead>
                      <tr class="titulotabla">
                        <td width="1%"><strong>N°</strong></td>
                        <td width="1px"></td>
                        <td width="1%">No. INFORME</td>
                        <td>NORMA</td>
                        <td>EMPRESA</td>
                        <td>GESTOR</td>
                        <td>ITEM</td>
                        <td>MARCA</td>
                        <td>MODELO</td>
                        <td>CATEGORIA</td>
                        <td>PRECIO</td>
                        <td>ESTADO</td>
                      </tr>
                    </thead>
                    <?php $result =$startRow_lista; if ($totalRows_lista > 0 ){do { ?>
                    <tr>
                      <td>
                        <strong>
                          <?php
                            $result += 1;
                            echo  $result;
                          ?>
                        </strong>
                      </td>

                      <td style=" padding:0px;"><a
                          href="historial-bien.php?codigo=<?php echo $row_lista['idbien']; ?>&bien=<?php echo $row_lista['nombien']; ?>&metodo=<?php 
                          if($metodo =='eliminar'){ echo 'YABCDFGJAHmMG7uay9pbs=';}?>"

                          title="Ver historial" class="btn <?php if( $row_lista['debaja'] =='1'){echo 'btn-danger';}elseif($row_lista['modificado'] =='1'){
                          echo 'btn-warning';
                          }else{echo 'btn-success';} ?> btn-sm"><span class="glyphicon glyphicon-zoom-in"></span></a>
                          <?php 
                            if($metodo =='eliminar'){
                          ?>
                        <?php
                          $consultas="SELECT * FROM movimiento where idbien = '".$row_lista['idbien']."'";
                          $rcons=mysqli_query($conexion, $consultas);
                          $fc=mysqli_num_rows($rcons);				
                        ?>
                        
                        <a href="#" data-toggle="modal" <?php if ($row_lista['debaja'] ==''){
                          echo "disabled";
                          } else { 
                            echo "enabled"; 
                            } ?>
                          data-target="#elimbien<?php echo $row_lista['idbien']; ?>" title="Eliminar"
                          stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-danger btn-sm"><span
                            class="glyphicon glyphicon-remove"></span></a>

                        <!-- reporte en Eliminar -->
                        <form action="eliminar.php" method="post" name="frmimv">
                          <!-- Modal -->
                          <div class="modal fade" id="elimbien<?php echo $row_lista['idbien']; ?>" tabindex="-1"
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
                                  <strong>Numero de Informe:</strong> <?php echo $row_lista['codbieninv']; ?><br>
                                  <strong>Nombre del Item:</strong> <?php echo $row_lista['nombien']; ?>

                                </div>
                                
                                <div class="modal-footer">
                                  <?php if ($row_lista['debaja'] == ""){
                                    echo '<label style ="font-size:14px"  class="label label-danger">No se puede eliminar</label>'; 
                                    } ?>
                                  <input name="idbien" type="hidden" required id="idbien"
                                    value="<?php echo $row_lista['idbien']; ?>" size="32">

                                  <input name="nomtabla" type="hidden" required id="nomtabla" value="bien" size="32">

                                  <button type="button" class="btn btn-default" data-dismiss="modal"
                                    title="Cancelar">Cancelar</button>

                                  <input name="Grabar" type="submit" class="art-button"
                                    <?php if ($row_lista['debaja'] =='1'){echo "enable";} else { echo "disabled"; } ?> title="Eliminar"
                                    value="Si, Eliminar">
                                </div>
                              </div>
                            </div>
                          </div>

                        </form> <!-- Fin de eliminar -->
                        <?php  } ?>
                      </td>

                      <td><?php echo $row_lista['codbieninv']; ?></td>
                      <td><?php echo $row_lista['codfamiliabien']; ?> </td>
                      <td><?php echo $row_lista['razonsocial']; ?> </td>
                      <td><?php echo $row_lista['codcolor']; ?> </td>
                      <td><?php echo $row_lista['nombien']; ?> </td>
                      <td><?php echo $row_lista['codmarca']; ?></td>
                      <td><?php echo $row_lista['codmodelo']; ?></td>
                      <td><?php echo $row_lista['condicion']; ?></td>
                      <td>$ <?php echo $row_lista['valor']; ?></td>
                      
                      <td><?php if( $row_lista['debaja'] =='1'  ){
                            echo ' <span class="label label-danger  " style="font-size:14px">DE BAJA</span> ';
                          }elseif($row_lista['modificado'] =='1'){
                            echo ' <span class="label label-warning " style="font-size:14px">MODIFICADO</span> ';
                            }else{
                              echo ' <span class="label label-success " style="font-size:14px">ACTIVO</span> ';
                            } ?>
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
<?php
mysqli_free_result($lista);
?>
<?php }else{
header("Location: pagina-restringida.php");
}?>