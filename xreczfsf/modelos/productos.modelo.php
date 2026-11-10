<?php

require_once "conexion.php";


class ModeloProductosPdf{
	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/

	static public function mdlMostrarProductosPdf($tabla, $item, $valor, $orden){
		if($item != null){
			//$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY idbien DESC");
		//	$stmt = Conexion::conectar()->prepare("SELECT p.*, c.categoria FROM $tabla AS p INNER JOIN categorias AS c WHERE p.id_categoria = c.id AND p.$item = :$item");
		//$stmt = Conexion::conectar()->prepare("SELECT p.*, c.nombien FROM $tabla AS p INNER JOIN biencatalogo AS c WHERE p.codbiencat = c.codbiencat AND p.$item = :$item");
			//$stmt = Conexion::conectar()->prepare("SELECT p.*, c.codfamiliabien FROM $tabla AS p INNER JOIN familiabien AS c WHERE p.idfamiliabien = c.idfamiliabien AND p.$item = :$item");
			//$stmt = Conexion::conectar()->prepare("SELECT p.*, c.nombien, f.codfamiliabien FROM $tabla AS p JOIN biencatalogo AS c ON c.codbiencat = p.codbiencat JOIN familiabien AS f ON F.idfamiliabien = P.idfamiliabien WHERE p.codbiencat = c.codbiencat AND P.idfamiliabien = F.idfamiliabien AND p.$item = $item");
			$stmt = Conexion::conectar()->prepare("SELECT  bien.*,biencatalogo.nombien,  f.familiabien,  
			m.idmov, m.idoficinadest, m.foto, m.condicion, m.observacion, m.idusuarioreg,m.objetomov, m.doctransf, familiabien.codfamiliabien, proveedor.codproveedor, 
			proveedor.razonsocial, proveedor.personacontacto FROM $tabla 
			inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat 
			LEFT JOIN familiabien f ON bien.idfamiliabien = f.idfamiliabien left join movimiento m on m.idbien = bien.idbien and m.idmov = bien.idmovimiento 
			left join familiabien on familiabien.idfamiliabien = bien.idfamiliabien 
			left join proveedor on proveedor.idproveedor = bien.idproveedor 
			where  bien.$item = :$item");
									
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
		}else{
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY $orden DESC");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}
		$stmt -> close();
		$stmt = null;
	}
}

class ModeloProductos{
	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/
	static public function mdlMostrarProductos($tabla, $item, $valor, $orden){
		if($item != null){
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY idbien DESC");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
		}else{
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla where (codunicoinfor != '') and (ventas = 0) AND debaja IS NULL ORDER BY $orden DESC");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}
		$stmt -> close();
		$stmt = null;
	}

	/*=============================================
	REGISTRO DE PRODUCTO
	=============================================*/
	static public function mdlIngresarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_categoria, codigo, descripcion, imagen, stock, precio_compra, precio_venta) VALUES (:id_categoria, :codigo, :descripcion, :imagen, :stock, :precio_compra, :precio_venta)");

		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_compra", $datos["precio_compra"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	EDITAR PRODUCTO
	=============================================*/
	static public function mdlEditarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET codbiencat = :codbiencat, codbieninv = :codbieninv, codmarca = :codmarca, codmodelo = :codmodelo, conosin = :conosin, accesorios = :accesorios WHERE codbieninv = :codbieninv");

		$stmt->bindParam(":codbiencat", $datos["codbiencat"], PDO::PARAM_INT);
		$stmt->bindParam(":codbieninv", $datos["codbieninv"], PDO::PARAM_STR);
		$stmt->bindParam(":codmarca", $datos["codmarca"], PDO::PARAM_STR);
		$stmt->bindParam(":codmodelo", $datos["codmodelo"], PDO::PARAM_STR);
		$stmt->bindParam(":conosin", $datos["conosin"], PDO::PARAM_STR);
		$stmt->bindParam(":accesorios", $datos["accesorios"], PDO::PARAM_STR);
		
		if($stmt->execute()){
			return "ok";
		}else{
			return "error";
		}
		$stmt->close();
		$stmt = null;
	}


	/*=============================================
	BORRAR PRODUCTO
	=============================================*/

	static public function mdlEliminarProducto($tabla, $datos){
		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE idbien = :idbien");
		$stmt -> bindParam(":idbien", $datos, PDO::PARAM_INT);
		if($stmt -> execute()){
			return "ok";
		}else{
			return "error";	
		}

		$stmt -> close();
		$stmt = null;
	}

	/*=============================================
	ACTUALIZAR PRODUCTO
	=============================================*/

	static public function mdlActualizarProducto($tabla, $item1, $valor1, $valor){
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE idbien = :idbien");
		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":idbien", $valor, PDO::PARAM_STR);
		if($stmt -> execute()){
			return "ok";
		}else{
			return "error";	
		}
		$stmt -> close();
		$stmt = null;

	}


	/*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/	
	static public function mdlMostrarSumaVentas($tabla){
		$stmt = Conexion::conectar()->prepare("SELECT SUM(ventas) as total FROM $tabla");
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}


}