<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0154';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

CargaAcademica::eliminarTiposNotasCategoria($conexion, $config, base64_decode($_GET["idR"]));

try{
	mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_categorias_notas WHERE catn_id='" . base64_decode($_GET["idR"]) . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
exit();