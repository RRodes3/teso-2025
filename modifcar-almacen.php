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
    $activarmenu='modificar-familia';
    include('aside.php');
    ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <span class="glyphicon glyphicon-edit"></span>
        </h1>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-7">
            <div class="box box-info">
              <form class="form-horizontal" method="post" name="form1" action="<?php echo $editFormAction; ?>">
                <div class="box-body">


                <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">No. INFORME:</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled autofocus type="text" required name="descripcion"
                        value="<?php echo htmlentities($row_modificaroficina['codbieninv'], ENT_COMPAT, 'utf-8'); ?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">MUESTRA:</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled type="text" name="muestra"
                        value="<?php echo htmlentities($row_modificaroficina['codbiencat'], ENT_COMPAT, 'utf-8'); ?>"
                        required size="32">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">MARCA:</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled type="text" name="marca" required
                        value="<?php echo htmlentities($row_modificaroficina['codmarca'], ENT_COMPAT, 'utf-8'); ?>"
                        size="32">
                    </div>
                  </div>

                  
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">MODELO:</label>
                    <div class="col-sm-9">
                      <input class="form-control" disabled type="text" name="modelo" required
                        value="<?php echo htmlentities($row_modificaroficina['codmodelo'], ENT_COMPAT, 'utf-8'); ?>"
                        size="32">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">ACCESORIOS:</label>
                    <div class="col-sm-9">
                      <input class="form-control" type="text" name="accesorios" required
                        value="<?php echo htmlentities($row_modificaroficina['accesorios'], ENT_COMPAT, 'utf-8'); ?>"
                        size="32">
                    </div>
                  </div>

                  
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">OBSERVACIONES:</label>
                    <div class="col-sm-9">
                      <input class="form-control" type="text" name="observaciones" required
                        value="<?php echo htmlentities($row_modificaroficina['observaciones'], ENT_COMPAT, 'utf-8'); ?>"
                        size="32">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">ASIGNAR ANAQUEL:</label>
                    <div class="col-sm-9">
                      <input class="form-control" type="text" name="lugar" required
                        value="<?php echo htmlentities($row_modificaroficina['lugar'], ENT_COMPAT, 'utf-8'); ?>"
                        size="32">
                    </div>
                  </div>
                  <input type="hidden" name="MM_update" value="form1">
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                  <button type="submit" class="btn btn-info pull-right" title="Grabar registro">ACTUALIZAR</button>
                  <input type="hidden" name="codbieninv"value="<?php echo htmlentities($row_modificaroficina['codbieninv'], ENT_COMPAT, 'utf-8'); ?>">
                  <input name="idbien" type="hidden" required id="idbien"
                    value="<?php echo htmlentities($row_modificaroficina['idbien'], ENT_COMPAT, 'utf-8'); ?>"
                    size="32">
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