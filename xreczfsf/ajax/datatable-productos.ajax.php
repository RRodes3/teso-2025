<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";
require_once "../controladores/clientes.controlador.php";
require_once "../modelos/clientes.modelo.php";
require_once "../controladores/categorias.controlador.php";
require_once "../modelos/categorias.modelo.php";
require_once "../controladores/normas.controlador.php";
require_once "../modelos/normas.modelo.php";


class TablaProductos{
 	/*=============================================
 	 MOSTRAR LA TABLA DE PRODUCTOS
  	=============================================*/ 
	public function mostrarTablaProductos(){
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
 	 		TRAEMOS LA CLIENTE
  			=============================================*/ 
			  $itemCliente = "idproveedor";
			  $valorCliente = $productos[$i]["idproveedor"];
			  $respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);

			  	  	/*=============================================
 	 		TRAEMOS LA NORMA
  			=============================================*/ 
		
		  	$item = "idfamiliabien";
		  	$valor = $productos[$i]["idfamiliabien"];

		  	$normas = ControladorNormas::ctrMostrarNormas($item, $valor);

		  	/*=============================================
 	 		TRAEMOS LA Item
  			=============================================*/ 

		  	$item = "codbiencat";
		  	$valor = $productos[$i]["codbiencat"];

		  	$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);

		  	/*=============================================
 	 		STOCK
  			=============================================*/ 

  //			if($productos[$i]["accesorios"] == 0  ){
	//			$stock = "<button class='btn btn-danger'>".$productos[$i]["accesorios"]."</button>";
	//		}else if ($productos[$i]["accesorios"] <> 0  ){
	//			$stock = "<button class='btn btn-success'>".$productos[$i]["accesorios"]."</button>";
	//		}else{
//
//				$stock = "<button class='btn btn-danger'>".$productos[$i]["accesorios"]."</button>";

//			} 
  


	//		  if($productos["estado"] != 0){
	//			echo '<td><button class="btn btn-success btn-xs btnActivar" idProducto="'.$productos[$i]["idbien"].'" estadoProducto="0">Activado</button></td>';
	//		  }else{
	//			echo '<td><button class="btn btn-danger btn-xs btnActivar" idProducto="'.$productos[$i]["idbien"].'" estadoProducto="1">Desactivado</button></td>';
	//		  } 
		  	/*=============================================
 	 		TRAEMOS LAS ACCIONES   				esto va despues de fregistro 
			  =============================================*/ 
			  if(isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Especial"){
				$botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarProducto' idProducto='".$productos[$i]["idbien"]."' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button></div>"; 
			}else{
				 $botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarProducto' idProducto='".$productos[$i]["idbien"]."' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnEliminarProductoo' idProducto='".$productos[$i]["idbien"]."'><i class='fa fa-times'></i></button></div>"; 
			}
		  	$datosJson .='[
				  "'.($i+1).'",
				  "'.$productos[$i]["codbieninv"].'",				
				  "'.$respuestaCliente["razonsocial"].'",
				  "'.$respuestaCliente["personacontacto"].'",
				  "'.$normas["codfamiliabien"].'",
				  "'.$categorias["nombien"].'",
				  "'.$productos[$i]["codmarca"].'",
				  "'.$productos[$i]["codmodelo"].'",
				  "'.$productos[$i]["accesorios"].'",
				  "'.$productos[$i]["conosin"].'",
				  "'.$productos[$i]["fregistro"].'",

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
$activarProductos = new TablaProductos();
$activarProductos -> mostrarTablaProductos();

