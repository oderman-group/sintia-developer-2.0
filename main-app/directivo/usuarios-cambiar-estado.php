<?php 
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0087';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo 2;
	exit();
}

if (base64_decode($_GET["lock"]) == 1) $estado = 0;
else $estado = 1;
try{
    mysqli_query($conexion, "UPDATE usuarios SET uss_bloqueado='" . $estado . "' WHERE uss_id='" . base64_decode($_GET["idR"]) . "'");
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}
include("../compartido/guardar-historial-acciones.php");
echo $estado;