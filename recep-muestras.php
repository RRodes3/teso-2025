<?php
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['msg'])) {
  $mensaje = "";
}

if (isset($_SESSION['U_spprivilegio']) and in_array("s_superv", $_SESSION['U_spprivilegio'])) {

  require_once('Connections/conexion.php');
  $menuactivo = 'movimiento';

  if ((isset($_POST["searchinform"])) && ($_POST["searchinform"] == "formsearch")) {
    $searchInfor = $_POST['noinforme'];
    $query_search = "SELECT bien.*, biencatalogo.*, proveedor.* FROM bien
        LEFT JOIN biencatalogo on bien.codbiencat = biencatalogo.codbiencat        
        LEFT JOIN proveedor on bien.idproveedor = proveedor.idproveedor
        WHERE bien.codbieninv = '" . $searchInfor . "'";
    $search = mysqli_query($conexion, $query_search) or die();
    $row_search = mysqli_fetch_assoc($search);
    $row_search_result = mysqli_num_rows($search);
    $resultSearch = $row_search;
  }
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
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="./upload.js"></script>
    <style>
      .preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
      }

      .preview-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
      }

      .preview-item img {
        max-width: 120px;
        height: auto;
        margin-bottom: 5px;
      }

      .file-label {
        display: inline-block;
        padding: 10px 15px;
        background-color: #002750ff;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        margin-bottom: 20px;
      }

      .file-label:hover {
        background-color: #002a57ff;
      }
    </style>
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <main>
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
            <div class="row ">
              <div class="box box-primary">
                <div class="box-body">
                  <h1>
                    <span class="glyphicon glyphicon-tag"></span>Registrar muestra y Todos sus componentes.
                  </h1>
                </div>
              </div>
            </div>
          </section>

          <section class="content-search">
            <div class="row ">
              <div class="col-md-10 col-md-offset-1">
                <div class="box box-primary">
                  <div class="box-body">
                    <div class="selec-inform">
                      <div class="form-group">
                        <label for="id" class="col-sm-2 control-label">Conoces el numero de informe?:</label>
                        <div class="col-sm-10">
                          <select class="form-control" name="referenciaIngreso" id="referenciaIngreso">
                            <option value="">-- Selecciona una opcion-- </option>
                            <option value="yesmyfriend">Si</option>
                            <option value="nomyfriend">No</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group" style="display: none;">
                        <div class="search-informe col-md-8">
                          <div class="tittle-wrapper">
                            <h4>Ingresa el numero de informe</h4>
                          </div>
                          <input type="hidden" id="nInforme">
                          <?php include('./search.php'); ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <section class="content">
            <div class="row ">
              <div class="col-md-10 col-md-offset-1">
                <div class="box box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Toma de Fotografías</h3>
                  </div>

                  <form class="form-horizontal" id="formulario" role="form" method="post" action="recibeFile.php" enctype="multipart/form-data">
                    <div class="box-body">
                      <!-- numero de informe -->
                      <div class="form-group" style="display: block;">
                        <label for="inputCliente" class="col-sm-2 col-form-label">No Informe:</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="noInforme" name="noInforme" value="<?php
                                                                                                          if (isset($resultSearch))
                                                                                                            echo $resultSearch['codbieninv'];
                                                                                                          else
                                                                                                            echo '';
                                                                                                          ?>" placeholder="NoInforme">

                        </div>
                      </div>

                      <!-- referencia -->
                      <div class="form-group" style="display: none;">
                        <label for="id" class="col-sm-2 control-label">Rerefencia automatica:</label>
                        <div class="col-sm-10">
                          <input class="form-control" type="text" disabled name="referencia" id="referencia" size="32">
                        </div>
                      </div>

                      <!-- Empresa -->
                      <div class="form-group row">
                        <label for="idempresa" class="col-sm-2 col-form-label">Cliente:</label>
                        <div class="col-sm-10">
                          <?php if (!isset($resultSearch)) {
                          ?>
                            <select class="form-control select2" name="idempresa" id="idempresa">
                              <option value="">-- Seleccione al cliente-- </option>
                              <?php
                              $rs = mysqli_query($conexion, "SELECT idproveedor,codproveedor, razonsocial FROM proveedor");
                              while ($off = mysqli_fetch_array($rs)) {
                              ?>
                                <OPTION VALUE="<?php echo $off['idproveedor']; ?>"><?php echo $off['codproveedor'] . ' - ' . $off['razonsocial']; ?></OPTION>
                              <?php
                              }
                              ?>
                            </select>
                          <?php
                          } else {
                          ?>
                            <input type="text" class="form-control" id="idempresaa" name="idempresaa" value=" <?php echo $resultSearch['razonsocial']; ?> " placeholder="Razon social Cliente">
                            <input type="hidden" class="form-control" id="idempresa" name="idempresa" value=" <?php echo $resultSearch['idproveedor']; ?> " placeholder="Razon social Cliente">
                          <?php
                          }  ?>
                        </div>
                      </div>

                      <!-- Nombre del producto -->
                      <div class="form-group row">
                        <label for="idproducto" class="col-sm-2 col-form-label">Item:</label>
                        <div class="col-sm-10">
                          <?php if (!isset($resultSearch)) { ?>
                            <select class="form-control select2" required name="idproducto" id="idproducto">
                              <option value="">-- Seleccione el item-- </option>
                              <?php
                              $rs = mysqli_query($conexion, "SELECT codbiencat, nombien FROM biencatalogo");
                              while ($off = mysqli_fetch_array($rs)) { ?>
                                <OPTION VALUE=<?php echo $off['codbiencat']; ?>><?php echo $off['nombien']; ?></OPTION>
                              <?php } ?>
                            </select>

                          <?php } else { ?>
                            <input type="text" class="form-control" id="idproductoo" name="idproductoo" value="<?php echo $resultSearch['nombien']; ?> " placeholder="Item">
                            <input type="hidden" class="form-control" id="idproducto" name="idproducto" value="<?php echo $resultSearch['codbiencat']; ?> " placeholder="Item">
                          <?php } ?>
                        </div>
                      </div>

                      <!-- Observaciones -->
                      <div class="form-group">
                        <label for="observaciones" class="col-sm-2 control-label">Observaciones</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" required id="observaciones" name="observaciones"
                            placeholder="Escriba aca sus observaciones ...">
                        </div>
                      </div>

                      <!-- Btn para subir imagenes -->
                      <div class="form-group text-center">
                        <div class="col-sm-6">
                          <label for="image-input" class="file-label"> <i class="fa fa-camera"></i></label>
                          <input type="file" name="fotos_muestra[]" multiple id="image-input" accept="image/*" class="form-control-file" style="display: none;">
                          <span class="badge bg-blue" id="imageCounter" style="margin-left: 10px;">0 imágenes</span>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-12">
                          <div id="preview" class="preview-container"></div>
                        </div>
                      </div>
                    </div>
                    <!-- /.box-body -->
                    <!-- Submit form images -->
                    <div class="box-footer text-right">
                      <input type="hidden" class="form-control" id="idusuario" name="idusuario" value="<?php echo $_SESSION['U_spidus']; ?>">
                      <button type="submit" name="submit" class="btn btn-primary">Guarda Registro</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </section>

        </div>
        <?php include('footer.html'); ?>
      </div>
    </main>

    <!-- jQuery 2.2.3 -->
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- page script -->
    <script src="plugins/select2/select2.full.min.js"></script>
    <script>
      $(function() {
        $(".select2").select2();
      });
    </script>

    <script>
      // Función para generar código aleatorio alfanumérico de 7 caracteres
      function generateRandomCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        let code = '';
        for (let i = 0; i < 7; i++) {
          code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return code;
      }

      document.getElementById('referenciaIngreso').addEventListener('change', function() {
        const conoceInforme = this.value;
        const nInformeField = document.getElementById('nInforme').parentElement.parentElement;
        const entregaField = document.getElementById('referencia').parentElement.parentElement;
        const noInformeField = document.getElementById('noInforme').parentElement.parentElement;

        const referenciaInput = document.getElementById('referencia');
        const noInformeInput = document.getElementById('noInforme');

        if (conoceInforme === 'yesmyfriend') {
          nInformeField.style.display = 'block';
          entregaField.style.display = 'none';
          noInformeField.style.display = 'block';

          referenciaInput.value = ''; // Limpiar el campo
          noInformeInput.value = ''; // Limpiar el campo

        } else if (conoceInforme === 'nomyfriend') {
          nInformeField.style.display = 'none';
          entregaField.style.display = 'block';
          noInformeField.style.display = 'none';

          // Generar y asignar código aleatorio alfanumérico
          referenciaInput.value = generateRandomCode();
        } else {
          nInformeField.style.display = 'none';
          entregaField.style.display = 'none';
          noInformeField.style.display = 'block';
          referenciaInput.value = ''; // Limpiar el campo
        }
      });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.all.min.js"></script>
    <script>
      <?php
      // Si existe un mensaje en la URL
      if (isset($_GET['msg']) && $_GET['msg'] !== "") :
      ?>
        Swal.fire({
          icon: "success",
          title: "Registro exitoso",
          text: "Recuerde identificar las muestras correctamente.",
          confirmButtonText: "Cerrar",
          allowOutsideClick: false
        }).then((result) => {
          // Limpia la URL para que el mensaje no vuelva a salir si recargas
          window.location = "recep-muestras.php";
        });
      <?php endif; ?>
    </script>

  </body>

  </html>
<?php } else {
  header("Location: pagina-restringida.php");
} ?>