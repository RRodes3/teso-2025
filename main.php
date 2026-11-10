<?php

function paginador_tablas($pagina, $Npaginas, $url, $botones){

      if ($Npaginas <= 1) {
        return "";
    }


    $html = '';
    $html .= '
    <style>
    .pagination-wrapper { text-align: center; } /* Centrar los botones */

    .pagination { 
    display: inline-block; 
    margin: 10px 0; 
    padding-left: 0; 
    }

    .pagination > li { 
    display: inline-block; 
    float: none; 
    }

    .pagination > li > a, .pagination > li > span {
        border-radius: 50% !important;
        width: 38px;
        height: 38px;
        line-height: 36px;
        text-align: center;
        padding: 0;
        margin: 0 4px;
        display: inline-block;
    }
    .pagination .disabled span { 
    opacity: 0.6; 
    }
    </style>';

    $html .= '<div class="pagination-wrapper"><nav aria-label="Paginación"><ul class="pagination">';

    // Anterior
    if($pagina <= 1){
        $html .= '<li class="disabled"><span aria-hidden="true">Atras</span></li>';
    } else {
        $html .= '<li><a href="'.$url.($pagina-1).'" aria-label="Anterior"><span aria-hidden="true">Atras</span></a></li>';
    }

    // Calcular rango de botones centrado
    $half = floor($botones / 2);
    $start = max(1, $pagina - $half);
    $end = min($Npaginas, $start + $botones - 1);
    if (($end - $start + 1) < $botones) {
        $start = max(1, $end - $botones + 1);
    }

    // Mostrar primer si no está en rango
    if ($start > 1) {
        $html .= '<li><a href="'.$url.'1">1</a></li>';
        if ($start > 2) {
            $html .= '<li class="disabled"><span>…</span></li>';
        }
    }

    // Botones principales
    for($i = $start; $i <= $end; $i++){
        if($i == $pagina){
            $html .= '<li class="active"><span>'.$i.' <span class="sr-only">(actual)</span></span></li>';
        } else {
            $html .= '<li><a href="'.$url.$i.'">'.$i.'</a></li>';
        }
    }

    // Mostrar último si no está en rango
    if ($end < $Npaginas) {
        if ($end < $Npaginas - 1) {
            $html .= '<li class="disabled"><span>…</span></li>';
        }
        $html .= '<li><a href="'.$url.$Npaginas.'">'.$Npaginas.'</a></li>';
    }

    // Siguiente
    if($pagina >= $Npaginas){
        $html .= '<li class="disabled"><span aria-hidden="true">Siguiente</span></li>';
    } else {
        $html .= '<li><a href="'.$url.($pagina+1).'" aria-label="Siguiente"><span aria-hidden="true">Siguiente</span></a></li>';
    }

    $html .= '</ul></nav></div>';
    return $html;
}

function limpiar_cadena($cadena){
    $cadena = trim($cadena);
    $cadena = stripslashes($cadena);
    $cadena = htmlspecialchars($cadena);
    return $cadena;
}

function verificar_datos($filtro, $cadena){
    if(preg_match("/^".$filtro."$/", $cadena)){
        return false;
    }else{
        return true;
    }
}

function buscaDatos($conexion, $search, $inicio, $registros) {
    $sqlQuery = "SELECT 
                    b.nombien, 
                    c.razonsocial, 
                    f.noInforme, 
                    f.observaciones,
                    f.referencia,
                    COUNT(*) AS total_fotos,
                    MIN(f.namefoto) AS primera_foto,
                    MIN(b.nombien) AS muestra,
                    c.razonsocial AS cliente
                FROM fotos_muestra f
                INNER JOIN biencatalogo b ON f.iditem = b.codbiencat
                INNER JOIN proveedor c ON f.id_cliente = c.idproveedor
                WHERE f.referencia LIKE '%$search%'
                   OR b.nombien LIKE '%$search%'
                   OR c.razonsocial LIKE '%$search%'
                   OR f.noInforme LIKE '%$search%'
                GROUP BY f.referencia, f.noInforme
                ORDER BY f.referencia DESC
                LIMIT $inicio, $registros";

    return mysqli_query($conexion, $sqlQuery);
}


function mostrarTodos($conexion, $inicio, $registros) {
    $sqlQuery = "SELECT 
                    b.nombien, 
                    c.razonsocial, 
                    f.noInforme, 
                    f.referencia, 
                    COUNT(*) AS total_fotos,
                    MIN(f.namefoto) AS primera_foto,
                    MIN(b.nombien) AS muestra,
                    c.razonsocial AS cliente,
                    f.observaciones
                 FROM fotos_muestra f
                 INNER JOIN biencatalogo b ON f.iditem = b.codbiencat
                 INNER JOIN proveedor c ON f.id_cliente = c.idproveedor
                 GROUP BY f.referencia, f.noInforme
                 ORDER BY f.referencia DESC
                 LIMIT $inicio, $registros";

    return mysqli_query($conexion, $sqlQuery);
}


function contarTodos($conexion){
    $sql = "SELECT COUNT(*) AS total 
            FROM (
                SELECT DISTINCT referencia, noInforme
                FROM fotos_muestra
            ) AS t";
    $res = mysqli_query($conexion, $sql);
    return mysqli_fetch_assoc($res)['total'];
}



function contarBusqueda($conexion, $search){
    $sql = "SELECT COUNT(*) AS total
            FROM (
                SELECT DISTINCT f.referencia, f.noInforme
                FROM fotos_muestra f
                INNER JOIN biencatalogo b ON f.iditem = b.codbiencat
                INNER JOIN proveedor c ON f.id_cliente = c.idproveedor
                WHERE f.referencia LIKE '%$search%'
                   OR b.nombien LIKE '%$search%'
                   OR c.razonsocial LIKE '%$search%'
                   OR f.noInforme LIKE '%$search%'
            ) AS t";

    $res = mysqli_query($conexion, $sql);
    return mysqli_fetch_assoc($res)['total'];
}

?>