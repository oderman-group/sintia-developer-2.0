<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0162';

if(!Modulos::validarSubRol($idPaginaInterna)){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

try{
    mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones_resultados WHERE res_id_estudiante='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM academico_actividad_foro_comentarios WHERE com_id_estudiante='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM academico_actividad_foro_respuestas WHERE fore_id_estudiante='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM academico_actividad_tareas_entregas WHERE ent_id_estudiante='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM academico_ausencias WHERE aus_id_estudiante='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM academico_boletin WHERE bol_estudiante='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM academico_calificaciones WHERE cal_id_estudiante='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "UPDATE academico_matriculas SET mat_eliminado=1 WHERE mat_id='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM academico_nivelaciones WHERE niv_cod_estudiante='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM academico_recuperaciones_notas WHERE rec_cod_estudiante='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM disciplina_matricula_condicional WHERE cond_estudiante='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM disciplina_reportes WHERE dr_estudiante='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM disiplina_nota WHERE dn_cod_estudiante='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM finanzas_cuentas WHERE fcu_usuario='" . $_GET["idU"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".general_resultados WHERE resg_id_estudiante='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".seguridad_historial_acciones WHERE hil_usuario='" . $_GET["idU"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".social_noticias WHERE not_usuario='" . $_GET["idU"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM usuarios WHERE uss_id='" . $_GET["idU"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM usuarios_por_estudiantes WHERE upe_id_estudiante='" . $_GET["idE"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".social_emails WHERE ema_de='" . $_GET["idU"] . "' OR ema_para='" . $_GET["idU"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="estudiantes.php?success=SC_DT_3&id='.$_GET["idE"].'";</script>';
exit();