<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0282';
require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");

$datosCargaActual = CargaAcademica::datosRelacionadosCarga($_GET['cargaActual']);

$datosCargaPasar = CargaAcademica::datosRelacionadosCarga($_GET['cargaPasar']);

$coinciden = false;
if ($datosCargaActual['car_grupo'] == $datosCargaPasar['car_grupo'] && $datosCargaActual['car_materia'] == $datosCargaPasar['car_materia']) {
    $coinciden = true;
}

$arrayCoinciden=["coinciden"=>$coinciden];

header('Content-Type: application/json');
echo json_encode($arrayCoinciden);

require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");