<?php
declare(strict_types=1);

if (!isset($_GET['fecha_inicio'], $_GET['fecha_fin'])) {
    exit('Parámetros incompletos');
}

$fechaInicio = $_GET['fecha_inicio'];
$fechaFin    = $_GET['fecha_fin'];

/* Validación */
if ($fechaInicio > $fechaFin) {
    exit('Rango de fechas inválido');
}

/* 🔌 Conexión BD */
$host = "localhost";
$db   = "bdteso2025";
$user = "root";
$pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    exit('Error conexión BD');
}

/* 📌 Consulta */
$sql = "SELECT  bien.*,
    biencatalogo.nombien,      
        familiabien.codfamiliabien, familiabien.familiabien, 
    m.idmov, m.foto, m.condicion, m.observacion, m.clasificacion, m.idusuarioreg,m.objetomov, m.doctransf, 

    proveedor.codproveedor, proveedor.razonsocial, proveedor.personacontacto, 
    licencia.estado, licencia.idusuario, 
    usuario.idusuario, usuario.nombres, usuario.apellidos 
FROM bien 
inner join biencatalogo on bien.codbiencat = biencatalogo.codbiencat 
left join movimiento m on m.idbien = bien.idbien 
left join familiabien on familiabien.idfamiliabien = bien.idfamiliabien 
left join licencia on licencia.id_bien = bien.idbien 
left join usuario on usuario.idusuario = licencia.idusuario
left join proveedor on proveedor.idproveedor = bien.idproveedor
        WHERE (bien.idfamiliabien) <> 87 AND (bien.codbiencat <> 5170) AND bien.fregistro BETWEEN :inicio AND :fin
        ORDER BY bien.codbieninv ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':inicio' => $fechaInicio,
    ':fin'    => $fechaFin
]);

$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$datos) {
    exit('No hay datos para ese rango');
}

/* ==========================
   📊 GENERAR EXCEL
========================== */
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=reporte.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "\xEF\xBB\xBF"; // BOM para UTF-8

echo "<table border='1'>
        
<tr style='background:#030E74;color:#fff'>
    <th>N°</th>
    <th>No. INFORME</th>
    <th>SEGUIMIENTO</th>
    <th>NORMA</th>
    <th>ITEM</th>
    <th>MARCA</th>
    <th>MODELO</th>
    <th>ANAQUEL</th>
    <th>SERIE</th>
    <th>BAJA</th>
    <th>TENSION</th>
    <th>MODIFICADO</th>
    <th>CONSTA/INCLUYE</th>
    <th>OBSERVACIONES</th>
    <th>PRECIO</th>
    <th>CODIGO CLIENTE</th>
    <th>RAZON SOCIAL</th>
    <th>FACTURAR A</th>
    <th>GESTOR</th>
    <th>REPRESENTANTE</th>
    <th>ESTATUS</th>
    <th>QUIEN REGISTRO</th>
    <th>INGLAB</th>
    <th>FECHA DE REGISTRO</th>
    <th>FECHA DE ENTREGA</th>
</tr>
";
$contador = 1;

foreach ($datos as $fila) {
    
   /* ESTATUS BAJA */
    if ($fila['debaja'] == 1) {
        $estadoBaja = 'BAJA';
    } elseif ($fila['suspendido'] == 1) {
        $estadoBaja = 'SUSPENDIDO';
    } else {
        $estadoBaja = 'ACTIVO';
    }

    /* ESTATUS LICENCIA */
    if (($fila['estado']) == "" || $fila['debaja'] == 1) {
        $estatus = 'BAJA';
    } elseif (($fila['estado']) == "" || is_null ($fila['debaja'])) {
        $estatus = 'Asignado recientemente';
    } else {
        $estatus = $fila['estado'];
    }

    echo "
    <tr>
        <td>{$contador}</td>
        <td>{$fila['codbieninv']}</td>
        <td>{$fila['segui']}</td>
        <td>{$fila['codfamiliabien']} {$fila['familiabien']}</td>
        <td>{$fila['nombien']}</td>
        <td>{$fila['codmarca']}</td>
        <td>{$fila['codmodelo']}</td>
        <td>{$fila['lugar']}</td>
        <td>{$fila['serie']}</td>
        <td>{$estadoBaja}</td>
        <td>{$fila['clasificacion']}</td>
        <td>{$fila['modificado']}</td>
        <td>{$fila['caracteristica']}</td>
        <td>{$fila['observaciones']}</td>
        <td>{$fila['valor']}</td>
        <td>{$fila['codproveedor']}</td>
        <td>{$fila['razonsocial']}</td>
        <td>{$fila['facturara']}</td>
        <td>{$fila['codcolor']}</td>
        <td>{$fila['personacontacto']}</td>
        <td>{$estatus}</td>
        <td>{$fila['quienregis']}</td>
        <td>{$fila['nombres']} {$fila['apellidos']}</td>
        <td>{$fila['fechaadq']}</td>
        <td>{$fila['tiempovida']}</td>
    </tr>";
        $contador++;
}

echo "</table>";
exit;
