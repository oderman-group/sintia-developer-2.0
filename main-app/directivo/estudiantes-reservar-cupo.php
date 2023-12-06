<?php
include("session.php");
include("../modelo/conexion.php");
require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");

try{
	mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_encuestas(genc_estudiante, genc_fecha, genc_respuesta, genc_comentario, genc_institucion, genc_year) VALUES('".base64_decode($_GET["idEstudiante"])."', now(), 1, 'Reservado por un directivo (".UsuariosPadre::nombreCompletoDelUsuario($datosUsuarioActual).").','" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "') 
	ON DUPLICATE KEY UPDATE
	genc_fecha = VALUES(genc_fecha),
	genc_comentario = VALUES(genc_comentario),
	genc_respuesta = VALUES(genc_respuesta)
	");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
exit();