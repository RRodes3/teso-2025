<?php require_once('Connections/conexion.php');
if (!isset($_SESSION)) {
  session_start();
}
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formalmacen")) {


  $idbien = $_POST['idbien'];
  $informe = $_POST['noinforme'];
  $entregado = 1;

  date_default_timezone_set('America/Bogota');
  $fecha_entrega = date('Y-m-d h:i:s');

  $insertSQL = sprintf(
    "INSERT INTO entregamuestra (idinforme,	metodoentrega, entrega, recibe, mensajero, paqueteria, noguia, observaciones, fechaentrega
) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
    GetSQLValueString($_POST['idbien'], "int"),
    GetSQLValueString($_POST['metodoentrega'], "text"),
    GetSQLValueString($_POST['entrega'], "text"),
    GetSQLValueString($_POST['recibe'], "text"),
    GetSQLValueString($_POST['mensajero'], "text"),
    GetSQLValueString($_POST['paqueteria'], "text"),
    GetSQLValueString($_POST['noguia'], "text"),
    GetSQLValueString($_POST['obs'], "text"),
    GetSQLValueString($fecha_entrega, "date")
  );
  $Result1 = mysqli_query($conexion, $insertSQL) or die(mysqli_error());

  $query_updateAlmacen = "UPDATE  `bien` SET  `entrega` =  '" . $entregado . "' WHERE  `idbien` = '" . $idbien . "'";
  $listaAlmacen = mysqli_query($conexion, $query_updateAlmacen) or die(mysqli_error());

echo "<script>history.go(-2);</script>";

  //Registrar auditoria
  $query_auditoria = "insert into auditoria (fecharegistro,idusuario,accion,numacta) values (Now(),'" . $idusuario . "','Usuario de almacen entrego el informe: " . $informe . "')";
  $lista_audit = mysqli_query($conexion, $query_auditoria) or die(mysqli_error());
}

$colname_modificaroficina = "-1";
if (isset($_GET['codigo'])) {
  $colname_modificaroficina = $_GET['codigo'];
}

$query_modificaroficina = sprintf("SELECT bien.*, biencatalogo.nombien, proveedor.codproveedor, 
proveedor.razonsocial, proveedor.personacontacto FROM bien 
inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat 
left join proveedor on proveedor.idproveedor = bien.idproveedor 
where bien.idbien =  %s", GetSQLValueString($colname_modificaroficina, "int"));
$modificaroficina = mysqli_query($conexion, $query_modificaroficina) or die(mysqli_error());
$row_modificaroficina = mysqli_fetch_assoc($modificaroficina);
$totalRows_modificaroficina = mysqli_num_rows($modificaroficina);
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
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <header class="main-header">
      <?php include('header.php'); ?>
    </header>
    <?php
    $activarmenu = 'modificar-familia';
    include('aside.php');
    ?>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <span class="glyphicon glyphicon-edit"></span>
        </h1>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-md-7">
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">ENTREGA DE MUESTRA- fecha:</h3>
                <h3 class="box-title"><?php
                                      date_default_timezone_set('America/Bogota');
                                    echo $fech_entrega = date('d-m-Y');;
                                      ?>
                </h3>
                <form class="form-horizontal" method="post" name="formalmacen" action="<?php echo $editFormAction; ?>">
                  <div class="box-body">
                    <input name="idbien" type="hidden" id="idbien" name="idbien"
                      value="<?php echo htmlentities($row_modificaroficina['idbien'], ENT_COMPAT, 'utf-8'); ?>"
                      size="32">

                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">No. INFORME:</label>
                      <div class="col-sm-9">
                        <input class="form-control" disabled type="text" name="noinforme"
                          value="<?php echo htmlentities($row_modificaroficina['codbieninv'], ENT_COMPAT, 'utf-8'); ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">MUESTRA:</label>
                      <div class="col-sm-9">
                        <input class="form-control" disabled type="text" name="muestra"
                          value="<?php echo htmlentities($row_modificaroficina['nombien'], ENT_COMPAT, 'utf-8'); ?>"
                          required size="32">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">MARCA:</label>
                      <div class="col-sm-9">
                        <input class="form-control" disabled type="text" name="marca" required
                          value="<?php echo htmlentities($row_modificaroficina['codmarca'], ENT_COMPAT, 'utf-8'); ?>"
                          size="32">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">MODELO:</label>
                      <div class="col-sm-9">
                        <input class="form-control" disabled type="text" name="modelo" required
                          value="<?php echo htmlentities($row_modificaroficina['codmodelo'], ENT_COMPAT, 'utf-8'); ?>"
                          size="32">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">ACCESORIOS:</label>
                      <div class="col-sm-9">
                        <input class="form-control" disabled type="text" name="accesorios" required
                          value="<?php echo htmlentities($row_modificaroficina['accesorios'], ENT_COMPAT, 'utf-8'); ?>"
                          size="32">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">CLIENTE:</label>
                      <div class="col-sm-9">
                        <input class="form-control" disabled type="text" name="cliente" required
                          value="<?php echo htmlentities($row_modificaroficina['razonsocial'], ENT_COMPAT, 'utf-8'); ?>"
                          size="32">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">GESTOR:</label>
                      <div class="col-sm-9">
                        <input class="form-control" disabled type="text" name="gestor" required
                          value="<?php echo htmlentities($row_modificaroficina['codcolor'], ENT_COMPAT, 'utf-8'); ?>"
                          size="32">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">OBSERVACIONES:</label>
                      <div class="col-sm-9">
                        <input class="form-control" type="text" name="obs" id="obs" size="32">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">METODO DE ENTREGA:</label>
                      <div class="col-sm-9">
                        <select class="form-control" name="metodoentrega" id="metodoentrega">
                          <option value="">-- Seleccione un metodo de entrega-- </option>
                          <option value="sitio">Sitio</option>
                          <option value="mensajeria">Mensajeria</option>
                          <option value="paqueteria">Paqueteria</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group" id="paqueteria-group" style="display: none;">
                      <label for="id" class="col-sm-3 control-label">PAQUETERIA:</label>
                      <div class="col-sm-9">
                        <select class="form-control" name="paqueteria" id="paqueteria">
                          <option value="">-- Seleccione una paqueteria-- </option>
                          <option value="estafeta">ESTAFETA</option>
                          <option value="dhl">DHL</option>
                          <option value="fedex">FEDEX</option>
                          <option value="ups">UPS</option>
                          <option value="turbina">TURBINA</option>
                          <option value="paquetexpress">PAQUETEXPRESS</option>
                          <option value="estrella">ESTRELLA BLANCA / IBN</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group" style="display: none;">
                      <label for="id" class="col-sm-3 control-label">N° GUIA:</label>
                      <div class="col-sm-9">
                        <input class="form-control" type="text" name="noguia" id="noguia" size="32">
                      </div>
                    </div>


                    <div class="form-group" style="display: none;">
                      <label for="id" class="col-sm-3 control-label">QUIEN ENTREGA:</label>
                      <div class="col-sm-9">
                        <input class="form-control" type="text" name="entrega" id="entrega" size="32">
                      </div>
                    </div>

                    <div class="form-group" style="display: none;">
                      <label for="id" class="col-sm-3 control-label">QUIEN RECIBE:</label>
                      <div class="col-sm-9">
                        <input class="form-control" type="text" name="recibe" id="recibe" size="32">
                      </div>
                    </div>

                    <div class="form-group" style="display: none;">
                      <label for="id" class="col-sm-3 control-label">MENSAJERO:</label>
                      <div class="col-sm-9">
                        <select class="form-control" name="mensajero" id="mensajero">
                          <option value="">-- Seleccione al mensajero-- </option>
                          <option value="1">Mario Flores</option>
                          <option value="2">Carlos Alberto</option>
                        </select>
                      </div>
                    </div>


                    <input type="hidden" name="MM_update" value="formalmacen">
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <a href="javascript:history.back(-1);" class="btn btn-danger" title="Cancelar">CANCELAR</a>
                    <button type="submit" class="btn btn-success pull-right" title="Grabar registro">ENTREGA</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">INFORMACION DE LA MUESTRA</h3>
                <img src="./img/almacen.jpg" class="img-thumbnail" alt="">
              </div>
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
  <script>
    document.getElementById('metodoentrega').addEventListener('change', function() {
      const metodoEntrega = this.value;
      const paqueteriaGroup = document.getElementById('paqueteria-group');
      const noguiaField = document.getElementById('noguia').parentElement.parentElement;

      // Mostrar el campo de paquetería solo si el método de entrega es "paqueteria"
      if (metodoEntrega === 'paqueteria') {
        paqueteriaGroup.style.display = 'block';
        noguiaField.style.display = 'block';
      } else {
        paqueteriaGroup.style.display = 'none';
        noguiaField.style.display = 'none';
      }
    });

    document.getElementById('metodoentrega').addEventListener('change', function() {
      const metodoEntrega = this.value;
      const entregaField = document.getElementById('entrega').parentElement.parentElement;
      const recibeField = document.getElementById('recibe').parentElement.parentElement;

      // Mostrar el campo de paquetería solo si el método de entrega es "paqueteria"
      if (metodoEntrega === 'sitio') {
        entregaField.style.display = 'block';
        recibeField.style.display = 'block';
      } else {
        entregaField.style.display = 'none';
        recibeField.style.display = 'none';
      }
    });

    document.getElementById('metodoentrega').addEventListener('change', function() {
      const metodoEntrega = this.value;
      const mensajeroField = document.getElementById('mensajero').parentElement.parentElement;

      // Mostrar el campo de paquetería solo si el método de entrega es "paqueteria"
      if (metodoEntrega === 'mensajeria') {
        mensajeroField.style.display = 'block';
      } else {
        mensajeroField.style.display = 'none';
      }
    });
  </script>
</body>

</html>

<?php
mysqli_free_result($modificaroficina);
?>