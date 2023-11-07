<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0131';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

try{
	$rEntregas = mysqli_query($conexion, "SELECT * FROM academico_actividad_tareas_entregas WHERE ent_id_actividad='".base64_decode($_GET["idR"])."'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$rutaEntregas = '../files/tareas-entregadas';
while($registroEntregas = mysqli_fetch_array($rEntregas, MYSQLI_BOTH)){
	if(file_exists($rutaEntregas."/".$registroEntregas['ent_archivo'])){
		unlink($rutaEntregas."/".$registroEntregas['ent_archivo']);	
	}

	if(file_exists($rutaEntregas."/".$registroEntregas['ent_archivo2'])){
		unlink($rutaEntregas."/".$registroEntregas['ent_archivo2']);	
	}

	if(file_exists($rutaEntregas."/".$registroEntregas['ent_archivo3'])){
		unlink($rutaEntregas."/".$registroEntregas['ent_archivo3']);	
	}
}

try{
	mysqli_query($conexion, "DELETE FROM academico_actividad_tareas_entregas WHERE ent_id_actividad='".base64_decode($_GET["idR"])."'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

try{
	$consultaRegistro=mysqli_query($conexion, "SELECT * FROM academico_actividad_tareas WHERE tar_id='".base64_decode($_GET["idR"])."'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$registro = mysqli_fetch_array($consultaRegistro, MYSQLI_BOTH);

$ruta = '../files/tareas';
if(file_exists($ruta."/".$registro['tar_archivo'])){
	unlink($ruta."/".$registro['tar_archivo']);	
}

if(file_exists($ruta."/".$registro['tar_archivo2'])){
	unlink($ruta."/".$registro['tar_archivo2']);	
}

if(file_exists($ruta."/".$registro['tar_archivo3'])){
	unlink($ruta."/".$registro['tar_archivo3']);	
}

try{
	mysqli_query($conexion, "UPDATE academico_actividad_tareas SET tar_estado=0 WHERE tar_id='".base64_decode($_GET["idR"])."'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="actividades.php?error=ER_DT_3";</script>';
exit();