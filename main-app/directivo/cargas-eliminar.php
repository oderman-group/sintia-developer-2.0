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


try {
	mysqli_query($conexion, "INSERT INTO ".BD_ADMIN.".seguridad_historial_registros_borrados(hrb_id_institucion, hrb_year, hrb_id_registro, hrb_responsable, hrb_referencia)VALUES('".$config['conf_id_institucion']."', '".$_SESSION["bd"]."', '".base64_decode($_GET["id"])."', '".$_SESSION["id"]."', 'CARGA_ACADEMICA')");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="cargas.php?success=SC_DT_3&id='.$_GET["id"].'";</script>';
exit();