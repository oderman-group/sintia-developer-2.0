<?php 
include("session-compartida.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");

$idPaginaInterna = 'CM0059';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

$estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $_GET['nota']);
echo $estiloNota['notip_nombre'];

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");