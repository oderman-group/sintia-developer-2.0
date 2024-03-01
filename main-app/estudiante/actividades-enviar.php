<?php
include("session.php");
include("verificar-usuario.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'ES0058';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Actividades.php");

$archivoSubido = new Archivos;

$fechas = Actividades::fechaEntregaActividad($conexion, $config, $_POST["idR"]);

if($fechas[1]<0 and $fechas[3]==1){
	
	include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=207&fechaH='.$fechas[2].'&diasP='.$fechas[1].'";</script>';
	exit();
}

$destino = ROOT_PATH."/main-app/files/tareas-entregadas";
$num = Actividades::contarEntregas($conexion, $config, $datosEstudianteActual['mat_id'], $_POST["idR"]);

if($num == 0){

	Actividades::guardarEntrega($conexion, $config, $_POST, $_FILES, $storage, $datosEstudianteActual['mat_id']);
	
}else{

	Actividades::actualizarEntrega($conexion, $config, $_POST, $_FILES, $storage, $datosEstudianteActual['mat_id']);
	
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=107";</script>';
exit();