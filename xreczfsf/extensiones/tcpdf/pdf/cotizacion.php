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


class imprimirFactura
{
	public $codigo;
	public function traerImpresionFactura()
	{
		//TRAEMOS LA INFORMACIÓN DE LA VENTA
		$itemVenta = "codigo";
		$valorVenta = $this->codigo;
		$respuestaVenta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);

		$fecha = substr($respuestaVenta["fecha"], 0, -8);
		$productos = json_decode($respuestaVenta["productos"], true);
		
		$informes = json_decode($respuestaVenta["productos"], true);
		$resultArray = array($informes[0]['descripcion']);		
		foreach ($resultArray as $value){
		$value;
		 $codigoo = substr($value, -8);
		}
		$noCot= "COT"."- ".$codigoo;

		//$countproductos = var_dump($productos);
		$neto = number_format($respuestaVenta["neto"], 2);
		$impuesto = number_format($respuestaVenta["impuesto"], 2);
		$total = number_format($respuestaVenta["total"], 2);

		//TRAEMOS LA INFORMACIÓN DEL CLIENTE
		$itemCliente = "idproveedor";
		$valorCliente = $respuestaVenta["id_cliente"];
		$respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);
		$nombrearchivo = $respuestaCliente["razonsocial"];


		//TRAEMOS LA INFORMACIÓN DEL VENDEDOR
		$itemVendedor = "id";
		$valorVendedor = $respuestaVenta["id_vendedor"];
		$respuestaVendedor = ControladorUsuarios::ctrMostrarUsuarios($itemVendedor, $valorVendedor);

		
		foreach ($productos as $key => $item) {

			$itemProducto = "codbieninv";
			$valorProducto = $item["descripcion"];
			
			$orden = null;

			$respuestaProducto = ControladorProductosPdf::ctrMostrarProductosPdf($itemProducto, $valorProducto, $orden);
			$facturara = $respuestaProducto["codcolor"];
		}

		//REQUERIMOS LA CLASE TCPDF
		require_once('tcpdf_include.php');
		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// remove default header/footer
		$pdf->setPrintHeader(false);
		//$pdf->setPrintFooter(false);

		$pdf->startPageGroup();
		$pdf->AddPage();
		// ---------------------------------------------------------

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
<table style="font-size:9px; padding:6px 10px;">	
	<tr>				
		<td style="width:155px; text-align:left; font-size:9px;">					
		</td>
		<td style=" background-color:white; width:250px; font-size:12px; text-align:center">	
			<div style="border: 1px solid #DFDFDF; padding:16px;">	
			<strong>COTIZACIÓN</strong>
			</div>
		</td>
		<td style="width:155px; text-align:right; font-size:9px;">
			FECHA: $fecha <br>
			N°:<strong> $noCot</strong>	<br>
			Cotización valida por 30 dias.

		</td>			
	</tr>
	
</table>
EOF;
		$pdf->writeHTML($bloque3, false, false, false, false, '');


		// ---------------------------------------------------------
		$bloque4 = <<<EOF

<table style="font-size:9px; padding:3px 10px;">	
<tr>
			<td style=" background-color:white; width:540px"></td>
		</tr>	
<tr>		
	<td style="border: 1px solid #DFDFDF; background-color:white; text-align: center; width:540px">
	DATOS PARA EL INFORME DE RESULTADOS
	</td>
</tr>
<tr>
<td style=" background-color:white; width:540px"></td>
</tr>
</table>

	<table style="font-size:9px; padding:3px 10px;">
				
		<tr>		
			<td style="border: 1px solid #DFDFDF; background-color:white; width:540x">
				<strong>EMPRESA:</strong> $respuestaCliente[razonsocial]
			</td>
		</tr>
	</table>

	<table style="font-size:9px; padding:5px 10px;">	
		<tr>		
			<td style="border: 1px solid #DFDFDF; background-color:white; width:540px">
			<strong>DIRECCIÓN:</strong> $respuestaCliente[direccion]
			</td>
		</tr>
	</table>
	<table style="font-size:9px; padding:3px 10px;">	
	<tr>		
		<td style="border: 1px solid #DFDFDF; background-color:white; width:540px">
		<strong>REPRESENTANTE LEGAL:</strong> $respuestaCliente[personacontacto]
		</td>
	</tr>
</table>
<table style="font-size:9px; padding:3px 10px;">	
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
	
	<table style="font-size:9px; padding:3px 10px;">	
	<tr>		
	<td style="border: 1px solid #DFDFDF; background-color:white; text-align: center; width:540px">
	DATOS PARA FACTURACIÓN
	</td>
</tr>
		<tr>
			<td style="border: 1px solid #DFDFDF; background-color:white; width:540px">
				<strong>DATOS DEL SOLICITANTE:</strong> $facturara
			</td>
		</tr>
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

		//---------------------------------------------------
		$bloque6 = <<<EOF
	<table style="font-size:9px; padding:5px 10px;">
		<tr>
        <td style="border: 1px solid #fff; background-color:#060044; width:35px; text-align:center">
		<p style="color:#fff;">No.</p>
		</td>
		<td style="border: 1px solid #fff; background-color:#060044; width:125px; text-align:center">
		<p style="color:#fff;">Norma</p></td>
		<td style="border: 1px solid #fff; background-color:#060044; width:160px; text-align:center"><p style="color:#fff;">Item</p></td>
		<td style="border: 1px solid #fff; background-color:#060044; width:65px; text-align:center"><p style="color:#fff;">Marca</p></td>
		<td style="border: 1px solid #fff; background-color:#060044; width:90px; text-align:center"><p style="color:#fff;">Modelo</p></td>				
		<td style="border: 1px solid #fff; background-color:#060044; width:65px; text-align:center"><p style="color:#fff;">Importe</p></td>
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
			//$password = $respuestaProducto["passinforteso"];
			$gestor = $respuestaProducto["codcolor"];
			$precioTotal = number_format($item["total"], 2);

			$totalReg = $key + 1;

			$bloque7 = <<<EOF
	<table style="font-size:8px; padding:2px 10px;">
		<tr>			
			<td style="border: 1px solid #666; color:#333; background-color:white; width:35px; text-align:left">
            	$totalReg
			</td>
			<td style="border: 1px solid #666; color:#333; background-color:white; width:125px; text-align:left">
				$categoria
			</td>
			<td style="border: 1px solid #666; color:#333; background-color:white; width:160px; text-align:left">
				$muestra
			</td>
			<td style="border: 1px solid #666; color:#333; background-color:white; width:65px; text-align:left">
				$marca
			</td>
			<td style="border: 1px solid #666; color:#333; background-color:white; width:90px; text-align:left"> 
				$modelo
			</td>
			<td style="border: 1px solid #666; color:#333; background-color:white; width:65px; text-align:left">
				$$precioTotal
			</td>
		</tr>
	</table>
EOF;
			$pdf->writeHTML($bloque7, false, false, false, false, '');
		}
		// ---------------------------------------------------------

		$bloque8 = <<<EOF
	<table style="font-size:8px; padding:3px 10px;">
		<tr>
			<td style="color:#333; background-color:white; width:360px; text-align:right"></td>
			<td style="border-bottom: 1px solid #666; background-color:white; width:100px; text-align:right"></td>
			<td style="border-bottom: 1px solid #666; color:#333; background-color:white; width:80px; text-align:right"></td>
		</tr>
		<tr>
			<td style=" border-right: 1px solid #666; color:#333; background-color:white; width:360px; text-align:left"></td>
			<td style="border: 1px solid #666;  background-color:white; width:100px; text-align:right">
				SUBTOTAL:
			</td>
			<td style="border: 1px solid #666; color:#333; background-color:white; width:80px; text-align:center">
				$ $neto
			</td>
		</tr>
		<tr>
			<td style="font-size:9px; border-right: 1px solid #666; color:#333; background-color:white; width:360px; text-align:left">
			
			</td>
			<td style="border: 1px solid #666; background-color:white; width:100px; text-align:right">
				IVA:
			</td>
			<td style="border: 1px solid #666; color:#333; background-color:white; width:80px; text-align:center">
				$ $impuesto
			</td>
		</tr>
		<tr>
			<td style="font-size:9px; border-right: 1px solid #666; color:#333; background-color:white; width:360px; text-align:left">
			Para quejas, sugerencias y/o comentarios: calidad@labteso.com</td>
			<td style="border: 1px solid #666; background-color:white; width:100px; text-align:right">
				TOTAL:
			</td>
			<td style="border: 1px solid #666; color:#333; background-color:white; width:80px; text-align:center">
			<strong>$ $total</strong>
			</td>
			<br>
		</tr>
		<tr>
			<td style=" background-color:white; width:540px"></td>
		</tr>

	</table>
EOF;
		$pdf->writeHTML($bloque8, false, false, false, false, '');
		$bloque9 = <<<EOF
	<table>		
		<tr>			
			<td style="background-color:white; width:540px">				
				<div style="font-size:9px; text-align:left; line-height:15px;">										
					<strong>Para ver el alcance completo de la acreditación del laboratorio, visitar el catalogo de acreditados en www.ema.org.mx
					</strong>Consulta nuestra pagina web: www.labteso.com	
				</div>
			</td>
		</tr>
	</table>
EOF;
		$pdf->writeHTML($bloque9, false, false, false, false, '');
		$bloque11 = <<<EOF
	<table>		
		<tr>			
			<td style="background-color:white; width:540px">				
				<div style="font-size:9px; text-align: center; line-height:15px;">					
					<br>						
					Jacinto Canek 15-A, Col. San Juan Ixhuatepec, C.P. 54180, Tlalnepantla, Edo. de México, Tels.: (55) 4426-1914, (55) 4426-4362,                   
   					(55) 4426-1034 y (55) 4426-3060, supervision.clientes@labteso.com
				</div>				
				<br>
			</td>
		</tr>
	</table>	
EOF;
		$pdf->writeHTML($bloque11, false, false, false, false, '');

		$bloque12 = <<<EOF
<table>		
	<tr>			
		<td style="background-color:white; width:270px;">
			<div style="font-size:9px; line-height:5px;">					
								
			</div>			
		</td>
		<td style="background-color:white; width:270px;">			
			<div style="font-size:10px; line-height:5px; text-align: right;">					
				FLAM-404-03-01
			</div>		
		</td>	
	</tr>
</table>

EOF;
		$pdf->writeHTML($bloque12, false, false, false, false, '');
		// ---------------------------------------------------------
		//SALIDA DEL ARCHIVO 
		$nombrear = ($noCot . " " . $nombrearchivo) . ".pdf";
		$pdf->Output($nombrear, 'D');
	}
}

$factura = new imprimirFactura();
$factura->codigo = $_GET["codigo"];
$factura->traerImpresionFactura();
