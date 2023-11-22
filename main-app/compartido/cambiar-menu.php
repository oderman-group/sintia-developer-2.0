<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0013';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");

try{
    mysqli_query($conexion, "UPDATE ".BD_GENERAL.".usuarios SET uss_tipo_menu='" . $_GET["tipoMenu"] . "' WHERE uss_id='" . $_SESSION["id"] . "' AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}");
    $_SESSION["datosUsuario"]["uss_tipo_menu"] = $_GET["tipoMenu"];
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
exit();