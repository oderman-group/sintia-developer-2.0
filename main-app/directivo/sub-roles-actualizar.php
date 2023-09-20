<?php
include("session.php");
$idPaginaInterna = 'DT0208';

include("../compartido/historial-acciones-guardar.php");
Modulos::validarAccesoDirectoPaginas();
require_once("../class/SubRoles.php");

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if (empty($_POST["paginas"])) {
	include("../compartido/guardar-historial-acciones.php");
	$msj = 'Seleccione al menos una página para el rol';
	$url = $_SERVER["HTTP_REFERER"] . '&error=ER_DT_15&msj=' . $msj;
	echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
	exit();
}


$datos = array(
	"id" =>$_POST["subr_id"],
    "nombre" =>$_POST["nombre"],
    "paginas" => $_POST["paginas"]
	
);

SubRoles::actualizar($datos);	

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="sub-roles-editar.php?success=SC_DT_2&id=' . base64_encode($_POST["subr_id"]) . '";</script>';
exit();
