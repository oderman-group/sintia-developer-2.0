<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/conexion-datos.php");
$server = $servidorConexion;
$user = $usuarioConexion;
$pass = $claveConexion;
$dbName = $baseDatosAdmisiones;
$dbNameInstitucion = 'odermangroup_dev_2024';

try{
	$pdo = new PDO('mysql:host='.$server.';dbname='.$dbName, $user, $pass);
    //$pdo->exec("SET CHARACTER SET utf-8");
}catch (PDOException $e) {
	echo "Error!: " . $e->getMessage() . "<br/>";
	die();
}

try{
	$pdoI = new PDO('mysql:host='.$server.';dbname='.$dbNameInstitucion, $user, $pass);
    //$pdoI->exec("SET CHARACTER SET utf-8");
}catch (PDOException $e) {
	echo "Error!: " . $e->getMessage() . "<br/>";
	die();
}

require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/config-admisiones.php");