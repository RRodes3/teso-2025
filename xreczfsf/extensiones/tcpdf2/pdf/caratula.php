<?php require_once('../../../../Connections/conexion.php');
$menuactivo ='movimiento';
if (!isset($_SESSION)) {
  session_start();
} ?>
<?php
$idbien = '';
$idmov = '';
if(isset( $_GET['id'])){ $idbien = $_GET['id'];}



$query_lista = "SELECT bien.*,biencatalogo.nombien,  proveedor.codproveedor, proveedor.razonsocial, proveedor.direccion, proveedor.personacontacto, familiabien.codfamiliabien, familiabien.familiabien FROM bien 
inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat  
inner join proveedor on bien.idproveedor = proveedor.idproveedor 
inner join familiabien on bien.idfamiliabien = familiabien.idfamiliabien  
WHERE bien.idbien ='$idbien' ORDER BY biencatalogo.nombien ASC";
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$roowfila=mysqli_fetch_array($lista);
$totalRows_lista = mysqli_num_rows($lista);
$nombrearchivo = $roowfila["codbieninv"]; 
 
if(isset( $_GET['idmov']) and $_GET['idmov'] !=''){ $idmov = $_GET['idmov'];
}else{ $idmov = $roowfila['idmovimiento'];
	
	}

$query_listamov = "SELECT * FROM movimiento where idbien = '".$idbien ."' and idmov = '".$idmov."' order by idmov desc limit 1 ";
$listamov = mysqli_query($conexion, $query_listamov) or die(mysqli_error());
$roowfilamov=mysqli_fetch_array($listamov);
$totalRows_listamov = mysqli_num_rows($listamov);  


  



//REQUERIMOS LA CLASE TCPDF

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->startPageGroup();

$pdf->AddPage();

//-------------------------------------------------------------------------------

$bloque1 = <<<EOF

<table style="font-size:12px; padding:6px 10px;">	
	<tr>		
		<td style=" background-color:white; width:150px">			 
		</td>
		<td style=" background-color:white; width:255px; text-align:center">		
	
		</td>
		<td style=" background-color:white; width:150px; text-align:left">				
		</td>
	</tr>
</table>

EOF;
$pdf->writeHTML($bloque1, false, false, false, false, '');

//---------------------------------------------------------
$bloque2 = <<<EOF

	<div align="center"; top: "50%">
	<img src="images/impr_izquierda.jpg" width="50px">
	LABORATORIO TESO DE MEXICO S.A. DE C.V.
	<BR>
	<BR>

	</div>
EOF;
$pdf->writeHTML($bloque2, false, false, false, false, '');
// ---------------------------------------------------------
//-------------------------------------------------------------------------------

$bloque3 = <<<EOF

<table style=" padding:6px 10px;">	
	<tr>		
		<td style=" background-color:white; width:150px">			 
		</td>
		<td style="border: 1px solid #4B4B4B; background-color:white; width:255px; text-align:center; font-size:12px;">		
		<strong>BORRADOR DE ENSAYOS</strong>
		</td>
		<td style=" background-color:white; width:150px; text-align:right; font-size:10px;">
		1/______				
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
<td style=" background-color:white; width:150px"></td>
<td style=" background-color:white; width:405px"></td>
</tr>
<tr>		
	<td style="background-color:white; width:150px; text-align:right">
		NUMERO DE INFORME: 
	</td>
	<td style="border: 0.5px solid #A7A7A7; background-color:white; width:405px; text-align:left;  font-size:11px">		
		 $roowfila[codbieninv] $roowfila[segui]
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
<td style="border: 1px solid #060044; background-color:#060044; width:50%; text-align:center"><p style="color:#fff;">REALIZO</p></td>
<td style="border: 1px solid #060044; background-color:#060044; width:50%; text-align:center"><p style="color:#fff;">AUTORIZO</p></td>
</tr>
<tr>		
	<td style="border: 1px solid #060044; background-color:white; width:50%;text-align: center ">
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
	</td>
	<td style="border: 1px solid #060044; background-color:white; width:50%; text-align: center">		

	</td>
</tr>

</table>
EOF;

$pdf->writeHTML($bloque4, false, false, false, false, '');

// ---------------------------------------------------------
//SALIDA DEL ARCHIVO 


$nombrear=($nombrearchivo).".pdf";
$pdf->Output($nombrear, 'D');

?>