<?php
/*session_start();
$_SESSION["bd"] = date("Y");*/
include("../modelo/conexion.php");
include("../class/UsuariosPadre.php");

$fila = UsuariosPadre::sesionUsuario($_GET["usuario"]);
if(!empty($fila))
{
	//session_destroy();
	session_start();
	$_SESSION["idO"] = $fila[0];
	switch($fila[3]){
		case 1:
		  $url = '../admin';
		break;
		
		case 2:
		  setcookie("carga","",-3600);
		  $_SESSION["carga"] = "";
		  $url = '../docente';
		break;
		
		case 3:
		  $url = '../acudiente';
		break;
		
		case 4:
		  $url = '../estudiante';
		break;
		
		case 5:
		  $url = '../directivo';
		break;
	}
	header("Location:".$url);	
	exit();
}else{
	header("Location:../index.php?error=2");
	exit();
}
?>
