<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0047';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

try{
    mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".general_alertas WHERE alr_id='" . $_GET["idR"] . "'");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $_SERVER["HTTP_REFERER"] . '";</script>';
exit();