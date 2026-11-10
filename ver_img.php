<?php
// Nombre recibido desde la URL
$foto = isset($_GET['foto']) ? $_GET['foto'] : null;

if (!$foto) {
    exit("Imagen no especificada");
}

// Ruta de tu carpeta compartida
$ruta = "\\\\192.168.1.5\\server-photos\\files_muestras\\" . $foto;

// Si no existe, mostrar mensaje
if (!file_exists($ruta)) {
    header("HTTP/1.0 404 Not Found");
    exit("Imagen no encontrada");
}

// Detecta el tipo de imagen automáticamente (IMPORTANTE)
$mime = mime_content_type($ruta);
header("Content-Type: $mime");

// Envía la imagen para que el navegador pueda visualizarla
readfile($ruta);
exit;
?>
