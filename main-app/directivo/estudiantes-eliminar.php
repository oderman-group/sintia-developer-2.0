<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0162';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

$idE="";
if(!empty($_GET["idE"])){ $idE=base64_decode($_GET["idE"]);}
$idU="";
if(!empty($_GET["idU"])){ $idU=base64_decode($_GET["idU"]);}

try{
    mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados WHERE res_id_estudiante='" . $idE . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_comentarios WHERE com_id_estudiante='" . $idE . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_respuestas WHERE fore_id_estudiante='" . $idE . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_tareas_entregas WHERE ent_id_estudiante='" . $idE . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_ausencias WHERE aus_id_estudiante='" . $idE . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM academico_boletin WHERE bol_estudiante='" . $idE . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM academico_calificaciones WHERE cal_id_estudiante='" . $idE . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "UPDATE academico_matriculas SET mat_eliminado=1 WHERE mat_id='" . $idE . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_nivelaciones WHERE niv_cod_estudiante='" . $idE . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_recuperaciones_notas WHERE rec_cod_estudiante='" . $idE . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".BD_DISCIPLINA.".disciplina_matricula_condicional WHERE cond_estudiante='" . $idE . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".BD_DISCIPLINA.".disciplina_reportes WHERE dr_estudiante='" . $idE . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".BD_DISCIPLINA.".disiplina_nota WHERE dn_cod_estudiante='" . $idE . "' WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".BD_FINANCIERA.".finanzas_cuentas WHERE fcu_usuario='" . $idU . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".general_resultados WHERE resg_id_estudiante='" . $idE . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".seguridad_historial_acciones WHERE hil_usuario='" . $idU . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".social_noticias WHERE not_usuario='" . $idU . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM usuarios WHERE uss_id='" . $idU . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".BD_GENERAL.".usuarios_por_estudiantes WHERE upe_id_estudiante='" . $idE . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
    mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".social_emails WHERE ema_de='" . $idU . "' OR ema_para='" . $idU . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="estudiantes.php?success=SC_DT_3&id='.$_GET["idE"].'";</script>';
exit();