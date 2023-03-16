<?php 
session_start();
$idPaginaInterna = 'GN0001';
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
include(ROOT_PATH."/conexion-datos.php");
$conexionBaseDatosServicios = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);
$institucionConsulta = mysqli_query($conexionBaseDatosServicios, "SELECT * FROM ".$baseDatosServicios.".instituciones WHERE ins_id='".$_POST["bd"]."'");

$institucion = mysqli_fetch_array($institucionConsulta, MYSQLI_BOTH);

$_SESSION["inst"] = $institucion['ins_bd'];

if(isset($_POST["agnoIngreso"]) and is_numeric($_POST["agnoIngreso"])){
	$_SESSION["bd"] = $_POST["agnoIngreso"];
}else{
	$_SESSION["bd"] = date("Y");
}

include("../modelo/conexion.php");


$rst_usrE = mysqli_query($conexion, "SELECT uss_usuario, uss_id, uss_intentos_fallidos FROM usuarios 
WHERE uss_usuario='".trim($_POST["Usuario"])."' AND TRIM(uss_usuario)!='' AND uss_usuario IS NOT NULL");

$numE = mysqli_num_rows($rst_usrE);
if($numE==0){
	header("Location:".REDIRECT_ROUTE."/index.php?error=1&inst=".$_POST["bd"]."&year=".$_POST["agnoIngreso"]);
	exit();
}
$usrE = mysqli_fetch_array($rst_usrE, MYSQLI_BOTH);

if($usrE['uss_intentos_fallidos']>3 and md5($_POST["suma"])<>$_POST["sumaReal"]){
	header("Location:".REDIRECT_ROUTE."/index.php?error=3&msg=varios-intentos-fallidos:".$usrE['uss_intentos_fallidos']."&inst=".$_POST["bd"]."&year=".$_POST["agnoIngreso"]);
	exit();
}

$rst_usr = mysqli_query($conexion, "SELECT * FROM usuarios 
WHERE uss_usuario='".trim($_POST["Usuario"])."' AND uss_clave='".$_POST["Clave"]."' AND TRIM(uss_usuario)!='' AND uss_usuario IS NOT NULL AND TRIM(uss_clave)!='' AND uss_clave IS NOT NULL");

$num = mysqli_num_rows($rst_usr);
$fila = mysqli_fetch_array($rst_usr, MYSQLI_BOTH);
if($num>0)
{	
	if($fila['uss_bloqueado'] == 1){
		header("Location:".REDIRECT_ROUTE."/index.php?error=6&inst=".$_POST["bd"]."&year=".$_POST["agnoIngreso"]);
		exit();
	}

	$URLdefault = 'noticias.php';
	if($_POST["urlDefault"]!=""){$URLdefault = $_POST["urlDefault"];}	
	
	switch($fila[3]){
		case 1:
			$url = '../directivo/'.$URLdefault;
		break;
		
		case 2:
		  $url = '../docente/cargas.php';
		break;
		
		case 3:
		  $url = '../acudiente/estudiantes.php';
		break;
		
		case 4:
		  $url = '../estudiante/matricula.php';
		break;
		
		case 5:
		  $url = '../directivo/'.$URLdefault;
		break;
		
		default:
		  $url = 'salir.php';
		break;
	}
	
	$configConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".configuracion WHERE conf_base_datos='".$_SESSION["inst"]."' AND conf_agno='".$_SESSION["bd"]."'");
	$config = mysqli_fetch_array($configConsulta, MYSQLI_BOTH);
	$_SESSION["configuracion"] = $config;

	$informacionInstConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_informacion WHERE info_institucion='" . $config['conf_id_institucion'] . "' AND info_year='" . $_SESSION["bd"] . "'");
	$informacion_inst = mysqli_fetch_array($informacionInstConsulta, MYSQLI_BOTH);
	$_SESSION["informacionInstConsulta"] = $informacion_inst;

	$datosUnicosInstitucionConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".instituciones WHERE ins_id='".$config['conf_id_institucion']."'");
	$datosUnicosInstitucion = mysqli_fetch_array($datosUnicosInstitucionConsulta, MYSQLI_BOTH);
	$_SESSION["datosUnicosInstitucion"] = $datosUnicosInstitucion;

	$arregloModulos = array();
	$modulosSintia = mysqli_query($conexion, "SELECT mod_id, mod_nombre FROM ".$baseDatosServicios.".modulos
	INNER JOIN ".$baseDatosServicios.".instituciones_modulos ON ipmod_institucion='".$config['conf_id_institucion']."' AND ipmod_modulo=mod_id
	WHERE mod_estado=1");
	while($modI = mysqli_fetch_array($modulosSintia, MYSQLI_BOTH)){
		$arregloModulos [$modI['mod_id']] = $modI['mod_nombre'];
	}

	$_SESSION["modulos"] = $arregloModulos;

	//INICIO SESION
	$_SESSION["id"] = $fila[0];
	$_SESSION["datosUsuario"] = $fila;

	include("navegador.php");
	include("ip.php");
	mysqli_query($conexion, "UPDATE usuarios SET uss_estado=1, uss_ultimo_ingreso=now(), uss_intentos_fallidos=0 WHERE uss_id='".$fila[0]."'");

	mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_so, hil_pagina_anterior)VALUES('".$fila[0]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPaginaInterna."', now(),'".php_uname()."','".$_SERVER['HTTP_REFERER']."')");

	echo '<script type="text/javascript">window.location.href="'.$url.'";</script>';
	exit();
}else{
	mysqli_query($conexion, "UPDATE usuarios SET uss_intentos_fallidos=uss_intentos_fallidos+1 WHERE uss_id='".$usrE['uss_id']."'");


	mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".usuarios_intentos_fallidos(uif_usuarios, uif_ip, uif_clave, uif_institucion, uif_year)VALUES('".$usrE['uss_id']."', '".$_SERVER['REMOTE_ADDR']."', '".$_POST["Clave"]."', '".$_POST["bd"]."', '".$_SESSION["bd"]."')");


	header("Location:".REDIRECT_ROUTE."/index.php?error=2&inst=".$_POST["bd"]."&year=".$_POST["agnoIngreso"]);
	exit();
}