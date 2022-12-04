<?php
include("session.php");
include("../modelo/conexion.php");

    mysql_query("DELETE FROM academico_actividad_evaluaciones_resultados WHERE res_id_estudiante='" . $_GET["idE"] . "'", $conexion);
    mysql_query("DELETE FROM academico_actividad_foro_comentarios WHERE com_id_estudiante='" . $_GET["idE"] . "'", $conexion);
    mysql_query("DELETE FROM academico_actividad_foro_respuestas WHERE fore_id_estudiante='" . $_GET["idE"] . "'", $conexion);
    mysql_query("DELETE FROM academico_actividad_tareas_entregas WHERE ent_id_estudiante='" . $_GET["idE"] . "'", $conexion);
    mysql_query("DELETE FROM academico_ausencias WHERE aus_id_estudiante='" . $_GET["idE"] . "'", $conexion);
    mysql_query("DELETE FROM academico_boletin WHERE bol_id_estudiante='" . $_GET["idE"] . "'", $conexion);
    mysql_query("DELETE FROM academico_calificaciones WHERE cal_id_estudiante='" . $_GET["idE"] . "'", $conexion);
    mysql_query("UPDATE academico_matriculas SET mat_eliminado=1 WHERE mat_id='" . $_GET["idE"] . "'", $conexion);
    mysql_query("DELETE FROM academico_nivelaciones WHERE niv_cod_estudiante='" . $_GET["idE"] . "'", $conexion);
    mysql_query("DELETE FROM academico_recuperaciones_notas WHERE rec_cod_estudiante='" . $_GET["idE"] . "'", $conexion);
    mysql_query("DELETE FROM disciplina_matricula_condicional WHERE cond_estudiante='" . $_GET["idE"] . "'", $conexion);
    mysql_query("DELETE FROM disciplina_reportes WHERE dr_estudiante='" . $_GET["idE"] . "'", $conexion);
    mysql_query("DELETE FROM disiplina_nota WHERE dn_cod_estudiante='" . $_GET["idE"] . "'", $conexion);
    mysql_query("DELETE FROM finanzas_cuentas WHERE fcu_usuario='" . $_GET["idU"] . "'", $conexion);
    mysql_query("DELETE FROM general_resultados WHERE resg_id_estudiante='" . $_GET["idE"] . "'", $conexion);
    mysql_query("DELETE FROM seguridad_historial_acciones WHERE hil_usuario='" . $_GET["idU"] . "'", $conexion);
    mysql_query("DELETE FROM social_amigos WHERE ams_usuario='" . $_GET["idU"] . "' OR ams_amigo", $conexion);
    mysql_query("DELETE FROM social_noticias WHERE not_usuario='" . $_GET["idU"] . "'", $conexion);
    mysql_query("DELETE FROM social_visitas WHERE vis_usuario='" . $_GET["idU"] . "'", $conexion);
    mysql_query("DELETE FROM usuarios WHERE uss_id='" . $_GET["idU"] . "'", $conexion);
    mysql_query("DELETE FROM usuarios_por_estudiantes WHERE upe_id_estudiante='" . $_GET["idE"] . "'", $conexion);
    mysql_query("DELETE FROM social_preferencias_usuarios WHERE preu_usuario='" . $_GET["idU"] . "'", $conexion);
    mysql_query("DELETE FROM social_emails WHERE ema_de='" . $_GET["idU"] . "' OR ema_para='" . $_GET["idU"] . "'", $conexion);
    $lineaError = __LINE__;

    include("../compartido/reporte-errores.php");
    echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
    exit();