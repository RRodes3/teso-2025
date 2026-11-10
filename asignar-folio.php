<?php 
if (!isset($_SESSION)) {
  session_start();
} 
if ( isset($_SESSION['U_spprivilegio']) and in_array("s_superv", $_SESSION['U_spprivilegio'])) { 	 
require_once('Connections/conexion.php');
$menuactivo ='supervision';
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
$mensaje = '';
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $query_lista = "SELECT * FROM bien where numerosc = '".$_POST['numerosc']."'";
  $listaa = mysqli_query($conexion, $query_lista) or die(mysqli_error());
  if (mysqli_num_rows($listaa) == 0){
    $updateSQL = sprintf("UPDATE bien SET numerosc=%s WHERE idbien=%s",
                       GetSQLValueString($_POST['numerosc'], "text"), 
                       GetSQLValueString($_POST['idbien'], "int"));
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());
  echo '<script language=javascript>
  alert("Asignaste un nuevo folio")
  self.location="asignar-folio.php"
  </script>';
  }else{
  echo '<script language=javascript>
  alert("Ya asignaste el folio, Asigna un numero de folio Diferente")
  self.location="asignar-folio.php"
  </script>';
  }  
  $query_auditoria = "insert into auditoria  (fecharegistro,idusuario,accion,numacta) values (Now(),'".$idusuario."','Asigno el folio: ".$_POST['numerosc']."','')";  
  $lista_audit = mysqli_query($conexion, $query_auditoria) or die(mysqli_error());
}
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
    $activarmenu='registrar-licencia';
    include('aside.php');
    ?>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <i class="fa fa-file-archive-o"></i> Asigna Folio<a href="#info1"
            class="inf btn pull-right btn-xs btn-default" title="Agregar mas registros"
            style=" font-size:12px; color:#C10000"><span class="glyphicon glyphicon-plus"></span></a>
        </h1>
      </section>
      <section class="content">
        <?php if($mensaje == 'Correcto'){ 
                        ?>
        <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>¡Éxito!</strong> registro de forma satisfactorio.
        </div>
        <?php }elseif($mensaje != ""){ ?>
        <div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>¡Aviso! <?php echo $mensaje ?></strong>
        </div>
        <?php } ?>
        <div class="row">
          <div id="info1" class="col-sm-10 col-sm-offset-1 well oculto">
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title"><a href="#info1" class="inf btn btn-xs btn-default" title="Minimizar"
                    style=" font-size:12px; color:#C10000"><span class="glyphicon glyphicon-minus"></span></a> Asignación de folio</h3>
              </div>
              <form class="form-horizontal" method="post" name="form1" action="<?php echo $editFormAction; ?>"
                enctype='multipart/form-data'>
                <div class="box-body">
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Numero de Informe</label>
                    <div class="col-sm-9">
                      <?php
                          if (in_array("m_tododepedencia", $_SESSION['U_spprivilegio'])) {
                          $consulta_mysql=("SELECT  bien.*,biencatalogo.nombien, f.codfamiliabien, bien.debaja FROM bien
                          inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat 
                          LEFT JOIN familiabien f ON bien.idfamiliabien = f.idfamiliabien where bien.debaja IS NULL"); 
                          }else{
                            $consulta_mysql='select * from oficina inner join dependencia on oficina.iddependencia = dependencia.iddependencia 
                                inner join responsable on oficina.idrespoficina = responsable.idresponsable 
                                where dependencia.iddependencia = "'.$_SESSION['U_spiddependencia'].'" 
                                order by dependencia.dependencia, oficina.nomoficina asc';
                          }                                                                        
                        $resultado=mysqli_query($conexion,$consulta_mysql);                          
                        echo "<select required class='form-control select2' name='idbien' id='idbien' style='width:100%'><option value=''> -- Seleccione el No. de Informe --</option >";
                        while($fila=mysqli_fetch_array($resultado)){
                            echo "<option value='".$fila['idbien']."'>".$fila['codbieninv'].'&nbsp;&nbsp; / &nbsp;&nbsp; '.$fila['codfamiliabien'].'&nbsp;&nbsp; / &nbsp;&nbsp;'.$fila['nombien'].'&nbsp;&nbsp; / &nbsp;&nbsp;'.$fila['codmarca'].'&nbsp;&nbsp; / &nbsp;&nbsp; '.$fila['codmodelo'].' &nbsp;&nbsp; / &nbsp;&nbsp;'.$fila['codcolor']."</option>";
                        }
                        echo "</select>"; ?>
                    </div>
                  </div>
                  <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Serie:</label>
                      <div class="col-sm-9">
                          <input type="text" name="numerosc" class="form-control" value="<?php if (isset($_POST['numerosc'])) echo $_POST['numerosc']; ?>" placeholder="Folio">
                      </div>
                  </div>
                </div>                
                <div class="box-footer">
                  <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                  <button type="submit" class="btn btn-info pull-right" title="Grabar registro">GUARDAR</button>
                  <input type="hidden" name="MM_update" value="form1">
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