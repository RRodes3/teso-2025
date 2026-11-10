<?php require_once('Connections/conexion.php');
require_once('Connections/funciones.php');

if (!isset($_SESSION)) {
  session_start();
} 
//metodo = eliminar
$metodo = '';
if (isset($_GET['metodo'])){
$metodo=limpiar($_GET['metodo']);
$metodo=substr($metodo,10);
$metodo=decrypt($metodo,'URLCODIGO');
}
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





$currentPage = $_SERVER["PHP_SELF"];
$idbien = '';
if(isset( $_GET['codigo']) and $_GET['codigo'] !=''){ $idbien = $_GET['codigo'];
}else{ $idbien = '';
	
	}
//Info del bien

$query_listainfo = "SELECT  bien.*,biencatalogo.nombien,  familiabien.familiabien,familiabien.codfamiliabien, proveedor.razonsocial, proveedor.codproveedor FROM bien inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat  inner join familiabien on bien.idfamiliabien= familiabien.idfamiliabien inner join proveedor on bien.idproveedor = proveedor.idproveedor  where bien.idbien ='".$idbien."'";
$listainfo = mysqli_query($conexion, $query_listainfo) or die(mysqli_error());
$roowinfo=mysqli_fetch_array($listainfo);
$totalroowinfo = mysqli_num_rows($listainfo);  



$maxRows_lista = 100;
$pageNum_lista = 0;
if (isset($_GET['pageNum_lista'])) {
  $pageNum_lista = $_GET['pageNum_lista'];
}
$startRow_lista = $pageNum_lista * $maxRows_lista;

$colname_lista = "-1";

$query_lista = "SELECT  m.*, oficina.nomoficina,dependencia.dependencia, concat_ws (' ',r.nombres , r.apellidos) as responsableof, concat_ws (' ',usuario.nombres , usuario.apellidos) as responsablereg FROM movimiento m inner join oficina on oficina.idoficina = m.idoficinadest inner join dependencia on dependencia.iddependencia = oficina.iddependencia inner join responsable r on r.idresponsable = oficina.idrespoficina inner join usuario on m.idusuarioreg = usuario.idusuario where m.idbien ='".$idbien."' order by m.idmov desc";

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
 <meta name="author"   content="Eduardo sandoval"> 
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
  $activarmenu='historial-bien';
  include('aside.php');
  ?>

  <div class="content-wrapper">

   <section class="content-header">
    <h1>
     <i class="fa fa-history"></i>
     Información e historial del bien <?php 
				  if($metodo =='eliminar'){
				  echo ' - Eliminar';
				  }
				  ?>
    </h1>
    <ol class="breadcrumb">
     <a href="javascript:history.back(-1);" class="btn btn-default btn-sm" title="Volver atras">Volver atras <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
     <a href='javascript:window.print(); void 0;' id="btnImprimirPantalla" class="btn btn-primary btn-sm " role="button" title="Imprimir reporte" stsyle="font-size:10px"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
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
<div class="box-body" >
  <!-- Titulo del reporte-->
<div style="text-align:center" class="visible-print-block">
 <strong> INFORMACIÓN E HISTORIAL</strong>
  
</div>


<div classs="table-responsive">	
        <table width="100%" border="0" style="background-color: #F4F4F4" >
                    <tr valign="baseline"  class="titulotabla">
                      <td width="25%"  style="height:30px"><strong>No. Teso:</strong></td>
                      <td width="25%" ><?php echo $roowinfo['codbieninv'];?></td>                     
                    </tr>                                        
                    <tr valign="baseline">
                      <td  style="height:30px" ><strong>NOMBRE DEL ITEM:</strong></td>
                      <td colspan="3"><strong><?php echo $roowinfo['nombien'];?></strong></td>
                      </tr>
                    <tr valign="baseline">
                      <td style="height:30px"  ><strong>MARCA:</strong></td>
                      <td><?php echo $roowinfo['codmarca'];?></td>
                      <td><strong>MODELO:</strong></td>
                      <td><?php echo $roowinfo['codmodelo'];?></td>
                    </tr>
                    <tr valign="baseline">
                      <td  style="height:30px"><strong>SERIE: </strong></td>
                      <td><?php echo $roowinfo['serie'];?></td>
                      <td><strong>GESTOR:</strong></td>
                      <td><?php echo $roowinfo['codcolor'];?></td>
                    </tr>
                    <tr valign="baseline">
                      <td style=" padding-right:10px;height:30px"><strong>NORMA:</strong></td>
                      <td><?php echo $roowinfo['codfamiliabien'];?></td>
                      <td><strong>NOM:</strong></td>
                      <td><?php echo $roowinfo['familiabien'];?></td>
                    </tr>                    
                    <tr valign="baseline">
                      <td  style="height:30px"><strong>FECHA DE INGRESO:</strong></td>
                      <td><?php echo $roowinfo['fechaadq'];?></td>
                      <td><strong>FECHA DE ENTREGA PROPUESTA:</strong></td>
                      <td><?php echo $roowinfo['tiempovida'];?></td>
                    </tr>                     
                    <tr>
    				          <td><?php echo $roowinfo['codproveedor'];?></td>
                      <td  style="height:30px"><strong>CLIENTE:</strong></td>
                      <td><?php echo $roowinfo['razonsocial'];?></td>                      
                    </tr>
                     
                     
                  
                    
                  </table>
        </div>
        
        <!-- <div class="table-responsive">	     
             <table id="example"  cellspacing="0"  border="0" style="width:99.90%; "class="display" >--
             <table id="serviciosa" class="table table-bordered table-striped display"  cellspacing="0" width="100%">
             <thead>
              <tr class="titulotabla">
              <td  colspan="11" class="text-center no-padding" ><strong>LISTA DE MOVIMIENTOS</strong></td>
              </tr>
             
              <tr class="titulotabla">
              <td width="1%" ><strong>N° ACTA</strong></td>
              <td width="1px" class="hidden-print">FOTO</td>
              <td width="1px">DEPENDENCIA</td>
              <td >OFICINA</td>
              <td >OBSERVACIÓN / MOTIVO</td>
              <td >CONDICIÓN</td>
              <td >DOC.</td>
              <td >FECHA INSTALACIÓN:</td>
              <td >FECHA DE ACTA:</td>
              <td >RESPONSABLE REG.:</td>              
              <td  class="hidden-print">Acta:</td>
              
              
            </tr>
            </thead>
            <?php $result =$startRow_lista; if ($totalRows_lista > 0 ){do { ?>
            <tr>
              <td> 
            
            <strong>
                <?php
						$result += 1;
						//echo  $result;
						?>
                        <?php echo $idmov = substr ( '00000'.$row_lista['idmov'], -6);?>
              </strong> 
              <?php if( $row_lista['debaja'] =='1'){echo '<span class="label label-warning ">DE BAJA</span>';} ?>
              </td>
               
              <td style=" padding:0px;" class="hidden-print" ><a href="#" class="thumbnail" data-toggle="modal" title="Ver grande" data-target="#lightbox"> <img src="<?php echo 'fotos/'.$row_lista['foto'];?>"  onerror="this.src='img/bien.jpg';" width="78" height="73" ></a>
              <?php
			   if($metodo =='eliminar' and $result == 1){
          $fc=0;        
        ?>
                <!-- reporte en Eliminar --
 <form action="eliminar.php" method="post" name="frmimv"> 
<!-- Modal --
<div class="modal fade" id="elimbien<?php echo $row_lista['idmov']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title color" id="myModalLabel">¿Estas seguro eliminar el registro?</h4>
      </div>
      <div class="modal-body">
      
        <strong class="alert-warning">Detalles:</strong><br>
    <strong>Número de acta:</strong> <?php echo $idmov = substr ( '00000'.$row_lista['idmov'], -6);?><br>
    <strong>Oficina de destino:</strong> <?php echo $row_lista['nomoficina']; ?>
        
      </div>
      <div class="modal-footer">
        <?php if ($fc!=0){echo '<label style ="font-size:14px"  class="label label-danger">No se puede eliminar</label>'; } ?>
      <input  name="idmov" type="hidden" required id="idmov" value="<?php echo $row_lista['idmov']; ?>" size="32" >
        <input  name="valretorno" type="hidden" required id="valretorno" value="<?php echo @$_GET['codigo']; ?>" size="32" >
        <input  name="nomtabla" type="hidden" required id="nomtabla" value="movimiento" size="32" >
        <button type="button" class="btn btn-default" data-dismiss="modal" title="Cancelar">Cancelar</button>
        <input name="Grabar" type="submit" class="btn btn-primary"  <?php if ($fc!=0){echo "disabled";} else { echo "enabled"; } ?>   title="Eliminar" value="Si, Eliminar">
      </div>
    </div>
  </div>
</div>

</form> <!-- Fin de eliminar --
<?php  } ?>
              </td>
              <td><?php echo $row_lista['dependencia']; ?></td>
              <td><?php echo $row_lista['nomoficina']; ?><br> 
              <strong>Resp:</strong> <?php echo $row_lista['responsableof']; ?>
              </td>
              <td><?php echo $row_lista['observacion']; ?><?php echo $row_lista['objetomov']; ?></td>
              <td><?php echo $row_lista['condicion']; ?></td> 
              <td><?php if(is_file( 'doc/'.$row_lista['doctransf'] )){ ?><a title="Contiene documento - Descargar" target="_blank" href="doc/<?php echo $row_lista['doctransf']; ?>" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-download-alt"></span></a><?php }else{ echo $row_lista['doctransf'];  }?></td>
              <td><?php echo $row_lista['fechainsta']; ?></td>
              <td><?php echo $row_lista['fechareg']; ?></td>
              <td><?php echo $row_lista['responsablereg']; ?></td>
              <td class="hidden-print"><a title="Imprimir acta"  href="<?php if ($row_lista['debaja'] !='1'){?>imprimir-movimiento.php?id=<?php echo $row_lista['idbien']; ?>&idmov=<?php echo $row_lista['idmov']; ?>&sdffasfndsfjkdasfndasfnid<?php }else{ echo 'imprimir-baja.php?id='.$row_lista['idbien'].'&sadsadsadimprimirelultimomivimientoaAsDdSDQ';}?>" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-print"></span></a>
              <?php if($metodo =='eliminar' and $result == 1){?>
               <a href="#" data-toggle="modal" <?php if ($fc!=0){echo "disabled";} else { echo "enabled"; } ?> data-target="#elimbien<?php echo $row_lista['idmov']; ?>" title="Eliminar" stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span></a>
               <?php } ?>
              </td>
              
            </tr>
            <?php } while ($row_lista = mysqli_fetch_assoc($lista));} ?>
          </table>
        </div> -->

        <!--    --- Inicio Lista Licencia-->
        <?php
        $query_lista = "SELECT l.*,b.*, c.nombien   FROM `licencia` l INNER JOIN `bien` b ON l.id_bien = b.idbien
INNER JOIN `biencatalogo` c ON b.codbiencat = c.codbiencat WHERE l.flg_anulado != 1 and l.id_bien = '".$idbien."'";
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$row_lista = mysqli_fetch_assoc($lista);
$totalRows_lista = mysqli_num_rows($lista);
?>
     <div class="table-responsive"> 
       <table id="serviciosa"  width="100%" class="table table-bordered  table-hover display"  >
       <thead>
          <tr class="titulotabla">
              <td  colspan="11" class="text-center no-padding" ><strong>INGENIERO RESPONSABLE DEL INFORME</strong></td>
          </tr>
          <tr class="<?php echo 'titulotabla'; ?>">
            <td width="">N.</td>
            <td>TESO</td>
            <td >INGENIERO</td>
            <td >F. ASIGNADO</td>
            <td >F. VENCIMIENTO</td>        
          </tr>
        </thead>                  
       <?php if ($totalRows_lista > 0){ $i = 0 ; do {  $i += 1; ?>
             <tr <?php if($i % 2){echo 'filapar';}else{ echo 'filaimpar';} ?>>
              <th class="active"><?php echo $i; ?></th> 
              <td><?php echo $row_lista['codbieninv'].' '.$row_lista['nombien']; ?></td>
              <td><?php echo $row_lista['idusuario']; ?></td>
              <td><?php echo $row_lista['finicio']; ?></td>
              <td><?php echo $row_lista['fvencimiento']; ?></td>
              
              <!-- <td style="padding:1px; margin:0px" class="hidden-print"><a href="modificar-licencia.php?codigo=<?php echo $row_lista['id_licencia']; ?>" title="Editar" stylesd="padding-bottom:0px; padding-top:0px" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-pencil"></span></a> -->
            <?php           
                $fc = 0;            
            ?>
                         

              </td>
            </tr>
            <?php } while ($row_lista = mysqli_fetch_assoc($lista));} ?>
        </table>
  </div>
<!--    --- Fin Lista Licencias--> 
<!--        Para imagen modal-->

<div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
        <div class="modal-content">
            <div class="modal-body">
                <img src="" alt="" />
            </div>
        </div>
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
 } );
</script>

</body>
</html>
