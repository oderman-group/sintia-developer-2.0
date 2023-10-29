<?php
include($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
//include(ROOT_PATH."/conexion-datos.php");

require_once(ROOT_PATH."/main-app/class/Usuarios.php");
require_once(ROOT_PATH."/main-app/class/EnviarEmail.php");

$conexionBaseDatosServicios = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);
$institucionConsulta = mysqli_query($conexionBaseDatosServicios, "SELECT * FROM ".$baseDatosServicios.".instituciones 
WHERE ins_id='".$_POST["bd"]."'");

$institucion = mysqli_fetch_array($institucionConsulta, MYSQLI_BOTH);

$yearArray = explode(",", $institucion['ins_years']);
$yearStart = $yearArray[0];
$yearEnd = $yearArray[1];

$_SESSION["inst"] = $institucion['ins_bd'];

if(isset($yearEnd) and is_numeric($yearEnd)){
	$_SESSION["bd"] = $yearEnd;
}else{
	$_SESSION["bd"] = date("Y");
}

include("modelo/conexion.php");

$variable = 1;

$datosUsuario = Usuarios::datosUsuarioParaRecuperarClave($_POST["Usuario"]);


if(!empty($datosUsuario)){

	$data = [
		'institucion_id'   => $institucion['ins_id'],
		'institucion_bd'   => $institucion['ins_bd'],
		'institucion_agno' => $_SESSION["bd"],
		'usuario_id'       => $datosUsuario['uss_id'],
		'usuario_email'    => $datosUsuario['uss_email'],
		'usuario_nombre'   => $datosUsuario['uss_nombre'],
		'usuario_usuario'  => $datosUsuario['uss_usuario'],
		'usuario_clave'    => $datosUsuario['uss_clave'],
		'nueva_clave'      => Usuarios::generatePassword(8)
	];
	$asunto = 'Tus credenciales han llegado';
	$bodyTemplateRoute = ROOT_PATH.'/config-general/template-email-recuperar-clave.php';
	
	EnviarEmail::enviar($data, $asunto, $bodyTemplateRoute,null,null);
	Usuarios::guardarRegistroRestaruracion($data);

	echo '<script type="text/javascript">window.location.href="index.php?success=SC_DT_5&email='.$datosUsuario['uss_email'].'";</script>';
	exit();	
	
}else{
	echo '<script type="text/javascript">window.location.href="recuperar-clave.php?error=1&inst='.$institucion['ins_id'].'";</script>';
	exit();	
}