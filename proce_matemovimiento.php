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
$nombien = 'Documento';
	$nomdoctransf = '';
 require_once('Connections/conexion.php');
if ((isset($_POST["idbien"])) && ($_POST["idbien"] != "") and ($_POST["metodo"]=='registrar')) {

	$idbien = $_POST["idbien"];
  	$nomfoto = 'Mov'.$idbien.'-'.date('d-m-Y').rand(3, 99).'.jpg';
	if( empty($_FILES['uploadedfile']['name']) )
	{ $nomfoto= '';
	}
	
	
	 
 
	 if( empty($_FILES['doctransf']['name']) )
    {
        $nomdoctransf = '';
    }else{
	$extnuevoimagen = pathinfo($_FILES['doctransf']['name']);	 
	$numaleatorio = mt_rand(5,30);
	$nomdoctransf = 'Mov-'.substr(preg_replace('#([^.a-z0-9]+)#i', '', $nombien ), 0,10).$numaleatorio.'-'.date('d-m-Y').'.'.$extnuevoimagen['extension'];
	}	
   $iinsertSQL = sprintf("INSERT INTO movimiento (idbien,idoficinaori, idoficinadest, respbien,   foto, condicion, doctransf, observacion,fechainsta,  idusuarioreg, fechareg) VALUES (%s,%s, %s,%s,%s, %s, %s, %s, %s,%s, NOW())",
                       GetSQLValueString( $idbien, "int"),
					   GetSQLValueString($_POST['idoficinaori'], "text"),
                       GetSQLValueString($_POST['oficinadest'], "text"),					   
                       GetSQLValueString($_POST['respbien'], "text"),
                       GetSQLValueString($nomfoto , "text"),
                       GetSQLValueString($_POST['condicion'], "text"),					   
                       GetSQLValueString($nomdoctransf, "text"),
                       GetSQLValueString($_POST['observacion'], "text"),
                       GetSQLValueString($_POST['fechainsta'], "text"),                         
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
	

	$query_actbien = "UPDATE  `bien` SET  `idmovimiento` =  '".$idmov."' WHERE  `idbien` = '".$idbien."'";
	$listaactbien = mysqli_query($conexion, $query_actbien) or die(mysqli_error());

$target_pathh = "fotos/";
$target_path = $target_pathh.$nomfoto ; 
move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path);

$dir_subida = 'doc/';
$fichero_subido = $dir_subida.$nomdoctransf;

@move_uploaded_file($_FILES['doctransf']['tmp_name'], $fichero_subido);

 	

	$query_auditoria = "insert into auditoria  (fecharegistro,idusuario,accion,numacta) values (Now(),'".$idusuario."','Registró movimiento del bien con código de inv.: ".$_POST['codbieninv']."','".$idmov."')";	 
	$lista_audit = mysqli_query($conexion, $query_auditoria) or die(mysqli_error());


echo '<script language=javascript>
self.location="imprimir-movimiento.php?id='.$idbien.'&sadsadsadimprimirelultimomivimientoaAsDdSDQ"
</script>';

}elseif ((isset($_POST["idmov"])) && ($_POST["idbien"] != "") and ($_POST["metodo"]=='editar')) {

	$idbien = $_POST["idbien"];
  	$nomfoto = 'Mov-mod'.$idbien.'-'.date('d-m-Y').rand(3, 99).'.jpg';
	
	if( empty($_FILES['uploadedfile']['name']) )
	{ $nomfoto= $_POST["foto"];
	}
	if( empty($_FILES['doctransf']['name']) )
    {
        $nomdoctransf = '';
		$queryfich = '';
    }else{
	$extnuevoimagen = pathinfo($_FILES['doctransf']['name']);	 
	$numaleatorio = mt_rand(5,30);
	$nomdoctransf = 'Mov-modif-'.substr(preg_replace('#([^.a-z0-9]+)#i', '', $nombien ), 0,10).$numaleatorio.'-'.date('d-m-Y').'.'.$extnuevoimagen['extension'];
	$queryfich = ", doctransf = '".$nomdoctransf."'";
	}
	
	
	$updateSQL = sprintf("UPDATE movimiento SET idoficinadest=%s,respbien =%s,  foto=%s, condicion=%s, observacion=%s,   fechainsta=%s,  idusuariomod=%s, fechamod=Now() ".$queryfich." WHERE idmov=%s",
                       GetSQLValueString($_POST['oficinadest'], "int"),					   
                       GetSQLValueString($_POST['respbien'], "text"),
                       GetSQLValueString( $nomfoto, "text"),
                       GetSQLValueString($_POST['condicion'], "text"),
                       GetSQLValueString($_POST['observacion'], "text"),
                       GetSQLValueString($_POST['fechainsta'], "date"),
                       GetSQLValueString($idusuario, "int"),
                       GetSQLValueString($_POST['idmov'], "int"));

  
  $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());

$target_pathh = "fotos/";
$target_path = $target_pathh.$nomfoto ; 
move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path);

$dir_subida = 'doc/';
$fichero_subido = $dir_subida.$nomdoctransf;

@move_uploaded_file($_FILES['doctransf']['tmp_name'], $fichero_subido);

 	

	$query_auditoria = "insert into auditoria  (fecharegistro,idusuario,accion,numacta) values (Now(),'".$idusuario."','Modificó el movimiento del bien con código de inv.: ".$_POST['codbieninv']."','".$_POST['idmov']."')";	 
	$lista_audit = mysqli_query($conexion, $query_auditoria) or die(mysqli_error());
	
  header("Location: lista-bienmov.php");
}

?>