<?php
include("session.php");

Modulos::validarAccesoPaginas();
$idPaginaInterna = 'DT0150';
include("../compartido/historial-acciones-guardar.php");

try{
	mysqli_query($conexion, "DELETE FROM academico_areas WHERE ar_id=".$_GET["id"].";");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	include("../compartido/guardar-historial-acciones.php");
	
	echo '<script type="text/javascript">window.location.href="areas.php?error=ER_DT_3";</script>';
	exit();