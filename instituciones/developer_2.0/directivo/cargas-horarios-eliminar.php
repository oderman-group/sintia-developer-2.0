<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
	mysql_query("UPDATE academico_horarios SET hor_estado=0 WHERE hor_id=" . $_GET["idH"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="cargas-horarios.php?id=' . $_GET["idC"] . '";</script>';
	exit();