<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DV0048';
include("../compartido/historial-acciones-guardar.php");

try{
    mysqli_query($conexion, "UPDATE " . $baseDatosMarketPlace . ".categorias_productos SET catp_eliminado=1 WHERE catp_id='".$_GET["idR"]."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="mps-categorias-productos.php?error=ER_DT_3";</script>';
exit();