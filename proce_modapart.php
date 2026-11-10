<?php require_once('Connections/conexion.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {


  if ($_POST['idbien'] != '') {

    $query_listamov = "SELECT * FROM bien where idbien = '" . $_POST['idbien'] . "'";
    $listamov = mysqli_query($conexion, $query_listamov) or die(mysqli_error());
    $row_listamov = mysqli_fetch_array($listamov);
    $totalregistros = mysqli_num_rows($listamov);
    $idnorma = $row_listamov['idfamiliabien'];
    $nomitem = $row_listamov['codbiencat'];

    $validacionreg = false;

    if ($totalregistros == 1 and ($idnorma == 87) and ($nomitem == 5170)) {
      $validacionreg = true;
    }
    if ($totalregistros == 0 || $validacionreg == true) {

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
      $codigounico = generate_string($permitted_chars, 20);
      $claveinfor = $codigounico;

      $caracteres='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      $longpalabra=8;
      for($pass='', $n=strlen($caracteres)-1; strlen($pass) < $longpalabra ; ) {
        $x = rand(0,$n);
        $pass.= $caracteres[$x];
      }

      $passtes= $pass;  


      $factura = 2;
      $idbienpost = $_POST['idbien'];

      $usuarioreg = $_SESSION['U_spnombres'] . ' ' . $_SESSION['U_spapellidos'];
      $updateSQL = sprintf(
        "UPDATE bien SET codbieninv=%s, idfamiliabien=%s, codbiencat=%s, codmarca=%s, 
  codcolor=%s, codmodelo=%s, codunicoinfor=%s, passinforteso=%s, facturara=%s, quienregis=%s, existencia=%s, factura=%s, caracteristica=%s, segui=%s, 
  observaciones=%s, serie=%s, fechaadq=%s, fecharecitem=%s, idproveedor=%s, valor=%s, tiempovida=%s, idusuarioreg=%s WHERE `idbien` = '" . $idbienpost . "'",
        GetSQLValueString($_POST['codbieninv'], "text"),
        GetSQLValueString($_POST['idfamiliabien'], "int"),
        GetSQLValueString($_POST['codbiencat'], "text"),
        GetSQLValueString($_POST['codmarca'], "text"),
        GetSQLValueString($_POST['codcolor'], "text"),
        GetSQLValueString($_POST['codmodelo'], "text"),
        GetSQLValueString($claveinfor, "text"),
        GetSQLValueString($passtes, "text"),
        GetSQLValueString($_POST['facturara'], "text"),
        GetSQLValueString($usuarioreg, "text"),
        GetSQLValueString($_POST['existencia'], "text"),
        GetSQLValueString($factura, "int"),
        GetSQLValueString($_POST['caracteristica'], "text"),
        GetSQLValueString($_POST['segui'], "text"),
        GetSQLValueString($_POST['observaciones'], "text"),
        GetSQLValueString($_POST['serie'], "text"),
        GetSQLValueString($_POST['fechaadq'], "date"),
        GetSQLValueString($_POST['fecharecitem'], "date"),
        GetSQLValueString($_POST['idproveedor'], "int"),
        GetSQLValueString($_POST['valor'], "double"),
        GetSQLValueString($_POST['tiempovida'], "date"),
        GetSQLValueString($idusuario, "int")
      );

      $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());

      $iinsertSQL = sprintf(
        "INSERT INTO movimiento (idbien, condicion, clasificacion, idusuarioreg, fechareg) VALUES (%s, %s, %s, %s, NOW())",
        GetSQLValueString($_POST['idbien'], "int"),
        GetSQLValueString($_POST['condicion'], "text"),
        GetSQLValueString($_POST['clasificacion'], "text"),
        GetSQLValueString($idusuario, "text")
      );
      $Result2 = mysqli_query($conexion, $iinsertSQL) or die(mysqli_error());


      $query_idmov = "SELECT idmov FROM movimiento where idusuarioreg = '" . $idusuario . "' order by idmov desc";
      $listabienmov = mysqli_query($conexion, $query_idmov) or die(mysqli_error());
      $roowidbienmov = mysqli_fetch_array($listabienmov);

      $idmov = '';

      if (mysqli_num_rows($listabienmov) > 0) {
        $idmov = $roowidbienmov['idmov'];
      }

      $query_actbien = "UPDATE  `bien` SET  `idmovimiento` =  '" . $idmov . "' WHERE  `idbien` = '" . $idbienpost . "'";
      $listaactbien = mysqli_query($conexion, $query_actbien) or die(mysqli_error());
      
      $query_auditoria = "insert into auditoria  (fecharegistro,idusuario,accion,numacta) values (Now(),'" . $idusuario . "','Registró el numero de teso.: " . $noteso . "','" . $idmov . "')";
      $lista_audit = mysqli_query($conexion, $query_auditoria) or die(mysqli_error());
      $mensaje = "";

      $query_auditoria = "insert into auditoria  (fecharegistro,idusuario,accion,numacta) values (Now(),'" . $idusuario . "','Ingreso de apart informe: " . $_POST['codbieninv'] . "','')";
      $lista_audit = mysqli_query($conexion, $query_auditoria) or die(mysqli_error());



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
           GetSQLValueString($idbienpost, "int"),
           GetSQLValueString($idingasig, "int"),
           GetSQLValueString($fecha_asignacion_item, "date")
         );
         $Result1 = mysqli_query($conexion, $insertSQL) or die(mysqli_error());

         
         $totalasig= $numasig + 1;         
         $query_updateasig = "UPDATE  `usuario` SET  `asignaciones` =  '" . $totalasig . "', fech_asig = '".$fecha_asignacion_item."' WHERE  `idusuario` = '" . $idingasig. "'";
         $listaasig = mysqli_query($conexion, $query_updateasig) or die(mysqli_error());        
       }
         END ASIGNACION */



        

      /*===============================*/
      /*ASIGNACION DE INFORMES*/

      //INGENIEROS LABORATORISTAS
      //Enriquesoto
      $enriqueSoto = 3;
      //Edgar
      $edgarSoto = 10;
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
      $sajhid= 27;

      //NORMAS

      // Obtener el usuario asignado a la norma 019
      /*
      if ($idnorma == 14 || $idnorma == 15 || $idnorma == 70 || $idnorma == 98) {
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

      */
        if ($idnorma == 66 || $idnorma == 67 || $idnorma == 68 || $idnorma == 69) {
        $idingasig = $sajhid; // Usuario asignado a normas de metal mecanica
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

      // Insertar en la tablaasignaciones
      date_default_timezone_set('America/Bogota');       
      $fecha_asignacion_item = date('Y-m-d h:i:s');

      $insertSQL = sprintf(
        "INSERT INTO licencia (id_bien, idusuario, fech_asignacion) VALUES (%s, %s, %s)",
        GetSQLValueString($idbienpost, "int"),
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


      $updateGoTo = "lista-apart.php";
      if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
      }
      header(sprintf("Location: %s", $updateGoTo));
    } else {
      echo '<script language=javascript>
alert("Verifique su número de INFORME ya se encuenta un registro igual")
history.go(-1);
</script>';
    }
  } else {
    echo "<script type=\"text/javascript\">
history.go(-1);
</script>";

    echo '<script language=javascript>
alert("El Registro no debe ir vacio")
</script>';
  }
}
//////////////////////////////////////////



?>
