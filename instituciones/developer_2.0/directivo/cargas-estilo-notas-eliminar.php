<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
	mysql_query("DELETE FROM academico_notas_tipos WHERE notip_categoria='" . $_GET["idR"] . "'", $conexion);
	mysql_query("DELETE FROM academico_categorias_notas WHERE catn_id='" . $_GET["idR"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();