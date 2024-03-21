<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0130';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
require_once(ROOT_PATH."/main-app/class/Clases.php");

if(empty($_FILES['file']['name'])){
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="clases.php?error=ER_DT_4&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'&tab=3";</script>';
	exit();
}

Clases::eliminarPlanClases($conexion, $config, $cargaConsultaActual, $periodoConsultaActual);

Clases::guardarPlanClases($conexion, $conexionPDO, $config, $cargaConsultaActual, $periodoConsultaActual, $_FILES);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="clases.php?success=SC_GN_4&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'&tab=3";</script>';
exit();