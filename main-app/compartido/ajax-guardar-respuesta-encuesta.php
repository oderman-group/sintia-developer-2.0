<?php
include("session-compartida.php");
require_once(ROOT_PATH . "/main-app/class/PreguntaGeneral.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0061';
require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

$existeRespuestas = PreguntaGeneral::existeRespuestaPregunta($conexion, $config, $_GET['idPregunta'], $_GET['idAsignacion'], $datosUsuarioActual['uss_id']);
if(empty($existeRespuestas)) {
    PreguntaGeneral::guardarRespuestaPregunta($conexion, $config, $_GET['idPregunta'], $_GET['idAsignacion'], $datosUsuarioActual['uss_id'], $_GET['respuesta']);
}else{
    PreguntaGeneral::actualizarRespuestaPregunta($conexion, $config, $_GET['idPregunta'], $_GET['idAsignacion'], $datosUsuarioActual['uss_id'], $_GET['respuesta']);
}

require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");