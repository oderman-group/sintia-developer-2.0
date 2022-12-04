<?php
	include("session.php");
	include("../modelo/conexion.php");
	
	mysql_query("UPDATE academico_matriculas SET mat_promocionado=0 WHERE mat_grado='" . $_GET["curso"] . "'", $conexion);
	$g = mysql_fetch_array(mysql_query("SELECT * FROM academico_grados WHERE gra_id='" . $_GET["curso"] . "'", $conexion));
	if ($g[7] != "") {
		mysql_query("UPDATE academico_matriculas SET mat_grado='" . $g[7] . "', mat_promocionado=1 WHERE mat_grado='" . $g[0] . "' AND mat_promocionado=0 AND mat_eliminado=0", $conexion);
	}
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();