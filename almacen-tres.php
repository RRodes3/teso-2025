<?php 
if (!isset($_SESSION)) {
  session_start();
} 
if ( isset($_SESSION['U_spprivilegio']) and in_array("a_alma", $_SESSION['U_spprivilegio'])) { 	
require_once('Connections/conexion.php');
$menuactivo ='almacen';
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
$query_lista = "SELECT  bien.*,biencatalogo.nombien, m.idmov, m.foto, m.condicion, m.observacion, 
      m.idusuarioreg,m.objetomov, m.doctransf,
      familiabien.codfamiliabien, proveedor.codproveedor, proveedor.razonsocial, proveedor.personacontacto,  usuario.nombres, usuario.apellidos, licencia.idusuario FROM bien 
      inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat 
      left join movimiento m on m.idbien = bien.idbien and m.idmov = bien.idmovimiento       
      left join familiabien on familiabien.idfamiliabien = bien.idfamiliabien 
      left join licencia on licencia.id_bien = bien.idbien 
      left join usuario on usuario.idusuario = licencia.idusuario
      left join proveedor on proveedor.idproveedor = bien.idproveedor where bien.idbien between 17001 and 22000 and (proveedor.codproveedor <> '--' )";
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$row_lista = mysqli_fetch_assoc($lista);
$totalRows_lista = mysqli_num_rows($lista);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sistema Teso</title>
  <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
  <meta name="author" content="Eduardo Sandoval">
  <!--Desarrollado por: Eduardo Sandoval -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <link rel="stylesheet" href="estilos/jquery.dataTables.min.css">
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <header class="main-header">
      <?php include('header.php'); ?>
    </header>
    <?php 
    $activarmenu='registrar-fungible';
    include('aside.php');
    ?>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <i class="fa fa-cubes"></i>Almacen Teso
        </h1>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-body">              
                  <div class="table-responsive">
                    <table id="servicios" width="100%" class="table table-bordered table-striped dt-responsive tablas">
                      <thead>
                        <tr class="titulotabla">
                          <td width="2%">N.</td>
                          <td width="6%">No. INFORME</td>
                          <td width="20%">EMPRESA</td>                          
                          <td width="25%">CARACTERISTICA</td>                                          
                          <td width="15%">ACCESORIOS</td>
                          <td width="10%">ENTREGA MUESTRA A:</td>
                          <td width="15%">OBSERVACIONES</td>
                          <td width="5%">LUGAR</td>
                          <td>ESTATUS ENTREGA</td>
                          <td width="2%">-</td>
                        </tr>
                      </thead>
                      <?php if ($totalRows_lista > 0){ $i = 0 ; do {  $i += 1; ?>
                      <tr <?php if($i % 2){echo 'filapar';}else{ echo 'filaimpar';} ?>>
                        <th class="active"><?php echo $i; ?></th>                        
                        <td><?php echo $row_lista['codbieninv']; ?></td>
                        <td><strong>Empresa: </strong><?php echo $row_lista['razonsocial']; ?> <br>
                         <strong>Representante: </strong><?php echo $row_lista['personacontacto']; ?> <br>
                         <strong>Gestor: </strong><?php echo $row_lista['codcolor']; ?> 
                        </td>
                        <td>
                          <strong>Item: </strong><?php echo $row_lista['nombien']; ?> <br>
                          <strong>Marca: </strong><?php echo $row_lista['codmarca']; ?><br>
                          <strong>Modelo: </strong> <?php echo $row_lista['codmodelo']; ?> <br>
                          <strong>Norma: </strong> <?php echo $row_lista['codfamiliabien']; ?> <br>
                          <strong>Categoria: </strong><?php echo $row_lista['condicion']; ?> <br>
                          <strong>Fecha de recepcion de muestra: </strong><?php echo $row_lista['fechaadq']; ?><br>
                          <strong>Estado: </strong><?php if ($row_lista['debaja'] == '1') {
                                  echo ' <span class="label label-danger  " style="font-size:14px">DE BAJA</span> ';
                                } else {
                                  echo ' <span class="label label-success " style="font-size:14px">ACTIVO</span> ';
                                } ?>
                        </td>                      
                        <td><?php echo $row_lista['accesorios']; ?></td>
                        <td><?php echo $row_lista['nombres'].' '.$row_lista['apellidos']; ?></td> 
                        <td> <?php echo $row_lista['observaciones']; ?></td>
                        <td> <?php echo $row_lista['lugar']; ?></td>    
                                                    <td><?php
                                if ($row_lista['entrega'] == '1') {
                                  echo ' <span class="label label-success " style="font-size:14px">ENTREGADO</span> ';
                                } else {
                                  echo ' <span class="label label-danger  " style="font-size:14px">NO ENTREGADO</span> ';
                                }
                                ?></td>                                         
                              <td style=" padding:0px;"><select onChange="window.location.href=this.value"
                          style="width:35px; height:27px; padding:0px;" class="btn-warning">
                          <option>✔</option>
                          <option
                            value="modificar-almacen-tres.php?codigo=<?php echo $row_lista['idbien']; ?>">
                            ✔ Editar inf. del informe</option>  
                            <option <?php if ($row_lista['entrega'] == '1') {
                                          echo "disabled";
                                        } else {
                                          echo "enabled";
                                        } ?>
                                  value="entregamuestra.php?codigo=<?php echo $row_lista['idbien']; ?>">
                                  ✔ Entregar Muestra</option>                        
                          <option
                          value="xreczfsf/extensiones/tcpdf/pdf/etiqueta-almacen.php?id=<?php echo $row_lista['idbien']; ?>&bien=<?php echo $row_lista['nombien']; ?> ">
                            ✔ Imprimir Etiqueta</option>                          
                        </select>                                    
                                <?php						
                                  $fc = 0;						
                                  ?>
                        </td>
                     </tr>
                      <?php } while ($row_lista = mysqli_fetch_assoc($lista));} ?>
                    </table>
                  </div>                                          
              </div>              
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
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" language="javascript" class="init">
  $(document).ready(function() {
    $('#servicios').DataTable();
  });
  </script>
  <script>
  $(function() {
    $(".select2").select2();
  });
  </script>
  <script type="text/javascript" language="javascript" class="init">
  $(document).ready(function() {
    $('#example').DataTable();
  });
  jQuery(document).ready(function() {
    $(".oculto").hide();
    $(".inf").click(function() {
      var nodo = $(this).attr("href");
      if ($(nodo).is(":visible")) {
        $(nodo).hide();
        return false;
      } else {
        $(".oculto").hide("slow");
        $(nodo).fadeToggle("fast");
        return false;
      }
    });
  });
  </script>
</body>
</html>
<?php }else{
header("Location: pagina-restringida.php");
}?>