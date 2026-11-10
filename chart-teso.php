<?php
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_SESSION['U_spprivilegio']) and in_array("sm_auditoria", $_SESSION['U_spprivilegio']) or in_array("s_asig", $_SESSION['U_spprivilegio']) or in_array("f-admin-fact", $_SESSION['U_spprivilegio'])) {
    require_once('Connections/conexion.php');
    $menuactivo = 'mantenimiento';
?>
    <?php

    if (!function_exists("GetSQLValueString")) {
        function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
        {
            if (PHP_VERSION < 6) {
                $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
            }
            //$theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);
            switch ($theType) {
                case "text":
                    $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                    break;
                case "long":
                case "int":
                    $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                    break;
                case "double":
                    $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                    break;
                case "date":
                    $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                    break;
                case "defined":
                    $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                    break;
            }
            return $theValue;
        }
    }
    $colname_misdatos = "-1";
    if (isset($_SESSION["U_sdni"])) {
        $colname_misdatos = $_SESSION["U_sdni"];
    }



    $query_conteo_enero = sprintf('SELECT * FROM bien WHERE idfamiliabien != 87 and codbiencat != 5170 and (debaja IS NULL) and fregistro between "2025-01-01" and "2025-01-31"');
    $conteo_enero = mysqli_query($conexion, $query_conteo_enero) or die(mysqli_error());
    $asigrec_enero = mysqli_num_rows($conteo_enero);

    $query_conteo_febrero = sprintf('SELECT * FROM bien WHERE idfamiliabien != 87 and codbiencat != 5170 and (debaja IS NULL) and fregistro between "2025-02-01" and "2025-02-29"');
    $conteo_febrero = mysqli_query($conexion, $query_conteo_febrero) or die(mysqli_error());
    $asigrec_febrero = mysqli_num_rows($conteo_febrero);

    $query_conteo_marzo = sprintf('SELECT * FROM bien WHERE idfamiliabien != 87 and codbiencat != 5170 and (debaja IS NULL) and fregistro between "2025-03-01" and "2025-03-31"');
    $conteo_marzo = mysqli_query($conexion, $query_conteo_marzo) or die(mysqli_error());
    $asigrec_marzo = mysqli_num_rows($conteo_marzo);

    $query_conteo_abril = sprintf('SELECT * FROM bien WHERE idfamiliabien != 87 and codbiencat != 5170 and (debaja IS NULL) and fregistro between "2025-04-01" and "2025-04-31"');
    $conteo_abril = mysqli_query($conexion, $query_conteo_abril) or die(mysqli_error());
    $asigrec_abril = mysqli_num_rows($conteo_abril);

    $query_conteo_mayo = sprintf('SELECT * FROM bien WHERE idfamiliabien != 87 and codbiencat != 5170 and (debaja IS NULL) and fregistro between "2025-05-01" and "2025-05-30"');
    $conteo_mayo = mysqli_query($conexion, $query_conteo_mayo) or die(mysqli_error());
    $asigrec_mayo = mysqli_num_rows($conteo_mayo);

    $query_conteo_junio = sprintf('SELECT * FROM bien WHERE idfamiliabien != 87 and codbiencat != 5170 and (debaja IS NULL) and fregistro between "2025-06-01" and "2025-06-31"');
    $conteo_junio = mysqli_query($conexion, $query_conteo_junio) or die(mysqli_error());
    $asigrec_junio = mysqli_num_rows($conteo_junio);

    $query_conteo_julio = sprintf('SELECT * FROM bien WHERE idfamiliabien != 87 and codbiencat != 5170 and (debaja IS NULL) and fregistro between "2025-07-01" and "2025-07-30"');
    $conteo_julio = mysqli_query($conexion, $query_conteo_julio) or die(mysqli_error());
    $asigrec_julio = mysqli_num_rows($conteo_julio);

    $query_conteo_agosto = sprintf('SELECT * FROM bien WHERE idfamiliabien != 87 and codbiencat != 5170 and (debaja IS NULL) and fregistro between "2025-08-01" and "2025-08-31"');
    $conteo_agosto = mysqli_query($conexion, $query_conteo_agosto) or die(mysqli_error());
    $asigrec_agosto = mysqli_num_rows($conteo_agosto);

    $query_conteo_sep = sprintf('SELECT * FROM bien WHERE idfamiliabien != 87 and codbiencat != 5170 and (debaja IS NULL) and fregistro between "2025-09-01" and "2025-09-30"');
    $conteo_sep = mysqli_query($conexion, $query_conteo_sep) or die(mysqli_error());
    $asigrec_sep = mysqli_num_rows($conteo_sep);

    $query_conteo_oct = sprintf('SELECT * FROM bien WHERE idfamiliabien != 87 and codbiencat != 5170 and (debaja IS NULL) and fregistro between "2025-10-01" and "2025-10-31"');
    $conteo_oct = mysqli_query($conexion, $query_conteo_oct) or die(mysqli_error());
    $asigrec_oct = mysqli_num_rows($conteo_oct);
    $query_conteo_nov = sprintf('SELECT * FROM bien WHERE idfamiliabien != 87 and codbiencat != 5170 and (debaja IS NULL) and fregistro between "2025-11-01" and "2025-11-30"');
    $conteo_nov = mysqli_query($conexion, $query_conteo_nov) or die(mysqli_error());
    $asigrec_nov = mysqli_num_rows($conteo_nov);

    $query_conteo_dic = sprintf('SELECT * FROM bien WHERE idfamiliabien != 87 and codbiencat != 5170 and (debaja IS NULL) and fregistro between "2025-12-01" and "2025-12-31"');
    $conteo_dic = mysqli_query($conexion, $query_conteo_dic) or die(mysqli_error());
    $asigrec_dic = mysqli_num_rows($conteo_dic);

    $query_conteo = sprintf('SELECT licencia.estado, bien.debaja FROM `licencia`
    inner join bien on licencia.id_bien = bien.idbien
     WHERE (licencia.estado IS NULL) AND bien.debaja IS NULL ');
    $conteo = mysqli_query($conexion, $query_conteo) or die(mysqli_error());
    $asigrec = mysqli_num_rows($conteo);

    $query_conteo = sprintf('SELECT licencia.estado, bien.debaja FROM `licencia` 
    inner join bien on licencia.id_bien = bien.idbien
    WHERE licencia.estado = "Pruebas" AND bien.debaja IS NULL');
    $conteo = mysqli_query($conexion, $query_conteo) or die(mysqli_error());
    $Pruebas = mysqli_num_rows($conteo);

    $query_conteo = sprintf('SELECT licencia.estado, bien.debaja FROM `licencia` 
    inner join bien on licencia.id_bien = bien.idbien
    WHERE licencia.estado="Supervision" AND bien.debaja IS NULL');
    $conteo = mysqli_query($conexion, $query_conteo) or die(mysqli_error());
    $supervision = mysqli_num_rows($conteo);

    $query_conteo = sprintf('SELECT licencia.estado, bien.debaja FROM `licencia` 
    inner join bien on licencia.id_bien = bien.idbien
    WHERE licencia.estado="Supervisado" AND bien.debaja IS NULL');
    $conteo = mysqli_query($conexion, $query_conteo) or die(mysqli_error());
    $supervisado = mysqli_num_rows($conteo);

    $query_conteo = sprintf('SELECT licencia.estado, bien.debaja FROM `licencia` 
    inner join bien on licencia.id_bien = bien.idbien
    WHERE licencia.estado="enviado" AND bien.debaja IS NULL');
    $conteo = mysqli_query($conexion, $query_conteo) or die(mysqli_error());
    $enviados = mysqli_num_rows($conteo);

    $query_conteo_total = sprintf('SELECT * FROM bien WHERE idfamiliabien != 87 and codbiencat != 5170 AND debaja IS NULL');
    $conteo_total = mysqli_query($conexion, $query_conteo_total) or die(mysqli_error());
    $grantotal = mysqli_num_rows($conteo_total);

    $query_conteo_total_baja = sprintf('SELECT * FROM bien WHERE debaja = 1');
    $conteo_total_baja = mysqli_query($conexion, $query_conteo_total_baja) or die(mysqli_error());
    $grantotal_baja = mysqli_num_rows($conteo_total_baja);

    $query_conteo_envi_ener = sprintf('SELECT estado, fechaenvio FROM `licencia` WHERE estado="enviado" and fechaenvio between "2025-01-01" and "2025-01-31"');
    $conteo_envi_ener = mysqli_query($conexion, $query_conteo_envi_ener) or die(mysqli_error());
    $enviados_enero = mysqli_num_rows($conteo_envi_ener);

    $query_conteo_envi_feb = sprintf('SELECT estado, fechaenvio FROM `licencia` WHERE estado="enviado" and fechaenvio between "2025-02-01" and "2025-02-29"');
    $conteo_envi_feb = mysqli_query($conexion, $query_conteo_envi_feb) or die(mysqli_error());
    $enviados_febrero = mysqli_num_rows($conteo_envi_feb);

    $query_conteo_envi_marzo = sprintf('SELECT estado, fechaenvio FROM `licencia` WHERE estado="enviado" and fechaenvio between "2025-03-01" and "2025-03-31"');
    $conteo_envi_marzo = mysqli_query($conexion, $query_conteo_envi_marzo) or die(mysqli_error());
    $enviados_marzo = mysqli_num_rows($conteo_envi_marzo);

    $query_conteo_envi_abril = sprintf('SELECT estado, fechaenvio FROM `licencia` WHERE estado="enviado" and fechaenvio between "2025-04-01" and "2025-04-30"');
    $conteo_envi_abril = mysqli_query($conexion, $query_conteo_envi_abril) or die(mysqli_error());
    $enviados_abril = mysqli_num_rows($conteo_envi_abril);

    $query_conteo_envi_mayo = sprintf('SELECT estado, fechaenvio FROM `licencia` WHERE estado="enviado" and fechaenvio between "2025-05-01" and "2025-05-31"');
    $conteo_envi_mayo = mysqli_query($conexion, $query_conteo_envi_mayo) or die(mysqli_error());
    $enviados_mayo = mysqli_num_rows($conteo_envi_mayo);

    $query_conteo_envi_junio = sprintf('SELECT estado, fechaenvio FROM `licencia` WHERE estado="enviado" and fechaenvio between "2025-06-01" and "2025-06-30"');
    $conteo_envi_junio = mysqli_query($conexion, $query_conteo_envi_junio) or die(mysqli_error());
    $enviados_junio = mysqli_num_rows($conteo_envi_junio);

    $query_conteo_envi_julio = sprintf('SELECT estado, fechaenvio FROM `licencia` WHERE estado="enviado" and fechaenvio between "2025-07-01" and "2025-07-31"');
    $conteo_envi_julio = mysqli_query($conexion, $query_conteo_envi_julio) or die(mysqli_error());
    $enviados_julio = mysqli_num_rows($conteo_envi_julio);

    $query_conteo_envi_agosto = sprintf('SELECT estado, fechaenvio FROM `licencia` WHERE estado="enviado" and fechaenvio between "2025-08-01" and "2025-08-31"');
    $conteo_envi_agosto = mysqli_query($conexion, $query_conteo_envi_agosto) or die(mysqli_error());
    $enviados_agosto = mysqli_num_rows($conteo_envi_agosto);

    $query_conteo_envi_sep = sprintf('SELECT estado, fechaenvio FROM `licencia` WHERE estado="enviado" and fechaenvio between "2025-09-01" and "2025-09-30"');
    $conteo_envi_sep = mysqli_query($conexion, $query_conteo_envi_sep) or die(mysqli_error());
    $enviados_sep = mysqli_num_rows($conteo_envi_sep);

    $query_conteo_envi_oct = sprintf('SELECT estado, fechaenvio FROM `licencia` WHERE estado="enviado" and fechaenvio between "2025-10-01" and "2025-10-31"');
    $conteo_envi_oct = mysqli_query($conexion, $query_conteo_envi_oct) or die(mysqli_error());
    $enviados_oct = mysqli_num_rows($conteo_envi_oct);

    $query_conteo_envi_nov = sprintf('SELECT estado, fechaenvio FROM `licencia` WHERE estado="enviado" and fechaenvio between "2025-11-01" and "2025-11-30"');
    $conteo_envi_nov = mysqli_query($conexion, $query_conteo_envi_nov) or die(mysqli_error());
    $enviados_nov = mysqli_num_rows($conteo_envi_nov);

    $query_conteo_envi_dic = sprintf('SELECT estado, fechaenvio FROM `licencia` WHERE estado="enviado" and fechaenvio between "2025-12-01" and "2025-12-31"');
    $conteo_envi_dic = mysqli_query($conexion, $query_conteo_envi_dic) or die(mysqli_error());
    $enviados_dic = mysqli_num_rows($conteo_envi_dic);



    $query_conteo_ingresos = sprintf('SELECT * FROM bien WHERE idfamiliabien != 87 and codbiencat != 5170');
    $conteo_ingresos = mysqli_query($conexion, $query_conteo_ingresos) or die(mysqli_error());
    $asigrec_ingresos = mysqli_num_rows($conteo_ingresos);
    $totalingresos = $asigrec_ingresos;


    function obtener_porcentaje($cantidad, $totalingresos)
    {
        try {
            $cantidad = floatval($cantidad);
            $totalingresos = floatval($totalingresos);
            $porcentaje = ($cantidad * 100) / $totalingresos;
            $porcentaje = round($porcentaje, 0);
            return $porcentaje;
        } catch (Exception $e) {
            return "Error: Por favor, proporciona valores numéricos válidos para 'cantidad' y 'total'.";
        }
    }
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
        <link rel="stylesheet" href="estilos/jquery.dataTables.min.css">
        <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
        <link rel="stylesheet" href="plugins/morris/morris.css">
        <link rel="stylesheet" href="./xreczfsf/vistas/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    </head>

    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <?php include('header.php'); ?>
            </header>
            <?php
            $activarmenu = 'lista-bien-debaja';
            include('aside.php');
            ?>
            <div class="content-wrapper">
                <section class="content-header hidden-print">

                    <!-- <ol class="breadcrumb">
                        <form action="descexcel.php" method="post" id="FormularioExportacion">
                            <a href='javascript:window.print(); void 0;' id="btnImprimirPantalla" class="btn btn-primary btn-sm " role="button" title="Imprimir reporte" stsyle="font-size:10px"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
                            <button type="submit" id="botonExcel" class="btn btn-warning btn-sm "><span class="glyphicon glyphicon-download-alt"></span> Descargar</button>
                            <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
                            <input type="hidden" id="nomarchivo" name="nomarchivo" value="Auditoria de usuario.xls" />
                        </form>
                    </ol> -->
                    <button type="button" class="btn btn-default" id="daterange-btn">
                        <span>
                            <i class="fa fa-calendar"></i> Rango de fecha
                        </span>
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <?php
                    if (isset($_GET["fechaInicial"])) {
                        echo '<h3> El rango de fecha seleccionanda: ' . $fechaInicial = $_GET["fechaInicial"] . ' al ' . $fechaFinal = $_GET["fechaFinal"] . ' </h3>';
                    } else {
                        $fechaInicial = null;
                        $fechaFinal = null;
                    }
                    ?>
                </section>
                <?php include('header_impr.php'); ?>
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="box box-danger">

                                <div class="box-header with-border">
                                    <h3 class="box-title">Estatus Informes</h3>

                                    <div class="box-tools pull-right">

                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>

                                    </div>

                                </div>
                                <div class="box-body chart-responsive">
                                    <div class="total ">
                                        <h2>Total de informes activos: <?php echo $grantotal; ?></h2>
                                    </div>
                                    <h2>
                                        Informes dados de baja: <?php echo $grantotal_baja; ?>
                                    </h2>
                                    <div class="chart" id="sales-chart" style="height: 300px;"></div>
                                </div>
                            </div>

                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Ingresos / Enviados</h3>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>

                                </div>
                                <div class="box-body">
                                    <div class="chart">
                                        <canvas id="lineChart" style="height:250px"></canvas>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">

                                <div class="box-header">
                                    <h3 class="box-title">Produccion del personal</h3>

                                </div>
                                <div class="box-body no-padding">
                                    <table id="carga" width="100%" class="table table-hover display">
                                        <thead>
                                            <tr class="titulotabla">
                                                <td>No</td>
                                                <td>NOMBRES</td>
                                                <td>ASIG REC</td>
                                                <td>PRUEBAS</td>
                                                <td>SUPERVISION</td>
                                                <td>POR ENVIAR</td>
                                                <td>ENVIADO</td>
                                                <td>TOTAL DE INFORMES</td>
                                                <td>PROGRESO</td>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            if (isset($_GET["fechaInicial"])) {
                                                $fechaInicial = $_GET["fechaInicial"];
                                                $fechaFinal = $_GET["fechaFinal"];
                                            } else {
                                                $fechaInicial = null;
                                                $fechaFinal = null;
                                            }

                                            if ($fechaInicial == null) {
                                                $consulta_mysql = 'SELECT usuario.nombres, usuario.apellidos, licencia.estado, licencia.idusuario, count(*) AS total FROM licencia 
                                                LEFT JOIN usuario on usuario.idusuario = licencia.idusuario GROUP BY licencia.idusuario HAVING COUNT(*)>1 ORDER BY total desc ';
                                                $resultado = mysqli_query($conexion, $consulta_mysql);

                                                $consulta_mysql_regis = 'SELECT quienregis, count(*) AS totalregis FROM bien WHERE quienregis != "ATNC" GROUP BY quienregis HAVING COUNT(*)>1  ORDER BY totalregis desc';
                                                $resultado_regis = $conexion->query($consulta_mysql_regis);

                                                $consulta_mysql_nom = 'SELECT familiabien.codfamiliabien, familiabien.familiabien, bien.idfamiliabien, count(*) AS totalregisnom FROM bien 
                                                LEFT JOIN familiabien on familiabien.idfamiliabien = bien.idfamiliabien WHERE bien.idfamiliabien != 87 GROUP BY bien.idfamiliabien HAVING COUNT(*)>1  ORDER BY totalregisnom desc';
                                                $resultado_nom = $conexion->query($consulta_mysql_nom);
                                            } else if ($fechaInicial == $fechaFinal) {

                                                $consulta_mysql = "SELECT usuario.nombres, usuario.apellidos, licencia.fech_asignacion, licencia.estado, licencia.idusuario, count(*) AS total FROM licencia 
                                                 LEFT JOIN usuario on usuario.idusuario = licencia.idusuario 
                                                 WHERE licencia.fech_asignacion like '%$fechaFinal%' GROUP BY licencia.idusuario HAVING COUNT(*)>1 ORDER BY total desc ";
                                                $resultado = mysqli_query($conexion, $consulta_mysql);

                                                $consulta_mysql_regis = "SELECT quienregis, fregistro, count(*) AS totalregis FROM bien 
                                                WHERE fregistro like '%$fechaFinal%' GROUP BY quienregis HAVING COUNT(*)>1  ORDER BY totalregis desc";
                                                $resultado_regis = $conexion->query($consulta_mysql_regis);

                                                $consulta_mysql_nom = "SELECT familiabien.codfamiliabien, familiabien.familiabien, bien.idfamiliabien, bien.fregistro count(*) AS totalregisnom FROM bien 
                                                LEFT JOIN familiabien on familiabien.idfamiliabien = bien.idfamiliabien 
                                                WHERE bien.fregistro like '%$fechaFinal%' AND bien.idfamiliabien != 87 GROUP BY bien.idfamiliabien HAVING COUNT(*)>1  ORDER BY totalregisnom desc";
                                                $resultado_nom = $conexion->query($consulta_mysql_nom);
                                            } else {

                                                $fechaActual = new DateTime();
                                                $fechaActual->add(new DateInterval("P1D"));
                                                $fechaActualMasUno = $fechaActual->format("Y-m-d");
                                                $fechaFinal2 = new DateTime($fechaFinal);
                                                $fechaFinal2->add(new DateInterval("P1D"));
                                                $fechaFinalMasUno = $fechaFinal2->format("Y-m-d");

                                                if ($fechaFinalMasUno == $fechaActualMasUno) {

                                                    $consulta_mysql = "SELECT usuario.nombres, usuario.apellidos, licencia.fech_asignacion, licencia.estado, licencia.idusuario, count(*) AS total FROM licencia 
                                                    LEFT JOIN usuario on usuario.idusuario = licencia.idusuario 
                                                    WHERE licencia.fech_asignacion BETWEEN '$fechaInicial' AND '$fechaFinalMasUno' GROUP BY licencia.idusuario HAVING COUNT(*)>1 ORDER BY total desc ";
                                                    $resultado = mysqli_query($conexion, $consulta_mysql);

                                                    $consulta_mysql_regis = "SELECT quienregis, fregistro, count(*) AS totalregis FROM bien 
                                                    WHERE quienregis != 'ATNC' AND fregistro BETWEEN '$fechaInicial' AND '$fechaFinalMasUno' GROUP BY quienregis HAVING COUNT(*)>1  ORDER BY totalregis desc";
                                                    $resultado_regis = $conexion->query($consulta_mysql_regis);

                                                    $consulta_mysql_nom = "SELECT familiabien.codfamiliabien, familiabien.familiabien, bien.idfamiliabien, bien.fregistro, count(*) AS totalregisnom FROM bien 
                                                    LEFT JOIN familiabien on familiabien.idfamiliabien = bien.idfamiliabien 
                                                    WHERE bien.idfamiliabien != 87 AND bien.fregistro BETWEEN '$fechaInicial' AND '$fechaFinalMasUno' GROUP BY bien.idfamiliabien HAVING COUNT(*)>1  ORDER BY totalregisnom desc";
                                                    $resultado_nom = $conexion->query($consulta_mysql_nom);
                                                } else {

                                                    $consulta_mysql = "SELECT usuario.nombres, usuario.apellidos, licencia.fech_asignacion, licencia.estado, licencia.idusuario, count(*) AS total FROM licencia 
                                                    LEFT JOIN usuario on usuario.idusuario = licencia.idusuario 
                                                    WHERE licencia.fech_asignacion BETWEEN '$fechaInicial' AND '$fechaFinal' GROUP BY licencia.idusuario HAVING COUNT(*)>1 ORDER BY total desc ";
                                                    $resultado = mysqli_query($conexion, $consulta_mysql);

                                                    $consulta_mysql_regis = "SELECT quienregis, fregistro, count(*) AS totalregis FROM bien 
                                                    WHERE quienregis != 'ATNC' AND fregistro BETWEEN '$fechaInicial' AND '$fechaFinal' GROUP BY quienregis HAVING COUNT(*)>1  ORDER BY totalregis desc";
                                                    $resultado_regis = $conexion->query($consulta_mysql_regis);

                                                    $consulta_mysql_nom = "SELECT familiabien.codfamiliabien, familiabien.familiabien, bien.idfamiliabien, bien.fregistro, count(*) AS totalregisnom FROM bien 
                                                    LEFT JOIN familiabien on familiabien.idfamiliabien = bien.idfamiliabien 
                                                    WHERE bien.idfamiliabien != 87 AND (bien.fregistro BETWEEN '$fechaInicial' AND '$fechaFinal') GROUP BY bien.idfamiliabien HAVING COUNT(*)>1  ORDER BY totalregisnom desc";
                                                    $resultado_nom = $conexion->query($consulta_mysql_nom);
                                                }
                                            }

                                            $result = -1;
                                            do { ?>
                                                <tr>
                                                    <td>
                                                        <strong>
                                                            <?php
                                                            $result += 1;
                                                            echo  $result;
                                                            ?>
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        <div class="user-block">
                                                            <img class="img-circle img-bordered-sm" src="dist/img/avatar5.png" alt="user image">
                                                            <span class="username">
                                                                <?php echo $fila['nombres'] . ' ' . $fila['apellidos']; ?>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td><?php
                                                        $query_conteo_pruebas = sprintf('SELECT bien.debaja, count(*) FROM licencia
                                                        LEFT JOIN bien on bien.idbien = licencia.id_bien 
                                                         where licencia.estado IS NULL and licencia.idusuario="' . $fila["idusuario"] . '" and bien.debaja IS NULL');
                                                        $pruebas_total = mysqli_query($conexion, $query_conteo_pruebas) or die(mysqli_error());
                                                        $conteo_pruebas = mysqli_fetch_row($pruebas_total);

                                                        foreach ($conteo_pruebas as $total_pruebas) {
                                                            echo $total_pruebas;
                                                        }
                                                        ?></td>
                                                    <td><?php
                                                        $query_conteo_pruebas = sprintf('SELECT bien.debaja, count(*) FROM licencia
                                                        LEFT JOIN bien on bien.idbien = licencia.id_bien
                                                        where licencia.estado="Pruebas" and licencia.idusuario="' . $fila["idusuario"] . '" and bien.debaja IS NULL');
                                                        $pruebas_total = mysqli_query($conexion, $query_conteo_pruebas) or die(mysqli_error());
                                                        $conteo_pruebas = mysqli_fetch_row($pruebas_total);

                                                        foreach ($conteo_pruebas as $total_pruebas) {

                                                            echo "<b>" . $total_pruebas . "</b>";
                                                        }

                                                        ?></td>
                                                    <td><?php
                                                        $query_conteo_pruebas = sprintf('SELECT bien.debaja, count(*) FROM licencia
                                                        LEFT JOIN bien on bien.idbien = licencia.id_bien
                                                        where licencia.estado="Supervision" and licencia.idusuario="' . $fila["idusuario"] . '" and bien.debaja IS NULL');
                                                        $pruebas_total = mysqli_query($conexion, $query_conteo_pruebas) or die(mysqli_error());
                                                        $conteo_pruebas = mysqli_fetch_row($pruebas_total);

                                                        foreach ($conteo_pruebas as $total_pruebas) {
                                                            echo $total_pruebas;
                                                        }

                                                        ?></td>
                                                    <td><?php
                                                        $query_conteo_pruebas = sprintf('SELECT bien.debaja, count(*) FROM licencia
                                                        LEFT JOIN bien on bien.idbien = licencia.id_bien
                                                        where licencia.estado="Supervisado" and licencia.idusuario="' . $fila["idusuario"] . '" and bien.debaja IS NULL');
                                                        $pruebas_total = mysqli_query($conexion, $query_conteo_pruebas) or die(mysqli_error());
                                                        $conteo_pruebas = mysqli_fetch_row($pruebas_total);

                                                        foreach ($conteo_pruebas as $total_pruebas) {
                                                            echo "<b>" . $total_pruebas . "</b>";
                                                        }

                                                        ?></td>
                                                    <td><?php
                                                        $query_conteo_pruebas = sprintf('SELECT bien.debaja, count(*) FROM licencia
                                                        LEFT JOIN bien on bien.idbien = licencia.id_bien
                                                        where licencia.estado="Enviado" and licencia.idusuario="' . $fila["idusuario"] . '" and bien.debaja IS NULL');
                                                        $pruebas_total = mysqli_query($conexion, $query_conteo_pruebas) or die(mysqli_error());
                                                        $conteo_pruebas = mysqli_fetch_row($pruebas_total);

                                                        foreach ($conteo_pruebas as $total_pruebas) {
                                                            echo $total_pruebas;
                                                        }

                                                        ?></td>
                                                    <td> <b><?php echo $fila['total']; ?></b></td>
                                                    <td>
                                                        <?php
                                                        $cantidad = $fila['total'];
                                                        $resultadoporcentaje = obtener_porcentaje($cantidad, $totalingresos);
                                                        echo "$resultadoporcentaje%.";
                                                        ?>
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $resultadoporcentaje ?>%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                            } while ($fila = mysqli_fetch_array($resultado)) ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Ingresos por usuario</h3>

                                </div>
                                <div class="box-body no-padding">
                                    <table id="carga2" width="100%" class="table table-hover display">
                                        <thead>
                                            <tr class="titulotabla">
                                                <td>No</td>
                                                <td>NOMBRES</td>
                                                <td>TOTAL DE REGISTROS</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($resultado_regis) {
                                                while ($fila_regis = $resultado_regis->fetch_array(MYSQLI_ASSOC)) {
                                                    echo '                                                                         
                                                    <tr>                                                    
                                                    <td>
                                                        <div class="user-block">
                                                            <img class="img-circle img-bordered-sm" src="dist/img/avatar15.png" alt="user image">
                                                            <span class="username"></span>                                                        
                                                        </div>
                                                    </td>                                                                                                                                                                                  
                                                    ';
                                                    echo ' <td>' . $fila_regis["quienregis"] . '</td>';
                                                    echo ' <td><strong>' . $fila_regis["totalregis"] . '</strong></td>';
                                                    ' </tr> ';
                                                }
                                            } else {
                                                echo "error qqq";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Ingresos por Norma</h3>
                                </div>
                                <div class="box-body no-padding">
                                    <table id="carga3" width="100%" class="table table-hover display">
                                        <thead>
                                            <tr class="titulotabla">
                                                <td>No</td>
                                                <td>Norma</td>
                                                <td>Total de informes</td>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php

                                            $resulta = 0;
                                            $fila_nom = '';
                                            if ($resultado_nom) {

                                                while ($fila_nom = $resultado_nom->fetch_array(MYSQLI_ASSOC)) {
                                                    echo '                                                                         
                                                    <tr>
                                                    ';
                                                    echo ' <td> <strong>' .
                                                        $resulta += 1;
                                                    $resulta . '</strong></td>';
                                                    echo ' <td>' . '<strong>' . $fila_nom["codfamiliabien"] . '</strong>' . " /// " . $fila_nom['familiabien'] . '</td>';
                                                    echo ' <td><strong>' . $fila_nom["totalregisnom"] . '</strong></td>';
                                                    ' </tr> ';
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php include('footer.html'); ?>
        </div>
        <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="plugins/morris/morris.min.js"></script>
        <script src="plugins/chartjs/Chart.min.js"></script>
        <script src="plugins/fastclick/fastclick.js"></script>
        <script src="dist/js/app.min.js"></script>
        <script src="dist/js/demo.js"></script>
        <script src="plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="./xreczfsf/vistas/bower_components/moment/min/moment.min.js"></script>
        <script src="./xreczfsf/vistas/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

        <script type="text/javascript" language="javascript" class="init">
            $(document).ready(function() {
                $('#carga').DataTable();
            });
        </script>
        <script type="text/javascript" language="javascript" class="init">
            $(document).ready(function() {
                $('#carga2').DataTable();
            });
        </script>
        <script type="text/javascript" language="javascript" class="init">
            $(document).ready(function() {
                $('#carga3').DataTable();
            });
        </script>

        <script>
            $(function() {
                "use strict";

                //DONUT CHART
                var donut = new Morris.Donut({
                    element: 'sales-chart',
                    resize: true,
                    colors: ["#f56954", "#f569ff", "#00ffff", "#ff00ff", "#00a65a", ],
                    data: [{
                            label: "Asignados recientemente",
                            value: <?php echo $asigrec ?>
                        },
                        {
                            label: "Pruebas",
                            value: <?php echo $Pruebas ?>
                        },
                        {
                            label: "Supervision",
                            value: <?php echo $supervision ?>
                        },
                        {
                            label: "Pendientes por enviar",
                            value: <?php echo $supervisado ?>
                        },
                        {
                            label: "Enviados",
                            value: <?php echo $enviados ?>
                        }
                    ],
                    hideHover: 'auto'
                });
            });
        </script>
        <script>
            $(function() {

                var areaChartCanvas = $("#lineChart").get(0).getContext("2d");

                var areaChart = new Chart(areaChartCanvas);

                var areaChartData = {
                    labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                    datasets: [{
                            label: "Ingresos",
                            fillColor: "rgba(210, 214, 222, 1)",
                            strokeColor: "rgba(210, 214, 222, 1)",
                            pointColor: "rgba(210, 214, 222, 1)",
                            pointStrokeColor: "#c1c7d1",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: [
                                <?php echo $asigrec_enero; ?>,
                                <?php echo $asigrec_febrero; ?>,
                                <?php echo $asigrec_marzo; ?>,
                                <?php echo $asigrec_abril; ?>,
                                <?php echo $asigrec_mayo; ?>,
                                <?php echo $asigrec_junio; ?>,
                                <?php echo $asigrec_julio; ?>,
                                <?php echo $asigrec_agosto; ?>,
                                <?php echo $asigrec_sep; ?>,
                                <?php echo $asigrec_oct; ?>,
                                <?php echo $asigrec_nov; ?>,
                                <?php echo $asigrec_dic; ?>
                            ]
                        },
                        {
                            label: "Enviados",
                            fillColor: "rgba(60,141,188,0.9)",
                            strokeColor: "rgba(60,141,188,0.8)",
                            pointColor: "#3b8bba",
                            pointStrokeColor: "rgba(60,141,188,1)",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(60,141,188,1)",
                            data: [

                                <?php echo $enviados_enero; ?>,
                                <?php echo $enviados_febrero; ?>,
                                <?php echo $enviados_marzo; ?>,
                                <?php echo $enviados_abril; ?>,
                                <?php echo $enviados_mayo; ?>,
                                <?php echo $enviados_junio; ?>,
                                <?php echo $enviados_julio; ?>,
                                <?php echo $enviados_agosto; ?>,
                                <?php echo $enviados_sep; ?>,
                                <?php echo $enviados_oct; ?>,
                                <?php echo $enviados_nov; ?>,
                                <?php echo $enviados_dic; ?>
                            ]
                        }
                    ]
                };

                var areaChartOptions = {
                    //Boolean - If we should show the scale at all
                    showScale: true,
                    //Boolean - Whether grid lines are shown across the chart
                    scaleShowGridLines: false,
                    //String - Colour of the grid lines
                    scaleGridLineColor: "rgba(0,0,0,.05)",
                    //Number - Width of the grid lines
                    scaleGridLineWidth: 1,
                    //Boolean - Whether to show horizontal lines (except X axis)
                    scaleShowHorizontalLines: true,
                    //Boolean - Whether to show vertical lines (except Y axis)
                    scaleShowVerticalLines: true,
                    //Boolean - Whether the line is curved between points
                    bezierCurve: true,
                    //Number - Tension of the bezier curve between points
                    bezierCurveTension: 0.3,
                    //Boolean - Whether to show a dot for each point
                    pointDot: false,
                    //Number - Radius of each point dot in pixels
                    pointDotRadius: 4,
                    //Number - Pixel width of point dot stroke
                    pointDotStrokeWidth: 1,
                    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                    pointHitDetectionRadius: 20,
                    //Boolean - Whether to show a stroke for datasets
                    datasetStroke: true,
                    //Number - Pixel width of dataset stroke
                    datasetStrokeWidth: 2,
                    //Boolean - Whether to fill the dataset with a color
                    datasetFill: true,
                    //String - A legend template
                    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                    //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                    maintainAspectRatio: true,
                    //Boolean - whether to make the chart responsive to window resizing
                    responsive: true
                };

                //Create the line chart
                areaChart.Line(areaChartData, areaChartOptions);

                var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
                var lineChart = new Chart(lineChartCanvas);
                var lineChartOptions = areaChartOptions;
                lineChartOptions.datasetFill = false;
                lineChart.Line(areaChartData, lineChartOptions);


            });


            /*=============================================
            RANGO DE FECHAS
            =============================================*/

            $('#daterange-btn').daterangepicker({
                    ranges: {
                        'Hoy': [moment(), moment()],
                        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                        'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                        'Este mes': [moment().startOf('month'), moment().endOf('month')],
                        'Último mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment(),
                    endDate: moment()
                },

                function(start, end) {
                    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

                    var fechaInicial = start.format('YYYY-MM-DD');

                    var fechaFinal = end.format('YYYY-MM-DD');

                    var capturarRango = $("#daterange-btn span").html();

                    localStorage.setItem("capturarRango", capturarRango);

                    window.location = "chart-teso.php?ruta=report&fechaInicial=" + fechaInicial + "&fechaFinal=" + fechaFinal;


                }

            )

            /*=============================================
            CANCELAR RANGO DE FECHAS
            =============================================*/

            $(".daterangepicker.opensleft .range_inputs .cancelBtn").on("click", function() {

                localStorage.removeItem("capturarRango");
                localStorage.clear();
                window.location = "chart-teso.php?ruta=report&fechaInicial=" + fechaInicial + "&fechaFinal=" + fechaFinal;

            })

            /*=============================================
            CAPTURAR HOY
            =============================================*/

            $(".daterangepicker.opensleft .ranges li").on("click", function() {

                var textoHoy = $(this).attr("data-range-key");

                if (textoHoy == "Hoy") {

                    var d = new Date();

                    var dia = d.getDate();
                    var mes = d.getMonth() + 1;
                    var año = d.getFullYear();

                    if (mes < 10) {

                        var fechaInicial = año + "-0" + mes + "-" + dia;
                        var fechaFinal = año + "-0" + mes + "-" + dia;

                    } else if (dia < 10) {

                        var fechaInicial = año + "-" + mes + "-0" + dia;
                        var fechaFinal = año + "-" + mes + "-0" + dia;

                    } else if (mes < 10 && dia < 10) {

                        var fechaInicial = año + "-0" + mes + "-0" + dia;
                        var fechaFinal = año + "-0" + mes + "-0" + dia;

                    } else {

                        var fechaInicial = año + "-" + mes + "-" + dia;
                        var fechaFinal = año + "-" + mes + "-" + dia;

                    }

                    localStorage.setItem("capturarRango", "Hoy");

                    window.location = "chart-teso.php?ruta=report&fechaInicial=" + fechaInicial + "&fechaFinal=" + fechaFinal;

                }

            });
        </script>
    </body>

    </html>

<?php } else {
    header("Location: pagina-restringida.php");
} ?>