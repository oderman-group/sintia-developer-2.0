<?php 
include("session.php");
require_once(ROOT_PATH."/main-app/class/Movimientos.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0279';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

Movimientos::actualizarRecurrente($conexion, $config, $_POST);

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="factura-recurrente-editar.php?success=SC_DT_2&id='.base64_encode($_POST['id']).'";</script>';
exit();