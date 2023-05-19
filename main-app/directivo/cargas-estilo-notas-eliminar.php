<?php
include("session.php");

Modulos::validarAccesoPaginas();
$idPaginaInterna = 'DT0154';
include("../compartido/historial-acciones-guardar.php");

try{
	mysqli_query($conexion, "DELETE FROM academico_notas_tipos WHERE notip_categoria='" . $_GET["idR"] . "'");
	mysqli_query($conexion, "DELETE FROM academico_categorias_notas WHERE catn_id='" . $_GET["idR"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");
	include("../compartido/guardar-historial-acciones.php");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();