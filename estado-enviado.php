<?php 

require_once('Connections/conexion.php');

$codigo = '';
$nomtabla = '';
$nomtablaa = '';
$nomtablaaaa = '';

if(isset($_POST['nomtabla']) and $_POST['nomtabla']!='')
{
	$nomtablaa =$_POST['nomtabla'];
}
//Eliminar tabla familiabien
if(isset($_POST['id_licencia']) and $nomtablaa =='licencia')
{
	$codigo =$_POST['id_licencia'];
	date_default_timezone_set('America/Bogota');
$fecha = date('Y-m-d');
			$hora = date('h:i:s');
			$fechaenvio = $fecha.' '.$hora;
	 
	if($codigo !=''){		
		$deleteSQL = "UPDATE licencia SET estado = 'enviado', fechaenvio = '".$fechaenvio."'  WHERE id_licencia='".$codigo."'";
	
		$Result1 = mysqli_query($conexion, $deleteSQL ) or die(mysqli_error()); 
	}
	header("Location:informes-finales.php");
	
}
