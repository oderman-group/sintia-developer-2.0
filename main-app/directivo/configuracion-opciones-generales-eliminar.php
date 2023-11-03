<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
Modulos::verificarPermisoDev();

$idPaginaInterna = 'DV0041';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

try{
	mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_id='" . $_GET["idogen"] . "'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="configuracion-opciones-generales.php";</script>';
exit();