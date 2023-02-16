<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
try{
	mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones WHERE eva_id_carga='" . $_GET["id"] . "'");	
	mysqli_query($conexion, "DELETE FROM academico_actividad_foro WHERE foro_id_carga='" . $_GET["id"] . "'");
	mysqli_query($conexion, "DELETE FROM academico_actividad_foro WHERE foro_id_carga='" . $_GET["id"] . "'");
	mysqli_query($conexion, "DELETE FROM academico_actividad_preguntas WHERE preg_id_carga='" . $_GET["id"] . "'");
	mysqli_query($conexion, "DELETE FROM academico_actividad_tareas WHERE tar_id_carga='" . $_GET["id"] . "'");
	mysqli_query($conexion, "DELETE FROM academico_actividades WHERE act_id_carga='" . $_GET["id"] . "'");
	mysqli_query($conexion, "DELETE FROM academico_boletin WHERE bol_carga='" . $_GET["id"] . "'");
	mysqli_query($conexion, "DELETE FROM academico_clases WHERE cls_id_carga='" . $_GET["id"] . "'");
	mysqli_query($conexion, "DELETE FROM academico_cronograma WHERE cro_id_carga='" . $_GET["id"] . "'");
	mysqli_query($conexion, "DELETE FROM academico_horarios WHERE hor_id_carga='" . $_GET["id"] . "'");
	mysqli_query($conexion, "DELETE FROM academico_indicadores_carga WHERE ipc_carga='" . $_GET["id"] . "'");
	mysqli_query($conexion, "DELETE FROM academico_nivelaciones WHERE niv_id_asg='" . $_GET["id"] . "'");
	mysqli_query($conexion, "DELETE FROM academico_pclase WHERE pc_id_carga='" . $_GET["id"] . "'");
	mysqli_query($conexion, "DELETE FROM disiplina_nota WHERE dn_id_carga='" . $_GET["id"] . "'");
	mysqli_query($conexion, "DELETE FROM academico_cargas WHERE car_id='" . $_GET["id"] . "'");
} catch (Exception $e) {
	echo $e->getMessage();
	exit();
}	
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");

	echo '<script type="text/javascript">window.location.href="cargas.php?success=SC_DT_3&id='.$_GET["id"].'";</script>';
	exit();