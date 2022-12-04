<?php 
include("session.php");
include("../modelo/conexion.php");

	mysql_query("UPDATE academico_matriculas SET mat_compromiso=0 WHERE mat_id='" . $_GET["id"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="estudiantes.php";</script>';
	exit();