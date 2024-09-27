<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/Inscripciones.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0342';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

$aspirante = !empty($_GET["aspirante"]) ? base64_decode($_GET["aspirante"]) : null;

if (empty($aspirante)) {
    echo '<script type="text/javascript">window.location.href="inscripciones.php";</script>';
    exit();
}

try {
    Inscripciones::actualizarEstadoAspirante($aspirante);
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

exit();