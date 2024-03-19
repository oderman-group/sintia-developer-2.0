<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0138';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Evaluaciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

$idR = !empty($_GET["idR"]) ? base64_decode($_GET["idR"]) : "";

Evaluaciones::eliminarRespuesta($conexion, $config, $idR);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="evaluaciones-preguntas.php?error=ER_DT_3&idE='.$_GET["idE"].'";</script>';
exit();