<?php 
if (!isset($_SESSION)) {
  session_start();
} 
if ( isset($_SESSION['U_spprivilegio']) and in_array("m_regmovimiento", $_SESSION['U_spprivilegio'])) { 
	 
require_once('Connections/conexion.php');
$menuactivo ='movimiento';
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

$maxRows_lista = 8000;
$pageNum_lista = 0;
if (isset($_GET['pageNum_lista'])) {
  $pageNum_lista = $_GET['pageNum_lista'];
}
$startRow_lista = $pageNum_lista * $maxRows_lista;
$colname_lista = "-1";


if (in_array("m_tododepedencia", $_SESSION['U_spprivilegio'])) {
  $query_lista = "SELECT  bien.*,biencatalogo.nombien,  m.idmov, m.idoficinadest, m.foto, m.condicion, m.observacion, 
  m.idusuarioreg,m.objetomov, m.doctransf, familiabien.codfamiliabien, 
  proveedor.codproveedor, proveedor.razonsocial FROM bien 
  inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat  
  left join movimiento m on m.idbien = bien.idbien and m.idmov = bien.idmovimiento 
  left join familiabien on familiabien.idfamiliabien = bien.idfamiliabien 
  left join proveedor on proveedor.idproveedor = bien.idproveedor 
  where (bien.debaja <> '1' or bien.debaja is null) and ( bien.idbien between 5200 and 11000)";
}else
{
	$query_lista = "SELECT  bien.*,biencatalogo.nombien,  m.idmov, m.idoficinadest, m.foto, m.condicion, m.observacion, m.idusuarioreg,m.objetomov, m.doctransf, oficina.nomoficina,dependencia.dependencia, concat_ws (' ',r.nombres , r.apellidos) as responsable FROM bien inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat  left join movimiento m on m.idbien = bien.idbien and m.idmov = bien.idmovimiento left join oficina on oficina.idoficina = m.idoficinadest left join dependencia on dependencia.iddependencia = oficina.iddependencia left join responsable r on r.idresponsable = oficina.idrespoficina where (bien.debaja <> '1' or bien.debaja is null) and dependencia.iddependencia  ='".$_SESSION['U_spiddependencia']."'  order by biencatalogo.nombien, bien.codbieninv asc";
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
  <!--Desarrollado por: Eduardo Sandoval -->
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
  $activarmenu='lista-bienmov';
  include('aside.php');
  ?>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <i class="fa fa-retweet"></i>
          Lista de Informes [Movimiento - Impresiones]
        </h1>
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
                        <td width="1px">N°. INFORME</td>
                        <td width="30%">ITEM</td>
                        <!-- <td>CLAVE</td> -->
                        <td>NORMA</td>
                        <td>CARACTERISTICAS</td>
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
                      <td style=" padding:0px;"><select onChange="window.location.href=this.value"
                          style="width:35px; height:27px; padding:0px;" class="btn-warning">
                          <option>✔</option>
                          <!-- <option
                            value="registrar-movimiento.php?id=<?php echo $row_lista['idbien']; ?>&bien=<?php echo $row_lista['nombien']; ?>&muf21&fDFGg54GEds">
                            ✪ Realizar movimiento</option> -->
                            <option <?php if ($row_lista['factura'] =='1'){
                            echo "disabled";
                            } elseif ($row_lista['factura'] =='2') { 
                              echo "disabled"; 
                              } else {
                                echo "enabled"; 
                              } ?>
                            value="registrar-baja.php?id=<?php echo $row_lista['idbien']; ?>&bien=<?php echo $row_lista['nombien']; ?>">
                            ✪ Realizar baja
                          </option>
                        
                          <option <?php if ($row_lista['factura'] =='1'){
                            echo "disabled";
                            } elseif ($row_lista['factura'] =='2') { 
                              echo "disabled"; 
                              } else {
                                echo "enabled"; 
                              } ?>

                            value="modificar-bien.php?id=<?php echo $row_lista['idbien']; ?>&bien=<?php echo $row_lista['nombien']; ?>&metodo=dgdSFDASfdgfgrtFGFDG">
                            ✔ Editar inf. del informe
                          </option>

                          <option
                          value="xreczfsf/extensiones/tcpdf/pdf/caratula.php?id=<?php echo $row_lista['idbien']; ?>&bien=<?php echo $row_lista['nombien']; ?>">
                            ✔ Imprimir Caratula</option>                    
                        </select>
                      </td>

                      <td><?php echo $row_lista['codbieninv']; ?><br>                        
                      </td>
                      <td><?php echo $row_lista['nombien']; ?>
                      </td>                      
                      <td><strong>Empresa: </strong><?php echo $row_lista['razonsocial']; ?><br>
                      <strong>Norma: </strong><?php echo $row_lista['codfamiliabien']; ?><br>
                      <strong>Marca: </strong> <?php echo $row_lista['codmarca']; ?><br>
                        <strong>Modelo: </strong> <?php echo $row_lista['codmodelo']; ?><br>
                        <strong>Serie: </strong> <?php echo $row_lista['serie']; ?>                      
                      </td>

                      <td>                     
                        <strong>Consta / incluye:</strong><?php echo $row_lista['caracteristica']; ?><br>
                        <strong>Gestor:</strong> <?php echo $row_lista['codcolor']; ?><br>                        
                        <strong>Categoria:</strong> <?php echo $row_lista['condicion']; ?><br>
                        <strong>Observaciones:</strong> <?php echo $row_lista['observacion']; ?><br>  
                      </td>                  
                    </tr>
                    <?php } while ($row_lista = mysqli_fetch_assoc($lista));} ?>
                  </table>
                </div>

                <!--        Para imagen modal-->
                <div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                  aria-hidden="true">
                  <div class="modal-dialog">
                    <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
                    <div class="modal-content">
                      <div class="modal-body">
                        <img src="" alt="" />
                      </div>
                    </div>
                  </div>
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