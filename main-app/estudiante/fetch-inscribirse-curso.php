<?php
include("session.php");
$input = json_decode(file_get_contents("php://input"), true);
include("verificar-usuario.php");
require_once("../class/servicios/GradoServicios.php");
require_once("../class/servicios/MediaTecnicaServicios.php");
$codigo = $input['codigo'];
$matricula = $input['matricula'];

$response = array();

try {
    global $config;

    $parametros = [
        'matcur_id_curso' => $codigo,
        'matcur_id_institucion' => $config['conf_id_institucion'],
        'matcur_years' => $config['conf_agno']
    ];
    $cantidad = MediaTecnicaServicios::contar($parametros);
    $parametros["matcur_id_matricula"] = $matricula;
    $inscrito = MediaTecnicaServicios::listar($parametros);
    $curso = GradoServicios::consultarCurso($codigo);




    // se valida si esta activo el curso
    if (!$curso["gra_active"]) {
        $response["ok"] = false;
        $response["msg"] = "El curso " + $curso["gra_nombre"] + " no esta activo ";
        echo json_encode($response);
        exit();
    }
    // se valida si esta el curso marcado como auto enrollment
    if (!$curso["gra_auto_enrollment"]) {
        $response["ok"] = false;
        $response["msg"] = "El Curso no esta marcado para auto matricularce";
        echo json_encode($response);
        exit();
    }
    // se valida si esta inscrito el estudiante
    if (!empty($inscrito)) {
        $response["ok"] = false;
        $response["msg"] = "ya estoy inscrito en el curso " . $curso["gra_nombre"];
        echo json_encode($response);
        exit();
    }
    // se valida que tenga disponibilidad el curso
    if (intval($cantidad) >= intval($curso["gra_maximum_quota"])) {
        $response["ok"] = false;
        $response["msg"] = "El cupo maximo del curso " + $curso["gra_nombre"] + " es de " . $curso["gra_maximum_quota"];
        echo json_encode($response);
        exit();
    }

    MediaTecnicaServicios::guardarPorCurso($matricula, $codigo);
    $parametros = [
        'matcur_id_curso' => $codigo,
        'matcur_id_institucion' => $config['conf_id_institucion'],
        'matcur_years' => $config['conf_agno']
    ];
    $cantidad = MediaTecnicaServicios::contar($parametros);
    $response["ok"] = true;
    $response["msg"] = "Se registro con exito en el curso " . $curso["gra_nombre"];
    $response["cantidad"] =  $cantidad;
    $response["cantidad_maxima"] =  $curso["gra_maximum_quota"];
    $response["porcentage"] =  (intval($cantidad)  / $curso["gra_maximum_quota"]) * 100;
    $response["curso"] =  $codigo;
    include("../compartido/guardar-historial-acciones.php");
    echo json_encode($response);
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
    $response["ok"] = false;
    $response["msg"] = "Error " + $e->getMessage();
}
