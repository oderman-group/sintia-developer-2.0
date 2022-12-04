<?php
$servidorConexion = 'localhost';
$usuarioConexion = 'mobiliar';
$claveConexion = 'M1X32znd9l';
$conexion = mysql_connect($servidorConexion, $usuarioConexion, $claveConexion);
mysql_select_db("mobiliar_sintiademo",$conexion);

$datos = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE uss_id=1",$conexion));



$datos = [
	'usuario'=>$datos['uss_usuario'], 'docente'=>55, 'institucion'=>4
];

echo json_encode($datos);
?>