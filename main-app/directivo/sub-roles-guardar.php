<?php
include("session.php");
$idPaginaInterna = 'DT0207';

include("../compartido/historial-acciones-guardar.php");
Modulos::validarAccesoDirectoPaginas();
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

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="sub-roles.php?success=SC_DT_2&id=' . $idRegistro . '";</script>';
exit();
