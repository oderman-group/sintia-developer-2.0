<?php
	include("session.php");
	include("../modelo/conexion.php");
	
	$cargas = mysql_query("SELECT * FROM academico_cargas WHERE car_ih!=''", $conexion);
	while ($c = mysql_fetch_array($cargas)) {
		mysql_query("DELETE FROM academico_intensidad_curso WHERE ipc_curso='" . $c[2] . "' AND ipc_materia='" . $c[4] . "'", $conexion);
		if (mysql_errno() != 0) {
			echo mysql_error();
			exit();
		}
		mysql_query("INSERT INTO academico_intensidad_curso(ipc_curso, ipc_materia, ipc_intensidad)VALUES('" . $c[2] . "','" . $c[4] . "','" . $c['car_ih'] . "')", $conexion);
		if (mysql_errno() != 0) {
			echo mysql_error();
			exit();
		}
	}
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();