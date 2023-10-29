<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
if (isset($_SESSION["id"]) and $_SESSION["id"] != "") {

	$pagina = 'index.php';

	if (isset($_GET["urlDefault"]) and $_GET["urlDefault"] != "") {
		$pagina = $_GET["urlDefault"];
	}

    require_once(ROOT_PATH."/main-app/modelo/conexion.php");
	require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");
    
    $consultaSesion = UsuariosPadre::obtenerTodosLosDatosDeUsuarios(" AND uss_id='" . $_SESSION["id"] . "'");
		$sesionAbierta = mysqli_fetch_array($consultaSesion, MYSQLI_BOTH);

		switch ($sesionAbierta[3]) {
			case 1:
				$url = 'directivo/'.$pagina;
				break;
			case 2:
				$url = 'docente/'.$pagina;
				break;
			case 3:
				$url = 'acudiente/'.$pagina;
				break;
			case 4:
				$url = 'estudiante/'.$pagina;
				break;
			case 5:
				$url = 'directivo/'.$pagina;
				break;
			default:
				$url = 'controlador/salir.php';
				break;
		}

    header("Location:" . $url);
		exit();
}


try {
	$conexionBaseDatosServicios = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);
	$institucionesConsulta = mysqli_query($conexionBaseDatosServicios, "SELECT * FROM ".$baseDatosServicios.".instituciones 
	WHERE ins_estado = 1 AND ins_enviroment='".ENVIROMENT."'");
} catch(Exception $e){

	switch($e->getCode()){
		case 1044:
			$exception = "error=7";
		break;

		case 2002:
			$exception = "error=8&nodb=1";
		break;

		default:
			$exception = "nodb=1&error=".$e->getMessage()."&code=".$e->getCode();
		break;	
	}

	session_destroy();
	header("Location:".REDIRECT_ROUTE."/index.php?".$exception);
	exit();
}

require_once(ROOT_PATH."/main-app/class/Plataforma.php");

$datosContactoSintia = Plataforma::infoContactoSintia();