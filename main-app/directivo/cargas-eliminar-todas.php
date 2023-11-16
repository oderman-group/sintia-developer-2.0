<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0153';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

try{
	mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_preguntas WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_tareas WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM academico_actividades");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM academico_boletin");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM academico_cargas");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM academico_clases");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM academico_cronograma");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM academico_horarios");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM academico_indicadores_carga");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM academico_nivelaciones");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM academico_pclase");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM academico_calificaciones");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_comentarios WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_respuestas WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM academico_ausencias");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
	mysqli_query($conexion, "DELETE FROM ".BD_DISCIPLINA.".disiplina_nota WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
exit();