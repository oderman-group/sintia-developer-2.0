<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0131';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Actividades.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

$idR="";
if(!empty($_GET["idR"])){ $idR=base64_decode($_GET["idR"]);}

try{
	$rEntregas = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_tareas_entregas WHERE ent_id_actividad='".base64_decode($_GET["idR"])."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$rutaEntregas = '../files/tareas-entregadas';

while($registroEntregas = mysqli_fetch_array($rEntregas, MYSQLI_BOTH)){
	$url1= $storage->getBucket()->object(FILE_TAREAS_ENTREGADAS.$registroEntregas["ent_archivo"])->signedUrl(new DateTime('tomorrow'));
	$existe1=$storage->getBucket()->object(FILE_TAREAS_ENTREGADAS.$registroEntregas["ent_archivo"])->exists();
	if($existe1){
		unlink($url1);	
	}
	$url2= $storage->getBucket()->object(FILE_TAREAS_ENTREGADAS.$registroEntregas["ent_archivo2"])->signedUrl(new DateTime('tomorrow'));
	$existe2=$storage->getBucket()->object(FILE_TAREAS_ENTREGADAS.$registroEntregas["ent_archivo2"])->exists();
	if($existe2){
		unlink($url2);	
	}
	$url3= $storage->getBucket()->object(FILE_TAREAS_ENTREGADAS.$registroEntregas["ent_archivo3"])->signedUrl(new DateTime('tomorrow'));
	$existe3=$storage->getBucket()->object(FILE_TAREAS_ENTREGADAS.$registroEntregas["ent_archivo3"])->exists();
	if($existe3){
		unlink($url3);	
	}
}

try{
	mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_tareas_entregas WHERE ent_id_actividad='".base64_decode($_GET["idR"])."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

Actividades::eliminarActividad($conexion, $config, $idR, $storage);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="actividades.php?error=ER_DT_3";</script>';
exit();