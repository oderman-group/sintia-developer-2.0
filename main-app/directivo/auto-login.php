<?php
include("session.php");

$idPaginaInterna = 'DT0129';

$_SESSION['admin'] = $_SESSION['id'];

$_SESSION['id'] = $_GET['user'];

include("../compartido/guardar-historial-acciones.php");

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