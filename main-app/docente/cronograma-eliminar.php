<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0135';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
require_once(ROOT_PATH."/main-app/class/Cronograma.php");

$idR="";
if(!empty($_GET["idR"])){ $idR=base64_decode($_GET["idR"]);}

Cronograma::eliminarCronograma($conexion, $config, $idR);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="cronograma-calendario.php?error=ER_DT_3";</script>';
exit();