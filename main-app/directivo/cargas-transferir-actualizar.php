<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
	mysql_query("UPDATE academico_cargas SET car_docente='" . $_POST["para"] . "' WHERE car_docente='" . $_POST["de"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();