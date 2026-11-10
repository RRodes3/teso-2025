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
if ((isset($_POST["idbien"])) && ($_POST["idbien"] != "")) {
 require_once('Connections/conexion.php');
	$idbien = $_POST["idbien"];
  

	$query_actbien = "UPDATE  `bien` SET suspendido = '1' WHERE  `idbien` = '".$idbien."'";
	$listaactbien = mysqli_query($conexion, $query_actbien) or die(mysqli_error());

//	$query_auditoria = "insert into auditoria  (fecharegistro,idusuario,accion,numacta) values (Now(),'".$idusuario."','De baja el registro con numero de Informe.: ".$_POST['codbieninv']."')";	 
//	$lista_audit = mysqli_query($conexion, $query_auditoria) or die(mysqli_error());

echo'<script type="text/javascript">
alert("El informe se suspendio exitosamente");
window.location.href="lista-tesos-cuatro.php";
</script>';

}

?>