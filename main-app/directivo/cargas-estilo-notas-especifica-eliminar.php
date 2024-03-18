<?php 
include("session.php");
require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0155';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

CargaAcademica::eliminarTiposNotas($conexion, $config, base64_decode($_GET["idN"]));

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="cargas-estilo-notas-especifica.php?id=' . $_GET["idNC"] . '";</script>';
exit();