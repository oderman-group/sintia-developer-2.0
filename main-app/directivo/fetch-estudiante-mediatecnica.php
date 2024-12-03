<?php
include("session.php");
require_once("../class/servicios/MediaTecnicaServicios.php");
require_once("../class/servicios/GradoServicios.php");
require_once("../class/servicios/MatriculaServicios.php");
header("Content-type: application/json; charset=utf-8");
$input = json_decode(file_get_contents("php://input"), true);
if (empty($input)) {
    $tipo = base64_decode($_GET['tipo']);
    $curso = base64_decode($_GET['curso']);
    $matricula = base64_decode($_GET['matricula']);
} else {
    $tipo = $input['tipo'];
    $curso = $input['curso'];
    $matricula = $input['matricula'];
}
$response = array();
require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");

$cursoActual = GradoServicios::consultarCurso($curso);
$matricualActual = MatriculaServicios::consultar($matricula);

if (!empty($tipo)) {
    try {
        switch ($tipo) {
            case ACCION_CREAR:
                $existeEstudianteMT = MediaTecnicaServicios::existeEstudianteMTCursos($matricula, $curso, $config, $config['conf_agno']);
                if (!$existeEstudianteMT) {
                    $parametros = [
                        'matcur_id_curso' => $curso,
                        'matcur_id_institucion' => $config['conf_id_institucion'],
                        'matcur_years' => $config['conf_agno']
                    ];
                    $cantidad = MediaTecnicaServicios::contar($parametros);
                    // se valida que tenga disponibilidad el curso
                    if (intval($cantidad) >= intval($cursoActual["gra_maximum_quota"])) {
                        $response["ok"] = false;
                        $response["msg"] = "El cupo maximo del curso " . $cursoActual["gra_nombre"] . " es de " . $cursoActual["gra_maximum_quota"];
                        echo json_encode($response);
                        exit();
                    }
                    MediaTecnicaServicios::guardarPorCurso($matricula, $curso);
                    $response["ok"] = true;
                    $response["msg"] = "Se Agregó a ".MatriculaServicios::nombreCompleto($matricualActual)." en el cruso ". $cursoActual["gra_nombre"] . " correctamente.";
                } else {
                    $response["ok"] = false;
                    $response["msg"] = "El estudiante ".MatriculaServicios::nombreCompleto($matricualActual)." ya existe en el cruso ". $cursoActual["gra_nombre"] . ".";
                }
                break;
            case ACCION_MODIFICAR:
                if (empty($input)) {
                    $grupo =  base64_decode($_GET['grupo']);
                    $estado = base64_decode($_GET['estado']);
                } else {
                    $grupo = $input['grupo'];
                    $estado = $input['estado'];
                }
                MediaTecnicaServicios::editarporCurso($matricula, $curso, $grupo, $estado);
                $response["ok"] = true;
                $response["msg"] = "Se modificó a ".MatriculaServicios::nombreCompleto($matricualActual)." del cruso ". $cursoActual["gra_nombre"] . " correctamente.";
                break;
            case ACCION_ELIMINAR:
                MediaTecnicaServicios::eliminarPorCurso($matricula, $curso);
                $response["ok"] = true;
                $response["msg"] = "Se eliminó a ".MatriculaServicios::nombreCompleto($matricualActual)." del cruso ". $cursoActual["gra_nombre"] . " correctamente.";
                break;
            default:
                echo "Opción no reconocida";
        }
        include("../compartido/guardar-historial-acciones.php");
    } catch (Exception $e) {
        $response["ok"] = false;
        $response["msg"] = "Error " + $e->getMessage();
        include("../compartido/error-catch-to-report.php");
    }
} else {
    $response["Error"] = "Error";
}
echo json_encode($response);
