
<?php
require_once "xreczfsf/controladores/normas.controlador.php";
require_once "xreczfsf/modelos/normas.modelo.php";
require_once "xreczfsf/controladores/productos.controlador.php";
require_once "xreczfsf/modelos/productos.modelo.php";
require_once "xreczfsf/controladores/clientes.controlador.php";
require_once "xreczfsf/modelos/clientes.modelo.php";
require_once "xreczfsf/controladores/categorias.controlador.php";
require_once "xreczfsf/modelos/categorias.modelo.php";
require_once "xreczfsf/controladores/ventas.controlador.php";
require_once "xreczfsf/modelos/ventas.modelo.php";
?>
<?php

$item = null;
$valor = null;
$orden = "id";

$ventas = ControladorVentas::ctrSumaTotalVentas();

$normas = ControladorNormas::ctrMostrarNormas($item, $valor);
$totalNormas = count($normas);

$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
$totalCategorias = count($categorias);

$clientes = ControladorClientes::ctrMostrarClientes($item, $valor);
$totalClientes = count($clientes);

$itempro = null;
$valorpro = null;
$ordenpro = "idbien";
$productos = ControladorProductos::ctrMostrarProductos($itempro, $valorpro, $ordenpro);
$totalProductos = count($productos);

?>



<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-aqua">
    
    <div class="inner">
    <p>Catalogo de Normas</p>
    <h3><?php echo number_format($totalNormas); ?></h3>


    
    </div>
    
    <div class="icon">
      
      <i class="ion ion-social-usd"></i>
    
    </div>
    


  </div>

</div>

<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-green">
    
    <div class="inner">
    <p>Catalogo de nombre de productos</p>
      <h3><?php echo number_format($totalCategorias); ?></h3>
      
    </div>    
    <div class="icon">    
      <i class="ion ion-clipboard"></i>    
    </div>
  </div>

</div>

<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-yellow">
    
    <div class="inner">
    <p>Catalogo de Clientes</p>
      <h3><?php echo number_format($totalClientes); ?></h3>


  
    </div>
    
    <div class="icon">
    
      <i class="ion ion-person-add"></i>
    
    </div>


  </div>

</div>

<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-red">
  
    <div class="inner">
    <p>Registros </p>
      <h3><?php echo number_format($totalProductos); ?></h3>


    
    </div>
    
    <div class="icon">
      
      <i class="ion ion-ios-cart"></i>
    
    </div>
    


  </div>

</div>