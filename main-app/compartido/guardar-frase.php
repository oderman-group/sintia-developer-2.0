<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0038';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

try{
    mysqli_query($conexion, "INSERT INTO " . $baseDatosServicios . ".publicidad_guardadas(psave_publicidad, psave_institucion, psave_usuario, psave_fecha) VALUES('" . $_GET["idPub"] . "', '" . $config['conf_id_institucion'] . "', '" . $_SESSION["id"] . "', now())");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.close();</script>';
exit();