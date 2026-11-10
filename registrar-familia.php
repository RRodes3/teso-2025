<?php 
if (!isset($_SESSION)) {
  session_start();
} 
if ( isset($_SESSION['U_spprivilegio']) and in_array("sm_normas", $_SESSION['U_spprivilegio'])) { 
	 
require_once('Connections/conexion.php');
$menuactivo ='mantenimiento';
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")){
 if($_POST['codfamiliabien'] != ''){

$rss=mysqli_query( $conexion, "SELECT * FROM familiabien where codfamiliabien = '".$_POST['codfamiliabien']."' order by codfamiliabien desc limit 1
");
$totalpersonas = mysqli_num_rows($rss);
 if($totalpersonas == 0){	
 $insertSQL = sprintf("INSERT INTO familiabien (codfamiliabien, familiabien) VALUES (%s, upper(%s))",
                        GetSQLValueString($_POST['codfamiliabien'], "text"),
                       GetSQLValueString(trim($_POST['familiabien']), "text"));
  
  $Result1 = mysqli_query($conexion, $insertSQL) or die(mysqli_error());
 $mensaje ="Acabas de registrar  .:: '".$_POST['familiabien']."' ::. como una familia de Normas";
	}else{
	   
$msgerror ="Verifique la norma que ingresaste, ya se encuentra registrado" ;
}}else{ 
	   
$msgerror ="El campo Norma no debe estar vacio";}}

//Lista


$query_lista = "SELECT * FROM familiabien ORDER BY codfamiliabien ASC";
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
  <!--Desarrollado por:Eduardo Sandoval -->
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
    $activarmenu='registrar-familia';
    include('aside.php');
    ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <span class="glyphicon glyphicon-plus"></span>Lista de Normas acreditadas
        </h1>
      </section>

      <section class="content">


        <?php if (isset($msgerror) ){echo '<div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>¡Aviso!</strong> '. $msgerror.'
        </div>'; }?>
        <?php if (isset($mensaje) ){echo '<div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>¡Aviso!</strong> '. $mensaje.'
        </div>'; }?>

        <div class="row">
          <div class="col-md-7">
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Registra Norma</h3>
              </div>

              <form class="form-horizontal" method="post" name="form1" action="<?php echo $editFormAction; ?>">
                <div class="box-body">
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Norma:</label>
                    <div class="col-sm-9">
                      <input class="form-control" name="codfamiliabien" type="text" autofocus required
                        id="codfamiliabien" value="<?php if (isset($_GET["dato"])){echo $_GET["dato"]; }?>" size="32">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Caracteristica:</label>
                    <div class="col-sm-9">
                      <input class="form-control" name="familiabien" type="text" required id="familiabien" value=""
                        size="32">
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                  <button type="submit" class="btn btn-info pull-right" title="Grabar registro">GUARDAR</button>
                  <input type="hidden" name="MM_insert" value="form1">
                </div>

              </form>


            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-body">
                <div class="table-responsive">

                  <table id="servicios" width="100%" class="table table-hover display">
                    <thead>
                      <tr class="<?php echo 'titulotabla'; ?>">
                        <td width="5%">N.</td>
                        <td width="17%">Norma</td>
                        <td width="60%">Detalle Norma</td>
                        <td width="10%">Precio</td>
                        <td width="10%">Dias de entrega</td>
                        <td width="8%">-</td>
                      </tr>
                    </thead>
                    <?php if ($totalRows_lista > 0) {$i = 0 ; do {  $i += 1; ?>
                    <tr <?php if($i % 2){echo 'filapar';}else{ echo 'filaimpar';} ?>>
                      <th class="active"><?php echo $i; ?></th>
                      <td><?php echo $row_lista['codfamiliabien']; ?></td>
                      <td><?php echo $row_lista['familiabien']; ?></td>
                      <td>$ <?php echo $row_lista['precio']; ?>.00</td>
                      <td><?php echo $row_lista['tiempoentrega']; ?></td>
                      <td style="padding:1px; margin:0px"><a
                          href="modificar-familia.php?codigo=<?php echo $row_lista['idfamiliabien']; ?>" title="Editar"
                          stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-warning btn-sm"><span
                            class="glyphicon glyphicon-pencil"></span></a>
                        <?php
                            $consultas="SELECT * FROM bien where idfamiliabien = '".$row_lista['idfamiliabien']."'";
                            $rcons=mysqli_query($conexion, $consultas);
                            $fc=mysqli_num_rows($rcons);
                        ?>
                        <a href="#" data-toggle="modal" <?php if ($fc!=0){echo "disabled";} else { echo "enabled"; } ?>
                          data-target="#elimbien<?php echo $row_lista['idfamiliabien']; ?>" title="Eliminar"
                          stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-danger btn-sm"><span
                            class="glyphicon glyphicon-remove"></span></a>
                        
                        <form action="eliminar.php" method="post" name="frmimv">                          
                          <div class="modal fade" id="elimbien<?php echo $row_lista['idfamiliabien']; ?>" tabindex="-1"
                            role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                      aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title color" id="myModalLabel">¿Estas seguro eliminar el registro?
                                  </h4>
                                </div>
                                <div class="modal-body">

                                  <strong class="alert-warning">Detalles:</strong><br>
                                  <strong>Norma>: </strong> <?php echo $row_lista['codfamiliabien']; ?><br>
                                  <strong>Detalle Norma>: </strong> <?php echo $row_lista['familiabien']; ?>

                                </div>
                                <div class="modal-footer">
                                  <?php if ($fc!=0){echo '<label style ="font-size:14px"  class="label label-danger">No se puede eliminar</label>'; } ?>
                                  <input name="idfamiliabien" type="hidden" required id="idfamiliabien"
                                    value="<?php echo $row_lista['idfamiliabien']; ?>" size="32">
                                  <input name="nomtabla" type="hidden" required id="nomtabla" value="familiabien"
                                    size="32">
                                  <button type="button" class="btn btn-default" data-dismiss="modal"
                                    title="Cancelar">Cancelar</button>
                                  <input name="Grabar" type="submit" class=" btn btn-primary"
                                    <?php if ($fc!=0){echo "disabled";} else { echo "enabled"; } ?> title="Eliminar"
                                    value="Si, Eliminar">
                                </div>
                              </div>
                            </div>
                          </div>

                        </form> <!-- Fin de eliminar -->
                      </td>
                    </tr>
                    <?php } while ($row_lista = mysqli_fetch_assoc($lista));} ?>
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
</body>

</html>
<?php }else{
header("Location: pagina-restringida.php");
}?>