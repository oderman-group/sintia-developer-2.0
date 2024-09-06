<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/config-admisiones.php");
$server 		   = $servidorConexion;
$user   		   = $usuarioConexion;
$pass   		   = $claveConexion;
$dbName 		   = $baseDatosAdmisiones;
$conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion);
if(!empty($_REQUEST['idInst'])){
	$idInsti=base64_decode($_REQUEST['idInst']);
	try{
		$pdoAdmin = new PDO('mysql:host='.$server.';dbname='.$baseDatosServicios, $user, $pass);
	}catch (PDOException $e) {
		echo "Error!: " . $e->getMessage() . "<br/>";
		header("Location:".REDIRECT_ROUTE."/admisiones".$e);
		die();
	}

	
	//configuración
	$configConsulta = "SELECT * FROM configuracion
	INNER JOIN {$baseDatosAdmisiones}.config_instituciones ON cfgi_id_institucion=conf_id_institucion AND cfgi_inscripciones_activas=1 AND cfgi_year = conf_agno
	WHERE conf_id_institucion = ".$idInsti." AND conf_agno = ".date("Y");
	$configuracion = $pdoAdmin->prepare($configConsulta);
	$configuracion->execute();
	$config = $configuracion->fetch();

	if(empty($config['conf_id_institucion']) || empty($config['conf_agno'])) {
		header("Location:".REDIRECT_ROUTE."/admisiones");
		exit();
	}
	

	//información
	$infogConsulta = "SELECT * FROM general_informacion
	WHERE info_institucion = ".$idInsti." AND info_year = ".date("Y");
	$info = $pdoAdmin->prepare($infogConsulta);
	$info->execute();
	$datosInfo = $info->fetch();


	$BD_ADMISIONES_MOCK = $baseDatosServicios;

} else {
	header("Location:".REDIRECT_ROUTE."/admisiones");
	exit();
}

try{
	$pdo = new PDO('mysql:host='.$server.';dbname='.$dbName, $user, $pass);
}catch (PDOException $e) {
	echo "Error!: " . $e->getMessage() . "<br/>";
	header("Location:".REDIRECT_ROUTE."/admisiones".$e);
	die();
}

$dbNameInstitucion = !empty($BD_ADMISIONES_MOCK) ? $BD_ADMISIONES_MOCK : $baseDatosServicios;

try{
	$pdoI = new PDO('mysql:host='.$server.';dbname='.$dbNameInstitucion, $user, $pass);
}catch (PDOException $e) {
	header("Location:".REDIRECT_ROUTE."/admisiones".$e);
	echo "Error!: " . $e->getMessage() . "<br/>";
	exit();
}