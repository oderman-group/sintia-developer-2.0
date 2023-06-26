<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0157';
include("../compartido/historial-acciones-guardar.php");

try{
	mysqli_query($conexion, "DELETE FROM academico_indicadores WHERE ind_id=" . $_GET["idN"] . ";");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
exit();