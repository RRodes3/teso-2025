<?php 
if (!isset($_SESSION)) {
  session_start();
}   
if(isset ($_SESSION['U_sdni']) and $_SESSION['U_sdni']!='' and $_SESSION['U_sptipo']!=''){
 
  echo '<script language=javascript>
	self.location="diagrama.php"
</script>';
}
?>
<?php 
				 if (isset($_GET['falloo'])) {
          $mensaje ='Contraseña no válida.';}
				 elseif (isset($_GET['fallor'])) {
          $msgerror ='El usuario que ingresaste es incorrecto.' ;}
				 ?>
<!DOCTYPE html>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Sistema Teso</title>
<link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
<meta name="author" content="Eduardo Sandoval">
<meta name="description" content="Controla el inventario de bienes o activos que existen en una empresa">

<!-- Bootstrap -->
<script async src="estilo_login/analytics.js.descarga"></script>
<script src="estilo_login/pace.js.descarga"></script>
<link href="estilo_login/bootstrap.css" rel="stylesheet">
<link href="estilo_login/theme.css" rel="stylesheet">
<link href="estilo_login/font-awesome.css" rel="stylesheet">
<link href="estilo_login/animate.css" rel="stylesheet">
<link href="estilo_login/css" rel="stylesheet" type="text/css">
<link href="estilo_login/theme-loading-bar.css" rel="stylesheet">
<link href="estilo_login/theme-loading-bar.css" rel="stylesheet">
<script language="javascript">
function validacionrec() {
  var emailrec = document.getElementById('emailrec').value;
  var usuariorec = document.getElementById('usuariorec').value;
  if (emailrec.length == 0 && usuariorec.length == 0) {
    $("#recargadoerror").empty().append('* Debe ingresar por lo menos uno de ellos (Email o usuario)');
    return false;
  }
}
</script>
</head>

<body class="  pace-done">
  <div class="pace  pace-inactive">
    <div class="pace-progress" data-progress-text="100%" data-progress="99" style="width: 100%;">
      <div class="pace-progress-inner"></div>
    </div>
    <div class="pace-activity"></div>
  </div>
  <div class="container" id="container">
    <section id="form" class="animated fadeInDown">
      <div class="container">
        <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">

          <div class="panel white-alpha-90">
            <div class="panel-heading">
              <div class="panel-title text-center">
                <h2>Bienvenido</h2>
              </div>
            </div>
            <div class="panel-body">
              <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
              <form id="loginform" class="form-horizontal" method="post" role="form" action="proce_login.php">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input id="login-username" autofocus type="text" class="form-control" required name="miusuario"
                    value="" placeholder="Usuario">
                </div>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                  <input id="login-password" type="password" class="form-control" required name="mipass"
                    placeholder="password">
                </div>
                <div class="input-group col-xs-12 text-center login-action">
                  <div class="checkbox">

                    <input type="submit" class="btn btn-primary" value="Iniciar sesión">
                  </div>
                </div>
                <div style="margin-top:0px" class="form-group">
                  <div class="col-sm-12 controls">
              
                  </div>
                </div>
                <?php if (isset($msgerror) ){echo '<div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>¡Aviso!</strong> '. $msgerror.'
        </div>'; }?>
        <?php if (isset($mensaje) ){echo '<div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>¡Aviso!</strong> '. $mensaje.'
        </div>'; }?>
              </form>
            </div>
          </div>
        </div>
      </div>

    </section>
    <form method="post" name="fromRecClave" action="usuario/proceso_enviarcodigo.php" onsubmit="return validacionrec()">
      <div class="modal fade" id="ModalfromRecClave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-m" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                  aria-hidden="true">&times;</span></button>
              <h3 class="modal-title sinpadding" id="myModalLabel" style="margin:0">Recuperar clave</h3>
            </div>
            <div class="modal-body">
              <p>Puedes recuperar tu cuenta atravez de tu EMAIL o USUARIO <br>
                (Al ingresar cualquiera de las dos opciones puedes recuperar tu clave)</p>
              <div class="text-danger" id="recargadoerror" style=" width: 100%"></div>
              <input name="%%22SADF%A//--%3E%022%29%28%22SADF%22%29%29%3B%0A//--%3E%0A%3" type="hidden" value="">
              ✔ Ingrese email: <input class="form-control" type="email" name="emailrec" id="emailrec"
                placeholder="ejemplo@email.com" /> <br><input name="RecuperarClave" type="hidden"
                value="%22%29%29%3B%0A">
              ✔ O ingrese usuario: <input class="form-control" type="text" name="usuariorec" id="usuariorec"
                placeholder="miusuario" />
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <input type="submit" class="btn btn-primary" id="recu" name="btneliminar" value="Recuperar">
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <script src="estilo_login/jquery.min.js.descarga"></script>
  <script src="estilo_login/bootstrap.min.js.descarga"></script>
  <script src="estilo_login/jquery.backstretch.min.js.descarga"></script>
  <script>
  Pace.on('hide', function() {
    $("#container").fadeIn('1000');
    $.backstretch([
      "images/taxi-1.jpg",
      "images/taxi-3.jpg"
    ], {
      duration: 5000,
      fade: 1000
    });
  });
  </script>
  <script>
  (function(i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function() {
      (i[r].q = i[r].q || []).push(arguments)
    }, i[r].l = 1 * new Date();
    a = s.createElement(o),
      m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m)
  })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

  ga('create', 'UA-53918379-1', 'auto');
  ga('send', 'pageview');
  </script>
  <div class="backstretch"
    style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 100%; width: 100%; z-index: -999999; position: fixed;">
    <img src="estilo_login/taxi-3.jpg"
      style="position: absolute; margin: 0px; padding: 0px; border: none; width: 100%; height: 100%; max-height: none; max-width: none;">
  </div>
</body>
</html>