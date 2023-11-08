<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0013';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");

try{
    mysqli_query($conexion, "UPDATE usuarios SET uss_tipo_menu='" . $_GET["tipoMenu"] . "' WHERE uss_id='" . $_SESSION["id"] . "'");
    $_SESSION["datosUsuario"]["uss_tipo_menu"] = $_GET["tipoMenu"];
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
exit();