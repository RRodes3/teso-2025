<?php require_once('Connections/conexion.php');
if (!isset($_SESSION)) {
  session_start();
} ?>
<?php
$idbien = '';
$accion = '';
if(isset( $_GET['id'])){ $idbien = $_GET['id'];}
if(isset( $_GET['accion'])){ $accion = $_GET['accion'];}


$query_lista = "SELECT bien.*,biencatalogo.nombien,  proveedor.codproveedor, proveedor.razonsocial, familiabien.codfamiliabien, familiabien.familiabien FROM bien inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat   inner join proveedor on bien.idproveedor = proveedor.idproveedor inner join familiabien on bien.idfamiliabien = familiabien.idfamiliabien  WHERE bien.idbien ='$idbien' ORDER BY biencatalogo.nombien ASC";
$lista = mysqli_query($conexion, $query_lista ) or die(mysqli_error());
$roowfila=mysqli_fetch_array($lista);
$totalRows_lista = mysqli_num_rows($lista);  


$query_listamov = "SELECT * FROM movimiento where idbien = '".$roowfila['idbien']."' order by idmov desc limit 1 ";
$listamov = mysqli_query( $conexion,$query_listamov) or die(mysqli_error());
$roowfilamov=mysqli_fetch_array($listamov);
$totalRows_listamov = mysqli_num_rows($listamov);  


 ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema Teso</title>
  <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
  <meta name="author" content="Eduardo Sandoval">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">


  <style type="text/css" media="print">
  @page {
    margin: 0;
  }
  </style>
</head>

<body>
  <div class="wrapper">
    <?php 
  $activarmenu='imprimir-qr';
 // include('aside.php');
  ?>
    <div class="content-wrapper" style="margin-left: 0px;">

      <section class="content-header">
        <h1>
          <i class="fa fa-print"></i>
          Has registrado un nuevo Informe-Impresión del código QR
        </h1>
        <ol class="breadcrumb">
          <a href="<?php if($accion =='REGISTRO'){ echo 'registro-bien.php';}else{echo 'javascript:history.back(-1);';}?>"
            class="btn btn-default btn-sm" title="Volver atras">Volver atras <span
              class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>

          <a href='javascript:window.print(); void 0;' id="btnImprimirPantalla" class="btn btn-primary btn-sm"
            role="button" title="Imprimir reporte"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
        </ol>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">



              <div class="row">
                <div class="col-md-4 text-center" style="margin:0px; padding:0px; width:200px; ">
                  <div style="width:100%; font-size:14px ;margin:0px; padding:0px; ">

                    <?php
				//set it to writable location, a place for temp generated PNG files
				$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
				 
				//html PNG location prefix
				$PNG_WEB_DIR = 'temp/';
				
					
				$files = glob('temp/*'); // obtiene todos los archivos
				foreach($files as $file){
				  if(is_file($file)) // si se trata de un archivo
					unlink($file); // lo elimina
				}

				include "codigoqr/qrlib.php";    
				
				//ofcourse we need rights to create temp dir
				if (!file_exists($PNG_TEMP_DIR))
					mkdir($PNG_TEMP_DIR);
				
				$filename = $PNG_TEMP_DIR.'test.png';
				
				$matrixPointSize = 20;
				$errorCorrectionLevel = 'L';
			 
				$data ="No. INFORME: ".	$roowfila['codbieninv']."\n"
				. "CODIGO UNICO INFORME.: ".	$roowfila['codunicoinfor']."\n"
				. "NORMA: ".	$roowfila['codfamiliabien']."\n"
				. "NOMBRE: ".	$roowfila['nombien']."\n"
				. "MARCA: ".	$roowfila['codmarca']."\n"
				//. "COLOR: ".	$roowfila['codcolor']."\n"
				. "MODELO: ". $roowfila['codmodelo']."\n"
				. "N. SERIE: ".	$roowfila['serie']."\n"
				. "CONSTA / INCLUYE: ".	$roowfila['caracteristica']."\n"
				. "FECH. DE RECEPCION: ".	$roowfila['fechaadq']."\n"
				//. "RUT PROV: ".	$roowfila['codproveedor']."\n"
				. "CLIENTE / EMPRESA: ".	$roowfila['razonsocial'];
				
				$filename = $PNG_TEMP_DIR.'test'.md5(rand(3,50).'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
				QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 
				
				echo '<img style="width:85%;margin:0px; padding:0px " src="'.$PNG_WEB_DIR.basename($filename).'" /><br>'.$roowfila['codbieninv'].'<br>'.$roowfila['codfamiliabien'].'<br>'.$roowfila['codunicoinfor'].' ';  
				?>
                  </div>
                </div>
                <div class="col-md-8  hidden-print">
                  <strong>INFORMACIÓN DEL CODIGO:</strong>
                  <table width="100%" border="0" style="background-color: #F4F4F4">
                    <tr valign="baseline">
                      <td width="26%" style=""><strong>No. INFORME:</strong></td>
                      <td width="74%"><?php echo $roowfila['codbieninv'];?></td>
                    </tr>
                    <tr valign="baseline">
                      <td width="26%" style=""><strong>CLAVE:</strong></td>
                      <td width="74%"><?php echo $roowfila['codunicoinfor'];?></td>
                    </tr>


                    <tr valign="baseline">
                      <td style=""><span style=" padding-right:10px;"><strong>NORMA:</strong></span>
                      </td>
                      <td><?php echo $roowfila['codfamiliabien'];?></td>
                    </tr>
                    <tr valign="baseline">
                      <td style=""><strong>DESCRIPCION:</strong></td>
                      <td><?php echo $roowfila['familiabien'];?></td>
                    </tr>
                    <tr valign="baseline">
                      <td style=""><strong>NOMBRE DEL ITEM:</strong></td>
                      <td><?php echo $roowfila['nombien'];?></td>
                    </tr>

                    <tr valign="baseline">
                      <td style=""><strong>MARCA:</strong></td>
                      <td><?php echo $roowfila['codmarca'];?></td>
                    </tr>
                    <!-- <tr valign="baseline">
                      <td style=""><strong>COLOR:</strong></td>
                      <td><?php echo $roowfila['codcolor'];?></td>
                    </tr> -->
                    <tr valign="baseline">
                      <td style=""><strong>MODELO:</strong></td>
                      <td><?php echo $roowfila['codmodelo'];?></td>
                    </tr>
                    <tr valign="baseline">
                      <td style=" padding-right:10px;"><span style=""><strong>N° SERIE: </strong></span></td>
                      <td><?php echo $roowfila['serie'];?></td>
                    </tr>
                    <tr valign="baseline">
                      <td style=""><strong>CONSTA / INCLUYE:</strong></td>
                      <td><?php echo $roowfila['caracteristica'];?></td>
                    </tr>
                    <!-- <tr valign="baseline">
                      <td style=""><strong>FECHA DE ADQUISICIÓN:</strong></td>
                      <td><?php echo $roowfila['fechaadq'];?></td>
                    </tr> -->
                    <!-- <tr valign="baseline">
                      <td style=""><strong>RUT PROV.</strong></td>
                      <td><?php echo $roowfila['codproveedor'];?></td>
                    </tr> -->
                    <tr valign="baseline">
                      <td style=""><strong>CLIENTE / EMPRESA:</strong></td>
                      <td><?php echo $roowfila['razonsocial'];?></td>
                    </tr>

                    <tr valign="baseline">
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
                </div>
              </div>



            </div>
            <!-- /.box -->
          </div>
        </div>
      </section>

    </div>

    <?php // include('footer.html'); ?>

  </div>

  <!-- jQuery 2.2.3 -->
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>

  <script>
  // $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.6 -->
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <!--<script src="plugins/fastclick/fastclick.js"></script>
 AdminLTE App 
<script src="dist/js/app.min.js"></script>-->

</body>

</html>