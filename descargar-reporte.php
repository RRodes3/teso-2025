<?php
require_once('Connections/conexion.php');
require_once('Connections/funciones.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// metodo
$metodo = '';
$menuactivo = 'reportes';

if (isset($_GET['metodo'])) {
    $metodo = decrypt(substr(limpiar($_GET['metodo']), 10), 'URLCODIGO');
    $menuactivo = 'supervision';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistema TESO</title>
    <link rel="shortcut icon" href="img/icono.png">
    <meta name="author" content="Eduardo Sandoval">

    <!-- Bootstrap / AdminLTE -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

    <style>
        .content-header h1 {
            font-size: 22px;
            font-weight: 600;
        }

        .btn-excel {
            background: #1D6F42;
            color: #fff;
            border: none;
            margin-right: 8px;
        }

        .btn-excel:hover {
            background: #155c35;
            color: #fff;
        }

        .center-viewport {
            width: 90%;
            height: 540px;
            outline: solid 1px black;
            display: flex;
            justify-content: center;
            align-items: center;

        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <?php include('header.php'); ?>
        </header>

        <?php
        $activarmenu = 'lista-historial-bien';
        include('aside.php');
        ?>

        <div class="content-wrapper">

            <!-- ================= HEADER ================= -->
            <section class="content-header">
                <h1>
                    <i class="fa fa-list-ol"></i>
                    REPORTES
                    
                </h1>

                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Reportes</li>
                </ol>
            </section>

            <!-- ================= CONTENT ================= -->
            <section class="content">
                <h3>Generar reporte en excel, en un rango de fecha o reporte general</h3>
                <div class="center-viewport">
                
                      
                        <div class="col-md-6">
                            <div class="card-body">
                                <form method="GET" action="reporte-excel-pdf.php">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Fecha inicio</label>
                                            <input type="date" name="fecha_inicio" class="form-control" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Fecha fin</label>
                                            <input type="date" name="fecha_fin" class="form-control" required>
                                        </div>

                                        <div class="col-md-4 d-flex align-items-end gap-2">
                                            <button name="tipo" value="excel" class="btn btn-success w-100">
                                                📊 Excel
                                            </button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <h3>Ó</h3>
                        </div>
                          <div class="col-md-4">
                            <a href="descargar-reporte-excel.php"
                                target="_blank"
                                class="btn btn-excel btn-lg"
                                title="Descargar reporte en Excel">
                                <i class="fa fa-file-excel-o"></i> Exportar Excel General
                            </a>

                        
                    </div>




                </div>
            </section>
        </div>
        <?php include('footer.html'); ?>
    </div>

    <!-- JS -->
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>

    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

    <script>
        $(function() {
            $('#servicios').DataTable({
                paging: true,
                pageLength: 50,
                ordering: true,
                searching: true,
                responsive: true,
                language: {
                    url: 'plugins/datatables/Spanish.json'
                }
            });
        });
    </script>

</body>

</html>