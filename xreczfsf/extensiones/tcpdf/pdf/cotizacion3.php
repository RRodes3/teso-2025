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



require_once('tcpdf_include.php');

class MYPDF extends TCPDF {


public function Footer() {

    $this->SetY(-30);
    $this->SetFont('helvetica', '', 8);

    $html = '
    <table cellpadding="4">
        <tr>
            <td width="135" align="center"><strong>Nomenclatura:</strong></td>
            <td width="135" align="center"><strong>Núm. De edición:</strong></td>
            <td width="135" align="center"><strong>Fecha de emisión:</strong></td>
            <td width="135" align="center"><strong>Fecha de revisión:</strong></td>
        </tr>
        <tr>
            <td width="135" align="center">FATC-P01.03</td>
            <td width="135" align="center">00</td>
            <td width="135" align="center">Diciembre, 2025</td>
            <td width="135" align="center">N/A</td>
        </tr>
		<tr>			
					<td style="background-color:white; width:270px;">
						<div style="font-size:9px; line-height:5px;">													
						</div>			
					</td>
					<td style="background-color:white; width:270px;">			
						<div style="font-size:10px; line-height:5px; text-align: right;">					
							<img src="images/footerteso.png" width="120px">					
						</div>		
					</td>	
				</tr>
    </table>';

    $this->writeHTML($html, false, false, false, false, '');
}
}



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
		foreach ($resultArray as $value) {
			$value;
			$codigoo = substr($value, -8);
		}
		$noCot = "COT" . "- " . $codigoo;

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
		
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// remove default header/footer
		$pdf->setPrintHeader(false);
		//$pdf->setPrintFooter(false);
		$pdf->startPageGroup();
		
		
$pdf->SetMargins(15, 10, 15);
$pdf->SetAutoPageBreak(true, 30);


		$pdf->AddPage();
		// ---------------------------------------------------------
		//------------------------
		$bloque1 = <<<EOF
			<table cellpadding="5">
				<tr>
					<!-- LOGO -->
					<td width="180" height="60" align="left" valign="middle">
					<img src="images/headerteso.png" width="140">
					</td>
					<!-- TITULO -->
					<td width="180" height="60" align="center" valign="middle">
					<br>
					<br>
					<br>					
					COTIZACIÓN
					</td>
					<!-- CELDA VACÍA -->
					<td width="180" height="60">
					</td>
				</tr>
			</table>	
EOF;
		$pdf->writeHTML($bloque1, false, false, false, false, '');
		//-------------------------------------------------------------------------------
		// --------------------------------------------------------
		$bloque3 = <<<EOF
		<table style="font-size:9px; padding:3px 10px;">	
			<tr>
			<td style=" background-color:white; width:540px"></td>
			</tr>
		</table>
		<table style="font-size:9px; padding:3px 10px;">				
			<tr>		
				<td style="border: 1px solid #DFDFDF; background-color:white; width:340x">
					Cliente: $respuestaCliente[razonsocial]
				</td>
				<td style="border: 1px solid #DFDFDF; background-color:white; width:200px; text-align:right">		
				Fecha: $fecha			
				</td>
			</tr>
			<tr>		
				<td style="border: 1px solid #DFDFDF; background-color:white; width:340x">				
					Correo electronico: $respuestaCliente[email]
				</td>
				<td style="border: 1px solid #DFDFDF; background-color:white; width:200px; text-align:right">		
					No. De cotización: $noCot
				</td>
				</tr>
				<tr>		
				<td style="border: 1px solid #DFDFDF; background-color:white; width:340x">				
					Telefono: $respuestaCliente[email]
				</td>
				<td style="border: 1px solid #DFDFDF; background-color:white; width:200px; text-align:right">		
				Vigencia de cotización: 30 días
				</td>
			</tr>
			<tr>		
				<td style="border: 1px solid #DFDFDF; background-color:white; width:340x">								
				</td>
				<td style="border: 1px solid #DFDFDF; background-color:white; width:200px; text-align:right">		
				Contacto TESO: atencionaclientes@labteso.com
				</td>			
			</tr>
			<tr>
				<td style=" background-color:white; width:540px"></td>
			</tr>
		</table>
	
EOF;
		$pdf->writeHTML($bloque3, false, false, false, false, '');
		// ---------------------------------------------------------
		//----------------------------------------------------------
		$bloque4 = <<<EOF
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
		$pdf->writeHTML($bloque4, false, false, false, false, '');
		// ---------------------------------------------------------		
		// --------------------------------------------------------
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
		//-------------------------------------------------------------------------------
		// --------------------------------------------------------
			$bloque5 = <<<EOF
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
			$pdf->writeHTML($bloque5, false, false, false, false, '');
		}
		// ---------------------------------------------------------
		
		// --------------------------------------------------------
		$bloque6 = <<<EOF
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
					<td style="font-size:9px; border-right: 1px solid #666; color:#333; background-color:white; width:360px; text-align:left"></td>
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
		$pdf->writeHTML($bloque6, false, false, false, false, '');
	//-------------------------------------------------------------------------------

	// --------------------------------------------------------
		$bloque7 = <<<EOF
			<table>		
				<tr>			
					<td style="background-color:white; width:540px">				
						<div style="font-size:9px; text-align:left; line-height:15px;">		
							Para ver el alcance completo de la acreditación del laboratorio, visitar el catálogo de acreditados en www.ema.org.mx<br>
							Consulta nuestra pagina web: www.labteso.com	
						</div>					
						<div style="font-size:9px; text-align:left; line-height:15px;">		
							Datos bancarios<br>
							<strong>Beneficiario:</strong>  Laboratorio TESO de México<br>
							<strong>BBVA</strong> / No. De cuenta: <strong>0165242770</strong>/ Clabe interbancaria: <strong>012180001652427702</strong><br>
							<strong>Santander</strong>/ No. De cuenta: <strong>65508854854</strong>/ Clabe interbancaria <strong>014180655088548544</strong><br> <br>
							Para quejas, sugerencias y/o comentarios: lideratc@labteso.com <br>
							Consulta nuestra página web: www.labteso.com
						</div>
						<div style="font-size:9px; text-align: center; line-height:15px;">															
							Laboratorio Teso de México, Jacinto Canek 15A, Tlalnepantla de Baz, Estado de México, México. 
							CP. 54180 Teléfonos: 55-8967-5190, 55-4426-1914, 55-4426-1034, 55-44263060 y 55-4426-4362.
							<br>
						</div>	
					</td>
				</tr>
				<tr>
					<td style=" background-color:white; width:540px"></td>
				</tr>
				<tr>
					<td style=" background-color:white; width:540px"></td>
				</tr>
			</table>
EOF;
		$pdf->writeHTML($bloque7, false, false, false, false, '');
		//-------------------------------------------------------------------------------
		// --------------------------------------------------------
		
		// ---------------------------------------------------------
		//SALIDA DEL ARCHIVO 
		$nombrear = ($noCot . " " . $nombrearchivo) . ".pdf";
		$pdf->Output($nombrear, 'D');
	}
}


$factura = new imprimirFactura();
$factura->codigo = $_GET["codigo"];
$factura->traerImpresionFactura();
