<?php
include("session.php");

$idPaginaInterna = 'DT0129';

$_SESSION['admin'] = $_SESSION['id'];

$_SESSION['id'] = $_GET['user'];

$consultaUsuarioAuto = mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='".$_SESSION['id']."'");
$datosUsuarioAuto = mysqli_fetch_array($consultaUsuarioAuto, MYSQLI_BOTH);
$_SESSION["datosUsuario"] = $datosUsuarioAuto;

include("../compartido/guardar-historial-acciones.php");

switch ($_GET['tipe']) {
	case 2:
		$url = '../docente/index.php';
		if(isset($_GET['carga']) && is_numeric($_GET['carga'])){
			$url = '../docente/guardar.php?get=100&carga='.$_GET["carga"].'&periodo='.$_GET["periodo"];
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