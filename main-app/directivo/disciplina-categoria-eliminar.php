<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0159';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

try{
mysqli_query($conexion, "DELETE FROM ".BD_DISCIPLINA.".disciplina_faltas WHERE 
dfal_id_categoria='".base64_decode($_GET["id"])."' AND dfal_institucion={$config['conf_id_institucion']} AND dfal_year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
try{
mysqli_query($conexion, "DELETE FROM ".BD_DISCIPLINA.".disciplina_categorias WHERE dcat_id='".base64_decode($_GET["id"])."' AND dcat_institucion={$config['conf_id_institucion']} AND dcat_year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="disciplina-categorias.php?error=ER_DT_3";</script>';
exit();