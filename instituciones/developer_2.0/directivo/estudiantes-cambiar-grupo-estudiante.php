<?php
include("session.php");
include("../modelo/conexion.php");

	$estudiante = mysql_fetch_array(mysql_query("SELECT * FROM academico_matriculas WHERE mat_id='" . $_POST["estudiante"] . "'", $conexion));
	$cargasConsulta = mysql_query("SELECT * FROM academico_cargas WHERE car_curso='" . $estudiante["mat_grado"] . "' AND car_grupo='" . $estudiante["mat_grupo"] . "'", $conexion);
	while ($cargasDatos = mysql_fetch_array($cargasConsulta)) {
		$cargasConsultaNuevo = mysql_fetch_array(mysql_query("SELECT * FROM academico_cargas 
		WHERE car_curso='" . $_POST["cursoNuevo"] . "' AND car_grupo='" . $_POST["grupoNuevo"] . "' AND car_materia='" . $cargasDatos["car_materia"] . "'", $conexion));

		mysql_query("UPDATE academico_boletin SET bol_carga='" . $cargasConsultaNuevo["car_id"] . "' 
		WHERE bol_carga='" . $cargasDatos["car_id"] . "' AND bol_estudiante='" . $_POST["estudiante"] . "'", $conexion);
	}
	mysql_query("UPDATE academico_matriculas SET mat_grado='" . $_POST["cursoNuevo"] . "', mat_grupo='" . $_POST["grupoNuevo"] . "' WHERE mat_id='" . $_POST["estudiante"] . "'", $conexion);
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '"</script>';
	exit();