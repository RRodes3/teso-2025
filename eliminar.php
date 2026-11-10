<?php 

require_once('Connections/conexion.php');
$codigo = '';
$nomtabla = '';
if(isset($_POST['nomtabla']) and $_POST['nomtabla']!='')
{
	$nomtabla =$_POST['nomtabla'];
}
//Eliminar tabla familiabien
if(isset($_POST['idfamiliabien']) and $nomtabla =='familiabien')
{
	$codigo =$_POST['idfamiliabien'];
	if($codigo !=''){		
	$deleteSQL = "DELETE FROM familiabien WHERE idfamiliabien='".$codigo."'";

	$Result1 = mysqli_query($conexion, $deleteSQL) or die(mysqli_error());
	}
	header("Location:registrar-familia.php");
	
}elseif(isset($_POST['iding']) and $nomtabla =='ingeniero')
{
	//Eliminar tabla familiabien
	$codigo =$_POST['iding'];
	if($codigo !=''){		
	$deleteSQL = "DELETE FROM ingeniero WHERE iding='".$codigo."'";

	$Result1 = mysqli_query($conexion, $deleteSQL) or die(mysqli_error());
	}
	header("Location:registrar-ingeniero.php");

}elseif(isset($_POST['idproveedor']) and $nomtabla =='proveedor')
{
	//Eliminar tabla familiabien
	$codigo =$_POST['idproveedor'];
	if($codigo !=''){		
	$deleteSQL = "DELETE FROM proveedor WHERE idproveedor='".$codigo."'";

	$Result1 = mysqli_query($conexion, $deleteSQL) or die(mysqli_error());
	}
	header("Location:registrar-proveedor.php");

}elseif(isset($_POST['idresponsable']) and $nomtabla =='responsable')
{
	//Eliminar tabla responsable
	$codigo =$_POST['idresponsable'];
	if($codigo !=''){		
	$deleteSQL = "DELETE FROM responsable WHERE idresponsable='".$codigo."'";

	$Result1 = mysqli_query($conexion, $deleteSQL) or die(mysqli_error());
	}
	header("Location:registrar-responsable.php");
}elseif(isset($_POST['idmov']) and $nomtabla =='movimiento')
{
	//Eliminar tabla familiabien
	$codigo =$_POST['idmov'];
	$codbien = '';
	$codbien  =$_POST['valretorno'];
	if($codigo !=''){
	$deleteSQL = "DELETE FROM movimiento WHERE idmov='".$codigo."'";

	$Result1 = mysqli_query($conexion, $deleteSQL) or die(mysqli_error());
	
	//Sacar id del ultimo movimiento para actualizar 
	$ultiidmov = '';
	$debaja = '';
	$consultas="SELECT * FROM movimiento where idbien = '".$row_lista['idbien']."' order by idmov desc ";
	$rcons=mysqli_query($conexion, $consultas);
	$row_lista = mysqli_fetch_assoc($rcons);
	$fc=mysqli_num_rows($rcons);	
	if($fc > 0)	{
		$ultiidmov = $row_lista['idmov'];
		$debaja = $row_lista['debaja'];
		$updateSQL = "UPDATE bien SET idmovimiento='".$ultiidmov."', debaja ='".$debaja."' WHERE idbien='".$codbien."'";
		$rcons=mysqli_query($conexion, $consultas);
	}
	
	}
	header("Location:historial-bien.php?codigo=".$codbien."&metodo=YABCDFGJAHmMG7uay9pbs=");
}elseif(isset($_POST['idbien']) and $nomtabla =='bien')
{
	//Eliminar tabla responsable
	$codigo =$_POST['idbien'];
	if($codigo !=''){		
	$deleteSQL = "DELETE FROM bien WHERE idbien='".$codigo."'";

	$Result1 = mysqli_query($conexion, $deleteSQL) or die(mysqli_error());
	}
	header("Location:lista-historial-bien.php");
	
}elseif(isset($_POST['iddependencia']) and $nomtabla =='dependencia')
{
	//Eliminar dependencia
	$codigo =$_POST['iddependencia'];
	if($codigo !=''){		
	$deleteSQL = "DELETE FROM dependencia WHERE iddependencia='".$codigo."'";

	$Result1 = mysqli_query($conexion, $deleteSQL) or die(mysqli_error());
	}
	header("Location:registrar-oficina.php");
	
}elseif(isset($_POST['idoficina']) and $nomtabla =='oficina')
{
	//Eliminar oficina
	$codigo =$_POST['idoficina'];
	if($codigo !=''){		
	$deleteSQL = "DELETE FROM oficina WHERE idoficina='".$codigo."'";

	$Result1 = mysqli_query($conexion, $deleteSQL) or die(mysqli_error());
	}
	header("Location:registrar-oficina.php?depen=".$_POST['iddependencia']);
	
}elseif(isset($_POST['idusuario']) and $nomtabla =='usuario')
{
	//Eliminar Usuario
	$codigo =$_POST['idusuario'];
	if($codigo !=''){		
	$deleteSQL = "DELETE FROM usuario WHERE idusuario='".$codigo."'";

	$Result1 = mysqli_query($conexion, $deleteSQL) or die(mysqli_error());
	//eliminar de la tabla privilegios usuario
	$deletePrivSQL = "DELETE FROM privilegiousuario WHERE idusuario='".$codigo."'";

	$Result2 = mysqli_query($conexion, $deletePrivSQL) or die(mysqli_error());
	
	}
	header("Location:lista-usuarioedit.php");
	
}elseif(isset($_POST['idfungible']) and $nomtabla =='fungible')
{
	$codigo =$_POST['idfungible'];
	if($codigo !=''){		
		$deleteSQL = "DELETE FROM fungible WHERE idfungible='".$codigo."'";
	
		$Result1 = mysqli_query($conexion, $deleteSQL ) or die(mysqli_error()); 
	}
	header("Location:registrar-fungible.php");
	
}elseif(isset($_POST['id_licencia']) and $nomtabla =='licencia')
{
	$codigo =$_POST['id_licencia'];
	if($codigo !=''){		
		$deleteSQL = "DELETE FROM licencia WHERE id_licencia='".$codigo."'";
	
		$Result1 = mysqli_query($conexion, $deleteSQL ) or die(mysqli_error()); 
	}
	header("Location:asignar-informes.php");
	
}
?>