<?php require_once('../../../../Connections/conexion.php');
$menuactivo = 'movimiento';
if (!isset($_SESSION)) {
	session_start();
} ?>
<?php
$idbien = '';
$idmov = '';
if (isset($_GET['id'])) {
	$idbien = $_GET['id'];
}



$query_lista = "SELECT bien.*,biencatalogo.nombien,  proveedor.codproveedor, proveedor.razonsocial, proveedor.direccion, proveedor.personacontacto, familiabien.codfamiliabien, familiabien.familiabien FROM bien 
inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat  
inner join proveedor on bien.idproveedor = proveedor.idproveedor 
inner join familiabien on bien.idfamiliabien = familiabien.idfamiliabien  
WHERE bien.idbien ='$idbien' ORDER BY biencatalogo.nombien ASC";
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$roowfila = mysqli_fetch_array($lista);
$totalRows_lista = mysqli_num_rows($lista);
$nombrearchivo = $roowfila["codbieninv"];

if (isset($_GET['idmov']) and $_GET['idmov'] != '') {
	$idmov = $_GET['idmov'];
} else {
	$idmov = $roowfila['idmovimiento'];
}

$query_listamov = "SELECT * FROM movimiento where idbien = '" . $idbien . "' and idmov = '" . $idmov . "' order by idmov desc limit 1 ";
$listamov = mysqli_query($conexion, $query_listamov) or die(mysqli_error());
$roowfilamov = mysqli_fetch_array($listamov);
$totalRows_listamov = mysqli_num_rows($listamov);






//REQUERIMOS LA CLASE TCPDF

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);



$pdf->AddPage();

//-------------------------------------------------------------------------------



$bloque3 = <<<EOF

<table style=" padding:4px 8px;">	
	<tr>		
		<td style="border: 1px solid grey; background-color:white; width:170px; text-align: center; padding:50px">	
			<br><br><br>
			<img src="images/new logo 2.png" width="100px">	 
		</td>
		<td style=" border: 1px solid grey; background-color:white; width:275px; padding: 4px; text-align:center; font-size:11px;">	
		<br><br>
			<strong>LABORATORIO TESO DE MEXICO S.A. DE C.V.</strong> <br>	<br>
			<strong>CONTROL DE ENSAYOS <br></strong>
		</td>
		<td style=" border: 1px solid grey; background-color:white; padding: 30px; width:120px; text-align: left; font-size:10px;">
			<label>Marca con una x cada una de las etapas realizadas.</label><br>							
			<div style="text-align: left;  margin:5px;">	
			
			<input type="checkbox" name="term" value="1" /> <label for="agree"> En ensayos</label>	 <br>
			<input type="checkbox" name="term" value="1" /><label for="agree"> Supervisado </label>	 <br>
			<input type="checkbox" name="valid" value="1" /><label for="agree"> Validado</label>		<br>
			<input type="checkbox" name="public" value="1" /><label for="agree"> Publicado</label>	
		</div>
		</td>
	</tr>
</table>

EOF;
$pdf->writeHTML($bloque3, false, false, false, false, '');




//------------------------------

$bloque4 = <<<EOF
<table style="font-size:10px; padding:5px 8px;">	
<tr>
<td style=" background-color:white; width:150px"></td>
<td style=" background-color:white; width:405px"></td>
</tr>
<tr>		
	<td style="background-color:white; width:150px; text-align:right">
		NUMERO DE INFORME: 
	</td>
	<td style="border: 0.5px solid #A7A7A7; background-color:white; width:405px; text-align:left;  font-size:12px">		
		 $roowfila[codbieninv] $roowfila[segui]
	</td>
</tr>

</table>
<table style="font-size:10px; padding:5px 8px;">	

<tr>
<td style=" background-color:white; width:150px"></td>
<td style="border-bottom: 1px solid #4B4B4; background-color:white; width:405px"></td>
</tr>
		<tr>		
			<td style=" background-color:white; width:150px; text-align:right">
				NORMA: 
			</td>
			<td style="border: 0.5px soli #A7A7A7; background-color:white; width:405px; text-align:left">		
				 $roowfila[codfamiliabien]
			</td>
		</tr>
		<tr>		
			<td style=" background-color:white; width:150px">
				 
			</td>
			<td style="border: 0.5px solid #A7A7A7; background-color:white; width:405px; text-align:left">		
				 $roowfila[familiabien]
			</td>
		</tr>

	</table>

	<table style="font-size:10px; padding:5px 8px;">	
		<tr>		
			<td style=" background-color:white; width:150px; text-align:right">
				EMPRESA: 
			</td>
			<td style="border: 0.5px solid #A7A7A7; background-color:white; width:405px; text-align:left">		
				 $roowfila[razonsocial]
			</td>
		</tr>
		
	</table>
	<table style="font-size:10px; padding:5px 8px;">	
		<tr>		
			<td style=" background-color:white; width:150px; text-align:right">
				DIRECCIÓN: 
			</td>
			<td style="border: 0.5px solid #A7A7A7; background-color:white; width:405px; text-align:left">		
				 $roowfila[direccion]
			</td>
		</tr>
		
	</table>
	<table style="font-size:10px; padding:5px 8px;">	
		<tr>		
			<td style=" background-color:white; width:150px; text-align:right">
				REPRESENTANTE: 
			</td>
			<td style="border: 0.5px solid #A7A7A7; background-color:white; width:405px; text-align:left">		
				 $roowfila[personacontacto]
			</td>
		</tr>
	</table>

	<table style="font-size:10px; padding:5px 8px;">	
   <tr>		
	<td style=" background-color:white; width:150px">
	
	</td>
	<td style=" background-color:white; width:405px; text-align:left">		
		
	</td>
</tr>

</table>
	<table style="font-size:10px; padding:5px 8px;">	
	<tr>		
		<td style=" background-color:white; width:150px; text-align:right">
			ITEM: 
		</td>
		<td style="border: 0.5px solid #A7A7A7; background-color:white; width:405px; text-align:left">		
			 $roowfila[nombien]
		</td>
	</tr>
	
</table>
<table style="font-size:10px; padding:5px 8px;">	
<tr>		
	<td style=" background-color:white; width:150px; text-align:right">
		MARCA: 
	</td>
	<td style="border: 0.5px solid #A7A7A7; background-color:white; width:405px; text-align:left">		
		 $roowfila[codmarca]
	</td>
</tr>

</table>
<table style="font-size:10px; padding:5px 8px;">	
<tr>		
	<td style=" background-color:white; width:150px; text-align:right">
		MODELO: 
	</td>
	<td style="border: 0.5px solid #A7A7A7; background-color:white; width:405px; text-align:left">		
		 $roowfila[codmodelo]
	</td>
</tr>

</table>
<table style="font-size:10px; padding:5px 8px;">	
<tr>		
	<td style=" background-color:white; width:150px; text-align:right">
		No. DE SERIE: 
	</td>
	<td style="border: 0.5px solid #A7A7A7; background-color:white; width:405px; text-align:left">		
		 $roowfila[serie]
	</td>
</tr>

</table>
<table style="font-size:10px; padding:5px 8px;">	
<tr>		
	<td style="background-color:white; width:150px; text-align:right">
		CONSTA / INCLUYE:: 
	</td>
	<td style="border: 1px solid #A7A7A7; background-color:white; width:405px; text-align:left">		
		 <br>
		 <br>
	</td>
</tr>

</table>
<table style="font-size:10px; padding:5px 8px;">	
<tr>		
	<td style="background-color:white; width:150px; text-align:right">
	PAIS DE ORIGEN:
	</td>
	<td style="border: 1px solid #A7A7A7; background-color:white; width:405px; text-align:left">		
		
	</td>
</tr>

</table>

<table style="font-size:10px; padding:5px 8px;">	
<tr>		
	<td style="background-color:white; width:150px; text-align:right ">
		CATEGORIA DEL ITEM: 
	</td>
	<td style="border: 0.5px solid #A7A7A7; background-color:white; width:405px; text-align:left">		
		 $roowfilamov[condicion]
	</td>
</tr>

</table>
<table style="font-size:10px; padding:5px 8px;">	
<tr>		
	<td style="background-color:white; width:150px; text-align:right">
		RECEPCION DEL ITEM: 
	</td>
	<td style="border: 0.5px solid #A7A7A7; background-color:white; width:405px; text-align:left">		
		 $roowfila[fechaadq]
	</td>
</tr>
<tr>
<td style="border-bottom: 1px solid #A7A7A7; background-color:white; width:540px"></td>
</tr>

</table>
<table style="font-size:10px; padding:5px 10px;">	
<tr>		
<td style="border: 1px solid #004594; background-color:#004594; width:50%; text-align:center"><p style="color:#fff;">REALIZO</p></td>
<td style="border: 1px solid #004594; background-color:#004594; width:50%; text-align:center"><p style="color:#fff;">AUTORIZO</p></td>
</tr>
<tr>		
	<td style="border: 1px solid #004594; background-color:white; width:50%;text-align: center ">
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
	</td>
	<td style="border: 1px solid #004594; background-color:white; width:50%; text-align: center">		

	</td>
</tr>

</table>

EOF;

$pdf->writeHTML($bloque4, false, false, false, false, '');

// ---------------------------------------------------------
//SALIDA DEL ARCHIVO 


$nombrear = ($nombrearchivo) . ".pdf";
$pdf->Output($nombrear, 'D');

?>