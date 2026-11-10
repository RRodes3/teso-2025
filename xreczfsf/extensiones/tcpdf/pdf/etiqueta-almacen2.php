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
    }else{ 
        $idmov = $roowfila['idmovimiento'];	
	}
$query_listamov = "SELECT * FROM movimiento where idbien = '".$idbien ."' and idmov = '".$idmov."' order by idmov desc limit 1 ";
$listamov = mysqli_query($conexion, $query_listamov) or die(mysqli_error());
$roowfilamov=mysqli_fetch_array($listamov);
$totalRows_listamov = mysqli_num_rows($listamov);  

//REQUERIMOS LA CLASE TCPDF
require_once('tcpdf_include.php');
class MYPDF extends TCPDF {


    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-12);
        // Set font
        $this->SetFont('helvetica', 'I', 10);
        // Page number
        $this->Cell(0, 0, 'www.labteso.com ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 0, 'FLAM-508-02-07 ', 0, false, 'R', 0, '', 0, false, 'T', 'M');

    }
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// remove default header/footer
$pdf->setPrintHeader(false);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->SetFont('', '', 13);
$pdf->AddPage('L','A7');

$bloque1 = <<<EOF

    <div style="text-align: center; font-size: 10px; margin: 0; padding: 0;">
    <img src="images/tcpdf_logo.jpg" width="55px"> 
   <span style="font-size: 12px; font-weight: bold; text-align: center;"> LABORATORIO TESO DE MÉXICO, S.A DE C.V.        </span>
        <div style=" border: 1px solid black; font-size: 38px; font-weight: bold;"> 
            $roowfila[codbieninv] 
        </div>  
        <div style="text-align: center; font-size: 18px; border: 1px solid black;">    
            $roowfila[codfamiliabien]<br>  
            Presenta daño:<input type="checkbox" name="adap" value="1" /> 
            Seguimiento:<input type="checkbox" name="segui" value="1" />		     <br>	 <br>
           
            Anaquel: _____________ <input type="checkbox" name="opcion1" value="1" /> <input type="checkbox" name="opcion2" value="1" /> <input type="checkbox" name="opcion3" value="1" />
        </div>
    </div>
                
EOF;


$pdf->writeHTML($bloque1, false, false, false, false, '');
// ---------------------------------------------------------
//SALIDA DEL ARCHIVO 
$nombrear=("ETIQUETA"." ".$nombrearchivo).".pdf";
$pdf->Output($nombrear, 'D');

?>