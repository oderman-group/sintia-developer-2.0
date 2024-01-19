<?php 
include("session.php");
require_once(ROOT_PATH."/main-app/class/EvaluacionGeneral.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0286';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

EvaluacionGeneral::actualizar($_POST);

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="evaluacion-editar.php?success=SC_DT_2&id='.base64_encode($_POST['id']).'";</script>';
exit();