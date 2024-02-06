<?php
include("session.php");
require_once("../class/servicios/MediaTecnicaServicios.php");
header("Content-type: application/json; charset=utf-8");
$input = json_decode(file_get_contents("php://input"), true);
if (empty($input)) {
    $tipo = base64_decode($_GET['tipo']);
    $curso = base64_decode($_GET['curso']);
    $matricula = base64_decode($_GET['matricula']);
    $grupo =  base64_decode($_GET['grupo']);
    $estado = base64_decode($_GET['estado']);
} else {
    $tipo = $input['tipo'];
    $curso = $input['curso'];
    $matricula = $input['matricula'];
    $grupo = $input['grupo'];
    $estado = $input['estado'];
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");

if (!empty($tipo)) {
    try {
        switch ($tipo) {
            case ACCION_CREAR:
                MediaTecnicaServicios::guardarPorCurso($matricula, $curso);
                $response["ok"] = "true";
                break;
            case ACCION_MODIFICAR:
                MediaTecnicaServicios::editarporCurso($matricula, $curso, $grupo, $estado);
                $response["ok"] = "true";
                break;
            case ACCION_ELIMINAR:                
                MediaTecnicaServicios::eliminarPorCurso($matricula, $curso);
                $response["ok"] = "true";
                break;
            default:
                echo "Opción no reconocida";
        }
        include("../compartido/guardar-historial-acciones.php");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
} else {
    $response["Error"] = "Error";
}
echo json_encode($response);
