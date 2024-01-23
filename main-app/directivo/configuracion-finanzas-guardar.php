<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0274';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/Movimientos.php");

include("../compartido/sintia-funciones.php");
$archivoSubido = new Archivos;

$existe = Movimientos::validarConfiguracionFinanzas($conexion, $config);

if ($existe<1) {
	Movimientos::guardarConfiguracionFinanzas($conexion, $config, $_POST);
} else {
	Movimientos::actualizarConfiguracionFinanzas($conexion, $config, $_POST);
}

if (!empty($_FILES['firma']['name'])) {
	$archivoSubido->validarArchivo($_FILES['firma']['size'], $_FILES['firma']['name']);
	$explode=explode(".", $_FILES['firma']['name']);
	$extension = end($explode);
	$firma = uniqid($_SESSION["inst"] . '_' . $_SESSION["id"] . '_firma_') . "." . $extension;
	$destino = "../files/firmas";
	move_uploaded_file($_FILES['firma']['tmp_name'], $destino . "/" . $firma);

	Movimientos::actualizarFirmaConfiguracionFinanzas($conexion, $config, $firma);
}

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="configuracion-finanzas.php";</script>';
exit();