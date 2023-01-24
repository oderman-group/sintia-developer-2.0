<?php
include("session.php");

$_SESSION['admin'] = $_SESSION['id'];

$_SESSION['id'] = $_GET['user'];

mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_so, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', 'autologin', now(),'".php_uname()."','".$_SERVER['HTTP_REFERER']."')");

switch ($_GET['tipe']) {
	case 2:
		$url = '../docente/index.php';
	break;

	case 3:
		$url = '../acudiente/index.php';
	break;

	case 4:
		$url = '../estudiante/index.php';
	break;

	default:
		$url = '../controlador/salir.php';
	break;
}

header("Location:".$url);