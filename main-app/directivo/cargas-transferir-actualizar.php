<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0171';
include("../compartido/historial-acciones-guardar.php");

try{
	mysqli_query($conexion, "UPDATE academico_cargas SET car_docente='" . $_POST["para"] . "' WHERE car_docente='" . $_POST["de"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	include("../compartido/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();