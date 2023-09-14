<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DV0066';
include("../compartido/historial-acciones-guardar.php");

try{
    mysqli_query($conexion, "UPDATE " . $baseDatosMarketPlace . ".productos SET prod_estado=1 WHERE prod_id='".$_GET["idR"]."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="mps-productos.php?error=ER_DT_3";</script>';
exit();