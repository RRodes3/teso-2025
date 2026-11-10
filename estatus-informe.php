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
$maxRows_lista = 21000;
$pageNum_lista = 0;
if (isset($_GET['pageNum_lista'])) {
  $pageNum_lista = $_GET['pageNum_lista'];
}
$startRow_lista = $pageNum_lista * $maxRows_lista;
$colname_lista = "-1";
$usuario = 0; 
$variableusu = ""; 
if (isset($_GET['usuario'])) {
  $usuario = $_GET['usuario'];
  if ($usuario !='todo'){
	  $variableusu = "where usuario.idusuario = '". $usuario."'";
  }
}

$query_lista = "SELECT usuario.*, licencia.*, bien.*, biencatalogo.*, licencia.estado, licencia.id_bien FROM licencia
inner join bien on licencia.id_bien = bien.idbien
INNER JOIN biencatalogo ON bien.codbiencat = biencatalogo.codbiencat
inner join usuario on licencia.idusuario = usuario.idusuario ".$variableusu." ORDER BY licencia.estado ASC, licencia.id_bien DESC";
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
  $activarmenu='lista-bien-debaja';
  include('aside.php');
  ?>

    <div class="content-wrapper">

      <section class="content-header hidden-print">
        <h1>

          <form action="" method="get"><i class="fa fa-file-text"></i>
            Usuario:

            <?php
              if (in_array("m_tododepedencia", $_SESSION['U_spprivilegio'])) {										 
                          $consulta_mysql='select * from usuario where tipo = "INGLABORATORISTA" AND estado="ACTIVO"  order by nombres asc';

              }else{
                $consulta_mysql='SELECT usuario.*, dependencia.dependencia FROM  usuario 
                inner join oficina on usuario.idoficina = oficina.idoficina 
                inner join dependencia on oficina.iddependencia = dependencia.iddependencia =  "'.$_SESSION['U_spiddependencia'].'"   order by  apellidos asc';
              }
              $resultado=mysqli_query($conexion,$consulta_mysql);
            ?>

            <select required name='usuario' id='usuario' style="font-size:14px;color:#333"
              onchange='this.form.submit()'>
              <option value=''> -- Seleccione Usuario --</option>
              <option value='todo' <?php if ("todo"==$usuario){ echo 'selected';  } ?>> -- TODOS LOS USUARIOS --
              </option>
              <?php
              $nomdepen = '';
              while($fila=mysqli_fetch_array($resultado)){ ?>
              <option value='<?php echo $fila['idusuario'] ?>'
                <?php if ($fila['idusuario']==$usuario){ echo 'selected'; $nomdepen = $fila['user'];} ?>>
                <?php echo $fila['nombres'].' '.$fila['apellidos'].'&nbsp;&nbsp; ( &nbsp;&nbsp;'.$fila['user'].' )'?>
              </option>
              <?php }
              ?>
            </select>
          </form>
        </h1>


        <ol class="breadcrumb">
          <a href='javascript:window.print(); void 0;' id="btnImprimirPantalla" class="btn btn-primary btn-sm "
            role="button" title="Imprimir reporte" stsyle="font-size:10px"><span
              class="glyphicon glyphicon-print"></span> Imprimir</a>

          <a href='lista-general-info-asig.php' target="_blank" id="btnDescargar" class="btn btn-warning btn-sm "
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
              <!--<div class="box-header">
<h3 class="box-title">Hover Data Table</h3>
</div>-->
              <div id="Exportar_a_Excel" class="box-body">
                <!-- Titulo del reporte-->
                <div style="text-align:center" class="visible-print-block">
                  <strong> 
                  ESTATUS INFORMES ASIGNADOS </strong> 
                </div>
                <div class="table-responsive">
                  <table id="servicios" cellspacing="0" width="100%" class="table table-bordered table-hover display">
                    <thead>
                      <tr class="titulotabla">
                        <td width="2%"><strong>N°</strong></td>
                        <td width="8%">N. INFORME</td>
                        <td width="">ITEM</td>
                        <td width="">INGENIERO LAB</td>
                        <td width="">FECHA ASIGNADO</td>                        
                        <!-- <td width="">FECHA ENTREGA</td>                         -->
                        <td width="">INCIDENCIA</td> 
                        <td width="">ORGANISMO</td>
                        <td > FECHA DE HALLAZGO</td>
                        <td>FECHA DE RESPUESTA</td>
                        <td width="">ESTATUS</td>
                        <td width="">ESTADO</td>

                      </tr>
                    </thead>
                    <?php $result =$startRow_lista; if ($totalRows_lista > 0 ){do { ?>
                    <tr>
                      <td><strong>
                          <?php
                          $result += 1;
                          echo  $result;
                          ?>
                        </strong></td>
                        <td><?php echo $row_lista['codbieninv']; ?></td>
                        <td><?php echo $row_lista['nombien'].' '.$row_lista['codmarca'].''.$row_lista['codmodelo']; ?></td>
                        <td><?php echo $row_lista['nombres'].' '.$row_lista['apellidos']; ?></td> 
                        <td><?php echo $row_lista['fechaadq'];?></td>
                        <!-- <td><?php echo $row_lista['tiempovida'];?></td> -->
                        <td><?php echo $row_lista['tipodehallazgo'];?></td>
                        <td><?php echo $row_lista['organismo'];?></td>
                        <td><?php echo $row_lista['finicio'];?></td>
                        <td><?php echo $row_lista['fvencimiento'];?></td>

                        <td><?php if( $row_lista['estado'] == 'pendiente' || $row_lista['estado'] == 'Pruebas' ){
                          echo ' <span class="label label-warning  " style="font-size:14px">EN PRUEBAS</span> ';
                        }elseif($row_lista['estado'] == "completo" || $row_lista['estado'] == 'Supervision'){
                          echo ' <span class="label label-info " style="font-size:14px">EN SUPERVISION</span> ';
                          }
                        elseif($row_lista['estado'] == "finalizado" ||  $row_lista['estado'] == 'Supervisado'){
                          echo ' <span class="label label-success" style="font-size:14px">SUPERVISADO</span> ';
                          }
                          elseif($row_lista['estado'] == "enviado"){
                            echo ' <span class="label label-success" style="font-size:14px">ENVIADO</span> ';
                            }
                          else{
                            echo ' <span class="label label-danger " style="font-size:14px">ASIGNADO REC</span> ';
                          } ?>
                        </td>

                        <td><?php if( $row_lista['debaja'] == 1 ){
                          echo ' <span class="label label-danger  " style="font-size:14px">DEBAJA</span> ';
                        }
                          elseif($row_lista['suspendido'] == 1 ){
                            echo ' <span class="label label-warning" style="font-size:14px">SUSPENDIDO</span> ';
                          } else{
                            echo ' <span class="label label-success " style="font-size:14px">ACTIVO</span> ';
                          } ?>
                        </td>
                    </tr>
                    <?php } while ($row_lista = mysqli_fetch_assoc($lista));}else{ echo '<tr>
                       <td colspan="6"><strong>Sin registros</strong></td></tr>';} ?>
                  </table>
                </div>
              </div>
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
  <script language="javascript">
  $(document).ready(function() {
    $("#botonExcel").click(function(event) {
      $("#datos_a_enviar").val($("<div>").append($("#Exportar_a_Excel").eq(0).clone()).html());
      $("#FormularioExportacion").submit();
    });
  });
  </script>
</body>

</html>

<?php }else{
header("Location: pagina-restringida.php");
}?>