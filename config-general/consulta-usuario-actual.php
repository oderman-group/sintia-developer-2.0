<?php
require_once("../class/UsuariosPadre.php");
//SE RECARGA VARIABLE SESSION PARA EL USUARIO ACTUAL
if(isset($_SESSION["yearAnterior"])){
	$_SESSION["datosUsuario"] = UsuariosPadre::sesionUsuario($_SESSION["id"]);
}

if(!isset($idSession) || $idSession==""){$idSession = $_SESSION["id"];}
$datosUsuarioActual = $_SESSION["datosUsuario"];
