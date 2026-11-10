<?php require_once('Connections/conexion.php');
if (!isset($_SESSION)) {
  session_start();
} 
$menuactivo ='reportes';
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
//inventario
$dniusuario = '';
if(isset($_SESSION["U_sdni"])){ $dniusuario =$_SESSION["U_sdni"];}
 if (isset($_POST["Grabar"]) ) {
	   $updateSQL = sprintf("UPDATE bien SET `user`=%s, fechaad=NOW(), inventario=%s WHERE idbien=%s",
                       GetSQLValueString($dniusuario, "text"),
                                             GetSQLValueString($_POST['inventario'], "text"),
                       GetSQLValueString($_POST['idbien'], "int"));
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());
}
//DAR DE BAJA
 if (isset($_POST["baja"]) ) {
	   $updateSQL = sprintf("UPDATE bien SET `user`=%s, fechaad=NOW(), debaja='SI' WHERE idbien=%s", GetSQLValueString($dniusuario, "text"),
                       GetSQLValueString($_POST['idbien'], "int"));
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());
  
}
$currentPage = $_SERVER["PHP_SELF"];
$maxRows_lista = 100;
$pageNum_lista = 0;
if (isset($_GET['pageNum_lista'])) {
  $pageNum_lista = $_GET['pageNum_lista'];
}
$startRow_lista = $pageNum_lista * $maxRows_lista;
$colname_lista = "-1";
$dependencia = 0; 
$variabledep = ""; 
if (isset($_GET['dependencia'])) {
  $dependencia = $_GET['dependencia'];
  if ($dependencia !='todo'){
	  $variabledep = "oficina.iddependencia = '". $dependencia."' and";
  }
}
$query_lista = "SELECT  bien.*,biencatalogo.nombien,   m.idmov, m.idoficinadest, m.foto, m.condicion, m.observacion, m.idusuarioreg,m.objetomov, m.doctransf, oficina.nomoficina, concat_ws (' ',r.nombres , r.apellidos) as responsable FROM bien inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat   inner join movimiento m on m.idbien = bien.idbien and m.idmov = bien.idmovimiento inner join oficina on oficina.idoficina = m.idoficinadest inner join responsable r on r.idresponsable = oficina.idrespoficina where ".$variabledep." bien.debaja = '1'   order by biencatalogo.nombien, bien.codbieninv asc";
$query_limit_lista = sprintf("%s LIMIT %d, %d", $query_lista, $startRow_lista, $maxRows_lista);
$lista = mysqli_query($conexion, $query_limit_lista) or die(mysqli_error());
$row_lista = mysqli_fetch_assoc($lista);

if (isset($_GET['totalRows_lista'])) {
  $totalRows_lista = $_GET['totalRows_lista'];
} else {
  $all_lista = mysqli_query($conexion, $query_lista);
  $totalRows_lista = mysqli_num_rows($all_lista);
}
$totalPages_lista = ceil($totalRows_lista/$maxRows_lista)-1;

$queryString_lista = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_lista") == false && 
        stristr($param, "totalRows_lista") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_lista = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_lista = sprintf("&totalRows_lista=%d%s", $totalRows_lista, $queryString_lista);
?>
<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>Sistema Teso</title>
 <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
 <meta name="author"   content="Eduardo Sandoval"> 
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
  $activarmenu='lista-bien-debaja';
  include('aside.php');
  ?>

  <div class="content-wrapper">

   <section class="content-header hidden-print">
    <h1>
     
      <form action="" method="get"><i class="fa fa-file-text"></i>
                                        Lista de Informes dados de baja:  
                                        
	<?php
if (in_array("m_tododepedencia", $_SESSION['U_spprivilegio'])) {										 
					   $consulta_mysql='select * from dependencia inner join responsable on dependencia.idresponsable = responsable.idresponsable order by dependencia.dependencia desc';

}else{
	$consulta_mysql='select * from dependencia inner join responsable on dependencia.idresponsable = responsable.idresponsable where  dependencia.iddependencia = "'.$_SESSION['U_spiddependencia'].'"  order by dependencia.dependencia desc';
}
$resultado=mysqli_query($conexion,$consulta_mysql);
   ?>
   
 <select required name='dependencia' id='dependencia' style="font-size:14px;color:#333" onchange='this.form.submit()'><option value=''> -- Seleccione  --</option > 
 <option value='todo'  <?php if ("todo"==$dependencia){ echo 'selected';  } ?>> -- TODAS  --</option >
 <?php
 $nomdepen = '';
while($fila=mysqli_fetch_array($resultado)){ ?>
  <option value='<?php echo $fila['iddependencia'] ?>' <?php if ($fila['iddependencia']==$dependencia){ echo 'selected'; $nomdepen = $fila['dependencia'];} ?> ><?php echo $fila['dependencia'].'&nbsp;&nbsp; / &nbsp;&nbsp;'.$fila['nombres'].' '.$fila['apellidos']?></option>
 <?php }
  ?> </select>  </form>
    </h1>
    <ol class="breadcrumb">
    
     <form action="descexcel.php" method="post" id="FormularioExportacion">
                                         <a href='javascript:window.print(); void 0;' id="btnImprimirPantalla" class="btn btn-primary btn-sm " role="button" title="Imprimir reporte" stsyle="font-size:10px"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
  <button type="submit" id="botonExcel" class="btn btn-warning btn-sm "><span class="glyphicon glyphicon-download-alt"></span> Descargar</button>
                <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
                <input type="hidden" id="nomarchivo" name="nomarchivo" value="Lista de bienes de baja<?php //echo strtoupper($row_lista['nomoficina']); ?>.xls" />
</form>
    </ol>
   </section>
 <!--Cabecera de impresión-->
<?php include('header_impr.php'); ?>

   <section class="content">
    <div class="row" >
     <div class="col-xs-12">
      <div class="box">
<!--<div class="box-header">
<h3 class="box-title">Hover Data Table</h3>
</div>-->
<div id="Exportar_a_Excel" class="box-body" >
  



   <!-- Titulo del reporte-->
<div style="text-align:center" class="visible-print-block">
 <strong> FICHA DEL INVENTARIO<br>
   LISTA DE BIENES DADOS DE BAJA </strong> <?php //echo strtoupper($row_lista['nomoficina']); ?>
  
</div>
        <div class="table-responsive">	
             <table  id="servicios_"  cellspacing="0"    width="100%"  class="table table-bordered table-hover display" >
             <thead>
              <tr class="titulotabla">
              <td width="2%"><strong>N°</strong></td>
              <td width="15%">N° DE INVENTARIO</td>
              <td width="34%">NOMBRE DEL BIEN</td>
              <td width="20%">MARCA</td>
              <td width="11%">ESTADO</td>
              <td width="24%">RAZÓN</td>
              <td width="18%">UBICACIÓN</td>
              <td width="1%" class="hidden-print">&nbsp;</td>
              <td width="1%" class="hidden-print">&nbsp;</td>
              
              </tr>
            </thead>
            <?php $result =$startRow_lista; if ($totalRows_lista > 0 ){do { ?>
            <tr>
              <td><strong>
                <?php
						$result += 1;
						echo  $result;
						?>
              </strong></td>
              <td><?php echo $row_lista['codbieninv']; ?></td>
              <td><?php echo $row_lista['nombien']; ?>   </td>
              <td><?php echo $row_lista['codmarca']; ?></td>
              <td><?php echo $row_lista['condicion']; ?></td>
              <td><?php echo $row_lista['objetomov']; ?></td>
              <td><?php echo $row_lista['nomoficina']; ?></td>
              <td class="hidden-print"><a href="imprimir-baja.php?id=<?php echo $row_lista['idbien']; ?>&bien=<?php echo $row_lista['nombien']; ?>" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-print"></span></a></td>
              <td class="hidden-print"><?php if(is_file( 'doc/'.$row_lista['doctransf'] )){ ?><a title="Contiene documento - Descargar" target="_blank" href="doc/<?php echo $row_lista['doctransf']; ?>" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-download-alt"></span></a>
              <?php }?>
              </td>
              
            </tr>
            <?php } while ($row_lista = mysqli_fetch_assoc($lista));}else{ echo '<tr>
              <td colspan="6"><strong>Sin registros</strong></td></tr>';} ?>
          </table>
        </div>
 
</div>
<!-- /.box-body -->
        <div class="row visible-print-block">
        			<br>
                    <br>
                    <br><br>
                    <br>
                    <br>
                    <br>
                    

                  <div class="col-xs-6 text-center">Firma<br>Jefe de Unidad/Departamento</div>
                  <div class="col-xs-6 text-center">Firma<br>Funcionario Responsable del bien</div>
  					<br>
                    <br>
	                <br>
                    <br>
                    <br>
                    <br>

                  <div class="col-xs-6 text-center">Firma<br>Encargado del Inventario</div>
                  <div class="col-xs-6 "><div class="text-center">Firma</div><br>Director:</div>
		</div>  
                 
             
 

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
 } );
</script>
<script language="javascript">
            $(document).ready(function() {
                $("#botonExcel").click(function(event) {
                    $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
                    $("#FormularioExportacion").submit();
                });
            });
        </script>
</body>
</html>
