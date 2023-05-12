<?php
session_start();
require_once("../../config-general/config.php");
require_once("../../config-general/consulta-usuario-actual.php");
require_once("../compartido/sintia-funciones.php");
require_once("../class/UsuariosPadre.php");

$destinos = validarUsuarioActual($datosUsuarioActual);

if ($datosUsuarioActual['uss_clave'] != $_POST['claveActual']) {
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

mysqli_query($conexion, "UPDATE usuarios SET uss_clave='" . $_POST["claveNueva"] . "' 
WHERE uss_id='" . $_SESSION["id"] . "'");

$_SESSION["datosUsuario"] = UsuariosPadre::sesionUsuario($_SESSION['id']);

echo '<script type="text/javascript">window.location.href="' .$destinos. 'cambiar-clave.php?success=SC_DT_11";</script>';
exit();