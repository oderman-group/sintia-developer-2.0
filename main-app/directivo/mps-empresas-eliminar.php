<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DV0060';
include("../compartido/historial-acciones-guardar.php");

try{
    mysqli_query($conexion, "UPDATE " . $baseDatosMarketPlace . ".empresas SET emp_eliminado=1 WHERE emp_id='".$_GET["idR"]."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="mps-empresas.php?error=ER_DT_3";</script>';
exit();