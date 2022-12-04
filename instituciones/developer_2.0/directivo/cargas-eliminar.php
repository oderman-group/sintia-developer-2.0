<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
	mysql_query("DELETE FROM academico_actividad_evaluaciones WHERE eva_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro WHERE foro_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro WHERE foro_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_actividad_preguntas WHERE preg_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_actividad_tareas WHERE tar_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_actividades WHERE act_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_boletin WHERE bol_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_cargas WHERE car_id='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_clases WHERE cls_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_cronograma WHERE cro_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_horarios WHERE hor_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_indicadores_carga WHERE ipc_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_nivelaciones WHERE niv_id_asg='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_pclase WHERE pc_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM disiplina_nota WHERE dn_id_carga='" . $_GET["id"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();