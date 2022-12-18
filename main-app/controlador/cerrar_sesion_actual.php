<?php
	require("../modell/conexion.php");
	include("ip.php");
	$cerrar=mysql_query("UPDATE user SET usrestado='INACTIVO' WHERE usrestado='ACTIVO' AND usrultiaccip='".$ipp."'",$conexion);
	session_start();
	session_destroy();
	setcookie("usr_dir","",-36000);
	setcookie("usr_doc","",-36000);
	setcookie("usr_est","",-36000); 
	setcookie("usr_dir_aj","",-36000); 
	header("Location:../index.php");
	exit();
?>
