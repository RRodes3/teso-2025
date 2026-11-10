<?php 
require_once('Connections/conexion.php');
require_once('Connections/funciones.php');
  

?>
<?php

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  Global $con;
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }
 
 

  $theValue =$theValue;

  switch ($theType) {
    case "pass":
      $theValue = ($theValue != "") ? "" . $theValue . "" : "NULL";
      break; 
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
?>
<?php

if (!isset($_SESSION)) {
  session_start();
}


$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['mipass'])) {
  $loginUsername=$_POST['miusuario'];
  $password=$_POST['mipass'];
  $MM_fldUserAuthorization = "tipo";
  $MM_redirecttoReferrer = false;
  $MM_redirecttoReferrer = false;
  
    
  $LoginRS__query=sprintf("SELECT * FROM usuario WHERE `user`=%s and estado = 'ACTIVO'",
  GetSQLValueString($loginUsername, "text")); 
   
  $LoginRS = mysqli_query($conexion, $LoginRS__query) or die(mysqli_error());
   $row_login = mysqli_fetch_assoc($LoginRS);
  $loginFoundUser = mysqli_num_rows($LoginRS);
  if ($loginFoundUser) {
    if ( $password ==mysqli_result($LoginRS,0,'pass') )
  {
    $loginStrGroup  = mysqli_result($LoginRS,0,'tipo');
  $idusuarioing = mysqli_result($LoginRS,0,'idusuario');
  if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    
   
    $_SESSION['U_spidus'] =  $idusuarioing;
  $_SESSION['U_sppassus'] =  $row_login['pass'];
  $_SESSION['U_sdni'] =  $row_login['user'];
    $_SESSION['U_sptipo'] =  $row_login['tipo'];
  $_SESSION['U_spnombres'] = $row_login['nombres'];
  $_SESSION['U_spapellidos'] = $row_login['apellidos'];
  $_SESSION['U_spfregistro'] = $row_login['fregistro'];
  $_SESSION['U_spidoficina'] =  $row_login['idoficina'];
  $_SESSION['U_spiddependencia'] =  $row_login['iddependencia'];
   
  $query_privusu = sprintf("SELECT * FROM privilegiousuario inner join privilegio on privilegiousuario.idprivilegio = privilegio.idprivilegio  WHERE privilegiousuario.`idusuario` = %s", GetSQLValueString($idusuarioing, "int"));
  
  
  $privusu = mysqli_query($conexion, $query_privusu);
  $row_privusu = mysqli_fetch_assoc($privusu);
  $totalRows_privusu = mysqli_num_rows($privusu);
  
  unset($_SESSION['U_spprivilegio']);
  do {
    $_SESSION['U_spprivilegio'][]=$row_privusu['nomcontrol'] ; 
  } while ($row_privusu = mysqli_fetch_assoc($privusu)); 
  
    
    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];  
    }
    header("Location: diagrama.php" );
  }
  else {
     $_SESSION['contador'] += 1;
    header("Location:login.php?fjhfsdg&falloo=werdtfgyhujrftgyhujirftgyh");
    
  }
  }else
  {
    $_SESSION['contador']++;
    header("Location:login.php?dfbdscfgdwfb&fallor=sswcxnfduencfcxujsaiusdafhudyh");
  }
 
}
?>