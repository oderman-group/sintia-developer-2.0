<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0119';
require_once(ROOT_PATH."/main-app/class/Cronograma.php");
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

Cronograma::actualizarCronograma($conexion, $config, $_POST);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="cronograma-calendario.php?success=SC_DT_2&id='.base64_encode($_POST["idR"]).'";</script>';
exit();