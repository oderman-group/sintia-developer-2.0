<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0156';
require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

CargaAcademica::eliminarHorarios($conexion, $config, $_GET["idH"]);

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="cargas-horarios.php?id=' . $_GET["idC"] . '";</script>';
exit();