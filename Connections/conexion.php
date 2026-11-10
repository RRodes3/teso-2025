<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conexion = "localhost";
$database_conexion = "bdteso2025";
$username_conexion = "root";
$password_conexion = "";
/*
@$conexion = mysqli_pconnect($hostname_conexion, $username_conexion, $password_conexion) or trigger_error(mysqli_error(),E_USER_ERROR); 
mysqli_query("SET NAMES 'UTF8'"); 
*/
 
$conexion=mysqli_connect($hostname_conexion, $username_conexion, $password_conexion, $database_conexion);
mysqli_query($conexion, "SET NAMES 'utf8'");

$idusuario = '';
  
if (!isset($_SESSION)) {
  session_start();
} 
if (isset($_SESSION['U_spidus'])){
$idusuario = $_SESSION['U_spidus'];
 
}

function limpiarCadena($valor)
{
	$valor = str_ireplace("SELECT","",$valor);
	$valor = str_ireplace("COPY","",$valor);
	$valor = str_ireplace("DELETE","",$valor);
	$valor = str_ireplace("DROP","",$valor);
	$valor = str_ireplace("DUMP","",$valor);
	$valor = str_ireplace(" OR ","",$valor);
	$valor = str_ireplace("%","",$valor);
	$valor = str_ireplace("LIKE","",$valor);
	$valor = str_ireplace("--","",$valor);
	$valor = str_ireplace("^","",$valor);
	$valor = str_ireplace("[","",$valor);
	$valor = str_ireplace("]","",$valor); 
	$valor = str_ireplace("!","",$valor);
	$valor = str_ireplace("¡","",$valor);
	$valor = str_ireplace("?","",$valor);
	$valor = str_ireplace("=","",$valor);
	$valor = str_ireplace("&","",$valor);
	return $valor;
}


?>