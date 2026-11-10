<?php require_once('Connections/conexion.php');
if (!isset($_SESSION)) {
  session_start();
} ?>
<?php
$idbien = '';
if(isset( $_GET['id'])){ $idbien = $_GET['id'];}


$query_lista = "SELECT bien.*,biencatalogo.nombien, proveedor.codproveedor, proveedor.razonsocial, familiabien.codfamiliabien, familiabien.familiabien FROM bien inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat  inner join proveedor on bien.idproveedor = proveedor.idproveedor inner join familiabien on bien.idfamiliabien = familiabien.idfamiliabien  WHERE bien.idbien ='$idbien' ORDER BY biencatalogo.nombien ASC";
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$roowfila=mysqli_fetch_array($lista);
$totalRows_lista = mysqli_num_rows($lista);  


$query_listamov = "SELECT * FROM movimiento where idbien = '".$roowfila['idbien']."' order by idmov desc limit 1 ";
$listamov = mysqli_query($conexion, $query_listamov) or die(mysqli_error());
$roowfilamov=mysqli_fetch_array($listamov);
$totalRows_listamov = mysqli_num_rows($listamov);  


 ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema TESO</title>
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
  $activarmenu='imprimir-baja';
  include('aside.php');
  ?>

    <div class="content-wrapper">

      <section class="content-header">
        <h1>
          <i class="fa fa-print"></i>
          Impresión de baja del INFORME
        </h1>
        <ol class="breadcrumb">
          <a href="javascript:history.back(-1);" class="btn btn-default btn-sm" title="Volver atras">Volver atras <span
              class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a> <a
            href='javascript:window.print(); void 0;' id="btnImprimirPantalla" class="btn btn-primary btn-sm"
            role="button" title="Imprimir reporte"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
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


                  <!-- Titulo del reporte-->
                  <div style="text-align:center" class="visible-print-block">
                    <strong> FICHA DE LA<br>
                      BAJA </strong>

                  </div>



                  <table width="100%" border="0" style="background-color: #F4F4F4">


                    <tr valign="baseline">
                      <td width="25%">&nbsp;</td>
                      <td width="25%">&nbsp;</td>
                      <!-- <td width="25%" nowrap align="right" style=" padding-right:10px;height:30px"><strong>N. baja:
                        </strong></td> -->
                      <!-- <td width="25%">
                        <?php $idmov = substr ( '00000'.$roowfilamov['idmov'], -6);?>
                        <input disabled type="text" name="idmov" value="<?php echo $idmov;?>">
                      </td> -->
                    </tr>
                    <tr valign="baseline">
                      <td style="height:30px"><strong>No INFORME:</strong></td>
                      <td><?php echo $roowfila['codbieninv'];?></td>
                      <td><strong>FECHA DEL BAJA:</strong></td>
                      <td><?php echo $roowfilamov['fechareg'] ;?>></td>
                    </tr>


                    <tr valign="baseline">
                      <td style="height:30px"><strong>ITEM:</strong></td>
                      <td colspan="3"><?php echo $roowfila['nombien'];?></td>
                    </tr>
                    <tr valign="baseline">
                      <td style="height:30px"><strong>CONSTA / INCLUYE:</strong></td>
                      <td><?php echo $roowfila['caracteristica'];?></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr valign="baseline">
                      <td style="height:30px"><strong>MARCA:</strong></td>
                      <td><?php echo $roowfila['codmarca'];?></td>
                      <td><strong>MODELO:</strong></td>
                      <td><?php echo $roowfila['codmodelo'];?></td>
                    </tr>
                    <tr valign="baseline">
                      <td style="height:30px"><strong>SERIE: </strong></td>
                      <td><?php echo $roowfila['serie'];?></td>
                      <td><strong>CONTACTO:</strong></td>
                      <td><?php echo $roowfila['codcolor'];?></td>
                    </tr>
                    <tr valign="baseline">
                      <td style=" padding-right:10px;height:30px"><strong>NORMA:</strong></td>
                      <td><?php echo $roowfila['codfamiliabien'];?></td>
                      <td><strong>DESCRIPCION DE LA NORMA:</strong></td>
                      <td>&nbsp;</td>
                    </tr>

                    <tr valign="baseline">
                      <td style="height:30px"><strong>FECHA DE RECEPCION DEL ITEM:</strong></td>
                      <td><?php echo $roowfila['fechaadq'];?></td>
                      <td><strong>FECHA DE ENTREGA:</strong></td>
                      <td><?php echo $roowfila['tiempovida'];?></td>
                    </tr>

                    <tr valign="baseline">
                      <td><strong>VALOR:</strong></td>
                      <td>$ <?php echo $roowfila['valor'];?></td>
                    </tr>
                    <tr valign="baseline">
                      <td style="height:30px"><strong>CLIENTE:</strong></td>
                      <td><?php echo $roowfila['razonsocial'];?></td>
                      <td><strong>EMPRESA.</strong></td>
                      <td>
                        <?php echo $roowfila['codproveedor'];?></td>
                    </tr>

                    <tr valign="baseline" class="visible-print-block">
                      <td style="height:30px">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td style=" padding-right:10px; ">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>




                    <tr valign="baseline">
                      <td style="height:30px"><strong>CONDICION ACTUAL:</strong></td>
                      <td><?php echo $roowfilamov['condicion'];?></td>
                      <td><strong>INFORME TÉCNICO:</strong></td>
                      <td><?php echo $roowfilamov['doctransf'];?></td>
                    </tr>


                    <tr valign="baseline">
                      <td><strong>RAZÓN DE BAJA:</strong></td>
                      <td colspan="3"><?php echo $roowfilamov['objetomov'];?></td>
                    </tr>






                    <tr valign="baseline">
                      <td colspan="4">&nbsp;</td>
                    </tr>
                  </table>



                  <div class="row visible-print-block"><br>
                    <br>
                    <br><br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>

                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>

                    <!-- <div class="col-xs-4 text-center">Firma<br>Jefe de Departamento o Unidad</div>
                    <div class="col-xs-4 text-center">Firma<br>Funcionario Responsable del bien</div>
                    <div class="col-xs-4 text-center">Firma<br>Encargado del Inventario</div> -->
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