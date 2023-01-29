<?php 
include("../modelo/conexion.php");

$idPaginaInterna = 'GN0002';

if($_SESSION["id"]==""){
	header("Location:../index.php?s=0");
	exit();
}
mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_so, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPaginaInterna."', now(),'".php_uname()."','".$_SERVER['HTTP_REFERER']."')");



mysqli_query($conexion, "UPDATE usuarios SET uss_estado=0, uss_ultima_salida=now() WHERE uss_id='".$_SESSION["id"]."'");
setcookie("carga","",time()-3600);
setcookie("periodo","",time()-3600);
session_destroy();

header("Location:".$REDIRECT_ROUTE);