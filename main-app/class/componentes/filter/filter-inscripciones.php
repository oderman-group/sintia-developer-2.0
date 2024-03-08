<?php
    require_once("../../class/Inscripciones.php");    
    if (!empty($filtrosDecode['curso'])) {
        $filtro .= " AND asp_grado='" . $filtrosDecode['curso'] . "'";
    }
    if (!empty($filtrosDecode['estado'])) {
        $filtro .= " AND asp_estado_solicitud='" . $filtrosDecode['estado'] . "'";
    }
    $parametros['filtro'] =  $filtro;
    $lista = Inscripciones::listarTodos($parametros);

