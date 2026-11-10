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
$numero_informe = "";
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

$numero_informe = $_POST['codbieninv'];

  $updateSQL = sprintf("UPDATE bien SET accesorios=%s, observaciones=%s, lugar=%s WHERE idbien=%s",
                       GetSQLValueString($_POST['accesorios'], "text"),                                            
                       GetSQLValueString($_POST['observaciones'], "text"),
                       GetSQLValueString($_POST['lugar'], "text"),
                       GetSQLValueString($_POST['idbien'], "int"));
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());

  $updateGoTo = "almacen-tres.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));

  //Registrar auditoria
  $query_auditoria = "insert into auditoria  (fecharegistro,idusuario,accion,numacta) values (Now(),'".$idusuario."','Usuario de almacen Modifico el teso: ".$numero_informe."','')";  
  $lista_audit = mysqli_query($conexion, $query_auditoria) or die(mysqli_error());
}

$colname_modificaroficina = "-1";
if (isset($_GET['codigo'])) {
  $colname_modificaroficina = $_GET['codigo'];
}

$query_modificaroficina = sprintf("SELECT * FROM bien where idbien =  %s", GetSQLValueString($colname_modificaroficina, "int"));
$modificaroficina = mysqli_query($conexion, $query_modificaroficina) or die(mysqli_error());
$row_modificaroficina = mysqli_fetch_assoc($modificaroficina);
$totalRows_modificaroficina = mysqli_num_rows($modificaroficina);
?>

<?php include('modifcar-almacen.php'); ?>

<?php
mysqli_free_result($modificaroficina);
?>