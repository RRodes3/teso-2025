<?php require_once('Connections/conexion.php');
$menuactivo ='movimiento';
if (!isset($_SESSION)) {
  session_start();
} ?>
<?php
$idbien = '';
if(isset( $_GET['id'])){ $idbien = $_GET['id'];}

$query_lista = "SELECT bien.*,biencatalogo.nombien,  proveedor.codproveedor, proveedor.razonsocial, familiabien.codfamiliabien, familiabien.familiabien FROM bien inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat  inner join proveedor on bien.idproveedor = proveedor.idproveedor inner join familiabien on bien.idfamiliabien = familiabien.idfamiliabien  WHERE bien.idbien ='$idbien' ORDER BY biencatalogo.nombien ASC";
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
  <title>Sistema Teso</title>
  <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
  <meta name="author" content="Eduardo Sandoval">
  <!--Desarrollado por: Eduardo Sandoval - www.facebook.com-->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <link rel="stylesheet" href="plugins/select2/select2.min.css">

</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <?php include('header.php'); ?>
    </header>
    <?php 
    $activarmenu='registrar-baja';
    include('aside.php');
    ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <span class="glyphicon glyphicon-warning-sign"></span> Dar de baja a un informe
        </h1>
      </section>

      <section class="content">


        <div class="row">
          <div class="col-md-7">
            <div class="box box-info">

              <form class="form-horizontal" method="post" enctype='multipart/form-data' name="form1"
                action="proce_regbaja.php">
                <div class="box-body">

                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Empresa / Cliente:</label>
                    <div class="col-sm-9">
                      <input class="form-control" <input disabled type="text" name="idproveedor"
                        value="<?php echo $roowfila['codproveedor'].' - '.$roowfila['razonsocial'];?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Numero de informe:</label>
                    <div class="col-sm-9">
                      <input type="hidden" name="idbien" value="<?php echo $roowfila['idbien'];?>">
                      <input class="form-control" readonly type="text" name="codbieninv"
                        value="<?php echo $roowfila['codbieninv'];?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Nombre del Item:</label>
                    <div class="col-sm-9">
                      <input class="form-control" readonly type="text" name="nombien" id="nombien"
                        value="<?php echo $roowfila['nombien'];?>">

                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Marca:</label>
                    <div class="col-sm-9">

                      <input class="form-control" disabled type="text" name="codmarca" id="codmarca"
                        value="<?php echo $roowfila['codmarca'];?>">

                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Contacto:</label>
                    <div class="col-sm-9">

                      <input class="form-control" disabled type="text" name="codcolor" id="codcolor"
                        value="<?php echo $roowfila['codcolor'];?>">

                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Modelo:</label>
                    <div class="col-sm-9">

                      <input class="form-control" disabled type="text" name="codmodelo"
                        value="<?php echo $roowfila['codmodelo'];?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Serie:</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled type="text" name="serie"
                        value="<?php echo $roowfila['serie'];?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Norma:</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled type="text" name="cuentacontable" id="cuentacontable"
                        value="<?php echo $roowfila['codfamiliabien'];?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Valor:</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled type="number" step="Any" name="valor"
                        value="<?php echo $roowfila['valor'];?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Fecha de Ingreso:</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled type="text" name="fechaadq"
                        value="<?php echo $roowfila['fechaadq'];?>"></td>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Fecha de entrega:</label>
                    <div class="col-sm-9">
                      <input class="form-control" name="tiempovida" type="text" disabled
                        value="<?php echo $roowfila['tiempovida'];?>">
                    </div>
                  </div>
          

                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Foto actual:</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled name='uploadedfile' type='file'>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Condición actual:</label>
                    <div class="col-sm-9">
                      <select class="form-control" disabled name="condicion" id="condicion">

                        <OPTION value="NUEVO" <?php if ( $roowfilamov['condicion'] == 'NUEVO'){ echo 'selected'; };?>>
                          NUEVO</OPTION>
                        <OPTION value="REACONDICIONADO"
                          <?php if ( $roowfilamov['condicion'] == 'REACONDICIONADO'){ echo 'selected'; };?>>
                          REACONDICIONADO</OPTION>
                        <OPTION value="RECONSTRUIDO"
                          <?php if ( $roowfilamov['condicion'] == 'RECONSTRUIDO'){ echo 'selected'; };?>>RECONSTRUIDO
                        </OPTION>
                        <OPTION value="SEGUIMIENTO"
                          <?php if ( $roowfilamov['condicion'] == 'SEGUIMIENTO'){ echo 'selected'; };?>>SEGUIMIENTO
                        </OPTION>
                        <OPTION value="USADO" <?php if ( $roowfilamov['condicion'] == 'USADO'){ echo 'selected'; };?>>
                          USADO</OPTION>

                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Razón por que se elimina:</label>
                    <div class="col-sm-9">
                      <textarea class="form-control" name="objetomov" required placeholder="Razón"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Informe técnico de respaldo:</label>
                    <div class="col-sm-9">
                      <input class="form-control" name='doctransf' type='file'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Fecha de baja:</label>
                    <div class="col-sm-9">
                      <input class="form-control" required readonly type="text" name="fechaad"
                        value="<?php echo date('Y-m-d');?>">

                      <input type="hidden" name="MM_insert" value="form1">
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                  <button type="submit" class="btn btn-info pull-right" title="Grabar registro">GUARDAR</button>
                </div>

              </form>


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
  <script>
  $(function() {
    $(".select2").select2();
  });
  </script>
</body>

</html>