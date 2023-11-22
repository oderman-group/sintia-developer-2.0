<?php
session_start();
require_once("../../config-general/config.php");
require_once("../../config-general/consulta-usuario-actual.php");
require_once("../compartido/sintia-funciones.php");
require_once("../class/UsuariosPadre.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0001';
include("../compartido/historial-acciones-guardar.php");

$destinos = validarUsuarioActual($datosUsuarioActual);

if ($datosUsuarioActual['uss_clave'] != SHA1($_POST['claveActual'])) {
	echo '<script type="text/javascript">window.location.href="' .$destinos. 'cambiar-clave.php?error=ER_DT_12";</script>';
	exit();
}

if ($_POST['claveNueva'] != $_POST['claveNuevaDos']) {
	echo '<script type="text/javascript">window.location.href="' .$destinos. 'cambiar-clave.php?error=ER_DT_13";</script>';
	exit();
}

if(!validarClave($_POST["claveNueva"])){
	echo '<script type="text/javascript">window.location.href="' .$destinos. 'cambiar-clave.php?error=5";</script>';
	exit();
}

try{
	mysqli_query($conexion, "UPDATE ".BD_GENERAL.".usuarios SET uss_clave=SHA1('" . $_POST["claveNueva"] . "') WHERE uss_id='" . $_SESSION["id"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
$_SESSION["datosUsuario"] = UsuariosPadre::sesionUsuario($_SESSION['id']);

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="' .$destinos. 'cambiar-clave.php?success=SC_DT_11";</script>';
exit();