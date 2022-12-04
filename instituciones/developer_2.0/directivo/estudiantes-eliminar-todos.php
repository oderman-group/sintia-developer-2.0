<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
	mysql_query("DELETE FROM academico_actividad_evaluaciones_resultados", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro_comentarios", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro_respuestas", $conexion);
	mysql_query("DELETE FROM academico_actividad_tareas_entregas", $conexion);
	mysql_query("DELETE FROM academico_ausencias", $conexion);
	mysql_query("DELETE FROM academico_boletin", $conexion);
	mysql_query("DELETE FROM academico_calificaciones", $conexion);
	mysql_query("DELETE FROM academico_matriculas", $conexion); //ELIMINA TODO
	mysql_query("DELETE FROM academico_nivelaciones", $conexion);
	mysql_query("DELETE FROM academico_recuperaciones_notas", $conexion);
	mysql_query("DELETE FROM disciplina_matricula_condicional", $conexion);
	mysql_query("DELETE FROM disciplina_reportes", $conexion);
	mysql_query("DELETE FROM disiplina_nota", $conexion);
	mysql_query("DELETE FROM general_resultados", $conexion);
	mysql_query("DELETE FROM usuarios WHERE uss_tipo=4", $conexion);
	mysql_query("DELETE FROM usuarios_por_estudiantes", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();