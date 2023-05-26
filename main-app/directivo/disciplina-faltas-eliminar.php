<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0160';
include("../compartido/historial-acciones-guardar.php");

try{
mysqli_query($conexion, "DELETE FROM disciplina_faltas WHERE dfal_id='".$_GET["id"]."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");
	include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="disciplina-faltas.php?error=ER_DT_3";</script>';
exit();