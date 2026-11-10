<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";


require_once "../../../controladores/categorias.controlador.php";
require_once "../../../modelos/categorias.modelo.php";

require_once "../../../controladores/normas.controlador.php";
require_once "../../../modelos/normas.modelo.php";


class imprimirFactura{
public $codigo;
public function traerImpresionFactura(){
//TRAEMOS LA INFORMACIÓN DE LA VENTA
$itemVenta = "codigo";
$valorVenta = $this->codigo;
$respuestaVenta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);
$fecha = substr($respuestaVenta["fecha"],0,-8);
$productos = json_decode($respuestaVenta["productos"], true);
$neto = number_format($respuestaVenta["neto"],2);
$impuesto = number_format($respuestaVenta["impuesto"],2);
$total = number_format($respuestaVenta["total"],2);
//TRAEMOS LA INFORMACIÓN DEL CLIENTE
$itemCliente = "idproveedor";
$valorCliente = $respuestaVenta["id_cliente"];
$respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);
$nombrearchivo = $respuestaCliente["razonsocial"];
//TRAEMOS LA INFORMACIÓN DEL VENDEDOR
$itemVendedor = "id";
$valorVendedor = $respuestaVenta["id_vendedor"];
$respuestaVendedor = ControladorUsuarios::ctrMostrarUsuarios($itemVendedor, $valorVendedor);
//REQUERIMOS LA CLASE TCPDF
require_once('tcpdf_include.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->startPageGroup();
$pdf->AddPage();
// ---------------------------------------------------------

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

//------------------------
$bloque2 = <<<EOF
<div align="center"; top: "50%">
	<img src="images/impr_izquierda.jpg" width="50px">
	LABORATORIO TESO DE MEXICO S.A. DE C.V.
	<BR>
	

	</div>
	
EOF;
$pdf->writeHTML($bloque2, false, false, false, false, '');
//-------------------------------------------------------------------------------

$bloque3 = <<<EOF
<table style="font-size:12px; padding:6px 10px;">	
	<tr>		
		<td style=" background-color:white; width:150px">			 
		</td>
		<td style="border: 1px solid #DFDFDF; background-color:white; width:255px; text-align:center">		
		<strong>RECIBO DE MUESTRAS</strong>
		</td>
		<td style=" background-color:white; width:150px; text-align:right; font-size:9px;">
		FECHA: $fecha <br>N°:<strong> $valorVenta</strong>
		</td>
	
	</tr>
</table>
EOF;
$pdf->writeHTML($bloque3, false, false, false, false, '');


// ---------------------------------------------------------
$bloque4 = <<<EOF

	<table style="font-size:10px; padding:3px 10px;">	
	<tr>
<td style=" background-color:white; width:540px"></td>

</tr>
		<tr>		
			<td style="border: 1px solid #DFDFDF; background-color:white; width:540x">
			<strong>EMPRESA:</strong> $respuestaCliente[razonsocial]
			</td>

		</tr>
	</table>
	<table style="font-size:10px; padding:5px 10px;">	
		<tr>		
			<td style="border: 1px solid #DFDFDF; background-color:white; width:540px">
			<strong>DIRECCIÓN:</strong> $respuestaCliente[direccion]
			</td>
		</tr>
	</table>
	<table style="font-size:10px; padding:3px 10px;">	
	<tr>		
		<td style="border: 1px solid #DFDFDF; background-color:white; width:540px">
		<strong>REPRESENTANTE:</strong> $respuestaCliente[personacontacto]
		</td>
	</tr>
</table>
<table style="font-size:10px; padding:3px 10px;">	
		<tr>		
			<td style="border: 1px solid #DFDFDF; background-color:white; width:300px">
			<strong>RFC:</strong> $respuestaCliente[giro]
			</td>
			<td style="border: 1px solid #DFDFDF; background-color:white; width:240px; text-align:left">		
			<strong>E-MAIL:</strong> $respuestaCliente[email]
			</td>
		</tr>
		<tr>
		<td style=" background-color:white; width:540px"></td>
		</tr>
	</table>
EOF;
$pdf->writeHTML($bloque4, false, false, false, false, '');
// ---------------------------------------------------------

$bloque5 = <<<EOF
	<table style="font-size:9px; padding:2px 5px;">		
		<tr>			
			<td style=" background-color:white; width:540px ">													
				<p>
				<strong>	LAS SIGUIENTES MUESTRAS QUE ABAJO SE DETALLAN SON RECIBIDAS POR EL LABORATORIO TESO PARA LA REALIZACION DE PRUEBAS
					EN BASE A NORMAS OFICIALES MEXICANAS </strong>
				</p>
				<br>
			</td>
		</tr>
	</table>
EOF;
$pdf->writeHTML($bloque5, false, false, false, false, '');
//---------------------------------------------------

$bloque6 = <<<EOF
	<table style="font-size:10px; padding:5px 10px;">
		<tr>
		<td style="border: 1px solid #fff; background-color:#060044; width:85px; text-align:center">
		<p style="color:#fff;">No. INFORME</p>
		</td>
		<td style="border: 1px solid #fff; background-color:#060044; width:100px; text-align:center">
		<p style="color:#fff;">NORMA</p>
		</td>
		<td style="border: 1px solid #fff; background-color:#060044; width:135px; text-align:center"><p style="color:#fff;">ITEM</p></td>
		<td style="border: 1px solid #fff; background-color:#060044; width:70px; text-align:center"><p style="color:#fff;">MARCA</p></td>
		<td style="border: 1px solid #fff; background-color:#060044; width:70px; text-align:center"><p style="color:#fff;">MODELO</p></td>
		<td style="border: 1px solid #fff; background-color:#060044; width:80px; text-align:center"><p style="color:#fff;">IMPORTE</p></td>
		</tr>
	</table>
EOF;
$pdf->writeHTML($bloque6, false, false, false, false, '');
// ---------------------------------------------------------

foreach ($productos as $key => $item) {

$itemProducto = "codbieninv";
$valorProducto = $item["descripcion"];
$orden = null;
$respuestaProducto = ControladorProductosPdf::ctrMostrarProductosPdf($itemProducto, $valorProducto, $orden);
$categoria = $respuestaProducto["codfamiliabien"];
$muestra = $respuestaProducto["nombien"];
$marca = $respuestaProducto["codmarca"];
$modelo = $respuestaProducto["codmodelo"];
$fechaentrega = $respuestaProducto["tiempovida"];
$gestor = $respuestaProducto["codcolor"];
$precioTotal = number_format($item["total"], 2);
$bloque7 = <<<EOF
	<table style="font-size:9px; padding:2px 10px;">
		<tr>			
			<td style="border: 1px solid #666; color:#333; background-color:white; width:85px; text-align:center">
				<strong>$item[descripcion]</strong>
			</td>
			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">
				$categoria
			</td>
			<td style="border: 1px solid #666; color:#333; background-color:white; width:135px; text-align:center">
				$muestra
			</td>
			<td style="border: 1px solid #666; color:#333; background-color:white; width:70px; text-align:center">
				$marca
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:70px; text-align:center"> 
				$modelo
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:80px; text-align:center">$ 
				$precioTotal
			</td>
		</tr>
	</table>
EOF;
$pdf->writeHTML($bloque7, false, false, false, false, '');
}
// ---------------------------------------------------------

$bloque8 = <<<EOF
	<table style="font-size:10px; padding:3px 10px;">
		<tr>
			<td style="color:#333; background-color:white; width:360px; text-align:right"></td>
			<td style="border-bottom: 1px solid #666; background-color:white; width:100px; text-align:right"></td>
			<td style="border-bottom: 1px solid #666; color:#333; background-color:white; width:80px; text-align:right"></td>
		</tr>
		<tr>
			<td style="border-right: 1px solid #666; color:#333; background-color:white; width:360px; text-align:right"></td>
			<td style="border: 1px solid #666;  background-color:white; width:100px; text-align:right">
				SUBTOTAL:
			</td>
			<td style="border: 1px solid #666; color:#333; background-color:white; width:80px; text-align:center">
				$ $neto
			</td>
		</tr>
		<tr>
			<td style="border-right: 1px solid #666; color:#333; background-color:white; width:360px; text-align:center"></td>
			<td style="border: 1px solid #666; background-color:white; width:100px; text-align:right">
				IVA:
			</td>
			<td style="border: 1px solid #666; color:#333; background-color:white; width:80px; text-align:center">
				$ $impuesto
			</td>
		</tr>
		<tr>
			<td style="border-right: 1px solid #666; color:#333; background-color:white; width:360px; text-align:center"></td>
			<td style="border: 1px solid #666; background-color:white; width:100px; text-align:right">
				TOTAL:
			</td>
			<td style="border: 1px solid #666; color:#333; background-color:white; width:80px; text-align:center">
			<strong>$ $total</strong>
			</td>
			<br>
		</tr>
	</table>
EOF;
$pdf->writeHTML($bloque8, false, false, false, false, '');

$bloque9 = <<<EOF
	<table>		
		<tr>			
			<td style="background-color:white; width:540px">				
				<div style="font-size:9px; text-align:left; line-height:15px;">					
					<br>
					<strong>Para ver el alcance completo de la acreditación del laboratorio, visitar el catalogo de acreditados en www.ema.org.mx</strong>				
				</div>
				<div style="font-size:10px; text-align:left; line-height:15px;">					

					Accesorios: <strong><u>VER SOLICITUD ANEXA</u></strong>
					<br>				
				</div>
			</td>
		</tr>
	</table>
	<table>		
	<tr>			
		<td style="background-color:white; width: 270px">			
			<div style="font-size:10px; text-align:left; line-height:15px;">					

				Fecha de entrega de resultados propuesta: <strong><u> $fechaentrega</u></strong>
				<br>				
			</div>
		</td>
		<td style="background-color:white; width: 270px">			
		<div style="font-size:10px; text-align:left; line-height:15px;">					

			Observaciones: <strong><u>Informe para $gestor</u></strong>
			<br>				
		</div>
	</td>
	</tr>
</table>
EOF;
$pdf->writeHTML($bloque9, false, false, false, false, '');

//----------------------------------
$bloque10 = <<<EOF

	<table>		
		<tr>			
			<td style="background-color:white; width:540px">	
					
				<div style="font-size:10px; text-align:left; line-height:15px;">					
					Firma de quién recibió:      
					<br>     
					 
				</div>
			</td>
		</tr>
	</table>
EOF;
$pdf->writeHTML($bloque10, false, false, false, false, '');

$bloque11 = <<<EOF
	<table>		
		<tr>			
			<td style="background-color:white; width:540px">				
				<div style="font-size:10px; text-align: center; line-height:15px;">					
					<br>						
					Jacinto Canek 15-A, Col. San Juan Ixhuatepec, C.P. 54180, Tlalnepantla, Edo. de México, Tels.: (55) 4426-1914, (55) 4426-4362,                   
   					(55) 4426-1034 y (55) 4426-3060, direccion@labatc.com
 
				</div>
				<div style="font-size:10px; text-align: right; line-height:5px;">					
					FLAM-404-01-06
					<br>
					<br>
					<br>
					<br>
					<br>
					
				</div>
			</td>

		</tr>
	</table>
EOF;

$pdf->writeHTML($bloque11, false, false, false, false, '');

// ---------------------------------------------------------
//SALIDA DEL ARCHIVO 
$nombrear=($valorVenta."-".$nombrearchivo).".pdf";
$pdf->Output($nombrear, 'D');
}
}
$factura = new imprimirFactura();
$factura -> codigo = $_GET["codigo"];
$factura -> traerImpresionFactura();
?>

