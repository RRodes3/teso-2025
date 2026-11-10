<?php require_once('Connections/conexion.php');
if (!isset($_SESSION)) {
  session_start();
}  ?>
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
$array_privadmin = array(1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,40,41,42,43,44,45,46,47,48,49,50);
$array_privreg = array(1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,45,46,47,48,49);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

$query_lista = "SELECT * FROM usuario where user = '".$_POST['user']."'";
$listaa = mysqli_query($conexion, $query_lista) or die(mysqli_error());

if (mysqli_num_rows($listaa) == 0){
  $insertSQL = sprintf("INSERT INTO usuario (`user`, pass, nombres, apellidos, tipo, estado, fono, correo, idoficina) VALUES (lower(%s), lower(%s), %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['user'], "text"),
                       GetSQLValueString($_POST['user'], "text"),
                       GetSQLValueString($_POST['nombres'], "text"),
                       GetSQLValueString($_POST['apellidos'], "text"),
                       GetSQLValueString($_POST['tipo'], "text"),
                       GetSQLValueString($_POST['estado'], "text"),
                       GetSQLValueString($_POST['fono'], "text"),
                       GetSQLValueString($_POST['correo'], "text"),
                       GetSQLValueString($_POST['idoficina'], "int"));

  $Result1 = mysqli_query($conexion, $insertSQL) or die(mysqli_error());
  $query_infousu = "SELECT * FROM usuario order by idusuario desc";
  $infous = mysqli_query($conexion, $query_infousu) or die(mysqli_error());
  $row_infous = mysqli_fetch_assoc($infous);
  $idnuevousuario = $row_infous['idusuario'];
  if ($_POST['tipo'] == 'ADMINISTRADOR')
  {
  $row_array = count($array_privadmin); 
  for($i=0; $i<$row_array; $i++)
      {
	  $codpriv = $array_privadmin[$i];

    $insertSQL = sprintf("INSERT INTO privilegiousuario (idusuario, idprivilegio) VALUES (%s, %s)",
				   GetSQLValueString($idnuevousuario, "int"),
				   GetSQLValueString($codpriv, "int"));		
	
		$Result1 = mysqli_query($conexion, $insertSQL) or die(mysqli_error());
      }	 
 }elseif($_POST['tipo'] == 'REGISTRADOR')
 {
  $row_array = count($array_privreg); 
  for($i=0; $i<$row_array; $i++)
      {
	  $codpriv = $array_privreg[$i];
    $insertSQL = sprintf("INSERT INTO privilegiousuario (idusuario, idprivilegio) VALUES (%s, %s)",
				   GetSQLValueString($idnuevousuario, "int"),
				   GetSQLValueString($codpriv, "int"));			
		$Result1 = mysqli_query($conexion, $insertSQL) or die(mysqli_error());
      } 
 }
 
echo '<script language=javascript>
alert("Se Registro un Nuevo Usuario, que tendra acceso al sistema")
self.location="lista-usuarioedit.php"
</script>';
}else{
echo '<script language=javascript>
alert("Existe ya un registro con el mismo usuario, Ingrese el nombre de usuario diferente")
self.location="registrar-usuario.php"
</script>';

}}
?>