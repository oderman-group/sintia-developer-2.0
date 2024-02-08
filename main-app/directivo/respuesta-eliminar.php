<?php 
include("session.php");
require_once(ROOT_PATH."/main-app/class/Respuesta.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0313';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

$id = '';
if (!empty($_GET['id'])) {
	$id = base64_decode($_GET['id']);
}

Respuesta::eliminarRespuestas($conexion, $config, $id);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="respuesta.php?error=ER_DT_3";</script>';
exit();