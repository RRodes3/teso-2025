<?php if (!isset($_SESSION)) {
  session_start();
  require_once('Connections/conexion.php');
}
if (!isset($_SESSION['U_spidus']) and $_SESSION['U_spidus'] == '') {
  echo '<script language=javascript>
    alert("Por Favor Inicie Sesion")
    self.location="login.php"
    </script>';
}
?>
<header class="main-header hidden-print">

  <!-- Logo -->
  <a href="#" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini">Teso</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>Laboratorio </b>Teso</span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Navegación</span>
    </a>

    <div class="collapse navbar-collapse navbar-ex1-collapse">

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">


<!-- ////////////////////////////////////////////////////////////////////////////////////////// -->

<li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>              
              <span class="label label-danger">
              <?php
                $query_lista_hallazgo = "SELECT count(*) as tipodehallazgo from licencia where  (tipodehallazgo != 'NULL') and (fvencimiento is null)";
                $lista_hallazgo = mysqli_query($conexion, $query_lista_hallazgo) or die(mysqli_error());
                $row_lista_hallazgo = mysqli_fetch_assoc($lista_hallazgo);
                echo $row_lista_hallazgo['tipodehallazgo'];
                ?>
              </span>
            </a>
            <ul class="dropdown-menu">
            <li class="header"> <b> Tienes <?php echo $row_lista_hallazgo['tipodehallazgo']; ?> Incidencias</b> <br>¡Recuerda, tienes 1 día para dar respuesta a partir de la fecha indicada.! </li>              
              <li>
                <ul class="menu">
                  <li>
                    <b>
                    <?php
                    $resultado = mysqli_query($conexion, "SELECT l.*,b.*, c.nombien, u.nombres, u.apellidos FROM `licencia` l 
                  INNER JOIN `bien` b ON l.id_bien = b.idbien
                  INNER JOIN `usuario` u ON l.idusuario = u.idusuario
                  INNER JOIN `biencatalogo` c ON b.codbiencat = c.codbiencat 
                  WHERE (tipodehallazgo != 'NULL') and (fvencimiento is null) order by l.idusuario asc");
                    while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                      $fecharegistro = $fila['finicio'];
                      $fechar = new DateTime($fecharegistro);
                      $fechaactual = new DateTime(date("Y-m-d"));
                      $fechaestatus = $fechar->diff($fechaactual);
                      $fechaa = $fechaestatus->days;
                      if ($fechaa > 1) {
                        echo '<span style="color: red;"> Urgente!!!</span> Informe: ' . $fila['codbieninv'] . " / " . $fila['tipodehallazgo'] . " / " . $fila['organismo'] . " / " . $fila['nombres'] . " " . $fila['apellidos'] . "/ Fecha de incidencia " . $fila['finicio'] . '<br><br>';
                      } else {
                        echo 'Informe: ' . $fila['codbieninv'] . " / " . $fila['tipodehallazgo'] . " / " . $fila['organismo'] . " / " . $fila['nombres'] . " " . $fila['apellidos'] . "/ Fecha de incidencia " . $fila['finicio'] . '<br><br>';
                      }
                    }
                    mysqli_free_result($resultado);
                    ?>
                    </b>
                  </li>
                </ul>
              </li>
              <li class="footer">
                <a href="#"></a>
              </li>
            </ul>
          </li>


          <!-- ======================================================================================================================================= -->
          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-exclamation-triangle"></i>
              <span class="label label-danger">
                <?php
                $query_lista_hallazgo = "SELECT count(*) as modclasificacion from movimiento where modclasificacion = 1";
                $lista_hallazgo = mysqli_query($conexion, $query_lista_hallazgo) or die(mysqli_error());
                $row_lista_hallazgo = mysqli_fetch_assoc($lista_hallazgo);
                echo $row_lista_hallazgo['modclasificacion'];
                ?>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header"><b> Tienes <?php echo $row_lista_hallazgo['modclasificacion']; ?> Notificaciones</b> <br>¡Recuerda actualizar el importe de la cotizacion, con base a la nueva tensión.!</li>
              <li>
                <ul class="menu">
                  <li>
                    <b>
                      <?php
                      $resultado = mysqli_query($conexion, "SELECT l.*,b.*, c.nombien, m.clasificacion, u.nombres, u.apellidos FROM `licencia` l 
                  INNER JOIN `bien` b ON l.id_bien = b.idbien
                  INNER JOIN `usuario` u ON l.idusuario = u.idusuario
                  INNER JOIN `biencatalogo` c ON b.codbiencat = c.codbiencat 
                  INNER JOIN `movimiento` m ON b.idbien = m.idbien
                  WHERE m.modclasificacion = 1");
                      while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                        echo 'Informe: ' . $fila['codbieninv'] . " / Fue acualizada como: " . $fila['clasificacion'] . '<br>';
                      }
                      mysqli_free_result($resultado);
                      ?>
                    </b>
                  </li>
                </ul>
              </li>
              <li class="footer">
                <a href="#"></a>
              </li>
            </ul>
          </li>
          <!-- ======================================================================================================================================= -->

          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-slack"></i>
              <span class="label label-danger">
                <?php
                $query_lista_hallazgo = "SELECT count(*) as modnorma from bien where modnorma = 1";
                $lista_hallazgo = mysqli_query($conexion, $query_lista_hallazgo) or die(mysqli_error());
                $row_lista_hallazgo = mysqli_fetch_assoc($lista_hallazgo);
                echo $row_lista_hallazgo['modnorma'];
                ?>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header"><b> Tienes <?php echo $row_lista_hallazgo['modnorma']; ?> Notificaciones</b> <br>¡Recuerda actualizar el importe de la cotizacion, con base a la nueva norma.!</li>
              <li>
                <ul class="menu">
                  <li>
                    <b>
                      <?php
                      $resultado = mysqli_query($conexion, "SELECT l.*,b.*, c.nombien, m.clasificacion, m.modclasificacion, u.nombres, u.apellidos, f.codfamiliabien, f.familiabien FROM `licencia` l 
                      INNER JOIN `bien` b ON l.id_bien = b.idbien
                      INNER JOIN `usuario` u ON l.idusuario = u.idusuario
                      INNER JOIN `biencatalogo` c ON b.codbiencat = c.codbiencat 
                      INNER JOIN `movimiento` m ON b.idbien = m.idbien
                      inner join `familiabien` f on b.idfamiliabien = f.idfamiliabien
                      WHERE b.modnorma = 1");
                      while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                        echo 'Informe: ' . $fila['codbieninv'] . " /actualizacion de Norma a la: " . $fila['codfamiliabien'] . $fila['familiabien'] . '<br>';
                      }
                      mysqli_free_result($resultado);
                      ?>
                    </b>
                  </li>
                </ul>
              </li>
              <li class="footer">
                <a href="#"></a>
              </li>
            </ul>
          </li>

          <!-- ======================================================================================= -->


          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="img/icono.png" class="user-image" height="160" width="160" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['U_spnombres'] . ' ' . $_SESSION['U_spapellidos']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="img/logo.png" class="img-circle" alt="User Image">
                <p>
                  <?php echo $_SESSION['U_spnombres'] . ' ' . $_SESSION['U_spapellidos']; ?> <br />
                  <?php echo $_SESSION['U_sptipo']; ?>
                </p>
              </li>

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="mis-datos.php" class="btn btn-default btn-flat">Mis datos</a>
                </div>
                <div class="pull-right">
                  <a href="cerrar-sesion.php" class="btn btn-default btn-flat">Cerrar Sesión</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->

        </ul>
      </div>
  </nav>
</header>