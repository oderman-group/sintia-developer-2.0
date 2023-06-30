<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0155';
include("../compartido/historial-acciones-guardar.php");

try{
	mysqli_query($conexion, "DELETE FROM academico_notas_tipos WHERE notip_id=" . $_GET["idN"] . ";");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="cargas-estilo-notas-especifica.php?id=' . $_GET["idNC"] . '";</script>';
exit();