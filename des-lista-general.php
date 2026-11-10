<?php 
header("Content-Type: text/html; charset=UTF-8");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
ob_start();
if (!isset($_SESSION)) {
  session_start();
} 
if ( isset($_SESSION['U_spprivilegio']) and in_array("r_reportes", $_SESSION['U_spprivilegio'])) { 
	 
require_once('Connections/conexion.php');
?>
<?php
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

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);

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

$maxRows_lista = 11000;
$pageNum_lista = 0;
if (isset($_GET['pageNum_lista'])) {
  $pageNum_lista = $_GET['pageNum_lista'];
}
$startRow_lista = $pageNum_lista * $maxRows_lista;

$colname_lista = "-1";


if (in_array("m_tododepedencia", $_SESSION['U_spprivilegio'])) {
$query_lista = "SELECT  bien.*,biencatalogo.nombien,  f.familiabien, m.idmov, m.foto, m.condicion, m.observacion, m.clasificacion, 
m.idusuarioreg,m.objetomov, m.doctransf, familiabien.codfamiliabien, 
proveedor.codproveedor, proveedor.razonsocial, proveedor.personacontacto FROM bien 
inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat 
LEFT JOIN familiabien f ON bien.idfamiliabien = f.idfamiliabien 
left join movimiento m on m.idbien = bien.idbien and m.idmov = bien.idmovimiento 
left join familiabien on familiabien.idfamiliabien = bien.idfamiliabien 
left join proveedor on proveedor.idproveedor = bien.idproveedor order by bien.codbieninv asc";
}else
{
	$query_lista = "SELECT  bien.*,biencatalogo.nombien, f.familiabien, m.idmov, m.idoficinadest, m.foto, m.condicion, 
  m.observacion, m.idusuarioreg,m.objetomov,m.clasificacion, m.doctransf, oficina.nomoficina,dependencia.dependencia, concat_ws (' ',r.nombres , r.apellidos) as 
  responsable, m.fechareg FROM bien 
  inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat 
  LEFT JOIN familiabien f ON bien.idfamiliabien = f.idfamiliabien 
  left join movimiento m on m.idbien = bien.idbien and m.idmov = bien.idmovimiento 
  left join oficina on oficina.idoficina = m.idoficinadest 
  left join dependencia on dependencia.iddependencia = oficina.iddependencia 
  left join responsable r on r.idresponsable = oficina.idrespoficina where dependencia.iddependencia = '".$_SESSION['U_spiddependencia']."'  order by biencatalogo.nombien, bien.codbieninv asc";
}


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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />     
 <STYLE type="text/css">
table {
    border-collapse: collapse;
}
table, th, td {
   border: 1px  solid black;
   font-size:14px
}
.titulotabla{
	background-color:#030E74;
	color:#FFFFFF
	
		}
 </STYLE>
  <table id="servicios"  width="100%" class="table table-hover display"  >
             <thead>
              <tr>
                <td colspan="19" style="text-align:center;"><strong>LABORATORIO TESO DE MEXICO S.A. DE C.V.</strong>
                <br>
                <?php 
				if (in_array("m_tododepedencia", $_SESSION['U_spprivilegio'])) {
					echo '-';
				}else{
					echo '-';
				}
				?>
                </td>
               </tr>
              <tr class="titulotabla">
              <td width="1%" ><strong>N°</strong></td>            
              <td>No. INFORME</td>              
              <td >NORMA</td>
              <td >ITEM</td>
              <td >MARCA</td>              
              <td >MODELO</td>
              <td >SERIE</td>
              <td>BAJA</td>
              <td>CLASIFICACION</td>
              <td>MODIFICADO</td>
              <td >CONSTA/INCLUYE</td>            
              <td >OBSERVACIONES</td>  
              <td >PRECIO</td>
              <td >CODIGO CLIENTE</td>                
              <td >RAZON SOCIAL</td>  
              <td >FACTURAR A:</td>  
              <td>GESTOR </td>
              <td >REPRESENTANTE</td>  
              <td >FECHA DE REGISTRO</td>
              <td >FECHA DE Recepcion item</td>
              <td >FECHA DE ENTREGA</td>

            </tr>
            </thead>
            <?php $result =$startRow_lista; if ($totalRows_lista > 0 ){do { ?>
            <tr>
              <td>             
            <strong>
                <?php
					$result += 1;
					echo  $result;
				?>
              </strong> 
              </td>   
              <td><?php echo $row_lista['codbieninv']; ?></td>
              <td><?php echo $row_lista['codfamiliabien']; ?></td>
              <td><?php echo $row_lista['nombien']; ?></td>
              <td><?php echo $row_lista['codmarca']; ?></td>
              <td><?php echo $row_lista['codmodelo']; ?></td>
              <td><?php echo $row_lista['serie']; ?></td>  
              <td><?php echo $row_lista['debaja']; ?></td>   
              <td><?php echo $row_lista['clasificacion']; ?></td>   
              <td><?php echo $row_lista['modificado']; ?></td>           
              <td><?php echo $row_lista['caracteristica']; ?></td>
              <td><?php echo $row_lista['observaciones']; ?></td>
              <td><?php echo $row_lista['valor']; ?></td>
              <td><?php echo $row_lista['codproveedor']; ?></td>                  
              <td><?php echo $row_lista['razonsocial']; ?></td> 
              <td><?php echo $row_lista['facturara']; ?></td> 
              <td><?php echo $row_lista['codcolor']; ?></td> 
              <td><?php echo $row_lista['personacontacto']; ?></td> 
              <td><?php echo $row_lista['fregistro']; ?></td> 
              <td><?php echo $row_lista['fechaadq']; ?></td>
              <td><?php echo $row_lista['tiempovida']; ?></td>
            </tr>
            <?php } while ($row_lista = mysqli_fetch_assoc($lista));} ?>
          </table>

 
<?php
mysqli_free_result($lista);
	//header('Content-Type: text/html; charset=utf-8');
	header("Content-type: application/vnd.ms-excel; name='excel'; charset=utf-16");
	
	header("Content-Disposition: filename=Lista-general-de-Tesos.xls");
	header("Pragma: no-cache");
	header("Expires: 0"); 
?>
<?php }else{
header("Location: pagina-restringida.php");
}?>