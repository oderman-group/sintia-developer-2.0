<?php
require_once("../../class/Estudiantes.php");
require_once("../../class/servicios/GradoServicios.php");

$cursoActual=null;

if (!empty($filtrosDecode['curso'])) {
    $filtro .= " AND mat_grado='" . $filtrosDecode['curso'] . "'";
    $curso = $filtrosDecode['curso'];
    $cursoActual=GradoServicios::consultarCurso($curso);
    if (!empty($cursoActual) && $cursoActual["gra_tipo"] == GRADO_INDIVIDUAL) {
        $filtro = "";
    }
}



if (!empty($filtrosDecode['estadoM'])) {
    $filtro .= " AND mat_estado_matricula='" . $filtrosDecode['estadoM'] . "'";
}

$result = Estudiantes::listarEstudiantes(0, $filtro, "LIMIT 0, 20", $cursoActual,$valor);

$index = 0;
$arraysDatos = array();
if (!empty($result)) {
    while ($fila = $result->fetch_assoc()) {
        $arraysDatos[$index] = $fila;
        $index++;
    }
}
$lista = $arraysDatos;
