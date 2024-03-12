<?php
include("session-compartida.php");
require_once(ROOT_PATH . "/main-app/class/PreguntaGeneral.php");
require_once(ROOT_PATH . "/main-app/class/Asignaciones.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0061';
require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

$asignacion = Asignaciones::traerDatosAsignaciones($conexion, $config, $_GET['idAsignacion']);

$iniciadas = Asignaciones::consultarCantAsignacionesEmpezadas($conexion, $config, $asignacion['gal_id']);
if ($asignacion['gal_limite_evaluadores'] != 0 && $iniciadas >= $asignacion['gal_limite_evaluadores'] ) { 
	$enlace = UsuariosPadre::verificarTipoUsuario($datosUsuarioActual['uss_tipo'], "encuestas-pendientes.php");

	echo $enlace.'?error=ER_DT_21';
	exit();
}

$existeRespuestas = PreguntaGeneral::existeRespuestaPregunta($conexion, $config, $_GET['idPregunta'], $_GET['idAsignacion'], $datosUsuarioActual['uss_id']);
if(empty($existeRespuestas)) {
    PreguntaGeneral::guardarRespuestaPregunta($conexion, $config, $_GET['idPregunta'], $_GET['idAsignacion'], $datosUsuarioActual['uss_id'], $_GET['respuesta']);
}else{
    PreguntaGeneral::actualizarRespuestaPregunta($conexion, $config, $_GET['idPregunta'], $_GET['idAsignacion'], $datosUsuarioActual['uss_id'], $_GET['respuesta']);
}

require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");