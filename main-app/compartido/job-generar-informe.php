<?php
session_start();
include("../../config-general/config.php");
require_once("../class/Sysjobs.php");

$parametros = array(
    "carga" =>$_GET['carga'],
    "periodo" => $_GET['periodo'],
    "grado" => $_GET['grado'],
	"grupo"=>$_GET['grupo']
);

$parametrosBuscar = array(
    "tipo" =>JOBS_TIPO_GENERAR_INFORMES,
    "responsable" => $_SESSION['id'],
    "parametros" => json_encode($parametros),
	"agno"=>$config['conf_agno']
);
$buscarJobs=SysJobs::consultar($parametrosBuscar);
	
	try{
    //HISTORIAL DE ACCIONES
	$cantidad = mysqli_num_rows($buscarJobs);	
	$direccionOrigen = explode("?", $_SERVER["HTTP_REFERER"]);
	echo '<script type="text/javascript">window.location.href=;.$direccionOrigen[0]."?success=SC_DT_1&id=' . $idRegistro . '";</script>';

	if($cantidad<1){
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".sys_jobs(
			job_estado, 
			job_tipo, 
			job_fecha_creacion, 
			job_responsable, 
			job_id_institucion, 			
			job_mensaje,
			job_parametros,
			job_year, 
			job_intentos,
			job_prioridad)
		VALUES(
			'".JOBS_ESTADO_PENDIENTE."',
			'".JOBS_TIPO_GENERAR_INFORMES."',
			NOW(), 
			'".$_SESSION['id']."', 
			'".$config['conf_id_institucion']."', 
			'Generando el primer Jobs', 
			'".json_encode($parametros)."', 
			'".$config['conf_agno']."', 
			'1', 
			'".JOBS_PRIORIDAD_MEDIA."'
		)");
		$idRegistro = mysqli_insert_id($conexion);
		$mensaje="Se realizó exitosamente el proceso de generación de informe con el código ".$idRegistro;
	}else{
		$jobsEncontrado = mysqli_fetch_array($buscarJobs, MYSQLI_BOTH);
		$intentos = intval($jobsEncontrado["job_intentos"])+1;
		$datos = array(
			"intentos" =>$intentos,
			"id" => $jobsEncontrado['job_id']
		);
		
		SysJobs::actualizar($datos);
		$idRegistro = $jobsEncontrado["job_id"];
			$mensaje="Se actualizó exitosamente el proceso de generación de informe con el código ".$idRegistro." intentos(".$intentos.")";
	}
	
	include("../compartido/guardar-historial-acciones.php");	
	$url=$direccionOrigen[0].'?success=SC_DT_4&summary=' . $mensaje;
    echo '<script type="text/javascript">window.location.href="../docente/cargas.php?success=SC_DT_4&summary=' . $mensaje.'";</script>';
	exit();

} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}