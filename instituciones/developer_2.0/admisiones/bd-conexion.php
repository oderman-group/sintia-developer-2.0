<?php
$server = 'sintia.co';
$user = 'mobiliar_enuarlara';
$pass = 'CiUKh?V=_%b(';
$dbName = 'mobiliar_sintia_admisiones';
$dbNameInstitucion = 'mobiliar_dev_2022';

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

#CONSTANTES
$estadosSolicitud = array(
	1 => 'VERIFICACIÓN DE PAGO', 
	2 => 'PAGO RECHAZADO', 
	3 => 'PENDIENTE POR DILIGENCIAR EL FORMULARIO',
	4 => 'EN PROCESO',
	5 => 'EXAMEN Y ENTREVISTA', 
	6 => 'APROBADO', 
	7 => 'NO APROBADO',
	8 => 'VERIFICACIÓN DE CUPO DISPONIBLE',
	9 => 'MOVIDO AL AÑO SIGUIENTE'
);
$progresoSolicitud = array(
	1 => '15%', 
	2 => '15%', 
	3 => '30%', 
	4 => '60%',
	5 => '75%', 
	6 => '90%',
	7 => '100%',
	8 => '15%',
	9 => '100%',
);