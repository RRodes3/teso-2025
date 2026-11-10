 <STYLE type="text/css">
table {
    border-collapse: collapse;
}
table, th, td {
   border: 1px  solid black;
   font-size:11px
}
.titulotabla{
	background-color:#CCC;
	
		}
 </STYLE>
<?php	
	header("Content-type: application/vnd.ms-excel; name='excel'");
	header("Content-Disposition: filename=".$_POST['nomarchivo']."");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	//echo $_POST['datos_a_enviar'];
	$tabla=$_POST['datos_a_enviar'];
$tabla=utf8_decode($tabla);
echo $tabla;
?>