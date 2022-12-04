<?php 
include("conexion-datos.php");
//Conexion con el Servidor
$conexion = mysql_connect($servidorConexion, $usuarioConexion, $claveConexion);
//seleccionamos la base de datos
mysql_select_db($baseDatosServicios, $conexion);
?>