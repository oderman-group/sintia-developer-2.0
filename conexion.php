<?php 
include("conexion-datos.php");
//Conexion con el Servidor
$conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion);
//seleccionamos la base de datos
mysqli_select_db($conexion, $baseDatosServicios);
?>