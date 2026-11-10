<?php
$codfam = '';

if (isset($_GET['codnorma'])) {
	$codfam = $_GET['codnorma'];
}

require_once('../Connections/conexion.php');

$query_infofam = "SELECT * FROM familiabien where idfamiliabien = '" . $codfam . "'";
$infofam = mysqli_query($conexion, $query_infofam) or die(mysqli_error());

$row_infofam = mysqli_fetch_array($infofam);

$codfamilia =  $row_infofam['precio'];
$codinve = $codfamilia;
?>
<input class="form-control" type="text" name="precionorma" id="precionorma" value="<?php echo $codinve; ?>">

