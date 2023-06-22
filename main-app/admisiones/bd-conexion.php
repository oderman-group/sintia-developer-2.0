<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/config-admisiones.php");

$server 		   = $servidorConexion;
$user   		   = $usuarioConexion;
$pass   		   = $claveConexion;
$dbName 		   = $baseDatosAdmisiones;

if(!empty($_REQUEST['idInst']) || !empty($_REQUEST['institucion']) || $idInstitucion==1){
	try{
		$pdoAdmin = new PDO('mysql:host='.$server.';dbname='.$baseDatosServicios, $user, $pass);
	}catch (PDOException $e) {
		echo "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
	
	if(!empty($_REQUEST['institucion'])){
		$idInsti=$_REQUEST['institucion'];
	}elseif(!empty($_REQUEST['idInst'])){
		$idInsti=$_REQUEST['idInst'];
	}else{
		$idInsti=$idInstitucion;
	}

	//configuración
	$configConsulta = "SELECT * FROM configuracion
	WHERE conf_id_institucion = ".$idInsti." AND conf_agno = ".date("Y");
	$config = $pdoAdmin->prepare($configConsulta);
	$config->execute();
	$datosConfig = $config->fetch();

	//información
	$infogConsulta = "SELECT * FROM general_informacion
	WHERE info_institucion = ".$idInsti." AND info_year = ".date("Y");
	$info = $pdoAdmin->prepare($infogConsulta);
	$info->execute();
	$datosInfo = $info->fetch();

	$BD_ADMISIONES_MOCK = $datosConfig['conf_base_datos'].'_'.$datosConfig['conf_agno'];
}

if(!empty($_REQUEST['inst'])){
	$BD_ADMISIONES_MOCK = base64_decode($_REQUEST['inst']);
}

try{
	$pdo = new PDO('mysql:host='.$server.';dbname='.$dbName, $user, $pass);
}catch (PDOException $e) {
	echo "Error!: " . $e->getMessage() . "<br/>";
	die();
}

$dbNameInstitucion = $BD_ADMISIONES_MOCK;

try{
	$pdoI = new PDO('mysql:host='.$server.';dbname='.$dbNameInstitucion, $user, $pass);
}catch (PDOException $e) {
	echo "Error!: " . $e->getMessage() . "<br/>";
	die();
}