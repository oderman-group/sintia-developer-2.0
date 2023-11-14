<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0058';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

try{
    mysqli_query($conexion, "UPDATE academico_cargas SET car_posicion_docente='" . $_GET["posicionNueva"] . "' 
    WHERE car_id='" . $_GET["idCarga"] . "'");

    include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
    exit();
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}