<?php 
session_start();
include("../../../conexion-datos.php");
$conexion = mysql_connect($servidorConexion, $usuarioConexion, $claveConexion);
$institucionConsulta = mysql_query("SELECT * FROM ".$baseDatosServicios.".instituciones WHERE ins_id='".$_POST["bd"]."'",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
$institucion = mysql_fetch_array($institucionConsulta);

$_SESSION["inst"] = $institucion['ins_bd'];

if(isset($_POST["agnoIngreso"]) and is_numeric($_POST["agnoIngreso"])){
	$_SESSION["bd"] = $_POST["agnoIngreso"];
}else{
	$_SESSION["bd"] = date("Y");
}

include("../modelo/conexion.php");

$pos = strstr($_POST["Usuario"], '#');
$poss = strstr($_POST["Usuario"], '--');
$posC = strstr($_POST["Clave"], '#');
$possC = strstr($_POST["Clave"], '--');
if($pos or $poss){
	header("Location:http://www.eumed.net/rev/cccss/04/rbar2.pdf");
	exit();
}
if($posC or $possC){
	header("Location:http://www.eumed.net/rev/cccss/04/rbar2.pdf");
	exit();
}



$rst_usrE = mysql_query("SELECT uss_usuario, uss_id, uss_intentos_fallidos FROM usuarios 
WHERE uss_usuario='".trim(mysql_real_escape_string($_POST["Usuario"]))."' AND TRIM(uss_usuario)!='' AND uss_usuario IS NOT NULL",$conexion);
if(mysql_errno()!=0){echo "44". mysql_error(); exit();}
$numE = mysql_num_rows($rst_usrE);
if($numE==0){
	header("Location:../index.php?error=1");
	exit();
}
$usrE = mysql_fetch_array($rst_usrE);

if($usrE['uss_intentos_fallidos']>3 and md5($_POST["suma"])<>$_POST["sumaReal"]){
	header("Location:../index.php?error=3");
	exit();
}

$rst_usr = mysql_query("SELECT * FROM usuarios 
WHERE uss_usuario='".trim(mysql_real_escape_string($_POST["Usuario"]))."' AND uss_clave='".mysql_real_escape_string($_POST["Clave"])."' AND TRIM(uss_usuario)!='' AND uss_usuario IS NOT NULL AND TRIM(uss_clave)!='' AND uss_clave IS NOT NULL",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
$num = mysql_num_rows($rst_usr);
$fila = mysql_fetch_array($rst_usr);
if($num>0)
{
	//VERIFICAR SI EL USUARIO ESTÁ BLOQUEADO
	//if($fila[20]==1){header("Location:../index.php?error=4");exit();}
	
	//INICIO SESION
	$_SESSION["id"] = $fila[0];
	
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
	include("navegador.php");
	include("ip.php");
	mysql_query("UPDATE usuarios SET uss_estado=1, uss_ultimo_ingreso=now(), uss_intentos_fallidos=0 WHERE uss_id='".$fila[0]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error();exit();}

	mysql_query("INSERT INTO seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_so, hil_pagina_anterior)VALUES('".$fila[0]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', 'Ingreso al sistema', now(),'".php_uname()."','".$_SERVER['HTTP_REFERER']."')",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}

	echo '<script type="text/javascript">window.location.href="'.$url.'";</script>';
	exit();
}else{
	mysql_query("UPDATE usuarios SET uss_intentos_fallidos=uss_intentos_fallidos+1 WHERE uss_id='".$usrE['uss_id']."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error();exit();}

	mysql_query("INSERT INTO ".$baseDatosServicios.".usuarios_intentos_fallidos(uif_usuarios, uif_ip, uif_clave, uif_institucion)VALUES('".$usrE['uss_id']."', '".$_SERVER['REMOTE_ADDR']."', '".mysql_real_escape_string($_POST["Clave"])."', '".$_POST["bd"]."')",$conexion);
	if(mysql_errno()!=0){echo mysql_error();exit();}

	header("Location:../index.php?error=2");
	exit();
}
?>