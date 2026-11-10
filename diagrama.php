<?php
require_once('Connections/conexion.php');
require_once('Connections/funciones.php');
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sistema Teso</title>
  <link rel="icon" sizes="10x16" href="img/logo-ico.png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Eduardo Sandoval">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

      <header class="main-header">
        <?php include('header.php'); ?>
      </header>


      <!-- Left side column. contains the logo and sidebar -->

      <?php 
      $menuactivo ='inicio';
      include('aside.php');
    ?>

  <!-- ---------------------------------------------- -->
   <div class="content-wrapper">
    <section class="content-header">
      <!-- <h1>
        Tablero
        <small>Panel de Control</small>
      </h1> -->
      <!-- <ol class="breadcrumb">     
        <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>      
        <li class="active">Tablero</li>   
      </ol> -->
    </section>
    <section class="content">
    <!-- <h4>
        Ingenieros: <br>
        1.- Recuerda que apartir del 25 de Abril (fecha de registro en la base), debes manejar las siguientes fechas: <br> - FECHA DE INGRESO DEL ITEM. <br>
        - FECHA DE INICIO DE PRUEBAS. <br> - FECHA DE TERMINACIÓN DE PRUEBAS. <br> - FECHA DE EMISIÓN DEL INFORME. <br>
        Conforme al procedimiento.
        <br><BR>
        2.- ¡Aviso! <br> En la ultima actualización del sistema Teso, se les ha habilitado la herramienta de notificaciones donde
        se les informara de las incidencias recibidas hacia los informes emitidos, esperando de su apoyo para la respuesta y contestación conforme a 
        la fecha indicada, cualquier duda favor de dirigirse con el coordinador de supervisión.        
      </h4> -->

      <div class="row">     
      <?php
        include "inicio/cajas-superiores.php";
      ?>
      </div> 
      <div class="row">      
          <div class="col-lg-12">
              <img src="img/intro-bng.png" alt="" class="img-fluid" style="width: 100%">
          </div>
      </div>
    </section>
     </div>

      <!-- ------------------------------------------------------ -->
      
 
    <?php include('footer.html'); ?>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div>
  <!-- ./wrapper -->

  <!-- jQuery 2.2.3 -->
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <!-- ChartJS 1.0.1 -->
  <script src="plugins/chartjs/Chart.min.js"></script>
  <!-- FastClick -->
  <script src="plugins/fastclick/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/app.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- page script -->

</body>

</html>