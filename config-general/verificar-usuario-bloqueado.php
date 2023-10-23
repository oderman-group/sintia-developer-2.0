<?php
if($datosUsuarioActual['uss_bloqueado']==1 && !strpos($_SERVER['PHP_SELF'], 'page-info.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
	include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
	$destinos = validarUsuarioActual($datosUsuarioActual);
	echo $destinos;
	header("Location:".$destinos."page-info.php?idmsg=221");
	exit();		
}