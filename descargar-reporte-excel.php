<?php
set_time_limit(0);
ini_set('memory_limit', '-1');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =======================
   VALIDAR PERMISOS
======================= */
if (
    !isset($_SESSION['U_spprivilegio']) ||
    !in_array("r_reportes", $_SESSION['U_spprivilegio'])
) {
    exit('Acceso denegado');
}

/* =======================
   CONEXIÓN
======================= */
require_once 'Connections/conexion.php';

/* =======================
   HEADERS EXCEL (STREAM)
======================= */
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=Lista-general-Tesos.xls");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

/* =======================
   QUERY (TODOS LOS CAMPOS)
======================= */
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

WHERE (bien.idfamiliabien) <> 87 AND (bien.codbiencat <> 5170);

";

/* 🔥 STREAM MYSQL */
$result = mysqli_query($conexion, $sql, MYSQLI_USE_RESULT);
if (!$result) {
    die(mysqli_error($conexion));
}

/* =======================
   CABECERA TABLA
======================= */
echo "<table border='1'>";

/* TÍTULO */
echo "
<tr>
    <td colspan='25' align='center'>
        <strong>LABORATORIO TESO DE MEXICO S.A. DE C.V.</strong>
    </td>
</tr>";

/* ENCABEZADOS */
echo "
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

/* =======================
   FILAS (STREAM)
======================= */
$contador = 1;

while ($row = mysqli_fetch_assoc($result)) {

    /* ESTATUS BAJA */
    if ($row['debaja'] == 1) {
        $estadoBaja = 'BAJA';
    } elseif ($row['suspendido'] == 1) {
        $estadoBaja = 'SUSPENDIDO';
    } else {
        $estadoBaja = 'ACTIVO';
    }

    /* ESTATUS LICENCIA */
    if (trim($row['estado']) == "" && $row['debaja'] == 1) {
        $estatus = 'BAJA';
    } elseif (trim($row['estado']) == "" && is_null($row['debaja'])) {
        $estatus = 'Asignado recientemente';
    } else {
        $estatus = $row['estado'];
    }

    echo "
    <tr>
        <td>{$contador}</td>
        <td>{$row['codbieninv']}</td>
        <td>{$row['segui']}</td>
        <td>{$row['codfamiliabien']} {$row['familiabien']}</td>
        <td>{$row['nombien']}</td>
        <td>{$row['codmarca']}</td>
        <td>{$row['codmodelo']}</td>
        <td>{$row['lugar']}</td>
        <td>{$row['serie']}</td>
        <td>{$estadoBaja}</td>
        <td>{$row['clasificacion']}</td>
        <td>{$row['modificado']}</td>
        <td>{$row['caracteristica']}</td>
        <td>{$row['observaciones']}</td>
        <td>{$row['valor']}</td>
        <td>{$row['codproveedor']}</td>
        <td>{$row['razonsocial']}</td>
        <td>{$row['facturara']}</td>
        <td>{$row['codcolor']}</td>
        <td>{$row['personacontacto']}</td>
        <td>{$estatus}</td>
        <td>{$row['quienregis']}</td>
        <td>{$row['nombres']} {$row['apellidos']}</td>
        <td>{$row['fechaadq']}</td>
        <td>{$row['tiempovida']}</td>
    </tr>
    ";

    $contador++;

    /* 🔥 liberar buffer cada 500 filas */
    if ($contador % 500 === 0) {
        ob_flush();
        flush();
    }
}

echo "</table>";

mysqli_free_result($result);
mysqli_close($conexion);
exit;
