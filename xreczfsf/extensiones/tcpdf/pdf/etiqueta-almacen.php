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

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->startPageGroup();
$pdf->AddPage('L','A7');
// set font
$pdf->SetFont('', '', 13);



$bloque1 = <<<EOF
    <div style="text-align: center; margin: 0; padding: 0;">
        <img src="images/impr_izquierda.jpg" width="35px"> 
        LABORATORIO TESO DE MEXICO S.A. DE C.V.    
        <div style=" border: 1px solid black; font-size: 28px; font-weight: bold; margin: 0; padding: 0;"> 
        $roowfila[codbieninv] 
        </div>      
        NOM:$roowfila[codfamiliabien]
        <div style="font-size: 15px; font-weight: bold; margin: 0; padding: 0;"> 
        ANAQUEL: $roowfila[lugar]
        </div> 
        FLAM-508-02-06
    </div>
    
    

EOF;
$pdf->writeHTML($bloque1, false, false, false, false, '');
// ---------------------------------------------------------
//SALIDA DEL ARCHIVO 
$nombrear=("ETIQUETA"." ".$nombrearchivo).".pdf";
$pdf->Output($nombrear, 'D');

?>