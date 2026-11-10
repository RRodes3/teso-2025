<?php require_once('Connections/conexion.php');
if (!isset($_SESSION)) {
  session_start();
}
?>

<?php
if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
  {
    if (PHP_VERSION < 6) {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }
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
if ((isset($_POST["autorizado"])) && ($_POST["autorizado"] == "frmimv")) {


  $idbien = ['idbien'];

  $queryapart = sprintf("SELECT * FROM bien WHERE `idbien`=%s", GetSQLValueString($_POST['idbien'], "int"));
  $ResulQuery = mysqli_query($conexion, $queryapart) or die(mysqli_error());
  $row_informs = mysqli_fetch_assoc($ResulQuery);
  $Foundapart = mysqli_num_rows($ResulQuery);
  //echo $row_login['quienregis'].''.$quienregis1;


  if ($row_informs['apartado'] == 1) {


    $quienregis1 = $_SESSION['U_spnombres'] . ' ' . $_SESSION['U_spapellidos'];

    $LoginRS__query = sprintf("SELECT * FROM bien WHERE `idbien`=%s", GetSQLValueString($_POST['idbien'], "int"));
    $LoginRS = mysqli_query($conexion, $LoginRS__query) or die(mysqli_error());
    $row_login = mysqli_fetch_assoc($LoginRS);
    $loginFoundUser = mysqli_num_rows($LoginRS);
    //echo $row_login['quienregis'].''.$quienregis1;
    $validuser = FALSE;

    if ($row_login['quienregis'] == $quienregis1) {
      $validuser = TRUE;
    }

    if ($validuser == TRUE) {

      $passUser = $_SESSION['U_sppassus'];

      if ($_POST['password'] == $passUser) {
        header("Location: modif-apart.php?id=" . $_POST['idbien'] . "&metodo=dgdSFDASfdgfgrtFGFDG");
      } else {
        echo '<script language=javascript>
      alert("Error!, la contraseña es incorrecta")
      history.go(-1);
      </script>';
      }
    } else {
      echo '<script language=javascript>
    alert("Error!, El Numero de Informe ya fue APARTADO, no puedes ocupar el numero de informe, POR QUE?  ¡porque ya fue apartado!")
    history.go(-1);
    </script>';
    }
  } elseif ($_POST['apartado'] == NULL) {

    $iduseractive = $_SESSION['U_spidus'];
    $quienregis = $_SESSION['U_spnombres'] . ' ' . $_SESSION['U_spapellidos'];
    $apartar = 1;
    $updateSQL = sprintf(
      "UPDATE bien SET quienregis=%s, apartado=%s, refapartado=%s, idusuarioreg=%s WHERE idbien=%s",
      GetSQLValueString($quienregis, "text"),
      GetSQLValueString($apartar, "int"),
      GetSQLValueString($_POST['refapartado'], "text"),
      GetSQLValueString($iduseractive, "int"),
      GetSQLValueString($_POST['idbien'], "int")
    );
    $Result1 = mysqli_query($conexion, $updateSQL) or die(mysqli_error());
    $updateGoTo = "lista-apart.php";
    if (isset($_SERVER['QUERY_STRING'])) {
      $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
      $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
  } else {
    echo '<script language=javascript>
    alert("Error!, comunicarse con el area de sistemas ")
    history.go(-1);
    </script>';
  }
}

?>