<?php require_once('Connections/conexion.php'); ?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE proveedor SET codproveedor=%s, idcliente=%s, razonsocial=%s, giro=%s, direccion=%s, email=%s, telefono=%s, personacontacto=%s WHERE idproveedor=%s",
                       GetSQLValueString($_POST['codproveedor'], "text"),
                       GetSQLValueString($_POST['idcliente'], "int"),
                       GetSQLValueString($_POST['razonsocial'], "text"),
                       GetSQLValueString($_POST['giro'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['telefono'], "text"),
                       GetSQLValueString($_POST['personacontacto'], "text"),
                       GetSQLValueString($_POST['idproveedor'], "int"));

  
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());

  $updateGoTo = "registrar-proveedor.php?modificar";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_lista = "-1";
if (isset($_GET['codigo'])) {
  $colname_lista = $_GET['codigo'];
}

$query_lista = sprintf("SELECT * FROM proveedor WHERE idproveedor = %s", GetSQLValueString($colname_lista, "int"));
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$row_lista = mysqli_fetch_assoc($lista);
$totalRows_lista = mysqli_num_rows($lista);
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
    $activarmenu='modificar-proveedor';
    include('aside.php');
    ?>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <span class="glyphicon glyphicon-edit"></span>Modificar Empresa
        </h1>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-7">
            <div class="box box-info">
              <form class="form-horizontal" method="post" name="form1" action="<?php echo $editFormAction; ?>">
                <div class="box-body">

                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Id Empresa:</label>
                    <div class="col-sm-9">
                      <input required class="form-control" type="text" name="codproveedor"
                        value="<?php echo htmlentities($row_lista['codproveedor'], ENT_COMPAT, 'utf-8'); ?>" size="32">
                    </div>
                  </div>
                  <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Cliente:</label>
                      <div class="col-sm-9">
                        <select class="form-control select2" required name="idcliente" id="idcliente">
                          <option value="">-- Seleccione -- </option>
                          <?php
                          $rs = mysqli_query($conexion, "SELECT * FROM clientes");
                          while ($off = mysqli_fetch_array($rs)) {
                          ?>
                            <OPTION VALUE="<?php echo $off['idcl']; ?>"><?php echo $off['idclient'] . ' - ' . $off['razonsocial']; ?></OPTION>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Razón Social:</label>
                    <div class="col-sm-9">
                      <input required class="form-control" type="text" name="razonsocial"
                        value="<?php echo htmlentities($row_lista['razonsocial'], ENT_COMPAT, 'utf-8'); ?>" size="32">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Rfc:</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="giro"
                        value="<?php echo htmlentities($row_lista['giro'], ENT_COMPAT, 'utf-8'); ?>" size="32">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Representante Legal:</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="personacontacto"
                        value="<?php echo htmlentities($row_lista['personacontacto'], ENT_COMPAT, 'utf-8'); ?>"
                        size="32">
                      <input type="hidden" name="MM_update" value="form1">
                      <input type="hidden" name="idproveedor" value="<?php echo $row_lista['idproveedor']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Dirección:</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="direccion"
                        value="<?php echo htmlentities($row_lista['direccion'], ENT_COMPAT, 'utf-8'); ?>" size="32">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Email:</label>
                    <div class="col-sm-9">
                      <input type="email" class="form-control" name="email"
                        value="<?php echo htmlentities($row_lista['email'], ENT_COMPAT, 'utf-8'); ?>" size="32">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Teléfono:</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="telefono"
                        value="<?php echo htmlentities($row_lista['telefono'], ENT_COMPAT, 'utf-8'); ?>" size="32">
                    </div>
                  </div>

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                  <button type="submit" class="btn btn-info pull-right" title="Grabar registro">ACTUALIZAR</button>
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