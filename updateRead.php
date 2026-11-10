<?php 

require_once('Connections/conexion.php');

$codigo = '';
$nomtabla = '';
$nomtablaa = '';
$nomtablaaaa = '';

 
if(isset($_POST['nomok']) and $_POST['nomok']!='')
{
	$nomtabla =$_POST['nomok'];
}		
		$deleteSQL = 'UPDATE movimiento SET modclasificacion = NULL';		
		$Result1 = mysqli_query($conexion, $deleteSQL ) or die(mysqli_error()); 
        
	header("Location: ad-fact-four.php");
