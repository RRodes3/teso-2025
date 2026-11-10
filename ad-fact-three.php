<?php
if (!isset($_SESSION)) {
  session_start();
}
if (isset($_SESSION['U_spprivilegio']) and in_array("f-fact-control", $_SESSION['U_spprivilegio'])) {

  require_once('Connections/conexion.php');
  $menuactivo = 'facturacion';
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
  $maxRows_lista = 8001;
  $pageNum_lista = 0;
  if (isset($_GET['pageNum_lista'])) {
    $pageNum_lista = $_GET['pageNum_lista'];
  }
  $startRow_lista = $pageNum_lista * $maxRows_lista;

  $colname_lista = "-1";
  if (in_array("m_tododepedencia", $_SESSION['U_spprivilegio'])) {
    $query_lista = "SELECT bien.idbien, bien.codbieninv, bien.codmarca, bien.codmodelo, bien.facturara, bien.codcolor, bien.numerosc, bien.valor, bien.factura, bien.codfactura, bien.fechafactura, bien.fechapagosc, bien.montopagosc, biencatalogo.nombien, familiabien.codfamiliabien, usuario.nombres, usuario.apellidos, proveedor.codproveedor, proveedor.razonsocial FROM bien
  inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat
  left join familiabien on familiabien.idfamiliabien = bien.idfamiliabien
  left join licencia on licencia.id_bien = bien.idbien
  left join movimiento on movimiento.idbien = bien.idbien 
  left join proveedor on proveedor.idproveedor = bien.idproveedor 
  left join usuario on licencia.idusuario = usuario.idusuario where bien.idbien between 17001 and 22500 and ( proveedor.codproveedor <> '--' )";
  } else {
    $query_lista = "SELECT  bien.*,biencatalogo.nombien,   m.idmov, m.idoficinadest, m.foto, m.condicion, m.observacion, m.idusuarioreg,m.objetomov, m.doctransf, oficina.nomoficina,dependencia.dependencia, concat_ws (' ',r.nombres , r.apellidos) as responsable FROM bien inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat left join movimiento m on m.idbien = bien.idbien and m.idmov = bien.idmovimiento left join oficina on oficina.idoficina = m.idoficinadest left join dependencia on dependencia.iddependencia = oficina.iddependencia left join responsable r on r.idresponsable = oficina.idrespoficina where dependencia.iddependencia = '" . $_SESSION['U_spiddependencia'] . "'  order by biencatalogo.nombien, bien.codbieninv asc";
  }
  $query_limit_lista = sprintf("%s LIMIT %d, %d", $query_lista, $startRow_lista, $maxRows_lista);
  $lista = mysqli_query($conexion, $query_limit_lista) or die(mysqli_error());
  $row_lista = mysqli_fetch_assoc($lista);
  $totalRows_lista = mysqli_num_rows($lista);




  //$query_lista = "SELECT bien.idbien, bien.codbieninv, bien.codmarca, bien.codmodelo, bien.facturara, bien.codcolor, bien.valor, bien.factura, bien.codfactura, bien.fechafactura, bien.fechapagosc, bien.montopagosc, biencatalogo.nombien, familiabien.codfamiliabien, usuario.nombres, usuario.apellidos FROM bien
  //inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat
  //left join familiabien on familiabien.idfamiliabien = bien.idfamiliabien
  //left join licencia on licencia.id_bien = bien.idbien
  //left join usuario on licencia.idusuario = usuario.idusuario";
  //  $lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
  // $row_lista = mysqli_fetch_assoc($lista);
  // $totalRows_lista = mysqli_num_rows($lista);
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
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
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
      $activarmenu = 'registrar-catalogo';
      include('aside.php');
      ?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            <span class="glyphicon glyphicon-plus"></span> Teso facturación
          </h1>


          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell"></i>
              <span class="label label-warning">
                <?php
                $query_lista_hallazgo = "SELECT count(*) as modclasificacion from movimiento where modclasificacion = 1";
                $lista_hallazgo = mysqli_query($conexion, $query_lista_hallazgo) or die(mysqli_error());
                $row_lista_hallazgo = mysqli_fetch_assoc($lista_hallazgo);
                echo $row_lista_hallazgo['modclasificacion'];
                ?>
              </span>
            </a>

            <ul class="dropdown-menu" style="padding: 20px; border: 2px solid red;">
              <li class="header"> <b> Tienes <?php echo $row_lista_hallazgo['modclasificacion']; ?> Notificaciones</b> <br></li>
              <li class="header"><b>
                  <?php
                  $resultado = mysqli_query($conexion, "SELECT l.*,b.*, c.nombien, m.clasificacion, u.nombres, u.apellidos FROM `licencia` l 
                  INNER JOIN `bien` b ON l.id_bien = b.idbien
                  INNER JOIN `usuario` u ON l.idusuario = u.idusuario
                  INNER JOIN `biencatalogo` c ON b.codbiencat = c.codbiencat 
                  INNER JOIN `movimiento` m ON b.idbien = m.idbien
                  WHERE modclasificacion = 1");
                  while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                    echo 'Informe: ' . $fila['codbieninv'] . " / Fue acualizada como: " . $fila['clasificacion'] . '<br>';
                  }
                  mysqli_free_result($resultado);
                  ?>
                  <form action="updateRead.php" method="POST" name="updateRead">
                    <input name="nomok" type="hidden" required id="nomok" value="updateRead" size="32">
                    <input name="updateRead" type="submit" class=" btn btn-primary" title="updateRead" value="Update">
                  </form>

                </b>
              </li>
              <li class="footer"></li>
            </ul>
          </li>

          <ol class="breadcrumb">

            <form action="list-facturacion.php" method="post" id="FormularioExportacion">
              <a href='javascript:window.print(); void 0;' id="btnImprimirPantalla" class="btn btn-primary btn-sm " role="button" title="Imprimir reporte" stsyle="font-size:10px"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
              <button type="submit" id="botonExcel" class="btn btn-warning btn-sm "><span class="glyphicon glyphicon-download-alt"></span> Descargar</button>
              <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
              <input type="hidden" id="nomarchivo" name="nomarchivo" value="Lista Estatus facturacion.xls" />
            </form>
          </ol>
        </section>
        <section class="content">
          <!--    --- Inicio Lista-->
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body">
                  <div class="table-responsive">
                    <table id="servicios" width="100%" class="table table-hover display">
                      <thead>
                        <tr class="<?php echo 'titulotabla'; ?>">
                          <td width="5%">N°</td>
                          <td width="5%">N° de Teso</td>
                          <td width="5%">FOLIO</td>
                          <!-- <td>TENSION</td> -->
                          <td width="10%">INGENIERO</td>
                          <td width="10%">NORMA</td>
                          <td width="5%">ESTADO</td>
                          <td width="10%">CODIGO/FACTURA</td>
                          <td width="10%">FECHA FACTURACION</td>
                          <td width="5">--</td>
                          <td width="15">FECHA DE PAGO</td>
                          <td width="15">PRECIO FINAL</td>
                          <td width="10">PAGADO</td>
                        </tr>
                      </thead>
                      <?php if ($totalRows_lista > 0) {
                        $i = 0;
                        do {
                          $i += 1; ?>
                          <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $row_lista['codbieninv']; ?></td>
                            <td><?php echo $row_lista['numerosc']; ?></td>
                            <!-- <td><?php echo $row_lista['clasificacion']; ?></td> -->
                            <td><?php echo $row_lista['nombres'] . ' ' . $row_lista['apellidos']; ?></td>
                            <td><?php echo $row_lista['codfamiliabien']; ?></td>
                            <td><?php if ($row_lista['factura'] == '1') {
                                  echo ' <span class="label label-success  " style="font-size:14px">FACTURADO</span> ';
                                } elseif ($row_lista['factura'] == '2') {
                                  echo ' <span class="label label-warning " style="font-size:14px">PREFACTURADO</span> ';
                                } else {
                                  echo ' <span class="label label-danger " style="font-size:14px">PENDIENTE</span> ';
                                } ?>
                            </td>
                            <td><?php echo $row_lista['codfactura']; ?></td>
                            <td><?php echo $row_lista['fechafactura']; ?></td>


                            <td style="padding:1px; margin:0px">
                              <a href="#" data-toggle="modal" data-target="#factura<?php echo $row_lista['idbien']; ?>" title="Editar" stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-success btn-sm">
                                <span class="glyphicon glyphicon-pencil"></span>
                              </a>
                              <form class="form-horizontal" method="post" enctype='multipart/form-data' name="form3" action="editar-facturacion.php">
                                <div class="modal fade" id="factura<?php echo $row_lista['idbien']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title color" id="myModalLabel">¿el informe ha sido Prefacturado o Facturado?
                                        </h4>
                                      </div>
                                      <div class="modal-body">
                                        <strong class="alert-warning">Detalles:</strong><br>
                                        <div class="col-sm-12">
                                          <div class="col-sm-6">
                                            <strong>No. Informe:</strong>
                                            <?php echo $row_lista['codbieninv']; ?><br>
                                            <strong>Item:</strong>
                                            <?php echo $row_lista['nombien']; ?><br>
                                            <strong>Marca:</strong>
                                            <?php echo $row_lista['codmarca']; ?><br>
                                            <strong>Modelo:</strong>
                                            <?php echo $row_lista['codmodelo']; ?><br>
                                          </div>
                                          <div class="col-sm-6">
                                            <strong>Facturar a:</strong>
                                            <?php echo $row_lista['facturara']; ?><br>
                                            <strong>Gestor:</strong>
                                            <?php echo $row_lista['codcolor']; ?><br>
                                            <strong>Precio:</strong>
                                            <?php echo $row_lista['valor']; ?><br>
                                          </div>
                                        </div>
                                        <div class="col-sm-12">
                                          <div class="form-group">
                                            <label for="id" class="col-sm-3 control-label">Tipo:</label>
                                            <div class="col-sm-9">
                                              <input name="idbien" type="hidden" required id="idbien" value="<?php echo htmlentities($row_lista['idbien'], ENT_COMPAT, 'utf-8'); ?>">
                                              <select class="" required name="factura" id="factura">
                                                <option value="">-- Seleccione una opcion-- </option>
                                                <OPTION value=" ">LIBERAR</OPTION>
                                                <OPTION value="1">FACTURADO</OPTION>
                                                <OPTION value="2">PREFACTURADO</OPTION>
                                              </select>
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <label for="id" class="col-sm-4 control-label">Numero de Factura:</label>
                                            <div class="col-sm-8">
                                              <input class="form-control" type="text" name="codfactura" value="<?php echo htmlentities($row_lista['codfactura'], ENT_COMPAT, ''); ?>">
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <label for="id" class="col-sm-4 control-label">Fecha de facturacion/prefatura:</label>
                                            <div class="col-sm-8">
                                              <input type="date" class="form-control" name="fechafactura" value="<?php echo htmlentities($row_lista['fechafactura'], ENT_COMPAT, ''); ?>">
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <label for="id" class="col-sm-4 control-label">Fecha de pago</label>
                                            <div class="col-sm-8">
                                              <input type="date" class="form-control" name="fechapagosc" value="<?php echo htmlentities($row_lista['fechapagosc'], ENT_COMPAT, ''); ?>">
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <label for="id" class="col-sm-4 control-label">Precio final</label>
                                            <div class="col-sm-8">
                                              <input type="text" class="form-control" name="montopagosc" value="<?php echo htmlentities($row_lista['montopagosc'], ENT_COMPAT, ''); ?>">
                                            </div>
                                          </div>
                                        </div>
                                        <input type="hidden" name="MM_update" value="form3">
                                      </div>
                                      <div class="modal-footer">
                                        <!-- <a href="javascript:history.back(-1);"class="btn btn-default" title="Cancelar">CANCELAR</a> -->
                                        <button type="submit" class="btn btn-info pull-right" title="Grabar registro">Actualizar</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </form>
                            </td>
                            <td><?php echo $row_lista['fechapagosc']; ?></td>
                            <td>$<?php echo $row_lista['montopagosc']; ?></td>
                            <td><?php if ($row_lista['fechapagosc'] != "") {
                                  echo ' <span class="label label-success" style="font-size:14px">PAGADO</span> ';
                                } ?>
                            </td>
                          </tr>
                      <?php } while ($row_lista = mysqli_fetch_assoc($lista));
                      } ?>
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
<?php } else {
  header("Location: pagina-restringida.php");
} ?>