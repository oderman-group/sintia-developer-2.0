<?php
if($datosUsuarioActual[3]==4){
	$usuarioEstudianteConsultaActual = $_SESSION["id"];
}else{
	$usrEstud="";
	if(!empty($_GET["usrEstud"])){ $usrEstud=base64_decode($_GET["usrEstud"]);}

	if(is_numeric($usrEstud)){
		$usuarioEstudianteConsultaActual = $usrEstud;
	}else{
		//Redireccionamos
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=300";</script>';
		exit();
	}
}

//ESTUDIANTE ACTUAL
require_once("../class/Estudiantes.php");
$datosEstudianteActual = Estudiantes::obtenerDatosEstudiantePorIdUsuario($usuarioEstudianteConsultaActual);