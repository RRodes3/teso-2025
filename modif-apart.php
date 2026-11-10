<?php require_once('Connections/conexion.php');
$menuactivo = 'movimiento';
if (!isset($_SESSION)) {
  session_start();
} ?>
<?php
$idbien = '';

$metodo = '';
if (isset($_GET['metodo'])) {
  $metodo = $_GET['metodo'];
}

if (isset($_GET['id'])) {
  $idbien = $_GET['id'];
}


$query_lista = "SELECT bien.*,biencatalogo.nombien, proveedor.codproveedor, proveedor.razonsocial, familiabien.codfamiliabien, 
familiabien.familiabien FROM bien inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat  
inner join proveedor on bien.idproveedor = proveedor.idproveedor 
inner join familiabien on bien.idfamiliabien = familiabien.idfamiliabien WHERE bien.idbien ='$idbien'";
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$row_lista = mysqli_fetch_array($lista);
$totalRows_lista = mysqli_num_rows($lista);

$query_listamov = "SELECT * FROM movimiento where idbien = '" . $row_lista['idbien'] . "' order by idmov desc limit 1 ";
$listamov = mysqli_query($conexion, $query_listamov) or die(mysqli_error());
$row_listamov = mysqli_fetch_array($listamov);
$totalRows_listamov = mysqli_num_rows($listamov);


$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
function generate_string($input, $strength = 16)
{
  $input_length = strlen($input);
  $random_string = 'TeSo';
  for ($i = 0; $i < $strength; $i++) {
    $random_character = $input[mt_rand(0, $input_length - 1)];
    $random_string .= $random_character;
  }
  return $random_string;
}
$codigounico = generate_string($permitted_chars, 64);



?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sistema Teso</title>
  <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
  <meta name="author" content="Eduardo Sandoval">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
  <script src="includes/carganormal.js"></script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <header class="main-header">
      <?php include('header.php'); ?>
    </header>
    <?php
    $activarmenu = 'registro-bien';
    include('aside.php');
    ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <span class="glyphicon glyphicon-plus"></span>Registrar Nuevo Informe
        </h1>
      </section>

      <section class="content">
        <div class="modal fade" id="modalInicio" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title color" id="myModalLabel">¿Estas seguro de registrar el informe? Ya se encuenta un informe con los mismos datos
                </h4>
              </div>
              <div class="modal-body">
                <strong class="alert-warning">Detalles:</strong><br>
                <strong>No. Teso: </strong> <?php echo json_encode($row_lista_bien['codbieninv']); ?> <br>
                <strong>Marca: </strong> <?php echo json_encode($row_lista_bien['codmarca']); ?> <br>
                <strong>Modelo: </strong> <?php echo json_encode($row_lista_bien['codmodelo']); ?> <br>
              </div>
              <div class="modal-footer">
                <input name="idfamiliabien" type="hidden" required id="idfamiliabien" value="" size="32">
                <input name="nomtabla" type="hidden" required id="nomtabla" value="familiabien" size="32">
                <button type="button" class="btn btn-default" data-dismiss="modal" title="Cancelar">Cancelar</button>
                <input type="button" class="btn btn-default" id="enviar" data-dismiss="modal" title="Si" value="OK">
              </div>
            </div>
          </div>
        </div>

        <form class="form-horizontal" method="post" enctype='multipart/form-data' name="form1" action="proce_modapart.php">    
          <div class="row">
            <div class="col-md-6">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">ALTA INFORME</h3>
                </div>
                <div class="box-body">
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">El Item es por otra norma?:</label>
                    <div class="col-sm-9">
                      <select class="form-control" name="existencia" id="existencia">
                        <OPTION selected value="NO">NO</OPTION>
                        <OPTION value="SI">SI</OPTION>
                      </select>
                    </div>
                  </div>                  
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Codigo cliente:</label>
                    <div class="col-sm-9">
                      <select class="form-control select2" required name="idproveedor" id="idproveedor" onchange="Carga('includes/imput_codinv.php?codfam='+this.value, 'DivReporte')">
                        <option value="">-- Seleccione -- </option>
                        <?php
                        $rs = mysqli_query($conexion, "SELECT idproveedor, codproveedor, razonsocial FROM proveedor");
                        while ($off = mysqli_fetch_array($rs)) {
                        ?>
                          <OPTION VALUE="<?php echo $off['idproveedor']; ?>"><?php echo $off['codproveedor'] . ' - ' . $off['razonsocial']; ?></OPTION>
                        <?php
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Representante:</label>
                    <div class="col-sm-9">
                      <div id="DivReporte">
                        <input class="form-control" type="text" name="codbieninvs" id="codbieninvs" value="" placeholder="Representante" readonly autocomplete="off">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Facturar a:</label>
                    <div class="col-sm-9">
                      <input name="facturara" type="text" class="form-control" id="facturara" autocomplete="off" required placeholder="Escribe aquien se le va a faturar">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Norma:</label>
                    <div class="col-sm-9">
                      <select class="form-control select2" required name="idfamiliabien" id="idfamiliabien" onchange="Carga('includes/imput_codinv.php?codfam='+this.value, 'DivReportee')">
                        <option value="">-- Seleccione una Norma-- </option>
                        <?php
                        $rs = mysqli_query($conexion, "SELECT * FROM familiabien ORDER BY familiabien ");
                        while ($off = mysqli_fetch_array($rs)) {
                        ?>
                          <OPTION VALUE="<?php echo $off['idfamiliabien']; ?>">
                            <?php echo $off['codfamiliabien'] . ' - ' . $off['familiabien']; ?></OPTION>
                        <?php
                        }
                        ?>
                      </select>
                    </div>
                  </div>                
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">No. Teso:</label>
                    <div class="col-sm-9">

                      <input type="hidden" id="idbien" name="idbien" value="<?php echo $row_lista['idbien']; ?>">
                      <div id="DivReporte"><input class="form-control" required readonly type="text" name="codbieninv" required id="codbieninv" value="<?php echo $row_lista['codbieninv']; ?>"></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Item:</label>
                    <div class="col-sm-9">
                      <select class="form-control select2" required name="codbiencat" id="codbiencat">
                        <option value="">-- Seleccione una Item-- </option>
                        <?php
                        $rs = mysqli_query($conexion, "SELECT * FROM biencatalogo ORDER BY nombien ");
                        while ($off = mysqli_fetch_array($rs)) {
                        ?>
                          <OPTION VALUE=<?php echo $off['codbiencat']; ?>><?php echo $off['nombien']; ?></OPTION>
                        <?php
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Marca:</label>
                    <div class="col-sm-9">
                      <input name="codmarca" type="text" class="form-control" id="codmarca" list="datalistamarca" autocomplete="off" required value="<?php if (isset($_POST['codmarca'])) echo $_POST['codmarca']; ?>" placeholder="Escribe o seleccione la marca">
                      <datalist id="datalistamarca">
                        <?php
                        $rs = mysqli_query($conexion, "SELECT distinct codmarca FROM bien order by codmarca");
                        while ($off = mysqli_fetch_array($rs)) {
                        ?>
                          <option><?php echo $off['codmarca']; ?></option>
                        <?php
                        }
                        ?>
                      </datalist>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Modelo:</label>
                    <div class="col-sm-9">
                      <input name="codmodelo" type="text" class="form-control" id="codmodelo" list="datalistamodelo" autocomplete="off" value="<?php if (isset($_POST['codmodelo'])) echo $_POST['codmodelo']; ?>" placeholder="Escribe o seleccione el modelo" required>
                      <datalist id="datalistamodelo">
                        <?php
                        $rs = mysqli_query($conexion, "SELECT distinct codmodelo FROM bien order by codmodelo");
                        while ($off = mysqli_fetch_array($rs)) {
                        ?>
                          <option><?php echo $off['codmodelo']; ?></option>
                        <?php
                        }
                        ?>
                      </datalist>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Serie:</label>
                    <div class="col-sm-9">
                      <input class="form-control" type="text" name="serie" value="<?php if (isset($_POST['serie'])) echo $_POST['serie']; ?>" placeholder="Serie">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Consta / Incluye:</label>
                    <div class="col-sm-9">
                      <textarea class="form-control" type="text" id="caracteristica" name="caracteristica" value="<?php echo $_POST['caracteristica']; ?>" placeholder="Accesorios"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Gestor:</label>
                    <div class="col-sm-9">
                      <input name="codcolor" type="text" class="form-control" id="codcolor" list="datalistacolor" autocomplete="off" value="<?php if (isset($_POST['codcolor'])) echo $_POST['codcolor']; ?>"" placeholder=" Escribe o seleccione el contacto">
                      <datalist id="datalistacolor">
                        <?php
                        $rs = mysqli_query($conexion, "SELECT distinct codcolor FROM bien order by codcolor");
                        while ($off = mysqli_fetch_array($rs)) {
                        ?>
                          <option><?php echo $off['codcolor']; ?></option>
                        <?php
                        }
                        ?>
                      </datalist>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Precio:</label>
                    <div class="col-sm-9">
                      <div class="input-group">
                        <div class="input-group-addon">$</div><input name="valor" type="number" class="form-control" min="0.0" step="any" required id="valor" placeholder="0.00" value="<?php if (isset($_POST['valor'])) echo $_POST['valor']; ?>"">
                      </div>
                    </div>
                  </div>
                  <div class=" form-group">
                        <label for="id" class="col-sm-3 control-label">Fecha de ingreso de solicitud:</label>
                        <div class="col-sm-9">
                          <input class="form-control" name="fecharecitem" type="date" id="fecharecitem" placeholder="Años/Mes/Dias" value="<?php echo $row_lista['fechaadq']; ?>">
                        </div>
                  </div>
                  <div class=" form-group">
                        <label for="id" class="col-sm-3 control-label">Fecha de recepcción de item:</label>
                        <div class="col-sm-9">
                          <input class="form-control" name="fechaadq" type="date" id="fechaadq" placeholder="Años/Mes/Dias" value="<?php echo $row_lista['fecharecitem']; ?>">
                        </div>
                  </div>
                  <div class=" form-group">
                        <label for="id" class="col-sm-3 control-label">Fecha de entrega propuesta:</label>
                        <div class="col-sm-9">
                          <input class="form-control" name="tiempovida" type="date" required id="tiempovida" placeholder="Años/Mes/Dias" value="<?php echo $row_lista['tiempovida']; ?>">
                        </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="box box-info">
                <div class="box-body">
                      <div class="form-group">
                        <label for="id" class="col-sm-3 control-label">Categoria del Item:</label>
                        <div class="col-sm-9">
                          <select class="form-control" name="condicion" id="condicion">
                            <OPTION selected value="NUEVO">NUEVO</OPTION>
                            <OPTION value="REACONDICIONADO">REACONDICIONADO</OPTION>
                            <OPTION value="RECONSTRUIDO">RECONSTRUIDO</OPTION>
                            <OPTION value="USADO">USADO</OPTION>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="id" class="col-sm-3 control-label">Tensión:</label>
                        <div class="col-sm-9">
                          <select class="form-control" name="clasificacion" id="clasificacion">
                            <OPTION selected value="TENSION NORMAL">TENSION NORMAL</OPTION>
                            <OPTION value="EXTRABAJA">EXTRABAJA</OPTION>
                            <OPTION value="KIT">KIT </OPTION>
                            <OPTION value="2K">> 2K</OPTION>
                            <OPTION value="METALMECANICA">METALMECANICA</OPTION>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="id" class="col-sm-3 control-label">El informe es de seguimiento:</label>
                        <div class="col-sm-9">
                          <select class="form-control" name="segui" id="segui">
                            <OPTION selected value=" ">NO</OPTION>
                            <OPTION value="SEGUIMIENTO">SI</OPTION>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="id" class="col-sm-3 control-label">Observaciones:</label>
                        <div class="col-sm-9">
                          <textarea class="form-control" required name="observaciones" id="observaciones" value="" placeholder="ESCRIBE UNA OBSERVACION DEL INFORME"></textarea>
                        </div>
                      </div>

                        <input type="hidden" name="MM_update" value="form1">
                        
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                      <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                      <button type="submit" class="btn btn-info pull-right" title="Grabar registro">GUARDAR</button>
                </div>
              </div>
            </div>
          </div>
        </form>
        
      </section>
    </div>
    <?php include('footer.html'); ?>
  </div>
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="plugins/fastclick/fastclick.js"></script>
  <script src="dist/js/app.min.js"></script>
  <script src="plugins/select2/select2.full.min.js"></script>
  <script src="xreczfsf/vistas/plugins/sweetalert2/sweetalert2.all.js"></script>
  <script>
    $(function() {
      $(".select2").select2();
    });
  </script>
  
</body>

</html>