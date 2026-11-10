<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['U_spprivilegio']) and in_array("s_superv", $_SESSION['U_spprivilegio']) or in_array("a_alma", $_SESSION['U_spprivilegio'])) {
    require_once('Connections/conexion.php');
    $menuactivo = 'movimiento';

    // Validar y obtener referencia
    $referencia = isset($_GET['idFoto']) ? mysqli_real_escape_string($conexion, $_GET['idFoto']) : '';
    if (empty($referencia)) {
        header("Location: consulta-fotos.php");
        exit;
    }

    // Obtener información de la referencia
    $sqlInfo = "SELECT b.nombien, c.razonsocial,
                                            f.referencia, noInforme,
                                            COUNT(*) as total_fotos,
                                            MIN(f.namefoto) as primera_foto,
                                            MIN(b.nombien) as muestra,
                                            MIN(c.razonsocial) as cliente                                            
                                        FROM fotos_muestra f 
                                        INNER JOIN biencatalogo b ON f.iditem = b.codbiencat
                                        INNER JOIN proveedor c ON f.id_cliente = c.idproveedor
                                        
    WHERE f.referencia = '$referencia' OR f.noInforme = '$referencia'";

    $resultInfo = mysqli_query($conexion, $sqlInfo);
    $info = mysqli_fetch_assoc($resultInfo);

    // Obtener todas las fotos de la referencia
    $sqlFotos = "SELECT * FROM fotos_muestra WHERE referencia = '$referencia' OR noInforme = '$referencia' ORDER BY namefoto";
    $resultFotos = mysqli_query($conexion, $sqlFotos);
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Ver Fotos - Sistema Teso</title>
        <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
        <style>
            .foto-container {
                margin-bottom: 20px;
            }

            .foto-container img {
                max-width: 100%;
                height: auto;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 5px;
            }

            .foto-info {
                margin-top: 10px;
            }
        </style>
    </head>

    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <?php include('header.php'); ?>
            </header>
            <?php include('aside.php'); ?>

            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        <i class="fa fa-picture-o"></i> Fotos de la muestra
                        <small>Referencia: <?php echo htmlspecialchars($referencia); ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="consulta-fotos.php"><i class="fa fa-dashboard"></i> Listado</a></li>
                        <li class="active">Ver Fotos</li>
                    </ol>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Información de la muestra</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Muestra:</strong> <?php echo htmlspecialchars($info['muestra']); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Cliente:</strong> <?php echo htmlspecialchars($info['cliente']); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Total fotos:</strong>
                                            <span class="badge bg-blue"><?php echo $info['total_fotos']; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php while ($foto = mysqli_fetch_array($resultFotos)) { ?>
                            <div class="col-md-4">
                                <div class="box box-widget">

                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?php echo htmlspecialchars($foto['namefoto']); ?></h3>
                                    </div>
                                    <div class="box-body">
                                        <img src="./ver_img.php?foto=<?php echo urlencode($foto['namefoto']); ?>" class="img-responsive">
                                    </div>

                                    <div class="box-footer">
                                        <a href="./ver_img.php?foto=<?php echo htmlspecialchars($foto['namefoto']); ?>"
                                            class="btn btn-default btn-xs"
                                            target="_blank">
                                            <i class="fa fa-eye"></i> Ver original
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </section>
            </div>
            <?php include('footer.html'); ?>
        </div>

        <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="dist/js/app.min.js"></script>
    </body>

    </html>
<?php
} else {
    header("Location: pagina-restringida.php");
}
?>