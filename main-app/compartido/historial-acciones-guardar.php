<?php
include("../modelo/conexion.php");
require_once("../class/Modulos.php");

$arregloModulos = $_SESSION["modulos"];

$tienePermiso = Modulos::verificarPermisosPaginas($idPaginaInterna);

if (!$tienePermiso && $idPaginaInterna!='DT0107') {
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=302&idPagina='.$idPaginaInterna.'";</script>';
	exit();	
}