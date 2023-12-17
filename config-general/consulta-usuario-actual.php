<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");
//SE RECARGA VARIABLE SESSION PARA EL USUARIO ACTUAL
if(isset($_SESSION["yearAnterior"])){
	$_SESSION["datosUsuario"] = UsuariosPadre::sesionUsuario($_SESSION["id"]);
}

if(!isset($idSession) || $idSession==""){$idSession = $_SESSION["id"];}
$datosUsuarioActual = $_SESSION["datosUsuario"];
