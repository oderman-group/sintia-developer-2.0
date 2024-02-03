<?php
include("session.php");
$input = json_decode(file_get_contents("php://input"), true);
include("verificar-usuario.php");

require_once("../class/servicios/MediaTecnicaServicios.php");
$codigo = $input['codigo'];
$matricula = $input['matricula'];
try {

    MediaTecnicaServicios::guardarPorCurso($matricula, $codigo, $config, ESTADO_CURSO_PRE_INSCRITO);
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
    $response["Error"] = "false";
}


$response["ok"] = "REGISTRO EXITOSO!";
include("../compartido/guardar-historial-acciones.php");

echo json_encode($response);
