<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
	mysql_query("DELETE FROM academico_notas_tipos WHERE notip_id=" . $_GET["idN"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="academico-categoria-notas-especifica.php?id=' . $_GET["idNC"] . '";</script>';
	exit();