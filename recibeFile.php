<?php

session_start();
include('connections/conexion.php');


$noInforme = $_POST['noInforme'];
$referencia = $_POST['referencia'];

$empresa = $_POST['idempresa'];
$muestra = $_POST['idproducto'];
$idusuario = $_POST['idusuario'];
$observaciones = $_POST['observaciones'];

$fecha_ingreso = date('Y-m-d H:i:s');

//$directorio_destino = "files_muestras";
$directorio_destino = "\\\\192.168.1.5\\server-photos\\files_muestras";
$totalArchivos = count($_FILES['fotos_muestra']['name']);

// Crear el directorio si no existe
if (!file_exists($directorio_destino)) {
    mkdir($directorio_destino, 0777, true);
}

$miDir = opendir($directorio_destino); //Habro el directorio
if (isset($_POST['submit']) && count($_FILES['fotos_muestra']['name']) > 0) {
    foreach ($_FILES['fotos_muestra']['name'] as $i => $name) {
        //strlen método de php pues devuelve la longitud de una cadena
        if (strlen($_FILES['fotos_muestra']['name'][$i]) > 1) {
            $fileName          = $_FILES['fotos_muestra']['name'][$i];
            $sourceFoto        = $_FILES['fotos_muestra']['tmp_name'][$i];
            $tamanoFoto        = $_FILES["fotos_muestra"]['size'][$i];
            $restricciontamano = "500"; //MB
            if ((($tamanoFoto / 1024) / 1024) <= $restricciontamano) {
                /**Renombrando cada foto que llega desde el formulario */
                $nuevoNombreFile    = substr(md5(uniqid(rand())), 0, 4);
                $extension_foto     = pathinfo($fileName, PATHINFO_EXTENSION);

                if(!empty($noInforme)){
                    $nameRegistro = $noInforme;
                } else {
                    $nameRegistro = $referencia;
                }
//                $nombreFoto         = $nuevoNombreFile . '_' . $referencia . '.' . $extension_foto;
                $nombreFoto         = $nuevoNombreFile . '_' . $nameRegistro . '.' . $extension_foto;
                
                $resultadoFotos     = $directorio_destino . '\\' . $nombreFoto;
                // Mover archivo a una ubicación permanente
                move_uploaded_file($sourceFoto, $resultadoFotos);
                // Insertar información del archivo en la base de datos
                $sql = "INSERT INTO fotos_muestra (noInforme, referencia, namefoto, id_cliente, iditem, iduser, observaciones, fecha_ingreso) VALUES ('{$noInforme}', '{$referencia}', '{$nombreFoto}', '{$empresa}', '{$muestra}', '{$idusuario}', '{$observaciones}', '{$fecha_ingreso}')";
                mysqli_query($conexion, $sql);
                                
                //Mensaje de exito                
            } else {
                echo '<p style="color:red">Existe una foto que supera el peso Maximo de ' . $tamanoFoto . '</p>';
            }
        }
    }
} else {
    echo '<p style="color:red">No se ha seleccionado ninguna foto</p>';
}
// Cerrar conexión a la base de datos
mysqli_close($conexion);
// Redirigir a la página de inicio$mensaje = urlencode("Fotos registradas correctamente");
$mensaje = urlencode("ok"); // puede ser 'success', '1', etc.
header("Location: recep-muestras.php?msg=$mensaje");
exit();
