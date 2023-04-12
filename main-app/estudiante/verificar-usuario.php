<?php
if($datosUsuarioActual[3]==4){
	$usuarioEstudianteConsultaActual = $_SESSION["id"];
}else{
	if(is_numeric($_GET["usrEstud"])){
		$usuarioEstudianteConsultaActual = $_GET["usrEstud"];
	}else{
		//Redireccionamos
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=300";</script>';
		exit();
	}
}

//ESTUDIANTE ACTUAL
require_once("../class/Estudiantes.php");
$datosEstudianteActual = Estudiantes::obtenerDatosEstudiantePorIdUsuario($usuarioEstudianteConsultaActual);