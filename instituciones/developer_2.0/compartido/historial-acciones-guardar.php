<?php
include("../modelo/conexion.php");
//TODO EL TEMA DE MODULOS Y PÁGINAS INTERNAS DESDE LA ADMON GENERAL DE SINTIA
$arregloModulos = array();
$modulosSintia = mysql_query("SELECT * FROM ".$baseDatosServicios.".modulos WHERE mod_estado=1",$conexion);
while($modI = mysql_fetch_array($modulosSintia)){
	
	$modulosInstitucion = mysql_num_rows(mysql_query("SELECT * FROM ".$baseDatosServicios.".instituciones_modulos WHERE ipmod_institucion='".$config['conf_id_institucion']."' AND ipmod_modulo='".$modI['mod_id']."'",$conexion));
	//Si tiene el módulo asignado
	if($modulosInstitucion>0){
		$arregloModulos[$modI['mod_id']]=1;
	}
	//Si no lo tiene
	else{
		$arregloModulos[$modI['mod_id']]=0;
	}
}

//Verificar si el usuario está en una pagina de un módulo NO ASIGNADO a la Institución
$paginaActualUsuario = mysql_fetch_array(mysql_query("SELECT * FROM ".$baseDatosServicios.".paginas_publicidad
INNER JOIN ".$baseDatosServicios.".instituciones_modulos ON ipmod_modulo=pagp_modulo
WHERE pagp_id='".$idPaginaInterna."'",$conexion));

if($paginaActualUsuario[0]==""){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=302&idPagina='.$idPaginaInterna.'";</script>';
	exit();	
}



//HISTORIAL DE ACCIONES
mysql_query("INSERT INTO ".$baseDatosServicios.".seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_ip, hil_so, hil_institucion, hil_pagina_anterior)
VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPaginaInterna."', '".$_SERVER["REMOTE_ADDR"]."', '".$_SERVER['HTTP_USER_AGENT']."', '".$config['conf_id_institucion']."', '".$_SERVER["HTTP_REFERER"]."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>