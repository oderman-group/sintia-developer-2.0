<?php
if($datosUsuarioActual['uss_bloqueado']==1 && !strpos($_SERVER['PHP_SELF'], 'page-info.php'))
{
	include("../compartido/sintia-funciones.php");
	$destinos = validarUsuarioActual($datosUsuarioActual);
	echo $destinos;
	header("Location:".$destinos."page-info.php?idmsg=221");
	exit();		
}