<?php 
include("session.php");
require_once(ROOT_PATH."/main-app/class/Asignaciones.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0322';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

Asignaciones::actualizarAsignaciones($conexion, $config, $_POST);

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="asignaciones.php?success=SC_DT_2&id='.base64_encode($_POST['id']).'&idE='.base64_encode($_POST['idE']).'";</script>';
exit();