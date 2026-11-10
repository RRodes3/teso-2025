<?php
require_once('Connections/conexion-subir-archivo.php');
$fn = Conexion();
if (substr($_FILES['archivo_csv']['name'], -3) == "csv") {
	$fecha 		= date('Y-m-d');
	$carpeta 	= "tmp_excel/";
	$n_archivo  = $fecha . '-' . $_FILES['archivo_csv']['name'];
	$row = 1;
	move_uploaded_file($_FILES['archivo_csv']['tmp_name'], $carpeta . $n_archivo);
	$fp = fopen($carpeta . $n_archivo, "r");
	while ($data = fgetcsv($fp, 1000, ",")) {		
		if ($row != 1) {
			$sql_archivo  = "INSERT INTO bien(codbieninv,idfamiliabien,codbiencat,codmarca,codcolor,codmodelo,codunicoinfor,facturara,quienregis,stock,ventas,existencia,factura,estado,observaciones,fechaadq,idproveedor,valor,tiempovida,idusuarioreg,fregistro)";
			$sql_archivo .= "VALUES('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]','$data[10]','$data[11]','$data[12]','$data[13]','$data[14]','$data[15]','$data[16]','$data[17]','$data[18]','$data[19]','$data[20]')";
			$rpta_archivo = mysqli_query($fn, $sql_archivo) or die(mysqli_error($fn));
			if (!$sql_archivo) {
				echo "<p>Hubo un problema al momento de importar el archivo. Por favor vuelva a intentarlo</p>";
				exit;
			}
		}
		$row++;
	}
	fclose($fp);
	echo "<p>La importacion del archivo CSV se ha subido satisfactoriamente</p>";
	exit();
}
