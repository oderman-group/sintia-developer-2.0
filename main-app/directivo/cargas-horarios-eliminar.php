<?php
include("session.php");

Modulos::validarAccesoPaginas();
$idPaginaInterna = 'DT0156';
include("../compartido/historial-acciones-guardar.php");

try{
	mysqli_query($conexion, "UPDATE academico_horarios SET hor_estado=0 WHERE hor_id=" . $_GET["idH"] . ";");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");
	include("../compartido/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="cargas-horarios.php?id=' . $_GET["idC"] . '";</script>';
	exit();