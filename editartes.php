<?php 

if (isset($_POST['autorizado'])){
    require_once('Connections/conexion.php');

$idinforme = $_POST['idbien'];
$codbieninv = $_POST['codbieninv'];
$claveeditar =$_POST['claveEditar'];
$clave0 = "Liz23!*";
$clave1 = "andrea2*23";
$clave2 = "Eloga508";
$clave3 = "T3r3*#";
$clave4 = "Jaqu3.*";

if ($claveeditar == $clave8)
{	
    header("Location: modificar-bien.php?id=".$idinforme."&tes=".$codbieninv."&metodo=dgdSFDASfdgfgrtFGFDG");	        
}else if ($claveeditar == $clave0 || $claveeditar == $clave1 || $claveeditar == $clave2 || $claveeditar == $clave3 || $claveeditar == $clave4) {
    header("Location: modif-inf.php?id=".$idinforme."&tes=".$codbieninv."&metodo=dgdSFDASfdgfgrtFGFDG");	            
}else{
    echo '<script language=javascript>
    alert("Error!, la contraseña es incorrecta")
    history.go(-1);
    </script>';
}

}