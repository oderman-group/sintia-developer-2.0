<?php 
include("session.php");
require_once(ROOT_PATH."/main-app/class/PreguntaGeneral.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0292';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

$_POST["obligatoria"]=empty($_POST["obligatoria"])?0:1;
$_POST["visible"]=empty($_POST["visible"])?0:1;
PreguntaGeneral::actualizar($_POST);

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="preguntas.php?success=SC_DT_2&id='.base64_encode($_POST['id']).'";</script>';
exit();