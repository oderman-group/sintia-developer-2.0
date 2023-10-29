<?php
include("session.php");
$idPaginaInterna = 'DT0207';

include("../compartido/historial-acciones-guardar.php");
Modulos::validarAccesoDirectoPaginas();

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

require_once("../class/SubRoles.php");

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if (empty($_POST["paginas"])) {
	include("../compartido/guardar-historial-acciones.php");
	$msj = 'Seleccione al menos una página para el rol';
	$url = $_SERVER["HTTP_REFERER"] . '?error=ER_DT_15&msj=' . $msj;
	echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
	exit();
}
if (empty($_POST["nombre"])) {
	include("../compartido/guardar-historial-acciones.php");
	$msj = 'Agregue un Nombre para el rol';
	$url = $_SERVER["HTTP_REFERER"] . '?error=ER_DT_15&msj=' . $msj;
	echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
	exit();
}

$idRegistro=SubRoles::crear($_POST["nombre"],$_POST["paginas"]);

if(!empty($_POST['directivos'])){
	SubRoles::crearRolesUsuarioMasivos($_POST['directivos'],$idRegistro);
}

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="sub-roles.php?success=SC_DT_2&id=' . base64_encode($idRegistro) . '";</script>';
exit();
