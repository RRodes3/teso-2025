<?php
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_SESSION['U_spprivilegio']) and in_array("s_superv", $_SESSION['U_spprivilegio'])) {
    require_once('Connections/conexion.php');
    $menuactivo = 'movimiento';
?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Sistema Teso</title>
        <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
        <meta name="author" content="Eduardo Sandoval">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
        <link rel="stylesheet" href="plugins/select2/select2.min.css">


    </head>

    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            
            <header class="main-header">
                <?php include('header.php'); ?>
            </header>
            <?php
            $activarmenu = 'registro-bien';
            include('aside.php');
            ?>

            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        <span class="glyphicon glyphicon-tag"></span>Registrar muestra (Todos sus componentes)
                    </h1>
                </section>

                <section class="content">
                    <div class="row ">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Toma de Fotografías</h3>
                                </div>
                                <div class="box-body">

                                       <div class="col-md-12">
                                        <?php
                                        // Modificamos la consulta para agrupar por referencia y contar fotos
                                        $sqlQuery = "SELECT b.nombien, c.razonsocial, g.nombre,
                                            f.referencia,
                                            COUNT(*) as total_fotos,
                                            MIN(f.namefoto) as primera_foto,
                                            MIN(b.nombien) as muestra,
                                            MIN(c.razonsocial) as cliente,
                                            MIN(g.nombre) as gestor
                                        FROM fotos_muestra f 
                                        INNER JOIN biencatalogo b ON f.iditem = b.codbiencat
                                        INNER JOIN proveedor c ON f.id_cliente = c.idproveedor
                                        INNER JOIN gestor g ON f.idgestor = g.idgestor
                                        GROUP BY f.referencia 
                                        ORDER BY f.referencia DESC";

                                        $resultadoSQL = mysqli_query($conexion, $sqlQuery);
                                        if (mysqli_num_rows($resultadoSQL) > 0) {
                                        ?>
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Referencia</th>
                                                            <th scope="col">Total Fotos</th>
                                                            <th scope="col">Muestra</th>
                                                            <th scope="col">Cliente</th>
                                                            <th scope="col">Gestor</th>
                                                            <th scope="col">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $count = 1;
                                                        while ($Data = mysqli_fetch_array($resultadoSQL)) { ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $count++; ?></th>
                                                                <td><?php echo $Data['referencia']; ?></td>
                                                                <td><span class="badge bg-blue"><?php echo $Data['total_fotos']; ?></span></td>
                                                                <td><?php echo $Data['muestra']; ?></td>
                                                                <td><?php echo $Data['cliente']; ?></td>
                                                                <td><?php echo $Data['gestor']; ?></td>
                                                                <td>
                                                                    <a href="ver_fotos.php?idFoto=<?php echo $Data['referencia']; ?>" 
                                                                       class="btn btn-info btn-xs">
                                                                        <i class="fa fa-picture-o"></i> Ver Fotos
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php
                                        } else { ?>
                                            <p class="sinResultados">No hay Resultados</p>
                                        <?php } ?>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                </section>
            </div>
            <?php include('footer.html'); ?>
        </div>

        <!-- <script src="./upload.js"></script> -->

        <!-- jQuery 2.2.3 -->
        <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <!-- ChartJS 1.0.1 -->
        <!-- <script src="plugins/chartjs/Chart.min.js"></script> -->
        <!-- FastClick -->
        <!-- <script src="plugins/fastclick/fastclick.js"></script> -->
        <!-- AdminLTE App -->
        <script src="dist/js/app.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <!-- <script src="dist/js/demo.js"></script> -->
        <!-- page script -->
        <script src="plugins/select2/select2.full.min.js"></script>
        <script>
            $(function() {
                $(".select2").select2();
            });
        </script>

    </body>

    </html>
<?php } else {
    header("Location: pagina-restringida.php");
} ?>