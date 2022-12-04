<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
	mysql_query("DELETE FROM academico_actividad_evaluaciones", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro", $conexion);
	mysql_query("DELETE FROM academico_actividad_preguntas", $conexion);
	mysql_query("DELETE FROM academico_actividad_tareas", $conexion);
	mysql_query("DELETE FROM academico_actividades", $conexion);
	mysql_query("DELETE FROM academico_boletin", $conexion);
	mysql_query("DELETE FROM academico_cargas", $conexion);
	mysql_query("DELETE FROM academico_clases", $conexion);
	mysql_query("DELETE FROM academico_cronograma", $conexion);
	mysql_query("DELETE FROM academico_horarios", $conexion);
	mysql_query("DELETE FROM academico_indicadores_carga", $conexion);
	mysql_query("DELETE FROM academico_nivelaciones", $conexion);
	mysql_query("DELETE FROM academico_pclase", $conexion);
	mysql_query("DELETE FROM academico_calificaciones", $conexion);
	mysql_query("DELETE FROM academico_actividad_evaluaciones_resultados", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro_comentarios", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro_respuestas", $conexion);
	mysql_query("DELETE FROM academico_ausencias", $conexion);
	mysql_query("DELETE FROM disiplina_nota", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();