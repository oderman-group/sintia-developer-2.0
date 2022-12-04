<?php
include("session.php");

$_SESSION['admin'] = $_SESSION['id'];

$_SESSION['id'] = $_GET['user'];


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