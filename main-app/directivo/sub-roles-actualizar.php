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

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if (empty($_POST["paginas"])) {
	include("../compartido/guardar-historial-acciones.php");
	$msj = 'Seleccione al menos una página para el rol';
	$url = $_SERVER["HTTP_REFERER"] . '&error=ER_DT_15&msj=' . $msj;
	echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
	exit();
}

$directivos= !empty($_POST["directivos"]) ? $_POST["directivos"] : "";

$datos = array(
	"id" =>$_POST["subr_id"],
    "nombre" =>$_POST["nombre"],
    "paginas" => $_POST["paginas"],
    "usuarios" => $directivos
	
);

SubRoles::actualizar($datos);	

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="sub-roles-editar.php?success=SC_DT_2&id=' . base64_encode($_POST["subr_id"]) . '";</script>';
exit();
