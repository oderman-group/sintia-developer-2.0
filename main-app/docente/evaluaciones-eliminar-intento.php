<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0141';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Evaluaciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

$idE = !empty($_GET["idE"]) ? base64_decode($_GET["idE"]) : "";
$idEstudiante = !empty($_GET["idEstudiante"]) ? base64_decode($_GET["idEstudiante"]) : "";

Evaluaciones::eliminarIntentos($conexion, $config, $idE, $idEstudiante);

Evaluaciones::eliminarIntentosEstudiante($conexion, $config, $idE, $idEstudiante);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="evaluaciones-resultados.php?error=ER_DT_3&idE='.$_GET["idE"].'";</script>';
exit();