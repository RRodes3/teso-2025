<?php
if (!isset($_SESSION)) {
  session_start();
}
if (isset($_SESSION['U_spprivilegio']) and in_array("m_reginf", $_SESSION['U_spprivilegio'])) {
  require_once('Connections/conexion.php');
  $menuactivo = 'movimiento';
?>

  <?php
  if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
      if (PHP_VERSION < 6) {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
      }
      //$theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);
      switch ($theType) {
        case "text":
          $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
          break;
        case "long":
        case "int":
          $theValue = ($theValue != "") ? intval($theValue) : "NULL";
          break;
        case "double":
          $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
          break;
        case "date":
          $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
          break;
        case "defined":
          $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
          break;
      }
      return $theValue;
    }
  }
  $query_lista = "SELECT MAX( codbieninv ) as idd FROM bien";
  $lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
  $roow = mysqli_fetch_array($lista);
  $totalRows_lista = mysqli_num_rows($lista);
  $codigo = $roow['idd'];
  $resetnum = "00";
  $notesofinal = "";
//  date_default_timezone_set('America/Bogota');
  date_default_timezone_set('America/Mexico_City');
  $fecha_asignacion_itemm = date('y-m-d');
  $fechaEnteraa = strtotime($fecha_asignacion_itemm);
  $anioo = date("y", $fechaEnteraa);
  $mess = date("m", $fechaEnteraa);
  $diaa = date("d", $fechaEnteraa);
  $codigoo = substr('00' . $codigo, -2);

  $numconse = $codigoo + 1;
  if (strlen($numconse) < 2) {
    $numconse = "0" . $numconse;
  }
  if ($numconse > 99) {
    $notesofinal = "TESO" .  $anioo . $mess . $diaa . $resetnum;
  } else {
    $notesofinal = "TESO" .  $anioo . $mess . $diaa . $numconse;
  }
  $noteso = $notesofinal;

  

  $editFormAction = $_SERVER['PHP_SELF'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
  }
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    $rss = mysqli_query($conexion, "SELECT idbien, codbieninv, idfamiliabien, existencia, codmarca, codmodelo FROM bien where codbieninv = '" . $noteso . "' 
    OR idproveedor = '" . $_POST['idproveedor'] . "' 
    AND (idfamiliabien = '" . $_POST['idfamiliabien'] . "' 
    OR idfamiliabien != '" . $_POST['idfamiliabien'] . "') 
    AND codmarca = '" . $_POST['codmarca'] . "' 
    AND codmodelo = '" . $_POST['codmodelo'] . "' AND existencia = '" . $_POST['existencia'] . "'");
    $row_lista_bien = mysqli_fetch_array($rss);
    $totaltesos = mysqli_num_rows($rss);
    $validregistermodal = false;
    if ($totaltesos > 0) {
      $validregistermodal = true;
    }
    if ($validregistermodal == false) {

      $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      
      function generate_string($input, $strength = 16)
      {
        $input_length = strlen($input);
        $random_string = 'TeSo';
        for ($i = 0; $i < $strength; $i++) {
          $random_character = $input[mt_rand(0, $input_length - 1)];
          $random_string .= $random_character;
        }
        return $random_string;
      }
      $claveinfor = generate_string($permitted_chars, 64);


      $caracteres='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      $longpalabra=8;
      for($pass='', $n=strlen($caracteres)-1; strlen($pass) < $longpalabra ; ) {
        $x = rand(0,$n);
        $pass.= $caracteres[$x];
      }
      $idnorma = $_POST['idfamiliabien'];
      $passtes= $pass;         
      $clave = $claveinfor;      
      $factura = 2;
      $stock = 1;      
      $usuarioreg = $_SESSION['U_spnombres'] . ' ' . $_SESSION['U_spapellidos'];
      $insertSQL = sprintf(
        "INSERT INTO bien (codbieninv, idfamiliabien, codbiencat, codmarca, 
        codcolor, codmodelo, codunicoinfor, passinforteso, facturara, quienregis, stock, existencia, factura, caracteristica, paisorigen, serie, fechaadq, fecharecitem, idproveedor, 
        valor, segui,observaciones, tiempovida, idusuarioreg, fregistro) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, NOW())",
        GetSQLValueString($noteso, "text"), //NO. Teso
        GetSQLValueString($_POST['idfamiliabien'], "text"),
        GetSQLValueString($_POST['codbiencat'], "text"),
        GetSQLValueString($_POST['codmarca'], "text"),
        GetSQLValueString($_POST['codcolor'], "text"),
        GetSQLValueString($_POST['codmodelo'], "text"),
        GetSQLValueString($clave, "text"),
        GetSQLValueString($passtes, "text"),
        GetSQLValueString($_POST['facturara'], "text"),
        GetSQLValueString($usuarioreg, "text"),
        GetSQLValueString($stock, "int"),
        GetSQLValueString($_POST['existencia'], "text"),
        GetSQLValueString($factura, "int"),
        GetSQLValueString($_POST['caracteristica'], "text"),
        GetSQLValueString($_POST['paisorigen'], "text"),
        GetSQLValueString($_POST['serie'], "text"),
        GetSQLValueString($_POST['fechaadq'], "date"),
        GetSQLValueString($_POST['fecharecitem'], "date"),
        GetSQLValueString($_POST['idproveedor'], "int"),
        GetSQLValueString($_POST['precionorma'], "double"),
        GetSQLValueString($_POST['segui'], "text"),
        GetSQLValueString($_POST['observaciones'], "text"),
        GetSQLValueString($_POST['tiempovida'], "date"),
        GetSQLValueString($idusuario, "int")
      );
      $Result1 = mysqli_query($conexion, $insertSQL) or die(mysqli_error());
      $query_idbien = "SELECT bien.idbien,biencatalogo.nombien  FROM bien inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat where bien.idusuarioreg = '" . $idusuario . "' order by bien.idbien desc";
      $listabien = mysqli_query($conexion, $query_idbien) or die(mysqli_error());
      $roowidbien = mysqli_fetch_array($listabien);
      $idbien = '';
      $nombien = '';
      $nomdoctransf = '';
      if (mysqli_num_rows($listabien) > 0) {
        $idbien = $roowidbien['idbien'];
        $nombien = $roowidbien['nombien'];
      }
      if (empty($_FILES['doctransf']['name'])) {
        $nomdoctransf = '';
      } else {
        $extnuevoimagen = pathinfo($_FILES['doctransf']['name']);
        $numaleatorio = mt_rand(5, 30);
        $nomdoctransf = 'Ingreso-' . substr(preg_replace('#([^.a-z0-9]+)#i', '', $nombien), 0, 10) . $numaleatorio . '-' . date('d-m-Y') . '.' . $extnuevoimagen['extension'];
      }
      $iinsertSQL = sprintf(
        "INSERT INTO movimiento (idbien, condicion, clasificacion, doctransf,  idusuarioreg, fechareg) VALUES (%s, %s, %s, %s, %s, NOW())",
        GetSQLValueString($idbien, "int"),
        GetSQLValueString($_POST['condicion'], "text"),
        GetSQLValueString($_POST['clasificacion'], "text"),
        GetSQLValueString($nomdoctransf, "text"),
        GetSQLValueString($idusuario, "text")
      );
      $Result2 = mysqli_query($conexion, $iinsertSQL) or die(mysqli_error());
      $query_idmov = "SELECT idmov FROM movimiento where idusuarioreg = '" . $idusuario . "' order by idmov desc";
      $listabienmov = mysqli_query($conexion, $query_idmov) or die(mysqli_error());
      $roowidbienmov = mysqli_fetch_array($listabienmov);
      //$theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);
      $idmov = '';
      if (mysqli_num_rows($listabienmov) > 0) {
        $idmov = $roowidbienmov['idmov'];
      }
      $query_actbien = "UPDATE  `bien` SET  `idmovimiento` =  '" . $idmov . "' WHERE  `idbien` = '" . $idbien . "'";
      $listaactbien = mysqli_query($conexion, $query_actbien) or die(mysqli_error());
      $query_auditoria = "insert into auditoria  (fecharegistro,idusuario,accion,numacta) values (Now(),'" . $idusuario . "','Registró el numero de teso.: " . $noteso . "','" . $idmov . "')";
      $lista_audit = mysqli_query($conexion, $query_auditoria) or die(mysqli_error());
      $mensaje = "";

       

/*===============================*/
 /*ASIGNACION DE INFORMES
 
 $query_asignacion = "SELECT idusuario, nombres, asignaciones FROM usuario 
 WHERE (asignaciones IS NOT NULL) AND (tipo= 'INGLABORATORISTA') AND (estado= 'ACTIVO') ORDER BY asignaciones ASC, fech_asig ASC";
 $lista_asignacion = mysqli_query($conexion, $query_asignacion) or die(mysqli_error());

 while ($row = mysqli_fetch_assoc($lista_asignacion)) {
   $idingasig = $row['idusuario'];
   $numasig=$row['asignaciones'];
   break;
 }          

 if ($idingasig != '') {

   date_default_timezone_set('America/Mexico_City');
   $fecha_asignacion_item = date('Y-m-d h:i:s');

   $insertSQL = sprintf(
     "INSERT INTO licencia (id_bien, idusuario, fech_asignacion) VALUES (%s, %s, %s)",
     GetSQLValueString($idbien, "int"),
     GetSQLValueString($idingasig, "int"),
     GetSQLValueString($fecha_asignacion_item, "date")
   );
   $Result1 = mysqli_query($conexion, $insertSQL) or die(mysqli_error());

   
   $totalasig= $numasig + 1;         
   $query_updateasig = "UPDATE  `usuario` SET  `asignaciones` =  '" . $totalasig . "', fech_asig = '".$fecha_asignacion_item."' WHERE  `idusuario` = '" . $idingasig. "'";
   $listaasig = mysqli_query($conexion, $query_updateasig) or die(mysqli_error());        
 }
  /* END ASIGNACION */


  


      /*===============================*/
      /*ASIGNACION DE INFORMES*/

      //INGENIEROS LABORATORISTAS
      //Enriquesoto
      //$enriqueSoto = 3;
      //Edgar
     // $edgarSoto = 10;
      //Omar Huerta
      $omarHuerta = 21;
      //Oaxaca
      $Oaxaca = 14;
      //Alfonso
      $alfosoRendon = 29;
      //Fernanflow
      $fernanFlow = 80;
      //Hugo
      $hugo = 39;
      //Metal Mecanica
      $daniMiranda = 89;

      $sajgid= 27;
      
      $idingasig="";

      //NORMAS

      // Obtener el usuario asignado a la norma 019
     /* if ($idnorma == 14 || $idnorma == 15 || $idnorma == 70 || $idnorma == 98) {
        $idingasig = $enriqueSoto; // Usuario asignado a normas de 019
        
        // Dos usuarios asignados a esta norma
        $usuario1 = $enriqueSoto;

        $usuario2 = $edgarSoto;

        // Consulta para determinar quién tiene menos asignaciones
        $query_asignacion = sprintf(
          "SELECT idusuario, asignaciones FROM usuario 
       WHERE idusuario IN (%s, %s) 
       ORDER BY asignaciones ASC LIMIT 1",
          GetSQLValueString($usuario1, "int"),
          GetSQLValueString($usuario2, "int")
        );
        $result_asignacion = mysqli_query($conexion, $query_asignacion) or die(mysqli_error());
        $row_asignacion = mysqli_fetch_assoc($result_asignacion);

        // Asignar el usuario con menos asignaciones
        $idingasig = $row_asignacion['idusuario'];



      } elseif ($idnorma == 66 || $idnorma == 67 || $idnorma == 68 || $idnorma == 69) {

      */
       if ($idnorma == 66 || $idnorma == 67 || $idnorma == 68 || $idnorma == 69) {  
        $idingasig = $sajgid; // Usuario asignado a normas de metal mecanica
      } elseif ($idnorma == 16 || $idnorma == 17 || $idnorma == 81) {
        $idingasig = $fernanFlow; // Usuario asignado a norma de eficiencia energetica
      } else {
        // Si no hay un usuario específico asignado, usar el usuario con menos asignaciones
        $query_asignacion = "SELECT idusuario, nombres, asignaciones FROM usuario 
      WHERE (asignaciones IS NOT NULL) AND (tipo= 'INGLABORATORISTA') AND (estado= 'ACTIVO') AND (idusuario != '$daniMiranda') AND (idusuario != 43) 
      ORDER BY asignaciones ASC, fech_asig ASC";
        $lista_asignacion = mysqli_query($conexion, $query_asignacion) or die(mysqli_error());

        while ($row = mysqli_fetch_assoc($lista_asignacion)) {
          $idingasig = $row['idusuario'];
          $numasig = $row['asignaciones'];
          break;
        }
      }

      // Insertar en la tabla licencia
       date_default_timezone_set('America/Bogota');
      $fecha_asignacion_item = date('Y-m-d h:i:s');

      $insertSQL = sprintf(
        "INSERT INTO licencia (id_bien, idusuario, fech_asignacion) VALUES (%s, %s, %s)",
        GetSQLValueString($idbien, "int"),
        GetSQLValueString($idingasig, "int"),
        GetSQLValueString($fecha_asignacion_item, "date")
      );
      $Result1 = mysqli_query($conexion, $insertSQL) or die(mysqli_error());
      // Actualizar asignaciones del usuario
      $query_updateasig = sprintf(
        "UPDATE usuario SET asignaciones = asignaciones + 1, fech_asig = %s WHERE idusuario = %s",
        GetSQLValueString($fecha_asignacion_item, "date"),
        GetSQLValueString($idingasig, "int")
      );
      $Result2 = mysqli_query($conexion, $query_updateasig) or die(mysqli_error());
      /* END ASIGNACION */

    }
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
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="plugins/select2/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="includes/carganormal.js"></script>
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
            <span class="glyphicon glyphicon-plus"></span>Registrar Nuevo Informe
          </h1>
        </section>

        <section class="content">
          <div class="modal fade" id="modalInicio" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title color" id="myModalLabel">¿Estas seguro de registrar el informe? Ya se encuenta un informe con los mismos datos
                  </h4>
                </div>
                <div class="modal-body">
                  <strong class="alert-warning">Detalles:</strong><br>
                  <strong>No. Teso: </strong> <?php echo json_encode($row_lista_bien['codbieninv']); ?> <br>
                  <strong>Marca: </strong> <?php echo json_encode($row_lista_bien['codmarca']); ?> <br>
                  <strong>Modelo: </strong> <?php echo json_encode($row_lista_bien['codmodelo']); ?> <br>
                </div>
                <div class="modal-footer">
                  <input name="idfamiliabien" type="hidden" required id="idfamiliabien" value="" size="32">
                  <input name="nomtabla" type="hidden" required id="nomtabla" value="familiabien" size="32">
                  <button type="button" class="btn btn-default" data-dismiss="modal" title="Cancelar">Cancelar</button>
                  <input type="button" class="btn btn-default" id="enviar" data-dismiss="modal" title="Si" value="OK">
                </div>
              </div>
            </div>
          </div>

          <form class="form-horizontal" method="post" enctype='multipart/form-data' name="form1" action="<?php echo $editFormAction; ?>">
            <div class="row">
              <div class="col-md-6">
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">ALTA INFORME</h3>
                  </div>
                  <div class="box-body">
                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">El Item es por otra norma?:</label>
                      <div class="col-sm-9">
                        <select class="form-control" name="existencia" id="existencia">
                          <OPTION selected value="NO">NO</OPTION>
                          <OPTION value="SI">SI</OPTION>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Codigo cliente:</label>
                      <div class="col-sm-9">
                        <select class="form-control select2" required name="idproveedor" id="idproveedor" onchange="Carga('includes/imput_codinv.php?codfam='+this.value, 'DivReporte')">
                          <option value="">-- Seleccione -- </option>
                          <?php
                          $rs = mysqli_query($conexion, "SELECT idproveedor, codproveedor, razonsocial, personacontacto FROM proveedor");
                          while ($off = mysqli_fetch_array($rs)) {
                          ?>
                            <OPTION VALUE="<?php echo $off['idproveedor']; ?>"><?php echo $off['codproveedor'] . ' - ' . $off['razonsocial']. ' - - -> ' . $off['personacontacto'];; ?></OPTION>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Representante:</label>
                      <div class="col-sm-9">
                        <div id="DivReporte">
                          <input class="form-control" type="text" name="codbieninvs" id="codbieninvs" value="" placeholder="Representante" readonly autocomplete="off">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Facturar a:</label>
                      <div class="col-sm-9">
                        <input name="facturara" type="text" class="form-control" id="facturara" autocomplete="off" required placeholder="Escribe aquien se le va a faturar">
                      </div>
                    </div>


                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Norma:</label>
                      <div class="col-sm-9">

                        <select class="form-control select2" required name="idfamiliabien" id="idfamiliabien" onchange="Carga('includes/imput_precio.php?codnorma='+this.value, 'DivReportee')">
                          <option value="">-- Seleccione una Norma-- </option>
                          <?php
                          $rs = mysqli_query($conexion, "SELECT * FROM familiabien");
                          while ($off = mysqli_fetch_array($rs)) {
                          ?>
                            <OPTION VALUE="<?php echo $off['idfamiliabien']; ?>"><?php echo $off['codfamiliabien'] . ' - ' . $off['familiabien']; ?></OPTION>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Precio:</label>
                        <div class="col-sm-9">
                          <div id="DivReportee">                          
                            <div class="input-group">
                          <div class="input-group-addon">$</div><input class="form-control" type="text" name="precionorma" id="precionorma" value="" placeholder="0.00" readonly autocomplete="off">
                        </div>
                        </div>
                      </div>
                    </div>

                    

                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Item:</label>
                      <div class="col-sm-9">
                        <select class="form-control select2" required name="codbiencat" id="codbiencat">
                          <option value="">-- Seleccione una Item-- </option>
                          <?php
                          $rs = mysqli_query($conexion, "SELECT * FROM biencatalogo");
                          while ($off = mysqli_fetch_array($rs)) {
                          ?>
                            <OPTION VALUE=<?php echo $off['codbiencat']; ?>><?php echo $off['nombien']; ?></OPTION>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Marca:</label>
                      <div class="col-sm-9">
                        <input name="codmarca" type="text" class="form-control" id="codmarca" list="datalistamarca" autocomplete="off" required value="<?php if (isset($_POST['codmarca'])) echo $_POST['codmarca']; ?>" placeholder="Escribe o seleccione la marca">
                        <datalist id="datalistamarca">
                          <?php
                          $rs = mysqli_query($conexion, "SELECT distinct codmarca FROM bien order by codmarca");
                          while ($off = mysqli_fetch_array($rs)) {
                          ?>
                            <option><?php echo $off['codmarca']; ?></option>
                          <?php
                          }
                          ?>
                        </datalist>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Modelo:</label>
                      <div class="col-sm-9">
                        <input name="codmodelo" type="text" class="form-control" id="codmodelo" list="datalistamodelo" autocomplete="off" value="<?php if (isset($_POST['codmodelo'])) echo $_POST['codmodelo']; ?>" placeholder="Escribe o seleccione el modelo" required>
                        <datalist id="datalistamodelo">
                          <?php
                          $rs = mysqli_query($conexion, "SELECT distinct codmodelo FROM bien order by codmodelo");
                          while ($off = mysqli_fetch_array($rs)) {
                          ?>
                            <option><?php echo $off['codmodelo']; ?></option>
                          <?php
                          }
                          ?>
                        </datalist>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Serie:</label>
                      <div class="col-sm-9">
                        <input class="form-control" type="text" name="serie" value="<?php if (isset($_POST['serie'])) echo $_POST['serie']; ?>" placeholder="Serie">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Consta / Incluye:</label>
                      <div class="col-sm-9">
                        <textarea class="form-control" type="text" id="caracteristica" name="caracteristica" value="<?php echo $_POST['caracteristica']; ?>" placeholder="Accesorios"></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Gestor:</label>
                      <div class="col-sm-9">
                        <input name="codcolor" type="text" class="form-control" id="codcolor" list="datalistacolor" autocomplete="off" value="<?php if (isset($_POST['codcolor'])) echo $_POST['codcolor']; ?>"" placeholder=" Escribe o seleccione el contacto">
                        <datalist id="datalistacolor">
                          <?php
                          $rs = mysqli_query($conexion, "SELECT distinct codcolor FROM bien order by codcolor");
                          while ($off = mysqli_fetch_array($rs)) {
                          ?>
                            <option><?php echo $off['codcolor']; ?></option>
                          <?php
                          }
                          ?>
                        </datalist>
                      </div>
                    </div>

                  <div class=" form-group">
                          <label for="id" class="col-sm-3 control-label">Fecha de ingreso de solicitud:</label>
                          <div class="col-sm-9">
                            <input class="form-control" name="fecharecitem" type="date" id="fecharecitem" placeholder="Años/Mes/Dias" value="<?php if (isset($_POST['fechaadq'])) echo $_POST['fechaadq']; ?>">
                          </div>
                        </div>
                        <div class=" form-group">
                          <label for="id" class="col-sm-3 control-label">Fecha de recepcción de item:</label>
                          <div class="col-sm-9">
                            <input class="form-control" name="fechaadq" type="date" id="fechaadq" placeholder="Años/Mes/Dias" value="<?php if (isset($_POST['fecharecitem'])) echo $_POST['fecharecitem']; ?>">
                          </div>
                        </div>
                        <div class=" form-group">
                          <label for="id" class="col-sm-3 control-label">Fecha de entrega propuesta:</label>
                          <div class="col-sm-9">
                            <input class="form-control" name="tiempovida" type="date" required id="tiempovida" placeholder="Años/Mes/Dias" value="<?php if (isset($_POST['tiempovida'])) echo $_POST['tiempovida']; ?>">
                          </div>
                        </div>                        
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="box box-info">
                      <div class="box-body">
                      <div class="form-group">
                      <label for="id" class="col-sm-3 control-label">Pais de Origen:</label>
                      <div class="col-sm-9">
                        <input class="form-control" type="text" name="paisorigen" value="<?php if (isset($_POST['paisorigen'])) echo $_POST['paisorigen']; ?>" placeholder="paisorigen">
                      </div>
                    </div>
                        <div class="form-group">
                          <label for="id" class="col-sm-3 control-label">Categoria del Item:</label>
                          <div class="col-sm-9">
                            <select class="form-control" required name="condicion" id="condicion">
                              <OPTION selected value="NUEVO">NUEVO</OPTION>
                              <OPTION value="REACONDICIONADO">REACONDICIONADO</OPTION>
                              <OPTION value="RECONSTRUIDO">RECONSTRUIDO</OPTION>
                              <OPTION value="USADO">USADO</OPTION>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="id" class="col-sm-3 control-label">Clasificación:</label>
                          <div class="col-sm-9">
                            <select class="form-control" name="clasificacion" id="clasificacion">
                              <OPTION selected value="TENSION NORMAL">TENSION NORMAL</OPTION>
                              <OPTION value="EXTRABAJA">EXTRABAJA</OPTION>
                              <OPTION value="KIT">KIT </OPTION>
                              <OPTION value="<2K">< 2K</OPTION>
                              <OPTION value=">2K">> 2K</OPTION>
                              <OPTION value="TRIFASICO">TRIFÁSICO</OPTION>
                              <OPTION value="METALMECANICA">METALMECANICA</OPTION>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="id" class="col-sm-3 control-label">El informe es de seguimiento:</label>
                          <div class="col-sm-9">
                            <select class="form-control" required name="segui" id="segui">
                              <OPTION selected value=" ">NO</OPTION>
                              <OPTION value="SEGUIMIENTO">SI</OPTION>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="id" class="col-sm-3 control-label">Observaciones:</label>
                          <div class="col-sm-9">
                            <textarea class="form-control" required name="observaciones" id="observaciones" value="" placeholder="ESCRIBE UNA OBSERVACION DEL INFORME"></textarea>
                          </div>
                        </div>
                        <label for="" style="color: grey;"><?php echo $noteso; ?></label>
                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                        <a href="javascript:history.back(-1);" class="btn btn-default" title="Cancelar">CANCELAR</a>
                        <button type="submit" class="btn btn-info pull-right" title="Grabar registro">GUARDAR</button>
                        <input type="hidden" name="MM_insert" value="form1">
                      </div>
                    </div>
                  </div>
                </div>
          </form>
        </section>
      </div>
      <?php include('footer.html'); ?>
    </div>
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script src="plugins/select2/select2.full.min.js"></script>
    <script src="xreczfsf/vistas/plugins/sweetalert2/sweetalert2.all.js"></script>
    <script>
      $(function() {
        $(".select2").select2();
      });
    </script>
    <script>
      <?php if ($mensaje == "") : ?>
        swal({
          position: 'top-end',
          type: 'success',
          title: "<?php echo $noteso; ?>",
          text: "Registro exitoso, Recuerde generar su recibo al terminar el registro.",
          showConfirmButton: true,
          confirmButtonText: "Cerrar"
        }).then(function(result) {
          window.location = "xreczfsf/index.php";
        });
      <?php endif; ?>
    </script>
    <script>
      <?php if ($validregistermodal == true) : ?>
        $(document).ready(function() {
          $("#modalInicio").modal("show");
        });
        document.getElementById('enviar').addEventListener('click', function(e) {
          e.preventDefault();
          $validregistermodal = false;
          console.log($validregistermodal);
        });
      <?php endif; ?>
    </script>
  </body>

  </html>
<?php } else {
  header("Location: pagina-restringida.php");
} ?>