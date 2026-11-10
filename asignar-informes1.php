<?php
if (!isset($_SESSION)) {
  session_start();
}
if (isset($_SESSION['U_spprivilegio']) and in_array("s_asig", $_SESSION['U_spprivilegio'])) {
  require_once('Connections/conexion.php');
  $menuactivo = 'supervision';
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
            <?php
            $query1 = mysqli_query($conexion, "Select bien.*,biencatalogo.nombien, familiabien.codfamiliabien, familiabien, proveedor.razonsocial from bien 
            inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat  
            INNER join proveedor on proveedor.idproveedor = bien.idproveedor
            inner join familiabien on familiabien.idfamiliabien = bien.idfamiliabien 
            where bien.idbien 
            not in (select licencia.id_bien from licencia) and bien.debaja IS NULL and bien.codunicoinfor != '' and bien.idfamiliabien != 87 and (bien.codcolor != 'uriel')");
            while ($off1 = mysqli_fetch_array($query1)) {
            ?>
              <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3" style="padding: 10px;">
                <form class="form-horizontal" method="POST" enctype='multipart/form-data' name="form1" action="asig-ing.php">
                  <div class="card " style="border: 1px solid var(--ux-1r9rmr1,var(--ux-97h3vl,#d3d3d3)); box-shadow: 0 1px 6px 0 rgb(118 118 118 / 25%); background: #ffffff9e; border-radius: 15px; padding: 10px;">
                    <!-- <img src="./img/icono.png" class="card-img-top" alt="Card image" /> -->
                    <div class="card-block">
                    <div class="card-tittle"  style="text-align: center;">                      
                      <h3><strong><?php echo $off1["codbieninv"]; ?></strong></h3>
                      <h4><?php echo $off1["codfamiliabien"]; ?></h4>                      
                      <p>
                      <?php echo $off1["familiabien"]; ?>
                      </p>
                    </div>
                      <p class="card-text">
                        <strong>Empresa: </strong><?php echo $off1['razonsocial']; ?><br>
                        <strong>Gestor: </strong><?php echo $off1['codcolor']; ?><br>
                        <strong>Item: </strong><?php echo $off1['nombien']; ?><br>
                        <strong>Marca: </strong><?php echo $off1['codmarca']; ?><br>
                        <strong>Modelo: </strong> <?php echo $off1['codmodelo']; ?><br>
                        <strong>Fecha de recepción: </strong><?php echo $off1['fechaadq']; ?><br>
                        <strong>Fecha de entrega propuesta: </strong><?php echo $off1['tiempovida']; ?><br>
                      </p>
                      <td>
                        <textarea class="form-control" name="observacionesasig" id="observacionesasig" value="" placeholder="ESCRIBE UNA OBSERVACION DEL INFORME"></textarea>
                      </td> <br>
                      <td>
                        <div class="card-select" style="padding-bottom: 10px;">
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
                        </div>
                      </td><br>
                    </div>
                    <input type="hidden" name="id_bien" id="id_bien" value="<?php echo $off1['idbien']; ?>">
                    <input type="hidden" name="MM_insert_ing" value="form1">                    
                    <button type="submit" class="btn btn-info" style="margin-left: 105px;" title="Grabar registro">Asignar Informe</button>                    
                  </div>
                </form>
              </div>
            <?php
            }
            ?>
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


  </body>

  </html>
<?php } ?>