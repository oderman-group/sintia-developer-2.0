<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0123';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/Evaluaciones.php");

include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

Evaluaciones::actualizarPreguntasEvaluacion($conexion, $config, $_POST, $_FILES);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="evaluaciones-preguntas.php?idE='.base64_encode($_POST["idE"]).'#pregunta'.base64_encode($_POST["idR"]).'";</script>';
exit();