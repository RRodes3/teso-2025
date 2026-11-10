<?php require_once('Connections/conexion.php');
$menuactivo ='movimiento';
if (!isset($_SESSION)) {
  session_start();
} ?>
<?php
$idbien = '';

$metodo = '';
if(isset( $_GET['metodo'])){ $metodo = $_GET['metodo'];}

if(isset( $_GET['id'])){ $idbien = $_GET['id'];}


$query_lista = "SELECT bien.*,biencatalogo.nombien, proveedor.codproveedor, proveedor.razonsocial, familiabien.codfamiliabien, familiabien.familiabien FROM bien inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat  inner join proveedor on bien.idproveedor = proveedor.idproveedor inner join familiabien on bien.idfamiliabien = familiabien.idfamiliabien  WHERE bien.idbien ='$idbien'";
$lista = mysqli_query($conexion, $query_lista) or die(mysqli_error());
$row_lista=mysqli_fetch_array($lista);
$totalRows_lista = mysqli_num_rows($lista);  

$query_listamov = "SELECT * FROM movimiento where idbien = '".$row_lista['idbien']."' order by idmov desc limit 1 ";
$listamov = mysqli_query($conexion, $query_listamov) or die(mysqli_error());
$row_listamov=mysqli_fetch_array($listamov);
$totalRows_listamov = mysqli_num_rows($listamov);  


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
     $codigounico= generate_string($permitted_chars, 20);
     


 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sistema Teso</title>
  <link rel="shortcut icon" href="img/icono.png" type="image/x-icon">
  <meta name="author"   content="Eduardo Sandoval">
  <meta name="viewport" content="width=device-width, initial-scale=1">   
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="estilos/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
 <script src="includes/carganormal.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <?php include('header.php'); ?>
    </header>
    <?php 
    $activarmenu='modificar-bien';
    include('aside.php');
    ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <span class="glyphicon glyphicon-edit"></span> Modificar información del Informe
        </h1>
      </section>

      <section class="content">

       

        <div class="row">
          <div class="col-md-7">
            <div class="box box-info">
    
	<form class="form-horizontal" method="post" enctype='multipart/form-data' name="form1" action="proce_moditem.php">
     <div class="box-body"> 
           
     <div class="form-group">
        <label for="id" class="col-sm-3 control-label"> Cliente:</label>
        <div class="col-sm-9">
         <select  required   class="form-control select2"  name="idproveedor" id="idproveedor">
                       <?php 
					    $rsprov=mysqli_query($conexion, "SELECT * FROM proveedor order by razonsocial");
                      while($prov=mysqli_fetch_array($rsprov))
						{
							
						?>
            <OPTION VALUE="<?php echo $prov['idproveedor'];?>" <?php  if( $prov['idproveedor'] == $row_lista['idproveedor']){ echo 'selected';} ?> ><?php echo $prov['codproveedor'].' - '.$prov['razonsocial'];?></OPTION>
            <?php 
						}
						?>
                       </select>
       </div>
      </div>
       
       <div class="form-group">
        <label for="id" class="col-sm-3 control-label">Norma:</label>
        <div class="col-sm-9">
        <select  autofocus  class="form-control select2"  name="idfamiliabien" id="idfamiliabien" >
                      
                        <?php 
					    $rs=mysqli_query($conexion, "SELECT * FROM familiabien ORDER BY familiabien ");
                      while($off=mysqli_fetch_array($rs))
						{
							
						?>
                        <OPTION VALUE=<?php echo $off['idfamiliabien'];?> <?php  if( $off['idfamiliabien'] == $row_lista['idfamiliabien']){ echo 'selected';} ?> ><?php echo $off['codfamiliabien'].' - '.$off['familiabien'];?></OPTION>
                        <?php 
						}
						?>
                      </select></div>
      </div>
      
       <div class="form-group">
        <label for="id" class="col-sm-3 control-label">No. Teso:</label>
        <div class="col-sm-9">
                       
                      <input   type="hidden"  name="idbien" value="<?php echo $row_lista['idbien'];?>" > 
                      <div id="DivReporte"><input class="form-control" required readonly type="text" name="codbieninv"  required id="codbieninv" value="<?php echo $row_lista['codbieninv'];?>" ></div>
                      </div>
      </div>

      <div class="form-group">
        <label for="id" class="col-sm-3 control-label">El informe es de seguimiento:</label>
        <div class="col-sm-9">
                       
                      <input   type="hidden"  name="segui" value="<?php echo $row_lista['segui'];?>" > 
                      <div id="DivReporte"><input class="form-control"   type="text" name="segui" id="segui" value="<?php echo $row_lista['segui'];?>" ></div>
                      </div>
      </div>


       <div class="form-group">
        <label for="id" class="col-sm-3 control-label">Nombre del item:</label>
        <div class="col-sm-9">
      <select  class="form-control select2" required   name="codbiencat" id="codbiencat">
                       <?php 
					    $rs=mysqli_query($conexion, "SELECT * FROM biencatalogo ORDER BY nombien ");
                      while($off=mysqli_fetch_array($rs))
						{
							
						?>
  <OPTION VALUE="<?php echo $off['codbiencat'];?>" <?php  if( $off['codbiencat'] == $row_lista['codbiencat']){ echo 'selected';} ?>  ><?php echo $off['nombien'];?></OPTION>
   <?php 
						}
						?>
                       </select>
    	 </div>
      </div>
      
       <div class="form-group">
        <label for="id" class="col-sm-3 control-label">Marca:</label>
        <div class="col-sm-9">
        <input type="text"  class="form-control"  name="codmarca" value="<?php echo htmlentities($row_lista['codmarca'], ENT_COMPAT, ''); ?>" size="32">        
     </div>
      </div>

      <div class="form-group">
        <label for="id" class="col-sm-3 control-label">Modelo:</label>
        <div class="col-sm-9">
         <input type="text"  class="form-control"  name="codmodelo" value="<?php echo htmlentities($row_lista['codmodelo'], ENT_COMPAT, ''); ?>" size="32"> 
       
        </div>
      </div>
      
       <div class="form-group">
        <label for="id" class="col-sm-3 control-label">Gestor:</label>
        <div class="col-sm-9">   
        <input type="text"  class="form-control"  name="codcolor" value="<?php echo htmlentities($row_lista['codcolor'], ENT_COMPAT, ''); ?>" size="32">  
                      </div>
      </div>
      <div class="form-group">
        <label for="id" class="col-sm-3 control-label">Facturar a:</label>
        <div class="col-sm-9">   
        <input type="text"  class="form-control"  name="facturara" value="<?php echo htmlentities($row_lista['facturara'], ENT_COMPAT, ''); ?>" size="32">  
        </div>
      </div>
          
       <div class="form-group">
        <label for="id" class="col-sm-3 control-label">Consta / Incluye:</label>
        <div class="col-sm-9">
        <input type="text"  class="form-control"  name="caracteristica" value="<?php echo htmlentities($row_lista['caracteristica'], ENT_COMPAT, ''); ?>" size="32">
        </div>
       </div>
      
       <div class="form-group">
        <label for="id" class="col-sm-3 control-label">Serie:</label>
        <div class="col-sm-9">
        <input type="text"  class="form-control" name="serie" value="<?php echo htmlentities($row_lista['serie'], ENT_COMPAT, ''); ?>" size="32"></td>
   		</div>
      </div>
            
       <div class="form-group">
        <label for="id" class="col-sm-3 control-label"> Fecha de ingreso:</label>
        <div class="col-sm-9">
      <input type="date"  class="form-control" name="fechaadq" value="<?php echo htmlentities($row_lista['fechaadq'], ENT_COMPAT, ''); ?>" size="32">
      </div>
      </div>
      
       <div class="form-group">
        <label for="id" class="col-sm-3 control-label">Precio:</label>
        <div class="col-sm-9">
       <input type="number"  class="form-control"  step="any" name="valor" value="<?php echo htmlentities($row_lista['valor'], ENT_COMPAT, ''); ?>" size="32">
      </div>
      </div>
      
      <div class="form-group">
        <label for="id" class="col-sm-3 control-label">Clasificacion:</label>
        <div class="col-sm-9">
       <input type="text"  class="form-control"  step="any" name="clasificacion" value="<?php echo htmlentities($row_listamov['clasificacion'], ENT_COMPAT, ''); ?>" size="32">
      </div>
      </div>

       <div class="form-group">
        <label for="id" class="col-sm-3 control-label">Fecha de entrega:</label>
        <div class="col-sm-9">
        <input type="date"  class="form-control"   required  name="tiempovida" value="<?php echo htmlentities($row_lista['tiempovida'], ENT_COMPAT, ''); ?>" size="32">
      </div>
      </div>   
       


      <div class="form-group">
                          <label for="id" class="col-sm-3 control-label">CODIGO UNICO DE INFORME:</label>
                          <div class="col-sm-9">
                            <input class="form-control" required type="text" name="codunicoinfor" value="<?php echo htmlentities($row_lista['codunicoinfor'], ENT_COMPAT, ''); ?>">
                            <?php echo $codigounico ?>
                          </div>
                        </div>  
        <input type="hidden" name="MM_update" value="form1">      
	    </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <a href="javascript:history.back(-1);"class="btn btn-default" title="Cancelar">CANCELAR</a>
        <button type="submit" class="btn btn-info pull-right" title="Grabar registro">Actualizar</button>
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
  $(function () {
    $(".select2").select2();    
  });
</script>
</body>
</html>
