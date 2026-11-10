<?php 
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  //$theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);

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
if ((isset($_POST["idbien"])) && ($_POST["idbien"] != "")) {
 require_once('Connections/conexion.php');
	$idbien = $_POST["idbien"];
  	$nomfoto = 'Elim'.$idbien.'-'.date('d-m-Y').rand(3, 30).'.jpg';
	if( empty($_FILES['uploadedfile']['name']) )
	{ $nomfoto= '';
	}
	 if( empty($_FILES['doctransf']['name']) )
    {
        $nomdoctransf = '';
    }else{
	$extnuevoimagen = pathinfo($_FILES['doctransf']['name']);
	 
	$numaleatorio = mt_rand(5,30);
	$nomdoctransf = substr(preg_replace('#([^.a-z0-9]+)#i', '', $_POST['nombien']), 0,10).$numaleatorio.'-'.date('d-m-Y').'.'.$extnuevoimagen['extension'];
	}
	
   $iinsertSQL = sprintf("INSERT INTO movimiento (idbien, foto, objetomov,doctransf, debaja,  idusuarioreg, fechareg) VALUES (%s, %s, %s, %s,'1', %s, NOW())",

                       GetSQLValueString( $idbien, "int"),
                       GetSQLValueString($nomfoto , "text"),
                       GetSQLValueString($_POST['objetomov'], "text"),
                       GetSQLValueString($nomdoctransf , "text"),                         
                       GetSQLValueString($idusuario, "text"));
					   
  
  $Result2 = mysqli_query($conexion, $iinsertSQL) or die(mysqli_error());
  


	$query_idmov = "SELECT idmov FROM movimiento where idusuarioreg = '".$idusuario."' order by idmov desc";
	$listabienmov = mysqli_query($conexion, $query_idmov) or die(mysqli_error());
	$roowidbienmov=mysqli_fetch_array($listabienmov);
	$idmov = '';
	if (mysqli_num_rows($listabienmov) > 0 )
		{
			$idmov = $roowidbienmov['idmov'];
		}
	

	$query_actbien = "UPDATE  `bien` SET  `idmovimiento` =  '".$idmov."', debaja = '1' WHERE  `idbien` = '".$idbien."'";
	$listaactbien = mysqli_query($conexion, $query_actbien) or die(mysqli_error());

	$target_pathh = "fotos/";
	$target_path = $target_pathh.$nomfoto ; 
	move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path);

	$dir_subida = 'doc/';
	$fichero_subido = $dir_subida.$nomdoctransf;

	move_uploaded_file($_FILES['doctransf']['tmp_name'], $fichero_subido);
	
 	

	$query_auditoria = "insert into auditoria  (fecharegistro,idusuario,accion,numacta) values (Now(),'".$idusuario."','De baja el registro con numero de Informe.: ".$_POST['codbieninv']."','".$idmov."')";	 
	$lista_audit = mysqli_query($conexion, $query_auditoria) or die(mysqli_error());

echo '<script language=javascript>
self.location="imprimir-baja.php?id='.$idbien.'&sadsadsadimprimirelultimomivimientoaAsDdSDQ"
</script>';

}

?>