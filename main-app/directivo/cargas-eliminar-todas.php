<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0153';
include("../compartido/historial-acciones-guardar.php");

try{
	mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones");
	mysqli_query($conexion, "DELETE FROM academico_actividad_foro");
	mysqli_query($conexion, "DELETE FROM academico_actividad_foro");
	mysqli_query($conexion, "DELETE FROM academico_actividad_preguntas");
	mysqli_query($conexion, "DELETE FROM academico_actividad_tareas");
	mysqli_query($conexion, "DELETE FROM academico_actividades");
	mysqli_query($conexion, "DELETE FROM academico_boletin");
	mysqli_query($conexion, "DELETE FROM academico_cargas");
	mysqli_query($conexion, "DELETE FROM academico_clases");
	mysqli_query($conexion, "DELETE FROM academico_cronograma");
	mysqli_query($conexion, "DELETE FROM academico_horarios");
	mysqli_query($conexion, "DELETE FROM academico_indicadores_carga");
	mysqli_query($conexion, "DELETE FROM academico_nivelaciones");
	mysqli_query($conexion, "DELETE FROM academico_pclase");
	mysqli_query($conexion, "DELETE FROM academico_calificaciones");
	mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones_resultados");
	mysqli_query($conexion, "DELETE FROM academico_actividad_foro_comentarios");
	mysqli_query($conexion, "DELETE FROM academico_actividad_foro_respuestas");
	mysqli_query($conexion, "DELETE FROM academico_ausencias");
	mysqli_query($conexion, "DELETE FROM disiplina_nota");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");
	include("../compartido/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();