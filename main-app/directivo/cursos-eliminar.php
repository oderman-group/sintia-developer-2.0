<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0158';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

try{
	mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_grados SET gra_estado=0 WHERE gra_id='" . base64_decode($_GET["id"]) . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");
	include("../compartido/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="cursos.php?error=ER_DT_3";</script>';
	exit();