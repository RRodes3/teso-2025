<?php
require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";
require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";
require_once "../controladores/categorias.controlador.php";
require_once "../modelos/categorias.modelo.php";
require_once "../controladores/normas.controlador.php";
require_once "../modelos/normas.modelo.php";
require_once "../controladores/clientes.controlador.php";
require_once "../modelos/clientes.modelo.php";

class AjaxVentas{

	/*=============================================
	EDITAR Ventas
	=============================================*/	
    

	public $idVenta;

	public function ajaxEditarVenta(){

		$item = "codigo";
		$valor = $this->idVenta;

		$respuesta = ControladorVentas::ctrMostrarVentas($item, $valor);

		echo json_encode($respuesta);

	}
}

/*=============================================
EDITAR CATEGORÍA
=============================================*/	
if(isset($_POST["idVenta"])){

	$venta = new AjaxVentas();
	$venta -> idVenta = $_POST["idVenta"];
	$venta -> ajaxEditarVenta();
}
