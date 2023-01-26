<?php
include("../modelo/conexion.php");
//TODO EL TEMA DE MODULOS Y PÁGINAS INTERNAS DESDE LA ADMON GENERAL DE SINTIA
$arregloModulos = array();
$modulosSintia = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".modulos WHERE mod_estado=1");
while($modI = mysqli_fetch_array($modulosSintia, MYSQLI_BOTH)){
	
	$modulosInstitucion = mysqli_num_rows(mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".instituciones_modulos WHERE ipmod_institucion='".$config['conf_id_institucion']."' AND ipmod_modulo='".$modI['mod_id']."'"));
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
$paginaActualUsuario = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".paginas_publicidad
INNER JOIN ".$baseDatosServicios.".instituciones_modulos ON ipmod_modulo=pagp_modulo AND ipmod_institucion='".$config['conf_id_institucion']."'
WHERE pagp_id='".$idPaginaInterna."'"), MYSQLI_BOTH);

if($paginaActualUsuario[0]==""){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=302&idPagina='.$idPaginaInterna.'";</script>';
	exit();	
}
?>