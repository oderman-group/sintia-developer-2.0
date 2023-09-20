<?php
include("session.php");
require_once("../class/UsuariosPadre.php");

$idPaginaInterna = 'DT0129';

$_SESSION['admin'] = $_SESSION['id'];

$_SESSION['id'] = base64_decode($_GET['user']);


$_SESSION["datosUsuario"] = UsuariosPadre::sesionUsuario($_SESSION['id']);

include("../compartido/guardar-historial-acciones.php");

switch (base64_decode($_GET['tipe'])) {
	case 2:
		$url = '../docente/index.php';
		if(isset($_GET['carga']) && is_numeric(base64_decode($_GET['carga']))){
			$url = '../docente/guardar.php?get='.base64_encode(100).'&carga='.$_GET["carga"].'&periodo='.$_GET["periodo"];
		}
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