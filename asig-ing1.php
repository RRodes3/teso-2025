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



$update = '';
$ingeniero = '';
if (isset($_POST["MM_insert_ing"]) and $_POST["MM_insert_ing"] != '') {
  $update = $_POST['MM_insert_ing'];
}

if (isset($_POST['idusuario']) and $update == 'form1') {
  $ingeniero = $_POST['idusuario'];
  if ($ingeniero != '') {
    date_default_timezone_set('America/Mexico_City');
    $fecha_asignacion_item = date('Y-m-d h:i:s');
    $insertSQL = sprintf(
      "INSERT INTO licencia (id_bien, fech_asignacion, observacionesasig, idusuario) VALUES (upper(%s), upper(%s), upper(%s), upper(%s))",
      GetSQLValueString($_POST['id_bien'], "text"),
      GetSQLValueString($fecha_asignacion_item, "date"),
      GetSQLValueString($_POST['observacionesasig'], "text"),
      GetSQLValueString($ingeniero, "int")
    );
    $Result1 = mysqli_query($conexion, $insertSQL) or die(mysqli_error());
  }
  $updateGoTo = "asignar-informes.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


?>
