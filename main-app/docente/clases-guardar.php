<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0116';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

require_once(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/Clases.php");

$codigo = Clases::guardarClases($conexion, $config, $_POST, $_FILES, $cargaConsultaActual, $periodoConsultaActual);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo base64_encode($codigo);
exit();