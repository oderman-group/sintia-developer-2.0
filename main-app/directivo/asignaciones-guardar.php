<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/Asignaciones.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0320';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

Asignaciones::guardarAsignaciones($conexion, $config, $_POST);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="asignaciones.php?success=SC_DT_1&idE='.base64_encode($_POST['idE']).'&id='.base64_encode($_POST['idE']).'";</script>';
exit();