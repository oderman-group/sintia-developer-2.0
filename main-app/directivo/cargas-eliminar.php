<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0148';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/EnviarEmail.php");
require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");

try{
	mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_cargas WHERE car_id='" . base64_decode($_GET["id"]) . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");

	$contenidoMsg = '
	<p>Se eliminó una carga académica. A continuación relacionamos la información:</p>
	<p>
		<b>ID carga:</b> '.base64_decode($_GET["id"]).'<br>
		<b>Institucion:</b> '.$config['conf_id_institucion'].'<br>
		<b>Año:</b> '.$_SESSION["bd"].'<br>
		<b>Responsable:</b> '.$_SESSION["id"].' - '.UsuariosPadre::nombreCompletoDelUsuario($datosUsuarioActual).'
	</p>
	';
	

} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

$data = [
	'usuario_email'    => 'info@oderman-group.com',
	'usuario_nombre'   => 'Jhon Oderman',
	'usuario2_email'   => $datosUsuarioActual['uss_email'],
	'usuario2_nombre'  => $datosUsuarioActual['uss_nombre'],
	'institucion_id'   => $config['conf_id_institucion'],
	'institucion_agno' => $_SESSION["bd"],
	'usuario_id'       => $_SESSION["id"],
	'contenido_msj'    => $contenidoMsg
];
$asunto = 'Se eliminó la carga académica - COD: '.base64_decode($_GET["id"]);
$bodyTemplateRoute = ROOT_PATH.'/config-general/plantilla-email-2.php';

EnviarEmail::enviar($data, $asunto, $bodyTemplateRoute, null, null);

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="cargas.php?success=SC_DT_3&id='.$_GET["id"].'";</script>';
exit();