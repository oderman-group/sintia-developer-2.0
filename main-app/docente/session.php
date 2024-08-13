<?php 
session_start();
//Si otro usuario de mayor rango entra como él
if (isset($_SESSION["idO"]) && $_SESSION["idO"]!="") {
	$idSession = $_SESSION["idO"];
} else {
	$idSession = $_SESSION["id"] ?? null;
}

if (empty($idSession)) {
	require_once '../class/Utilidades.php';
	$directory = Utilidades::getDirectoryUserFromUrl($_SERVER['PHP_SELF']);
	$page      = Utilidades::getPageFromUrl($_SERVER['PHP_SELF']);
	header("Location:../controlador/salir.php?urlDefault=".$page."&directory=".$directory);
} else {
	require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
	require_once(ROOT_PATH."/config-general/config.php");
	require_once(ROOT_PATH."/config-general/idiomas.php");
	require_once(ROOT_PATH."/config-general/consulta-usuario-actual.php");
	require_once(ROOT_PATH."/config-general/verificar-usuario-bloqueado.php");
	
	if ($datosUsuarioActual['uss_tipo'] != TIPO_DOCENTE) {
		require_once("../compartido/sintia-funciones.php");
		$destinos = validarUsuarioActual($datosUsuarioActual);
		echo '<script type="text/javascript">window.location.href="'.$destinos.'page-info.php?idmsg=301;</script>';
		exit();		
	}
}
