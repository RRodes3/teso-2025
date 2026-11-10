<?php
include_once 'main.php';

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['U_spprivilegio']) and in_array("s_superv", $_SESSION['U_spprivilegio']) or in_array("a_alma", $_SESSION['U_spprivilegio'])) {
    require_once('Connections/conexion.php');
    $menuactivo = 'movimiento';

$search = "";
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $search = trim($_POST['search']);
}

    // 1. PAGINACIÓN
    $pagina = 1;
if (!isset($_GET['pagina'])) {                                         
    $pagina = 1;                                                  
} else {                                                         
    $pagina = (int) $_GET['pagina'];                               
    if ($pagina <= 1){ $pagina = 1; }
}

$registros = 10; 
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

// 2. CONTAR REGISTROS
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $total = contarBusqueda($conexion, $search);
} else {
    $total = contarTodos($conexion);
}

$Npaginas = ceil($total / $registros);


// 3. CONSULTAR DATOS
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $items = buscaDatos($conexion, $search, $inicio, $registros);
} else {
    $items = mostrarTodos($conexion, $inicio, $registros);
}


    $url = "lista-consulta.php?pagina=";
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
                        <span class="glyphicon glyphicon-tag"></span>Consulta fotos de muestras
                    </h1>
                </section>
                <!-- Buscador -->
                <div class="container">
                    <div class="row">
                        <form action="" method="POST" class="form-inline pull-right" role="search" style="margin-top:10px;">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-filter"></i></span>
                                <input type="text" name="search" id="search" class="form-control" placeholder="¿Qué estás buscando?">
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary" style="margin-left:8px;">Buscar</button>
                        </form>
                    </div>
                </div>
                <!-- Fin Buscador -->
                <section class="content">
                    <div class="row ">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Informe/Referencia</h3>
                                </div>
                                <div class="box-body">
                                    <?php if (mysqli_num_rows($items) > 0) { ?>
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Referencia</th>
                                                            <th scope="col">Informe</th>
                                                            <th scope="col">Total Fotos</th>
                                                            <th scope="col">Muestra</th>
                                                            <th scope="col">Cliente</th>
                                                            <th scope="col">Observaciones</th>
                                                            <th scope="col">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $count = 1;
                                                        $contador = $inicio + 1;        //Paginador
                                                        $pag_inicio = $inicio + 1;      //Paginador
                                                        $pag_final = $inicio + $registros; //Paginador
                                                        //if($pag_final>$total){       //Paginador
                                                        while ($Data = mysqli_fetch_array($items)) { ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $contador; ?></th>
                                                                <td><?php echo $Data['referencia']; ?></td>
                                                                <td><?php echo $Data['noInforme']; ?></td>
                                                                <td><span class="badge bg-blue"><?php echo $Data['total_fotos']; ?></span></td>
                                                                <td><?php echo $Data['muestra']; ?></td>
                                                                <td><?php echo $Data['cliente']; ?></td>
                                                                <td><?php echo $Data['observaciones']; ?></td>


                                                                <td>
                                                                    <?php
                                                                    if (($Data['referencia'])) {
                                                                        $idFoto = $Data['referencia'];
                                                                    } else {
                                                                        $idFoto = $Data['noInforme'];
                                                                    }
                                                                    ?>

                                                                    <a href="ver_fotos.php?idFoto=<?php echo $idFoto; ?>"
                                                                        class="btn btn-info btn-xs">
                                                                        <i class="fa fa-picture-o"></i> Ver Fotos
                                                                    </a>
                                                                </td>

                                                            </tr>
                                                        <?php 
                                                    $contador++;
                                                    } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php
                                    } else { ?>
                                            <p class="sinResultados">No hay Resultados</p>
                                        <?php }

                                    if ($total >= 1 && $pagina <= $Npaginas) {
                                        echo paginador_tablas($pagina, $Npaginas, $url, 7);
                                    }
                                        ?>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php include('footer.html'); ?>

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