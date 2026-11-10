<?php
if (!isset($_SESSION)) {
  session_start();
}
if (isset($_SESSION['U_spprivilegio']) and in_array("s_clave_s", $_SESSION['U_spprivilegio'])) {
  require_once('Connections/conexion.php');
  $menuactivo = 'supervision';
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
  $maxRows_lista = 18000;
  $pageNum_lista = 0;
  if (isset($_GET['pageNum_lista'])) {
    $pageNum_lista = $_GET['pageNum_lista'];
  }
  $startRow_lista = $pageNum_lista * $maxRows_lista;
  $colname_lista = "-1";
  $usuario = 0;
  $variableusu = "";
  $estadoinforme = "finalizado";
  if (isset($_GET['usuario'])) {
    $usuario = $_GET['usuario'];
    if ($usuario != 'todo') {
      $variableusu = "usuario.idusuario = '" . $usuario . "' ";
    }
  }

  //$query_lista = "SELECT usuario.*, auditoria.*, dependencia.dependencia FROM auditoria inner join usuario on auditoria.idusuario = usuario.idusuario inner join oficina on usuario.idoficina = oficina.idoficina inner join dependencia on oficina.iddependencia = dependencia.iddependencia   ".$variableusu."    order by  fecharegistro desc, apellidos , nombres asc";
  $query_lista = "SELECT bien.idbien, bien.codbieninv, bien.codmarca, 
  bien.codmodelo, bien.codunicoinfor, bien.passinforteso, biencatalogo.codbiencat, biencatalogo.nombien, proveedor.razonsocial, familiabien.codfamiliabien FROM bien
inner join proveedor on proveedor.idproveedor = bien.idproveedor
inner join familiabien on familiabien.idfamiliabien = bien.idfamiliabien
INNER JOIN biencatalogo ON bien.codbiencat = biencatalogo.codbiencat";

  $query_limit_lista = sprintf("%s LIMIT %d, %d", $query_lista, $startRow_lista, $maxRows_lista);
  $lista = mysqli_query($conexion, $query_limit_lista) or die(mysqli_error());
  $row_lista = mysqli_fetch_assoc($lista);

  if (isset($_GET['totalRows_lista'])) {
    $totalRows_lista = $_GET['totalRows_lista'];
  } else {
    $all_lista = mysqli_query($conexion, $query_lista);
    $totalRows_lista = mysqli_num_rows($all_lista);
  }
  $totalPages_lista = ceil($totalRows_lista / $maxRows_lista) - 1;

  $queryString_lista = "";
  if (!empty($_SERVER['QUERY_STRING'])) {
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();
    foreach ($params as $param) {
      if (
        stristr($param, "pageNum_lista") == false &&
        stristr($param, "totalRows_lista") == false
      ) {
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
      $activarmenu = 'lista-bien-debaja';
      include('aside.php');
      ?>

      <div class="content-wrapper">
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
                          <td width="">CLIENTE</td>
                          <td width="">ITEM</td>
                          <td width="">MARCA</td>
                          <td width="">MODELO</td>
                          <td width="">NORMA</td>
                          <td width="">INICODE</td>
                          <td width="">PASSWORD</td>
                        </tr>
                      </thead>
                      <?php $result = $startRow_lista;
                      if ($totalRows_lista > 0) {
                        do { ?>
                          <tr>
                            <td><strong>
                                <?php
                                $result += 1;
                                echo  $result;
                                ?>
                              </strong></td>
                            <td><?php echo $row_lista['codbieninv']; ?></td>                                                    
                            <td><?php echo $row_lista['razonsocial']; ?></td>
                            <td><?php echo $row_lista['nombien']; ?></td>
                            <td><?php echo $row_lista['codmarca']; ?></td>                                                    
                            <td><?php echo $row_lista['codmodelo']; ?></td>                                                    
                            <td><?php echo $row_lista['codfamiliabien']; ?></td>                                                    
                            <td><?php echo $row_lista['codunicoinfor']; ?></td>                                                    
                            <td><?php echo $row_lista['passinforteso']; ?></td>                                                   
                           
                          </tr>
                      <?php } while ($row_lista = mysqli_fetch_assoc($lista));
                      } else {
                        echo '<tr>
                       <td colspan="6"><strong>Sin registros</strong></td></tr>';
                      } ?>
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

  </body>

  </html>

<?php } else {
  header("Location: pagina-restringida.php");
} ?>