<?php
require_once("../../class/Estudiantes.php");

if (!empty($filtrosDecode['curso'])) {
    $filtro .= " AND mat_grado='" . $filtrosDecode['curso'] . "'";
}

if (!empty($filtrosDecode['estadoM'])) {
    $filtro .= " AND mat_estado_matricula='" . $filtrosDecode['curso'] . "'";
}

$result = Estudiantes::listarEstudiantes(0, $filtro, "LIMIT 0, 20", null, $valor);
$index = 0;

$arraysDatos = array();
while ($fila = $result->fetch_assoc()) {
    $arraysDatos[$index] = $fila;
    $index++;
}
$lista = $arraysDatos;
