<?php require_once('Connections/conexion.php');
$menuactivo ='usuario';
if (!isset($_SESSION)) {
  session_start();
}  ?>
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
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <link rel="stylesheet" href="plugins/select2/select2.min.css">

</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <?php include('header.php'); ?>
    </header>
    <?php 
    $activarmenu='rvehiculo';
    include('aside.php');
    ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <span class="glyphicon glyphicon-user"></span> REGISTRAR USUARIO
        </h1>
      </section>

      <section class="content">


        <div class="row">
          <div class="col-md-7">
            <div class="box box-info">

              <form class="form-horizontal" action="proce_regusuario.php" method="post" name="form1" id="form1">
                <div class="box-body">

                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Usuario:</label>
                    <div class="col-sm-9">
                      <input class="form-control" autofocus required type="text" name="user" value="" size="32" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Clave:</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled type="text" required name="" value="Será su mismo usuario"
                        size="32" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Nombres:</label>
                    <div class="col-sm-9">
                      <input class="form-control" type="text" required name="nombres" value="" size="32" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Apellidos:</label>
                    <div class="col-sm-9">
                      <input class="form-control" type="text" required name="apellidos" value="" size="32" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Tipo:</label>
                    <div class="col-sm-9">
                      <select class="form-control" name="tipo" id="tipo">
                      <option selected="selected" value="GENERAL">USUARIO GENERAL</option>
                      <option value="INGLABORATORISTA">INGLABORATORISTA</option>
                        <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                        <option value="SUPERVISOR">SUPERVISOR</option>
                        <option value="ASISTENTE">ASISTENTE</option>
                        <option value="ALMACEN">ALMACEN</option>                        
                        <option value="CONTABILIDAD">CONTABILIDAD</option>
                        <option value="GESTOR">GESTOR</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Telefono/Celular:</label>
                    <div class="col-sm-9">
                      <input class="form-control" <input type="text" name="fono" value="" size="32" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Correo:</label>
                    <div class="col-sm-9">
                      <input class="form-control" type="email" name="correo" value="" size="32" />
                    </div>
                  </div>

                </div>
                <div class="box-footer">
                  <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                  <button type="submit" class="btn btn-info pull-right" title="Grabar registro">GUARDAR</button>

                  <input type="hidden" name="estado" value="ACTIVO">
                  <input type="hidden" name="MM_insert" value="form1" />
                </div>

              </form>


            </div>
          </div>
        </div>
      </section>
    </div>

    <?php include('footer.html'); ?>

  </div>
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script>
  $.widget.bridge('uibutton', $.ui.button);
  </script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="plugins/fastclick/fastclick.js"></script>
  <script src="dist/js/app.min.js"></script>
  <script src="plugins/select2/select2.full.min.js"></script>
  <script>
  $(function() {
    $(".select2").select2();
  });
  </script>
</body>

</html>