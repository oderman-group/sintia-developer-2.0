<?php
require_once("../../class/CargaAcademica.php");
if (!empty($filtrosDecode['curso'])) {
    $filtro .= " AND car_curso='" . $filtrosDecode['curso'] . "'";
}
$result = CargaAcademica::listarCargas($conexion, $config,  "", $filtro, "mat_id, car_grupo", "LIMIT 0, 20", $valor, $filtro2);
$index = 0;
$arraysDatos = array();
while ($fila = $result->fetch_assoc()) {
    $arraysDatos[$index] = $fila;
    $index++;
}
$lista = $arraysDatos;
