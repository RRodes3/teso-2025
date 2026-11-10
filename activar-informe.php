<?php 
require_once('Connections/conexion.php');

$idbien = '';
if (isset($_GET['id'])) {
  $idbien = $_GET['id'];
}

	$idbien = $idbien;

	$query_actbien = "UPDATE  `bien` SET suspendido = '' WHERE  `idbien` = '".$idbien."'";
	$listaactbien = mysqli_query($conexion, $query_actbien) or die(mysqli_error());


  echo'<script type="text/javascript">
  alert("El informe se activo exitosamente");
  window.location.href="lista-tesos-uno.php";
  </script>';


?>
