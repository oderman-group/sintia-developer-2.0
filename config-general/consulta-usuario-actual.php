<?php
if(!isset($idSession) || $idSession==""){$idSession = $_SESSION["id"];}
//USUARIO ACTUAL
$consultaUsuarioActual = mysql_query("SELECT * FROM usuarios WHERE uss_id='".$idSession."'",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
$numUsuarioActual = mysql_num_rows($consultaUsuarioActual);
$datosUsuarioActual = mysql_fetch_array($consultaUsuarioActual);


//Verificar si cumpleaños
$cumpleUsuario = mysql_fetch_array(mysql_query("SELECT YEAR(uss_fecha_nacimiento) AS agno FROM usuarios 
WHERE MONTH(uss_fecha_nacimiento)='".date("m")."' AND DAY(uss_fecha_nacimiento)='".date("d")."' AND uss_id='".$idSession."'",$conexion));
$edadUsuario = date("Y") - $cumpleUsuario['agno'];
?>