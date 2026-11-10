<?php
$codfam = '';
$codinv = '';
if (isset($_GET['codfam'])) {
	$codfam = $_GET['codfam'];
}
if (isset($_GET['codinv'])) {
	$codinv = $_GET['codinv'];
}
require_once('../Connections/conexion.php');
$query_infofam = "SELECT * FROM proveedor where idproveedor = '" . $codfam . "'";
$infofam = mysqli_query($conexion, $query_infofam) or die(mysqli_error());
$row_infofam = mysqli_fetch_array($infofam);
$codfamilia =  $row_infofam['personacontacto'];
$codinve = $codfamilia
?>
<input class="form-control" type="text" name="codbieninvs" id="codbieninvs" value="<?php echo $codinve; ?>">
<?php if ($codinv != '') { ?>
	<small>Cod. anterior: <?php echo $codinv; ?></small><?php } ?>