<?php
include("../modelo/conexion.php");

$arregloModulos = $_SESSION["modulos"];

//Verificar si el usuario está en una pagina de un módulo NO ASIGNADO a la Institución
$paginaActualUsuario = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".paginas_publicidad
INNER JOIN ".$baseDatosServicios.".instituciones_modulos ON ipmod_modulo=pagp_modulo AND ipmod_institucion='".$config['conf_id_institucion']."'
WHERE pagp_id='".$idPaginaInterna."'"), MYSQLI_BOTH);

if($paginaActualUsuario[0]==""){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=302&idPagina='.$idPaginaInterna.'";</script>';
	exit();	
}