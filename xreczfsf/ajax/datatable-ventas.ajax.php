<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";
require_once "../controladores/categorias.controlador.php";
require_once "../modelos/categorias.modelo.php";
require_once "../controladores/normas.controlador.php";
require_once "../modelos/normas.modelo.php";

class TablaProductosVentas{
 	/*=============================================
 	 MOSTRAR LA TABLA DE PRODUCTOS
  	=============================================*/ 
	public function mostrarTablaProductosVentas(){
		$item = null;
    	$valor = null;
    	$orden = "idbien";
  		$productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
  		if(count($productos) == 0){
  			echo '{"data": []}';
		  	return;
  		}	
  		$datosJson = '{
		  "data": [';
		  for($i = 0; $i < count($productos); $i++){
		  	/*=============================================
 	 		TRAEMOS LA categoria
  			=============================================*/ 
		
		  	$item = "codbiencat";
		  	$valor = $productos[$i]["codbiencat"];

			  $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
			  
			  	  	/*=============================================
 	 		TRAEMOS LA NORMA
  			=============================================*/ 
		
		  	$item = "idfamiliabien";
		  	$valor = $productos[$i]["idfamiliabien"];

		  	$normas = ControladorNormas::ctrMostrarNormas($item, $valor);


		  	/*=============================================
 	 		STOCK
  			============================================*/ 
  			if($productos[$i]["stock"] < 1){
  				$stock = "<button class='btn btn-danger'>Gen.</button>";
  			}else if($productos[$i]["debaja"] == 1){
				$stock = "<button class='btn btn-danger'>de baja.</button>";
  			}else{
				$stock = "<button class='btn btn-success'>Pend.</button>";
			  }


		  	/*=============================================
 	 		TRAEMOS LAS ACCIONES
  			=============================================*/ 

		  	$botones =  "<div class='btn-group'><button class='btn btn-primary agregarProducto recuperarBoton' idProducto='".$productos[$i]["idbien"]."'>Agregar</button></div>"; 

		  	$datosJson .='[
				  "'.($i+1).'",
				  "'.$productos[$i]["codbieninv"].'",
				  "'.$normas["codfamiliabien"].'",				  				
			      "'.$productos[$i]["codmarca"].'",
				  "'.$productos[$i]["codmodelo"].'",
				  "'.$productos[$i]["valor"].'",
			      "'.$stock.'",
			      "'.$botones.'"
			    ],';
		  }
		  $datosJson = substr($datosJson, 0, -1);
		 $datosJson .=   '] 
		 }';
		echo $datosJson;
	}
}

/*=============================================
ACTIVAR TABLA DE PRODUCTOS
=============================================*/ 
$activarProductosVentas = new TablaProductosVentas();
$activarProductosVentas -> mostrarTablaProductosVentas();

