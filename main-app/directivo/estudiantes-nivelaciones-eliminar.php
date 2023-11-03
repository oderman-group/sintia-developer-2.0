<?php 
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0088';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

try{
	mysqli_query($conexion, "DELETE FROM academico_nivelaciones WHERE niv_id='" . $_GET["idNiv"] . "'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="estudiantes-nivelaciones-registrar2.php?curso='.$_GET["curso"].'&grupo='.$_GET["grupo"].'";</script>';
exit();