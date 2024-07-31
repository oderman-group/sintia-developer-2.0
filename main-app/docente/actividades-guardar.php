<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0111';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Actividades.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

$codigo = Actividades::guardarActividad($conexion, $config, $_POST, $_FILES, $storage, $cargaConsultaActual, $periodoConsultaActual);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");

if (strpos($codigo, 'ACT') === 0) {
    echo base64_encode($codigo);
} else {
    echo 'ERROR '.$codigo;
}

exit();