<?php
include("session.php");
$idPaginaInterna = 'DT0208';

include("../compartido/historial-acciones-guardar.php");
Modulos::validarAccesoDirectoPaginas();

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

require_once("../class/SubRoles.php");

$nombre= !empty($_GET["nombre"]) ? $_GET["nombre"] : "";
$paginas= !empty($_GET["paginas"]) ? json_decode($_GET['paginas'], true) : "";
$directivos= !empty($_GET["directivos"]) ? json_decode($_GET['directivos'], true) : "";

$datos = array(
	"id" =>$_GET["id"],
    "nombre" =>$nombre,
    "paginas" => $paginas,
    "usuarios" => $directivos
	
);

SubRoles::actualizar($datos);	

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="sub-roles-editar.php?success=SC_DT_2&id=' . base64_encode($_GET["id"]) . '";</script>';
exit();
