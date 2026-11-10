<?php
if (!isset($_SESSION)) {
  session_start();
}
if (isset($_SESSION['U_spprivilegio']) and in_array("s_asig", $_SESSION['U_spprivilegio'])) {
  require_once('Connections/conexion.php');
  $menuactivo = 'supervision';
?>
  <?php
  $currentPage = $_SERVER["PHP_SELF"];
  $maxRows_list = 300;
  $pageNum_list = 0;
  if (isset($_GET['pageNum_list'])) {
    $pageNum_list = $_GET['pageNum_list'];
  }
  $startRow_list = $pageNum_list * $maxRows_list;
  $query_list = "Select bien.*,biencatalogo.nombien, familiabien.codfamiliabien, proveedor.razonsocial from bien 
  inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat  
  INNER join proveedor on proveedor.idproveedor = bien.idproveedor
  inner join familiabien on familiabien.idfamiliabien = bien.idfamiliabien 
  where bien.idbien 
  not in (select licencia.id_bien from licencia) and bien.debaja IS NULL and bien.codunicoinfor != '' and bien.idfamiliabien != 87 and (bien.codcolor != 'uriel')";
  $query_limit_list = sprintf("%s LIMIT %d, %d", $query_list, $startRow_list, $maxRows_list);
  $list = mysqli_query($conexion, $query_list) or die(mysqli_error());
  $row_list = mysqli_fetch_assoc($list);

  if (isset($_GET['totalRows_list'])) {
    $totalRows_list = $_GET['totalRows_list'];
  } else {
    $all_list = mysqli_query($conexion, $query_list);
    $totalRows_list = mysqli_num_rows($all_list);
  }
  $totalPages_list = ceil($totalRows_list / $maxRows_list) - 1;

  $queryString_list = "";
  if (!empty($_SERVER['QUERY_STRING'])) {
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();
    foreach ($params as $param) {
      if (
        stristr($param, "pageNum_list") == false &&
        stristr($param, "totalRows_list") == false
      ) {
        array_push($newParams, $param);
      }
    }
    if (count($newParams) != 0) {
      $queryString_list = "&" . htmlentities(implode("&", $newParams));
    }
  }
  $queryString_list = sprintf("&totalRows_list=%d%s", $totalRows_list, $queryString_list);
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
      $activarmenu = 'registrar-licencia';
      include('aside.php');
      ?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            <i class="fa fa-file-archive-o"></i> Asigna Informes<a href="#info1" class="inf btn pull-right btn-xs btn-default" title="Agregar mas registros" style=" font-size:12px; color:#C10000"><span class="glyphicon glyphicon-plus"></span></a>
          </h1>
        </section>
        <section class="content">
          <div class="row">
            <h1 style="text-align: center;">Informes pendientes por asignar</h1>
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body">
                  <div class="table-responsive">
                    <table id="servicios" width="100%" class="table table-hover display">
                      <thead>
                        <tr class="<?php echo 'titulotabla'; ?>">
                          <td width="">N°</td>
                          <td>No. Informe</td>
                          <td width="">Empresa</td>                            
                          <td width="20%">Norma</td>
                          <td width="">item</td>
                          <td width="15%">Fechas</td>
                          <td width="20%">Observaciones</td>
                          <td width="18%">ing</td>
                          <td>--</td>                            
                        </tr>
                      </thead>
                      <?php if ($totalRows_list > 0) {
                        $i = 0;
                        do {
                          $i += 1; ?>
                            <tr>
                              <form class="form-horizontal" method="POST" enctype='multipart/form-data' name="form1" action="asig-ing.php">
                              <td><?php echo $i; ?></td>
                              <td><?php echo $row_list['codbieninv']; ?></td>
                              <td>
                              <strong>Empresa: </strong><?php echo $row_list['razonsocial']; ?><br>
                              <strong>Gestor: </strong><?php echo $row_list['codcolor']; ?>
                            </td>
                              <td>                             
                              <?php echo $row_list['codfamiliabien']; ?>
                            </td>                                                          
                              <td>
                              <strong>Item: </strong><?php echo $row_list['nombien']; ?><br>
                              <strong>Marca: </strong><?php echo $row_list['codmarca']; ?><br>
                              <strong>Modelo: </strong> <?php echo $row_list['codmodelo']; ?><br>
                            </td>
                            <td>
                              <strong>Fecha de recepción: </strong><br><?php echo $row_list['fechaadq']; ?><br>
                              <strong>Fecha de entrega propuesta: </strong><br><?php echo $row_list['tiempovida']; ?><br>                              
                            </td>
                              <td>
                                <textarea class="form-control" name="observacionesasig" id="observacionesasig" value="" placeholder="ESCRIBE UNA OBSERVACION DEL INFORME"></textarea>
                              </td>
                              <td>
                                <select class="form-control select2" name="idusuario" id="idusuario">
                          <option value="">-- Seleccione a un ingeniero-- </option>
                                  <?php
                                  $rs = mysqli_query($conexion, "SELECT idusuario, nombres, apellidos, tipo FROM usuario where tipo = 'INGLABORATORISTA'");
                                  while ($off = mysqli_fetch_array($rs)) {
                                  ?>
                                    <OPTION VALUE=<?php echo $off['idusuario']; ?>><?php echo $off['nombres']; ?> <?php echo $off['apellidos']; ?></OPTION>
                                  <?php
                                  }
                                  ?>
                                </select>
                              </td>
                            <td style="padding:1px; margin:0px">
                                <input type="hidden" name="id_bien" id="id_bien" value="<?php echo $row_list['idbien']; ?>">
                                <input type="hidden" name="MM_update" value="form1">
                                <button type="submit" class="btn btn-info pull-right" title="Grabar registro">Asignar Informe</button>
                              </td> 
                            </form>
                            </tr>
                        <?php } while ($row_list = mysqli_fetch_assoc($list));
                        } ?>
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
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script src="plugins/select2/select2.full.min.js"></script>
    <!-- DataTables -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>


    <script type="text/javascript" language="javascript">
      $('.select2').select2({

      });
    </script>

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