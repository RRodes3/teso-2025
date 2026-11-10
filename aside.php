<head>
  <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
</head>
<?php if (!isset($_SESSION)) {
  session_start();
}
if (!isset($menuactivo)) {
  $menuactivo = '';
}
$actinicio = '';
$actmovimiento = '';
// $actventas = '';
$actmantenimiento = '';
$actsupervision = '';
$actgestor = '';
$actalmacen = '';
$actreportes = '';
$actusuario = '';
$actmisdatos = '';
if ($menuactivo == 'inicio') {
  $actinicio = 'active';
} else if ($menuactivo == 'movimiento') {
  $actmovimiento = 'active';
} else if ($menuactivo == 'mantenimiento') {
  $actmantenimiento = 'active';
} else if ($menuactivo == 'supervision') {
  $actsupervision = 'active';
} else if ($menuactivo == 'gestoria') {
  $actgestor = 'active';
} else if ($menuactivo == 'informes') {
  $actmisinformes = 'active';
} else if ($menuactivo == 'facturacion') {
  $actfacturacion = 'active';
} else if ($menuactivo == 'almacen') {
  $actalmacen = 'active';
} else if ($menuactivo == 'reportes') {
  $actreportes = 'active';
} else if ($menuactivo == 'usuario') {
  $actusuario = 'active';
} else if ($menuactivo == 'misdatos') {
  $actmisdatos = 'active';
}
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar " class="print-hidden">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="img/LOGO.jpg" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?php echo $_SESSION['U_spnombres'] . ' ' . $_SESSION['U_spapellidos'] ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="header">MENU DE NAVEGACION</li>
      <li class="<?php echo $actinicio;
                  if ($activarmenu == 'diagrama') {
                    echo 'active';
                  } ?> treeview">
        <a href="diagrama.php">
          <i class="glyphicon glyphicon-home"></i> <span>Inicio</span>
        </a>
      </li>

      <!-- MOVIMIENTO 2022 -->
      <?php
      if (in_array("m_movi", $_SESSION['U_spprivilegio'])) {
      ?>
        <li class="<?php echo $actmovimiento; ?> treeview ">
          <a href="#"> <i class="glyphicon glyphicon-ok"
              aria-hidden="true"></i> <span>Movimiento</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php
            if (in_array("m_reginf", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="registro-bien.php"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                  Registrar Informe</a></li>
            <?php }

            if (in_array("m_regmovimiento", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="lista-tesos-uno.php"><span class="glyphicon glyphicon-retweet" aria-hidden="true"></span> Lista de items 1</a></li>
            <?php }

            if (in_array("m_regmovimiento", $_SESSION['U_spprivilegio'])) {
            ?>
              <!-- <li><a href="lista-bienmov.php"><span class="glyphicon glyphicon-retweet" aria-hidden="true"></span> Movimientos Teso</a></li> -->
              <li><a href="lista-tesos-dos.php"><span class="glyphicon glyphicon-retweet" aria-hidden="true"></span> Lista de items 2</a></li>
              <li><a href="lista-tesos-tres.php"><span class="glyphicon glyphicon-retweet" aria-hidden="true"></span> Lista de items 3</a></li>
              <li><a href="lista-tesos-cuatro.php"><span class="glyphicon glyphicon-retweet" aria-hidden="true"></span> Lista de items 4</a></li>
            <?php }

            if (in_array("m_recibos", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="xreczfsf/index.php"><span class="fa fa-ticket" aria-hidden="true"></span>
                  Crear recibo</a></li>
            <?php }
            ?>
          </ul>
        </li>
      <?php }
      ?>

      <!-- RECEPCION DE MUESTRAS -->
      <?php
      if (in_array("s_superv", $_SESSION['U_spprivilegio'])) {
      ?>
        <li class="<?php echo $actmantenimiento; ?> treeview ">
          <a href="#"> <i class="glyphicon glyphicon-folder-open"
              aria-hidden="true"></i> <span> Recepción de Muestras </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php
            if (in_array("s_superv", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="recep-muestras.php"><span class="fa fa-camera" aria-hidden="true"></span>
                  Toma de Fotografias </a></li>
              <li><a href="lista-consulta.php"><span class="fa fa-file-image-o" aria-hidden="true"></span>
                  Consulta de fotos </a></li>

            <?php }
            ?>
          </ul>
        </li>
      <?php }
      ?>
      <!-- END RECEPCION DE MUESTRAS -->


      <!-- MANTENIMIENTO -->
      <?php
      if (in_array("sm_mantenimiento", $_SESSION['U_spprivilegio'])) {
      ?>
        <li class="<?php echo $actmantenimiento; ?> treeview "><a href="#"> <i class="glyphicon glyphicon-pencil"
              aria-hidden="true"></i> <span> Mantenimiento </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span></a>
          <ul class="treeview-menu">
            <?php
            if (in_array("sm_clientes", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="lista-clientes.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                  Lista Clientes </a></li>
              <li><a href="lista-empresas.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                  Lista Empresas </a></li>
              <li><a href="lista-gestores.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                  Lista Gestores </a></li>
              <li><a href="registrar-proveedor.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                  Registrar Empresas </a></li>
              <li><a href="registrar-gestor.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                  Registrar Gestor </a></li>

            <?php }
            if (in_array("sm_normas", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="registrar-familia.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Familia de normas</a></li>
            <?php }
            if (in_array("sm_items", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="registrar-catalogo.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Catalogo de items</a></li>
            <?php }
            if (in_array("sm_eliminf", $_SESSION['U_spprivilegio'])) {
            ?>
              <!-- <li><a href="lista-historial-bien.php?metodo=YABCDFGJAHmMG7uay9pbs="><span class="glyphicon glyphicon-remove"
                aria-hidden="true"></span>Eliminar informe - movimiento</a></li> -->
            <?php }
            if (in_array("sm_auditoria", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="lista-auditoria-usuario.php"><span class="glyphicon glyphicon-th-list"
                    aria-hidden="true"></span>Auditoria - Historial</a></li>
              <li><a href="import-infor.php"><span class="glyphicon glyphicon-th-list"
                    aria-hidden="true"></span>Importar Archivos</a></li>
            <?php }
            ?>
          </ul>
        </li>
      <?php }
      ?>


      <!-- RECEPCION DE MUESTRAS -->



      <!-- END RECEPCION DE MUESTRAS -->

      <!-- SUPERVISION -->
      <?php
      if (in_array("s_superv", $_SESSION['U_spprivilegio'])) {
      ?>
        <li class="<?php echo $actsupervision; ?> treeview "><a href="#"> <i class="glyphicon glyphicon-list-alt"
              aria-hidden="true"></i> <span>Supervision </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span></a>

          <ul class="treeview-menu">


            <!-- <li><a href="asignar-folio.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                Asignar Folio</a></li> -->

            <li><a href="informes-asig.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                Informes asignados 1</a></li>

            <li><a href="informes-asig2.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                Informes asignados 2</a></li>

            <li><a href="informes-asig-rec.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                Informes asignados recientemente</a></li>

            <li><a href="estatus-informe.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                Estatus Informe</a></li>

            <?php
            if (in_array("s_asig", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="asignar-informes.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Asignar Informe a Ingeniero</a></li>


            <?php }
            if (in_array("s_clave_s", $_SESSION['U_spprivilegio'])) {
            ?>

              <li><a href="clave-qr.php"><span class="glyphicon glyphicon-th-list"
                    aria-hidden="true"></span>Qr Items</a></li>

              <li><a href="informes-finales.php"><span class="glyphicon glyphicon-th-list"
                    aria-hidden="true"></span>Informes pendientes por enviar</a></li>

              <li><a href="informes-enviados.php"><span class="glyphicon glyphicon-th-list"
                    aria-hidden="true"></span>Informes enviados 1</a></li>
              <li><a href="informes-enviados-dos.php"><span class="glyphicon glyphicon-th-list"
                    aria-hidden="true"></span>Informes enviados 2</a></li>
              <li><a href="form-iteso.php"><span class="glyphicon glyphicon-th-list"
                    aria-hidden="true"></span>Registros I-teso</a></li>

            <?php }
            ?>
          </ul>
        </li>
      <?php }
      ?>

      <!-- GESTORIA -->
      <?php
      if (in_array("g-gestor", $_SESSION['U_spprivilegio'])) {
      ?>
        <li class="<?php echo $actgestor; ?> treeview "><a href="#"> <i class="glyphicon glyphicon-list-alt"
              aria-hidden="true"></i> <span>Gestor </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span></a>
          <ul class="treeview-menu">
            <?php
            if (in_array("g-gestor-control", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="estatus-informes-gestoria.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Estatus Informe</a></li>
            <?php }
            ?>
          </ul>
        </li>
      <?php }
      ?>

      <!-- FACTURACION -->
      <?php
      if (in_array("f-admin-fact", $_SESSION['U_spprivilegio'])) {
      ?>
        <li class="<?php echo $actfacturacion; ?> treeview "><a href="#"> <i class="glyphicon glyphicon-list-alt"
              aria-hidden="true"></i> <span>Admin Facturación </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span></a>
          <ul class="treeview-menu">
            <?php
            if (in_array("f-fact-control", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="registrar-clientes.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                  Registrar Clientes </a></li>

              <li><a href="./lista-empresas.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                  Lista Empresas </a></li>

              <li><a href="./lista-gestores.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                  Lista Gestores </a></li>
              <li><a href="ad-fact-one.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Informes 1</a></li>
              <li><a href="ad-fact-two.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Informes 2</a></li>


            <?php
            }
            if (in_array("f-fact-control", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="ad-fact-three.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Informes 3</a></li>

            <?php
            }
            if (in_array("f-fact-control", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="ad-fact-four.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Informes 4</a></li>

            <?php
            }
            ?>
          </ul>
        </li>
      <?php
      }
      ?>


      <!-- ALMACEN -->
      <?php
      if (in_array("a_almacen", $_SESSION['U_spprivilegio'])) {
      ?>
        <li class="<?php echo $actalmacen; ?> treeview "><a href="#"> <i class="glyphicon glyphicon-list-alt"
              aria-hidden="true"></i> <span>Almacen </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span></a>
          <ul class="treeview-menu">
            <?php
            if (in_array("a_alma", $_SESSION['U_spprivilegio'])) {
            ?>
             <li><a href="lista-consulta.php"><span class="fa fa-file-image-o" aria-hidden="true"></span>
                  Consulta de fotos INGRESOS</a></li>
              <li><a href="almacen-uno.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Almacen uno</a></li>
              <li><a href="almacen-dos.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Almacen dos</a></li>
              <li><a href="almacen-tres.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Almacen tres</a></li>
              <li><a href="almacen-cuatro.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Almacen cuatro</a></li>
              <li><a href="entregadas.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                  Muestras entregadas</a></li>
            <?php
            }
            ?>
          </ul>
        </li>
      <?php
      }
      ?>
      <!-- REPORTES -->
      <li class="<?php echo $actreportes; ?> treeview "><a href="#"> <i class="glyphicon glyphicon-list-alt"
            aria-hidden="true"></i> <span>Reportes </span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span></a>
        <ul class="treeview-menu">
          <?php
          if (in_array("r_reportes", $_SESSION['U_spprivilegio'])) {
          ?>

            <li><a href="reporte-general.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                Reporte General 1</a></li>
            <li><a href="reporte-general-two.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                Reporte General 2</a></li>
                    <li><a href="descargar-reporte.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                Descargar reporte</a></li>

          <?php }
          ?>
        </ul>
      </li>
      <!-- USUARIOS DEL SISTEMA -->
      <?php
      if (in_array("u_usuarios", $_SESSION['U_spprivilegio'])) {
      ?>

        <li class="<?php echo $actusuario; ?> treeview "><a href="#"> <i class="glyphicon glyphicon-cog"
              aria-hidden="true"></i> <span> Usuarios del sistema </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span></a>
          <ul class="treeview-menu">
            <?php
            if (in_array("u_regusu", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="registrar-usuario.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                  Registrar Usuarios</a></li>
            <?php }
            if (in_array("u_listausu", $_SESSION['U_spprivilegio'])) {
            ?>
              <li><a href="lista-usuario.php"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> Lista de
                  Usuarios</a></li>
            <?php }
            if (in_array("u_editusu", $_SESSION['U_spprivilegio'])) {
            ?>
            <?php }
            ?>
          </ul>
        </li>
      <?php }
      ?>
      <!-- MIS DATOS -->

      <li class="<?php echo $actmisdatos; ?> treeview "><a href="#"> <i class="glyphicon glyphicon-user"
            aria-hidden="true"></i> <span> Mis datos</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span></a>
        <ul class="treeview-menu">
          <li><a href="mis-datos.php" class="active"><span class="glyphicon glyphicon-th-list"
                aria-hidden="true"></span> Mis Datos</a></li>
          <li><a href="cambiar-clave.php"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Cambiar
              Clave</a></li>
          <li><a href="cerrar-sesion.php"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Cerrar
              Sesión</a></li>
        </ul>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>