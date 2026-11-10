<?php
if (!isset($_SESSION)) {
  session_start();
}
if ( isset($_SESSION['U_spprivilegio']) and in_array("f-fact-control", $_SESSION['U_spprivilegio']) || in_array("sm_clientes", $_SESSION['U_spprivilegio'])) { 

  require_once('Connections/conexion.php');
  $menuactivo = 'facturacion' || 'mantenimiento';
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
  $accion = '';
  $editFormAction = $_SERVER['PHP_SELF'];

  if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
  }

  if ((isset($_POST["idclient"])) && ($_POST["razonsocial"] != "")) {



    if ($_POST['idclient'] != '') {

      $rss = mysqli_query($conexion, "SELECT * FROM clientes where rfc = '" . $_POST['rfc'] . "'");
      $totalpersonas = mysqli_num_rows($rss);

      if ($totalpersonas == 0) {

        $insertSQL = sprintf(
          "INSERT INTO clientes (idclient, razonsocial, rfc, repre_legal, direccion, email, tel, fech_reg) VALUES (%s, %s, %s, %s, %s, %s, %s, Now())",
          GetSQLValueString($_POST['idclient'], "text"),
          GetSQLValueString($_POST['razonsocial'], "text"),
          GetSQLValueString($_POST['rfc'], "text"),
          GetSQLValueString($_POST['repre_legal'], "text"),
          GetSQLValueString($_POST['direccion'], "text"),
          GetSQLValueString($_POST['email'], "text"),
          GetSQLValueString($_POST['tel'], "int")
        );
        $Result1 = mysqli_query($conexion, $insertSQL) or die(mysqli_error());
        $accion = 'crear';
      } else {
        echo '<script language=javascript>
alert("El Cliente que ingresaste ya se encuentra registrado verifique el RFC")
history.go(-1);
</script>';
      }
    } else {
      echo "<script type=\"text/javascript\">
           history.go(-1);
       </script>";

      echo '<script language=javascript>
alert("El Cliente no debe estar vacio")
</script>';
    }
  }

  $query_lista_clientes = "SELECT * FROM clientes";
  $lista_clientes = mysqli_query($conexion, $query_lista_clientes) or die(mysqli_error());
  $row_lista_clientes = mysqli_fetch_assoc($lista_clientes);
  $totalRows_lista_clientes = mysqli_num_rows($lista_clientes);

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
      $activarmenu = 'registrar-proveedor';
      include('aside.php');
      ?>
      <div class="content-wrapper center">
        <section class="content-header">
          <h1>
            <i class="fa fa-user-o"></i>Registro Clientes
          </h1>
        </section>
        <section class="content">
          <?php if ($accion == 'crear') {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>¡Satisfactorio!</strong> Has registrado un nuevo Cliente: <strong>' . @$_POST['razonsocial'] . '</strong> y se le asigno el siguiente numero de Cliente: <strong>' . @$_POST['idclient'] . '</strong>
        </div>';
          } elseif (isset($_GET['modificar'])) {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>¡Satisfactorio!</strong> Se modificó datos del Cliente.
        </div>';
          } ?>


          <!--    --- Inicio Lista 1-->
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body">
                  <div class="table-responsive">
                    <table id="servicios" width="100%" class="table table-hover display">
                      <thead>
                        <tr class="<?php echo 'titulotabla'; ?>">
                          <td>N°</td>
                          <td>Id Cliente</td>
                          <td>Razon social</td>
                          <td>Rfc</td>
                          <td>Representante Legal</td>
                          <td>Dirección</td>
                          <td>email</td>
                          <td>Tel</td>
                          <!-- <td> </td> -->
                        </tr>
                      </thead>
                      <?php if ($totalRows_lista_clientes > 0) {
                        $i = 0;
                        do {
                          $i += 1; ?>
                          <tr <?php if ($i % 2) {
                                echo 'filapar';
                              } else {
                                echo 'filaimpar';
                              } ?>>
                            <th class="active"><?php echo $i; ?></th>
                            <td><?php echo $row_lista_clientes['idclient']; ?></td>
                            <td><?php echo $row_lista_clientes['razonsocial']; ?></td>
                            <td><?php echo $row_lista_clientes['rfc']; ?></td>
                            <td><?php echo $row_lista_clientes['repre_legal']; ?></td>
                            <td><?php echo $row_lista_clientes['direccion']; ?></td>
                            <td><?php echo $row_lista_clientes['email']; ?></td>
                            <td><?php echo $row_lista_clientes['tel']; ?></td>

                            <!-- <td style=" padding:0px"><a href="mod-client.php?id=<?php echo $row_lista_clientes['idcl']; ?>" title="Editar" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-pencil"></span></a> -->

                             
                            </td>
                          </tr>
                      <?php } while ($row_lista_clientes = mysqli_fetch_assoc($lista_clientes));
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
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" language="javascript" class="init">
      $(document).ready(function() {
        $('#servicios').DataTable();
      });
    </script>
    <script type="text/javascript" language="javascript" class="init">
      $(document).ready(function() {
        $('#servicioss').DataTable();
      });
    </script>
    <script>
      $(function() {
        $(".select2").select2();
      });
    </script>
  </body>

  </html>
<?php } else {
  header("Location: pagina-restringida.php");
} ?>