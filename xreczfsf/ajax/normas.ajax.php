<?php

require_once "../controladores/normas.controlador.php";
require_once "../modelos/normas.modelo.php";

class AjaxNormas{

	/*=============================================
	EDITAR CATEGORÍA
	=============================================*/	

	public $idCategoria;

	public function ajaxEditarNormas(){

		$item = "idfamiliabien";
		$valor = $this->idfamiliabien;

		$respuesta = ControladorNormas::ctrMostrarNormas($item, $valor);

		echo json_encode($respuesta);

	}
}

/*=============================================
EDITAR CATEGORÍA
=============================================*/	
if(isset($_POST["idfamiliabien"])){

	$categoria = new AjaxNormas();
	$categoria -> idfamiliabien = $_POST["idfamiliabien"];
	$categoria -> ajaxEditarNormas();
}
