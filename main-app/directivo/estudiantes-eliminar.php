<?php
include("session.php");
include("../modelo/conexion.php");
try{
    mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones_resultados WHERE res_id_estudiante='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "DELETE FROM academico_actividad_foro_comentarios WHERE com_id_estudiante='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "DELETE FROM academico_actividad_foro_respuestas WHERE fore_id_estudiante='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "DELETE FROM academico_actividad_tareas_entregas WHERE ent_id_estudiante='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "DELETE FROM academico_ausencias WHERE aus_id_estudiante='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "DELETE FROM academico_boletin WHERE bol_estudiante='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "DELETE FROM academico_calificaciones WHERE cal_id_estudiante='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "UPDATE academico_matriculas SET mat_eliminado=1 WHERE mat_id='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "DELETE FROM academico_nivelaciones WHERE niv_cod_estudiante='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "DELETE FROM academico_recuperaciones_notas WHERE rec_cod_estudiante='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "DELETE FROM disciplina_matricula_condicional WHERE cond_estudiante='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "DELETE FROM disciplina_reportes WHERE dr_estudiante='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "DELETE FROM disiplina_nota WHERE dn_cod_estudiante='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "DELETE FROM finanzas_cuentas WHERE fcu_usuario='" . $_GET["idU"] . "'");
    mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".general_resultados WHERE resg_id_estudiante='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".seguridad_historial_acciones WHERE hil_usuario='" . $_GET["idU"] . "'");
    mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".social_noticias WHERE not_usuario='" . $_GET["idU"] . "'");
    mysqli_query($conexion, "DELETE FROM usuarios WHERE uss_id='" . $_GET["idU"] . "'");
    mysqli_query($conexion, "DELETE FROM usuarios_por_estudiantes WHERE upe_id_estudiante='" . $_GET["idE"] . "'");
    mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".social_emails WHERE ema_de='" . $_GET["idU"] . "' OR ema_para='" . $_GET["idU"] . "'");

    echo '<script type="text/javascript">window.location.href="estudiantes.php?success=SC_DT_3&id='.$_GET["idE"].'";</script>';
    exit();
}catch(Exception $e){
    echo 'Caught exception: ',  $e->getMessage(), "\n";
    exit();
}  