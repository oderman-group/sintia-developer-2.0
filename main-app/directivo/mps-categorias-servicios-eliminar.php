<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DV0054';
include("../compartido/historial-acciones-guardar.php");

try{
    mysqli_query($conexion, "UPDATE " . $baseDatosMarketPlace . ".servicios_categorias SET svcat_activa=0 WHERE svcat_id='".base64_decode($_GET["idR"])."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="mps-categorias-servicios.php?error=ER_DT_3";</script>';
exit();