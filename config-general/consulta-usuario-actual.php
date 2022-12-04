<?php
if($idSession==""){$idSession = $_SESSION["id"];}
//USUARIO ACTUAL
$consultaUsuarioActual = $conexion->query("SELECT * FROM usuarios WHERE uss_id='".$idSession."'");

$numUsuarioActual = $consultaUsuarioActual->num_rows;
$datosUsuarioActual = mysqli_fetch_array($consultaUsuarioActual, MYSQLI_BOTH);

/*
//Verificar si cumpleaños
$cumpleUsuario = mysql_fetch_array(mysql_query("SELECT YEAR(uss_fecha_nacimiento) AS agno FROM usuarios 
WHERE MONTH(uss_fecha_nacimiento)='".date("m")."' AND DAY(uss_fecha_nacimiento)='".date("d")."' AND uss_id='".$idSession."'",$conexion));
$edadUsuario = date("Y") - $cumpleUsuario['agno'];
*/
?>