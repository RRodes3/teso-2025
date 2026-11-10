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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE bien SET factura=%s, codfactura=%s, fechafactura=%s, fechapagosc=%s, montopagosc=%s WHERE idbien=%s",
                       GetSQLValueString($_POST['factura'], "int"),                    
                       GetSQLValueString($_POST['codfactura'], "text"),
                       GetSQLValueString($_POST['fechafactura'], "date"), 
                       GetSQLValueString($_POST['fechapagosc'], "date"), 
                       GetSQLValueString($_POST['montopagosc'], "int"), 
                       GetSQLValueString($_POST['idbien'], "int"));
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());
  $updateGoTo = "ad-fact-one.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE bien SET factura=%s, codfactura=%s, fechafactura=%s, fechapagosc=%s, montopagosc=%s WHERE idbien=%s",
                       GetSQLValueString($_POST['factura'], "int"),                    
                       GetSQLValueString($_POST['codfactura'], "text"),
                       GetSQLValueString($_POST['fechafactura'], "date"), 
                       GetSQLValueString($_POST['fechapagosc'], "date"), 
                       GetSQLValueString($_POST['montopagosc'], "int"), 
                       GetSQLValueString($_POST['idbien'], "int"));
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());
  $updateGoTo = "ad-fact-two.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form3")) {
  $updateSQL = sprintf("UPDATE bien SET factura=%s, codfactura=%s, fechafactura=%s, fechapagosc=%s, montopagosc=%s WHERE idbien=%s",
                       GetSQLValueString($_POST['factura'], "int"),                    
                       GetSQLValueString($_POST['codfactura'], "text"),
                       GetSQLValueString($_POST['fechafactura'], "date"), 
                       GetSQLValueString($_POST['fechapagosc'], "date"), 
                       GetSQLValueString($_POST['montopagosc'], "int"), 
                       GetSQLValueString($_POST['idbien'], "int"));
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());
  $updateGoTo = "ad-fact-three.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form4")) {
  $updateSQL = sprintf("UPDATE bien SET factura=%s, codfactura=%s, fechafactura=%s, fechapagosc=%s, montopagosc=%s WHERE idbien=%s",
                       GetSQLValueString($_POST['factura'], "int"),                    
                       GetSQLValueString($_POST['codfactura'], "text"),
                       GetSQLValueString($_POST['fechafactura'], "date"), 
                       GetSQLValueString($_POST['fechapagosc'], "date"), 
                       GetSQLValueString($_POST['montopagosc'], "int"), 
                       GetSQLValueString($_POST['idbien'], "int"));
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());
  $updateGoTo = "ad-fact-four.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_modificaroficina = "-1";
if (isset($_GET['codigo'])) {
  $colname_modificaroficina = $_GET['codigo'];
}
?>