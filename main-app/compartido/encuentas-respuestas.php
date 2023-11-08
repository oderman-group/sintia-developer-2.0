<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0029';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if (!empty(trim($_POST["respuesta"]))) {
    try{
        mysqli_query($conexion, "INSERT INTO " . $baseDatosServicios . ".comentarios(adcom_institucion, adcom_usuario, adcom_fecha, adcom_respuesta, adcom_tipo, adcom_id_encuesta) VALUES('" . $config['conf_id_institucion'] . "', '" . $_SESSION["id"] . "', now(), '" . $_POST["respuesta"] . "', 1, '" . $_POST["encuesta"] . "')");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $_SERVER["HTTP_REFERER"] . '";</script>';
exit();