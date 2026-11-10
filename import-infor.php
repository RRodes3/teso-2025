<?php
if (!isset($_SESSION)) {
  session_start();
}
if (isset($_SESSION['U_spprivilegio']) and in_array("sm_auditoria", $_SESSION['U_spprivilegio'])) {

  require_once('Connections/conexion.php');
  $menuactivo = 'mantenimiento';
?>

  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistema Teso</title>
    <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
    <meta name="author" content="Eduardo Sandoval">
    <!--Desarrollado por:Eduardo Sandoval -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="estilos/jquery.dataTables.min.css">
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="plugins/select2/select2.min.css">

    <!-- <script src="carga.js"></script> -->

  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <header class="main-header">
        <?php include('header.php'); ?>
      </header>
      <?php
      $activarmenu = 'registrar-familia';
      include('aside.php');
      ?>

      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            <span class="glyphicon glyphicon-plus"></span>Importar .xlsx
          </h1>
        </section>


        <section class="content">
          <div class="container" style="width: 50%;">
            <div class="box-files" style="            
            width: 80%;
            display: grid;
            justify-content: center;
            height: 300px;
            background: #000000bd;
            color: white;
            border-radius: 15px;
            padding: 20px;
            ">
              <h2>Subir archivo a la base de datos</h2>
              <form name="frmSubirCSV" id="frmSubirCSV" method="post" enctype="multipart/form-data">
                <p><input type="file" name="archivo_csv" id="archivo_csv"></p>
                <p><input type="submit" class="enviar_archivo separar_boton" style="
                padding: 8px;
                color: #fff;
                border-radius: 10px;
                background: #a11d1d;
                margin-top: 20px;
                border: 1px solid white;
                font-size: 18px;
                " value="Enviar archivo"></p>
                <div id="estado"></div>
              </form>
            </div>
          </div>
        </section>

      </div>

      <?php include('footer.html'); ?>

    </div>
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>

    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script src="plugins/select2/select2.full.min.js"></script>
    <!-- DataTables -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {

        $('.enviar_archivo').on("click", function(evt) {

          evt.preventDefault();
          cargarArchivoCSV();

        });

      });

      function cargarArchivoCSV() {

        var archivo = $('input[name=archivo_csv]').val();
        var extension = $('#archivo_csv').val().split(".").pop().toLowerCase();
        var Formulario = document.getElementById('frmSubirCSV');
        var dataForm = new FormData(Formulario);

        var retornarError = false;

        if (archivo == "") {
          $('#archivo_csv').addClass('error');
          retornarError = true;
          $('#archivo_csv').focus();
        } else if ($.inArray(extension, ['csv']) == -1) {
          alert("¡El archivo que esta tratando de subir es invalido!");
          retornarError = true;
          $('#archivo_csv').val("");
        } else {
          $('#archivo_csv').removeClass('error');
        }

        // A continuacion se resalta todos los campos que contengan errores.
        if (retornarError == true) {
          return false;
        }

        $.ajax({

          url: 'files-xlsx.php',
          type: 'POST',
          data: dataForm,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            $('#estado').prepend('<p><img /></p>');
          },
          success: function(data) {
            $('#estado').fadeOut("fast", function() {
              $('#estado').html(data);
            });

            $('#estado').fadeIn("slow");
            $("#frmSubirCSV").find('input[type=file]').val("");

          },
          error: function(jqXHR, textStatus, errorThrown) {
            $loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
          }

        });


      }
    </script>
  </body>

  </html>

<?php } else {
  header("Location: pagina-restringida.php");
} ?>