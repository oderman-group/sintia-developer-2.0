<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
	mysql_query("UPDATE academico_matriculas SET mat_tipo=128", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();