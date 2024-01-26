<?php 
include("session.php");
require_once(ROOT_PATH."/main-app/class/Movimientos.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0280';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

$id = '';
if (!empty($_GET['id'])) {
	$id = base64_decode($_GET['id']);
}

Movimientos::eliminarRecurrente($conexion, $config, $id);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="factura-recurrente.php?error=ER_DT_3";</script>';
exit();